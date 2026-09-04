<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderStatusHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StaffController extends Controller
{
    public function dashboard()
    {
        $orders = $this->fetchFormattedOrders();
        return view('staff.dashboard', compact('orders'));
    }

    public function getLiveOrders()
    {
        $orders = $this->fetchFormattedOrders();
        return response()->json([
            'orders' => $orders,
            'counts' => [
                'pending'   => $orders->where('status', 'pending')->count(),
                'preparing' => $orders->where('status', 'preparing')->count(),
                'ready'     => $orders->where('status', 'ready')->count(),
                'completed' => $orders->where('status', 'completed')->count(),
            ]
        ]);
    }

    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,preparing,ready,completed,cancelled'
        ]);

        $oldStatus = $order->status;
        $order->status = $validated['status'];
        $order->save();

        OrderStatusHistory::create([
            'order_id'   => $order->id,
            'status'     => $validated['status'],
            'changed_by' => Auth::id(),
        ]);

        return response()->json([
            'success' => true,
            'message' => "Order #{$order->id} status changed from {$oldStatus} to {$order->status}.",
            'order'   => $order
        ]);
    }

    private function fetchFormattedOrders()
    {
        return Order::with(['user', 'items.product', 'items.extras'])
            ->orderBy('created_at', 'desc')
            ->take(60)
            ->get()
            ->map(function ($order) {
                return [
                    'id'               => $order->id,
                    'customer_name'    => $order->user ? $order->user->fullname : 'Guest Customer',
                    'customer_phone'   => $order->user ? $order->user->phone : null,
                    'order_type'       => $order->order_type,
                    'status'           => $order->status,
                    'total_amount'     => (float) $order->total_amount,
                    'delivery_address' => $order->delivery_address,
                    'latitude'         => $order->latitude,
                    'longitude'        => $order->longitude,
                    'special_note'     => $order->special_note,
                    'created_at'       => $order->created_at->format('h:i A'),
                    'created_date'     => $order->created_at->format('M d, Y'),
                    'time_ago'         => $order->created_at->diffForHumans(),
                    'items_count'      => $order->items->sum('quantity'),
                    'items'            => $order->items->map(function ($item) {
                        return [
                            'id'           => $item->id,
                            'name'         => $item->product ? $item->product->name : 'Unknown Product',
                            'image'        => $item->product && $item->product->image ? asset('storage/' . $item->product->image) : null,
                            'quantity'     => $item->quantity,
                            'unit_price'   => (float) $item->unit_price,
                            'subtotal'     => (float) $item->subtotal,
                            'special_note' => $item->special_note,
                        ];
                    })
                ];
            });
    }
}