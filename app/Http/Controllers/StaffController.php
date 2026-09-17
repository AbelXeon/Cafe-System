<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderStatusHistory;
use App\Models\Product;
use App\Models\Extra;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class StaffController extends Controller
{
    public function dashboard()
    {
        $orders = $this->fetchFormattedOrders();
        $products = Product::with('category')->orderBy('name')->get();
        $extras = Extra::orderBy('name')->get();

        return view('staff.dashboard', compact('orders', 'products', 'extras'));
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

    public function toggleProductAvailability(Product $product)
    {
        $product->is_available = !$product->is_available;
        $product->save();

        return response()->json([
            'success' => true,
            'is_available' => (bool)$product->is_available,
            'message' => $product->name . ' is now ' . ($product->is_available ? 'Available' : 'Marked as Sold Out / Out of Stock.')
        ]);
    }

    public function toggleExtraAvailability(Extra $extra)
    {
        $extra->is_available = !$extra->is_available;
        $extra->save();

        return response()->json([
            'success' => true,
            'is_available' => (bool)$extra->is_available,
            'message' => $extra->name . ' is now ' . ($extra->is_available ? 'Available' : 'Marked as Sold Out.')
        ]);
    }

    public function updateProfile(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $validated = $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:30',
        ]);

        $user->fullname = $validated['name'];
        if (isset($user->name)) {
            $user->name = $validated['name'];
        }
        $user->email = $validated['email'];
        $user->phone = $validated['phone'];
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully.',
            'user'    => [
                'name'  => $user->fullname ?? $user->name,
                'email' => $user->email,
                'phone' => $user->phone
            ]
        ]);
    }

    public function updatePassword(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $request->validate([
            'current_password' => 'required|current_password',
            'password'         => ['required', 'confirmed', Password::min(8)->letters()->mixedCase()->numbers()->symbols()],
        ]);

        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Password changed successfully.'
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
                    'customer_name'    => $order->user ? ($order->user->fullname ?? $order->user->name) : 'Guest Customer',
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
                            'extras'       => $item->extras->map(function($e) {
                                return $e->name ?? $e->title ?? $e;
                            })->toArray(),
                        ];
                    })
                ];
            });
    }
}