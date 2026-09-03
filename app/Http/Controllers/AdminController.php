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
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function dashboard()
    {
        // 1. Basic Models
        $categories = Category::withCount('products')->orderBy('name')->get();
        $products = Product::with('category')->latest()->get();
        $extras = Extra::latest()->get();
        $staff = User::with('role')
            ->whereHas('role', fn ($q) => $q->whereIn('name', ['staff', 'delivery']))
            ->latest()
            ->get();
        $roles = Role::whereIn('name', ['staff', 'delivery'])->get();

        // 2. Overview Analytics & Metrics
        $totalCustomers = User::whereHas('role', fn($q) => $q->where('name', 'customer'))->count();
        if ($totalCustomers === 0) {
            // Fallback count if customer role isn't assigned yet
            $totalCustomers = User::whereDoesntHave('role', fn($q) => $q->whereIn('name', ['admin', 'staff', 'delivery']))->count();
        }

        // Safely check total revenue and orders
        $totalOrders = class_exists(Order::class) ? Order::count() : 0;
        $totalRevenue = 0;
        if (class_exists(Order::class) && $totalOrders > 0) {
            // Sums total_price if exists, or defaults to 0
            $totalRevenue = Order::whereNotIn('status', ['cancelled', 'failed'])->sum('total_price') 
                ?: Order::whereNotIn('status', ['cancelled', 'failed'])->sum('total_amount') 
                ?: 0;
        }

        // 3. Last 7 Days Revenue & Orders Chart Data
        $chartLabels = [];
        $chartRevenue = [];
        $chartOrders = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $chartLabels[] = $date->format('D, M j');

            if (class_exists(Order::class)) {
                $dayOrders = Order::whereDate('created_at', $date->toDateString());
                $chartOrders[] = (clone $dayOrders)->count();
                $chartRevenue[] = (float) ((clone $dayOrders)->whereNotIn('status', ['cancelled', 'failed'])->sum('total_price') ?: 0);
            } else {
                $chartOrders[] = 0;
                $chartRevenue[] = 0.00;
            }
        }

        // 4. Category Distribution for Donut Chart
        $categoryLabels = $categories->pluck('name')->toArray();
        $categoryCounts = $categories->pluck('products_count')->toArray();

        // 5. Recent Admin Activities Log
        $recentActions = AdminAction::with('admin')->latest()->take(6)->get();

        return view('admin.dashboard', compact(
            'categories',
            'products',
            'extras',
            'staff',
            'roles',
            'totalCustomers',
            'totalOrders',
            'totalRevenue',
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

        $path = $request->file('image')->store('products', 'public');

        $product = Product::create([
            'category_id'  => $data['category_id'],
            'name'         => $data['name'],
            'description'  => $data['description'] ?? null,
            'price'        => $data['price'],
            'image'        => $path,
            'is_available' => $request->boolean('is_available', true),
        ]);

        AdminAction::create([
            'admin_id'    => $request->user()->id,
            'action'      => 'created_product',
            'target_type' => 'Product',
            'target_id'   => $product->id,
            'description' => "Created product \"{$product->name}\"",
        ]);

        return response()->json(['success' => true, 'product' => $product->load('category')]);
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

        return response()->json(['success' => true, 'staff' => $user->load('role')]);
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
            'is_available' => $request->boolean('is_available', true),
        ]);

        AdminAction::create([
            'admin_id'    => $request->user()->id,
            'action'      => 'created_extra',
            'target_type' => 'Extra',
            'target_id'   => $extra->id,
            'description' => "Created extra \"{$extra->name}\"",
        ]);

        return response()->json(['success' => true, 'extra' => $extra]);
    }
}