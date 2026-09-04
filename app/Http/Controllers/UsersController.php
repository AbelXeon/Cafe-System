<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Extra;
use App\Models\SavedLocation;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function dashboard(Request $request)
    {
        $categories = Category::with(['products' => function ($q) {
            $q->where('is_available', true);
        }])->get();

        $categoryNames = $categories->pluck('name')->values()->toArray();

        // Insert 'All' in the middle of categories
        if (!in_array('All', $categoryNames)) {
            $middleIndex = (int) floor(count($categoryNames) / 2);
            array_splice($categoryNames, $middleIndex, 0, 'All');
        }

        $extras = Extra::where('is_available', true)->get();

        $menuData = [
            'categories' => $categoryNames,
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
            'extras' => $extras->map(function ($extra) {
                return [
                    'id'    => $extra->id,
                    'name'  => $extra->name,
                    'price' => (float) $extra->price,
                ];
            })->values(),
        ];

        $addresses = SavedLocation::where('user_id', $request->user()->id)->get();

        // Fetch user orders
        $userOrders = Order::with(['items.product'])
            ->where('user_id', $request->user()->id)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(fn ($order) => $this->formatOrderForUser($order));

        return view('user.Dashboard', compact('categories', 'menuData', 'addresses', 'extras', 'userOrders'));
    }

    public function getLiveOrders(Request $request)
    {
        $orders = Order::with(['items.product'])
            ->where('user_id', $request->user()->id)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(fn ($order) => $this->formatOrderForUser($order));

        return response()->json([
            'orders' => $orders,
        ]);
    }

    public function storeOrder(Request $request)
    {
        $data = $request->validate([
            'items'                => 'required|array|min:1',
            'items.*.product_id'   => 'required|exists:products,id',
            'items.*.quantity'     => 'required|integer|min:1',
            'items.*.special_note' => 'nullable|string|max:500',
            'items.*.custom_price' => 'nullable|numeric|min:0',
            'saved_location_id'    => 'nullable|exists:saved_locations,id',
        ]);

        $products = Product::whereIn('id', collect($data['items'])->pluck('product_id'))
            ->get()
            ->keyBy('id');

        $subtotal = 0;
        foreach ($data['items'] as $item) {
            $unitPrice = isset($item['custom_price']) ? (float) $item['custom_price'] : (float) $products[$item['product_id']]->price;
            $subtotal += $unitPrice * $item['quantity'];
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
            'order_type'       => $deliveryAddress ? 'delivery' : 'dine_in',
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
            $unitPrice = isset($item['custom_price']) ? (float) $item['custom_price'] : (float) $product->price;

            OrderItem::create([
                'order_id'     => $order->id,
                'product_id'   => $product->id,
                'quantity'     => $item['quantity'],
                'unit_price'   => $unitPrice,
                'subtotal'     => $unitPrice * $item['quantity'],
                'special_note' => $item['special_note'] ?? null,
            ]);
        }

        $formatted = $this->formatOrderForUser($order->fresh(['items.product']));

        return response()->json(['success' => true, 'order' => $formatted]);
    }

    protected function formatOrderForUser($order)
    {
        // Calculate step index (1 to 5)
        // 1 = pending / received
        // 2 = preparing / accepted / in_kitchen / in_progress
        // 3 = ready / awaiting_pickup
        // 4 = out_for_delivery / on_the_way
        // 5 = delivered / completed
        $step = 1;
        $statusKey = strtolower($order->status ?? 'pending');

        if (in_array($statusKey, ['preparing', 'accepted', 'in_kitchen', 'in_progress', 'cooking'])) {
            $step = 2;
        } elseif (in_array($statusKey, ['ready', 'packed', 'ready_for_pickup'])) {
            $step = 3;
        } elseif (in_array($statusKey, ['out_for_delivery', 'on_way', 'picked_up'])) {
            $step = 4;
        } elseif (in_array($statusKey, ['delivered', 'completed'])) {
            $step = 5;
        } elseif (in_array($statusKey, ['cancelled', 'rejected'])) {
            $step = 0;
        }

        return [
            'id'               => $order->id,
            'status'           => $order->status,
            'status_step'      => $step,
            'order_type'       => $order->order_type ?? 'delivery',
            'total_amount'     => (float) $order->total_amount,
            'special_note'     => $order->special_note,
            'delivery_address' => $order->delivery_address,
            'latitude'         => $order->latitude,
            'longitude'        => $order->longitude,
            'created_at'       => optional($order->created_at)->format('M d, Y • h:i A'),
            'time_ago'         => $order->created_at?->diffForHumans(),
            'items'            => $order->items->map(function ($item) {
                return [
                    'id'           => $item->id,
                    'name'         => $item->product?->name ?? $item->name ?? 'Menu Item',
                    'quantity'     => $item->quantity,
                    'unit_price'   => (float) $item->unit_price,
                    'subtotal'     => (float) $item->subtotal,
                    'special_note' => $item->special_note,
                    'image'        => $item->product?->image ? asset('storage/' . $item->product->image) : null,
                ];
            }),
        ];
    }
}