<?php

namespace App\Http\Controllers;

use App\Models\DeliveryProfiles;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Models\Order;

class DeliveryController extends Controller
{
    public function dashboard()
    {
        $driverId = auth()->id();

        $orders = $this->driverOrders($driverId)->get()->map(fn ($o) => $this->formatOrder($o));

        $online = Session::get('delivery_online', false);

        return view('delivery.dashboard', compact('orders', 'online'));
    }

    /**
     * Polled every few seconds by the frontend.
     */
    public function getLiveOrders()
    {
        $driverId = auth()->id();

        $orders = $this->driverOrders($driverId)->get()->map(fn ($o) => $this->formatOrder($o));

        $counts = [
            'ready'             => $orders->where('status', 'ready')->count(),
            'out_for_delivery'  => $orders->where('status', 'out_for_delivery')->count(),
            'delivered'         => $orders->where('status', 'delivered')->count(),
        ];

        return response()->json([
            'orders' => $orders->values(),
            'counts' => $counts,
            'online' => Session::get('delivery_online', false),
        ]);
    }

    /**
     * Driver accepts an incoming (ready) order.
     */
    public function acceptOrder(Request $request, Order $order)
    {
        if ($order->status !== 'ready') {
            return response()->json(['message' => 'This order is no longer available for pickup.'], 422);
        }

        $order->update([
            'status'            => 'out_for_delivery',
            'delivery_user_id'  => auth()->id(),
        ]);

        return response()->json([
            'message' => 'Order accepted. Head to the kitchen to pick it up.',
            'order'   => $this->formatOrder($order->fresh(['items', 'user'])),
        ]);
    }

    /**
     * Driver declines — order stays "ready" and available to other drivers.
     */
    public function declineOrder(Request $request, Order $order)
    {
        return response()->json(['message' => 'Order skipped.']);
    }

    /**
     * Driver marks the order as delivered.
     */
    public function markDelivered(Request $request, Order $order)
    {
        if ($order->delivery_user_id !== auth()->id()) {
            return response()->json(['message' => 'This order is not assigned to you.'], 403);
        }

        $order->update(['status' => 'delivered']);

        return response()->json([
            'message' => 'Order marked as delivered. Nice work!',
            'order'   => $this->formatOrder($order->fresh(['items', 'user'])),
        ]);
    }

    /**
     * Toggle the driver's online/offline status (stored in session).
     */
    public function toggleOnline(Request $request)
    {
        $current = Session::get('delivery_online', false);
        Session::put('delivery_online', ! $current);

        return response()->json(['online' => ! $current]);
    }

    /**
     * Base query for the orders this driver should see.
     * NOTE: adjust relationship/column names (user, delivery_user_id)
     *       to match your actual Order model.
     */
    protected function driverOrders($driverId)
    {
        return Order::with(['items', 'user'])
            ->where(function ($q) use ($driverId) {
                $q->where('status', 'ready')
                  ->orWhere(function ($q2) use ($driverId) {
                      $q2->where('status', 'out_for_delivery')
                         ->where('delivery_user_id', $driverId);
                  });
            })
            ->orderBy('created_at', 'desc');
    }

    /**
     * Shape an order for the frontend.
     * Delivery address/lat/lng live directly on the orders table,
     * so no location relation is needed here.
     */
    protected function formatOrder($order)
    {
        $customer = $order->user;

        return [
            'id'             => $order->id,
            'status'         => $order->status,
            'total_amount'   => $order->total_amount,
            'order_type'     => $order->order_type ?? 'delivery',
            'special_note'   => $order->special_note,
            'created_at'     => optional($order->created_at)->format('M d, H:i'),
            'time_ago'       => $order->created_at?->diffForHumans(),

            // customer info
            'customer_name'  => $customer?->fullname ?? $customer?->name ?? 'Customer',
            'customer_phone' => $customer?->phone ?? null,
            'customer_email' => $customer?->email ?? null,

            // delivery destination (columns on the order itself)
            'address_text'   => $order->delivery_address,
            'latitude'       => $order->latitude,
            'longitude'      => $order->longitude,

            'items' => $order->items->map(fn ($i) => [
                'id'           => $i->id,
                'name'         => $i->name,
                'quantity'     => $i->quantity,
                'subtotal'     => $i->subtotal,
                'special_note' => $i->special_note,
                'extras'       => $i->extras,
                'image'        => $i->image ? asset('storage/' . $i->image) : null,
            ]),
        ];
    }
}