<?php

namespace App\Http\Controllers;

use App\Models\DeliveryProfiles;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rules\Password;
use App\Models\Order;

class DeliveryController extends Controller
{
    public function dashboard()
    {
        $driverId = Auth::id();

        $orders = $this->driverOrders($driverId)->get()->map(fn ($o) => $this->formatOrder($o));

        $online = Session::get('delivery_online', false);

        return view('delivery.dashboard', compact('orders', 'online'));
    }

    public function getLiveOrders()
    {
        $driverId = Auth::id();

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

    public function acceptOrder(Request $request, Order $order)
    {
        if ($order->status !== 'ready') {
            return response()->json(['message' => 'This order is no longer available for pickup.'], 422);
        }

        $order->update([
            'status'            => 'out_for_delivery',
            'delivery_user_id'  => Auth::id(),
        ]);

        return response()->json([
            'message' => 'Order accepted. Head to the kitchen to pick it up.',
            'order'   => $this->formatOrder($order->fresh(['items', 'user'])),
        ]);
    }

    public function declineOrder(Request $request, Order $order)
    {
        return response()->json(['message' => 'Order skipped.']);
    }

    public function markDelivered(Request $request, Order $order)
    {
        if ($order->delivery_user_id !== Auth::id()) {
            return response()->json(['message' => 'This order is not assigned to you.'], 403);
        }

        $order->update(['status' => 'delivered']);

        return response()->json([
            'message' => 'Order marked as delivered. Nice work!',
            'order'   => $this->formatOrder($order->fresh(['items', 'user'])),
        ]);
    }

    public function toggleOnline(Request $request)
    {
        $current = Session::get('delivery_online', false);
        Session::put('delivery_online', ! $current);

        return response()->json(['online' => ! $current]);
    }

    
    public function updateProfile(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $data = $request->validate([
            'name'  => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'phone' => ['nullable', 'string', 'max:25'],
        ]);

        // Support both name or fullname column
        if (Schema::hasColumn('users', 'fullname')) {
            $user->fullname = $data['name'];
        }
        $user->name = $data['name'];
        $user->email = $data['email'];

        if (Schema::hasColumn('users', 'phone')) {
            $user->phone = $data['phone'] ?? null;
        }

        $user->save();

        return response()->json([
            'status'  => 'success',
            'message' => 'Courier profile updated successfully.',
            'user'    => [
                'name'  => $user->fullname ?? $user->name,
                'email' => $user->email,
                'phone' => $user->phone ?? null,
            ]
        ]);
    }

    
    public function updatePassword(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password'         => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Password updated successfully.',
        ]);
    }

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

            'customer_name'  => $customer?->fullname ?? $customer?->name ?? 'Customer',
            'customer_phone' => $customer?->phone ?? null,
            'customer_email' => $customer?->email ?? null,

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