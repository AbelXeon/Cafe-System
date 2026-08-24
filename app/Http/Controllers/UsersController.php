<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItems;
use App\Models\Product;
use App\Models\SavedLocation;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function dashboard(Request $request)
    {
        $categories = Category::with(['products' => function ($q) {
            $q->where('is_available', true);
        }])->get();

        $menuData = [
            'categories' => $categories->pluck('name')->values(),
            'products' => $categories->flatMap(function ($category) {
                return $category->products->map(function ($product) use ($category) {
                    return [
                        'id'          => $product->id,
                        'name'        => $product->name,
                        'description' => $product->description,
                        'price'       => (float) $product->price,
                        'image'       => asset('storage/' . $product->image),
                        'category'    => $category->name,
                    ];
                });
            })->values(),
        ];

        $addresses = SavedLocation::where('user_id', $request->user()->id)->get();

        return view('user.dashboard', compact('categories', 'menuData', 'addresses'));
    }

    public function storeOrder(Request $request)
    {
        $data = $request->validate([
            'items'                => 'required|array|min:1',
            'items.*.product_id'   => 'required|exists:products,id',
            'items.*.quantity'     => 'required|integer|min:1',
            'items.*.special_note' => 'nullable|string|max:255',
            'saved_location_id'    => 'nullable|exists:saved_locations,id',
        ]);

        $products = Product::whereIn('id', collect($data['items'])->pluck('product_id'))
            ->get()
            ->keyBy('id');

        $subtotal = 0;
        foreach ($data['items'] as $item) {
            $subtotal += $products[$item['product_id']]->price * $item['quantity'];
        }

        $deliveryAddress = null;
        $latitude = null;
        $longitude = null;

        if (!empty($data['saved_location_id'])) {
            $location = SavedLocation::where('user_id', $request->user()->id)
                ->findOrFail($data['saved_location_id']);

            $deliveryAddress = $location->address;
            $latitude = $location->latitude;
            $longitude = $location->longitude;
        }

        $order = Order::create([
            'user_id'          => $request->user()->id,
            'order_type'       => 'dine_in',
            'special_note'     => null,
            'subtotal'         => $subtotal,
            'extra_total'      => 0,
            'delivery_fee'     => 0,
            'total_amount'     => $subtotal,
            'status'           => 'pending',
            'delivery_address' => $deliveryAddress,
            'latitude'         => $latitude,
            'longitude'        => $longitude,
        ]);

        foreach ($data['items'] as $item) {
            $product = $products[$item['product_id']];

            OrderItem::create([
                'order_id'     => $order->id,
                'product_id'   => $product->id,
                'quantity'     => $item['quantity'],
                'unit_price'   => $product->price,
                'subtotal'     => $product->price * $item['quantity'],
                'special_note' => $item['special_note'] ?? null,
            ]);
        }

        return response()->json(['success' => true, 'order' => $order->load('items')]);
    }
}