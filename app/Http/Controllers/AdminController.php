<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\Order;
use App\Models\Category;
use App\Models\Product;
use App\Models\Extra;
use App\Models\AdminAction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function dashboard()
    {
        // 1. Core Model Queries
        $categories = Category::withCount('products')
            ->orderBy('name')
            ->get();

        $products = Product::with('category')
            ->latest()
            ->get();

        $extras = Extra::latest()->get();

        $staff = User::with('role')
            ->whereHas('role', fn ($q) => $q->whereIn('name', ['staff', 'delivery']))
            ->latest()
            ->get();

        $roles = Role::whereIn('name', ['staff', 'delivery'])->get();


        // 2. Real Customers Count
        $totalCustomers = User::whereHas(
            'role',
            fn ($q) => $q->where('name', 'customer')
        )->count();

        if ($totalCustomers === 0) {
            $totalCustomers = User::whereDoesntHave(
                'role',
                fn ($q) => $q->whereIn('name', ['admin', 'staff', 'delivery'])
            )->count();
        }


        // 3. Determine the actual revenue column used by the orders table
        //
        // Your database may use either:
        //     total_price
        // or:
        //     total_amount
        //
        // We detect it once and use the SAME column everywhere below.
        $revenueColumn = null;

        if (class_exists(Order::class)) {
            if (Schema::hasColumn('orders', 'total_price')) {
                $revenueColumn = 'total_price';
            } elseif (Schema::hasColumn('orders', 'total_amount')) {
                $revenueColumn = 'total_amount';
            }
        }


        // 4. Real Total Orders & Revenue in ETB
        $totalOrders = class_exists(Order::class)
            ? Order::count()
            : 0;

        $totalRevenue = 0;

        if (
            class_exists(Order::class) &&
            $totalOrders > 0 &&
            $revenueColumn
        ) {
            $totalRevenue = (float) Order::whereNotIn('status', [
                'cancelled',
                'failed'
            ])->sum($revenueColumn);
        }


        // 5. Real Week-over-Week Revenue Comparison
        $thisWeekRevenue = 0;
        $lastWeekRevenue = 0;
        $revenueGrowthPercent = 0;

        if (class_exists(Order::class) && $revenueColumn) {

            // Current week
            $thisWeekRevenue = (float) Order::whereNotIn('status', [
                'cancelled',
                'failed'
            ])
                ->where(
                    'created_at',
                    '>=',
                    Carbon::now()->startOfWeek()
                )
                ->sum($revenueColumn);


            // Previous week
            $lastWeekRevenue = (float) Order::whereNotIn('status', [
                'cancelled',
                'failed'
            ])
                ->whereBetween('created_at', [
                    Carbon::now()
                        ->subWeek()
                        ->startOfWeek(),

                    Carbon::now()
                        ->subWeek()
                        ->endOfWeek()
                ])
                ->sum($revenueColumn);


            // Calculate percentage change
            if ($lastWeekRevenue > 0) {

                $revenueGrowthPercent = round(
                    (
                        ($thisWeekRevenue - $lastWeekRevenue)
                        / $lastWeekRevenue
                    ) * 100,
                    1
                );

            } elseif ($thisWeekRevenue > 0) {

                // No revenue last week but revenue this week
                $revenueGrowthPercent = 100;

            } else {

                // Both weeks have no revenue
                $revenueGrowthPercent = 0;
            }
        }


        // 6. Real 7-Day Revenue & Orders Data
        //    Used by Chart.js
        $chartLabels = [];
        $chartRevenue = [];
        $chartOrders = [];

        for ($i = 6; $i >= 0; $i--) {

            $date = Carbon::today()->subDays($i);

            $chartLabels[] = $date->format('D, M j');


            if (class_exists(Order::class)) {

                // All orders for this particular day
                $dayOrders = Order::whereDate(
                    'created_at',
                    $date->toDateString()
                );


                // Orders line
                $chartOrders[] = (clone $dayOrders)->count();


                // Revenue line
                //
                // IMPORTANT:
                // This now uses the exact same revenue column
                // used by Total Revenue and the weekly calculation.
                if ($revenueColumn) {

                    $dailyRevenue = (clone $dayOrders)
                        ->whereNotIn('status', [
                            'cancelled',
                            'failed'
                        ])
                        ->sum($revenueColumn);

                    $chartRevenue[] = (float) $dailyRevenue;

                } else {

                    $chartRevenue[] = 0.00;
                }

            } else {

                $chartOrders[] = 0;
                $chartRevenue[] = 0.00;
            }
        }


        // 7. Real Category Distribution
        $categoryLabels = $categories
            ->pluck('name')
            ->toArray();

        $categoryCounts = $categories
            ->pluck('products_count')
            ->toArray();


        // 8. Recent Admin Activities Log
        $recentActions = AdminAction::with('admin')
            ->latest()
            ->take(6)
            ->get();


        return view('admin.dashboard', compact(
            'categories',
            'products',
            'extras',
            'staff',
            'roles',
            'totalCustomers',
            'totalOrders',
            'totalRevenue',
            'revenueGrowthPercent',
            'chartLabels',
            'chartRevenue',
            'chartOrders',
            'categoryLabels',
            'categoryCounts',
            'recentActions'
        ));
    }


    public function storeProduct(Request $request)
    {
        $data = $request->validate([
            'category_id'  => 'required|exists:categories,id',
            'name'         => 'required|string|max:255',
            'description'  => 'nullable|string',
            'price'        => 'required|numeric|min:0',
            'image'        => 'required|image|max:4096',
            'is_available' => 'nullable|boolean',
        ]);

        $path = $request->file('image')->store(
            'products',
            'public'
        );

        $product = Product::create([
            'category_id'  => $data['category_id'],
            'name'         => $data['name'],
            'description'  => $data['description'] ?? null,
            'price'        => $data['price'],
            'image'        => $path,
            'is_available' => $request->boolean(
                'is_available',
                true
            ),
        ]);

        AdminAction::create([
            'admin_id'    => $request->user()->id,
            'action'      => 'created_product',
            'target_type' => 'Product',
            'target_id'   => $product->id,
            'description' => "Created product \"{$product->name}\"",
        ]);

        return response()->json([
            'success' => true,
            'product' => $product->load('category')
        ]);
    }


    public function updateProduct(Request $request, Product $product)
    {
        $data = $request->validate([
            'category_id'  => 'required|exists:categories,id',
            'name'         => 'required|string|max:255',
            'description'  => 'nullable|string',
            'price'        => 'required|numeric|min:0',
            'image'        => 'nullable|image|max:4096',
            'is_available' => 'nullable|boolean',
        ]);

        $updateData = [
            'category_id'  => $data['category_id'],
            'name'         => $data['name'],
            'description'  => $data['description'] ?? null,
            'price'        => $data['price'],
            'is_available' => $request->boolean('is_available', true),
        ];

        if ($request->hasFile('image')) {
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            $updateData['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($updateData);

        AdminAction::create([
            'admin_id'    => $request->user()->id,
            'action'      => 'updated_product',
            'target_type' => 'Product',
            'target_id'   => $product->id,
            'description' => "Updated product \"{$product->name}\"",
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Product updated successfully',
            'product' => $product->load('category')
        ]);
    }


    public function storeStaff(Request $request)
    {
        $data = $request->validate([
            'role_id'  => 'required|exists:roles,id',
            'fullname' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'email'    => 'nullable|email|unique:users,email',
            'phone'    => 'nullable|string|max:20',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'role_id'  => $data['role_id'],
            'fullname' => $data['fullname'],
            'username' => $data['username'],
            'email'    => $data['email'] ?? null,
            'phone'    => $data['phone'] ?? null,
            'password' => Hash::make($data['password']),
        ]);

        AdminAction::create([
            'admin_id'    => $request->user()->id,
            'action'      => 'created_staff',
            'target_type' => 'User',
            'target_id'   => $user->id,
            'description' => "Created {$user->role->name} account \"{$user->fullname}\"",
        ]);

        return response()->json([
            'success' => true,
            'staff' => $user->load('role')
        ]);
    }


    public function updateStaff(Request $request, User $user)
    {
        $data = $request->validate([
            'role_id'  => 'required|exists:roles,id',
            'fullname' => 'required|string|max:255',
            'username' => [
                'required',
                'string',
                'max:255',
                Rule::unique('users', 'username')->ignore($user->id),
            ],
            'email'    => [
                'nullable',
                'email',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'phone'    => 'nullable|string|max:20',
            'password' => 'nullable|string|min:6',
        ]);

        $updateData = [
            'role_id'  => $data['role_id'],
            'fullname' => $data['fullname'],
            'username' => $data['username'],
            'email'    => $data['email'] ?? null,
            'phone'    => $data['phone'] ?? null,
        ];

        if (!empty($data['password'])) {
            $updateData['password'] = Hash::make($data['password']);
        }

        $user->update($updateData);

        AdminAction::create([
            'admin_id'    => $request->user()->id,
            'action'      => 'updated_staff',
            'target_type' => 'User',
            'target_id'   => $user->id,
            'description' => "Updated staff member \"{$user->fullname}\"",
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Staff member updated successfully',
            'staff'   => $user->load('role')
        ]);
    }


    public function storeExtra(Request $request)
    {
        $data = $request->validate([
            'name'         => 'required|string|max:255',
            'price'        => 'required|numeric|min:0',
            'is_available' => 'nullable|boolean',
        ]);

        $extra = Extra::create([
            'name'         => $data['name'],
            'price'        => $data['price'],
            'is_available' => $request->boolean(
                'is_available',
                true
            ),
        ]);

        AdminAction::create([
            'admin_id'    => $request->user()->id,
            'action'      => 'created_extra',
            'target_type' => 'Extra',
            'target_id'   => $extra->id,
            'description' => "Created extra \"{$extra->name}\"",
        ]);

        return response()->json([
            'success' => true,
            'extra' => $extra
        ]);
    }


    public function updateExtra(Request $request, Extra $extra)
    {
        $data = $request->validate([
            'name'         => 'required|string|max:255',
            'price'        => 'required|numeric|min:0',
            'is_available' => 'nullable|boolean',
        ]);

        $extra->update([
            'name'         => $data['name'],
            'price'        => $data['price'],
            'is_available' => $request->boolean('is_available', true),
        ]);

        AdminAction::create([
            'admin_id'    => $request->user()->id,
            'action'      => 'updated_extra',
            'target_type' => 'Extra',
            'target_id'   => $extra->id,
            'description' => "Updated extra \"{$extra->name}\"",
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Extra item updated successfully',
            'extra'   => $extra
        ]);
    }
}