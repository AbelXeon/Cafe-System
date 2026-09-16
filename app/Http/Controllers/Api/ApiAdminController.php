<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\Order;
use App\Models\Category;
use App\Models\Product;
use App\Models\Extra;
use App\Models\AdminAction;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ApiAdminController extends Controller
{
    public function dashboard(Request $request)
    {
        // 1. Core Models
        $categories = Category::withCount('products')->orderBy('name')->get();
        $products = Product::with('category')->latest()->get();
        $extras = Extra::latest()->get();
        $staff = User::with('role')
            ->whereHas('role', fn ($q) => $q->whereIn('name', ['staff', 'delivery']))
            ->latest()
            ->get();
        $roles = Role::whereIn('name', ['staff', 'delivery'])->get();

        // 2. Metrics
        $totalCustomers = User::whereHas('role', fn($q) => $q->where('name', 'customer'))->count();
        if ($totalCustomers === 0) {
            $totalCustomers = User::whereDoesntHave('role', fn($q) => $q->whereIn('name', ['admin', 'staff', 'delivery']))->count();
        }

        $totalOrders = class_exists(Order::class) ? Order::count() : 0;
        $totalRevenue = 0;
        if (class_exists(Order::class) && $totalOrders > 0) {
            $totalRevenue = (float) (Order::whereNotIn('status', ['cancelled', 'failed'])->sum('total_price') ?: 0);
        }

        // 3. Weekly Revenue Growth
        $thisWeekRevenue = 0;
        $lastWeekRevenue = 0;
        $revenueGrowthPercent = 0;

        if (class_exists(Order::class)) {
            $thisWeekRevenue = Order::whereNotIn('status', ['cancelled', 'failed'])
                ->where('created_at', '>=', Carbon::now()->startOfWeek())
                ->sum('total_price') ?: 0;

            $lastWeekRevenue = Order::whereNotIn('status', ['cancelled', 'failed'])
                ->whereBetween('created_at', [
                    Carbon::now()->subWeek()->startOfWeek(),
                    Carbon::now()->subWeek()->endOfWeek()
                ])->sum('total_price') ?: 0;

            if ($lastWeekRevenue > 0) {
                $revenueGrowthPercent = round((($thisWeekRevenue - $lastWeekRevenue) / $lastWeekRevenue) * 100, 1);
            } elseif ($thisWeekRevenue > 0) {
                $revenueGrowthPercent = 100;
            }
        }

        // 4. Recent Actions
        $recentActions = AdminAction::with('admin')->latest()->take(6)->get()->map(function ($action) {
            return [
                'id' => $action->id,
                'description' => $action->description,
                'admin_name' => $action->admin->fullname ?? $action->admin->username ?? 'Admin',
                'time_ago' => $action->created_at->diffForHumans(),
            ];
        });

        return response()->json([
            'metrics' => [
                'totalRevenue' => (float) $totalRevenue,
                'revenueGrowthPercent' => $revenueGrowthPercent,
                'totalOrders' => $totalOrders,
                'totalCustomers' => $totalCustomers,
                'totalProducts' => $products->count(),
            ],
            'operations' => [
                'deliveryCount' => $staff->filter(fn($s) => $s->role?->name === 'delivery')->count(),
                'staffCount' => $staff->filter(fn($s) => $s->role?->name === 'staff')->count(),
                'extrasCount' => $extras->count(),
            ],
            'products' => $products->map(function ($p) {
                return [
                    'id' => $p->id,
                    'name' => $p->name,
                    'category' => $p->category?->name ?? 'General',
                    'price' => (float) $p->price,
                    'image' => asset('storage/' . $p->image),
                    'is_available' => (bool) $p->is_available,
                ];
            }),
            'staff' => $staff->map(function ($s) {
                return [
                    'id' => $s->id,
                    'fullname' => $s->fullname,
                    'username' => $s->username,
                    'role' => $s->role?->name ?? 'staff',
                    'phone' => $s->phone ?? '-',
                ];
            }),
            'extras' => $extras->map(function ($e) {
                return [
                    'id' => $e->id,
                    'name' => $e->name,
                    'price' => (float) $e->price,
                    'is_available' => (bool) $e->is_available,
                ];
            }),
            'categories' => $categories->pluck('name'),
            'recentActions' => $recentActions,
        ]);
    }
}