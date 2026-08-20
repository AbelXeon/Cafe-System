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
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function dashboard()
    {
        $staffRoleId = Role::where('name', 'staff')->value('id');
        $deliveryRoleId = Role::where('name', 'delivery')->value('id');

        $stats = [
            'staff_count' => User::where('role_id', $staffRoleId)->count(),
            'delivery_count' => User::where('role_id', $deliveryRoleId)->count(),
            'product_count' => Product::count(),
            'category_count' => Category::count(),
            'order_count' => Order::count(),
        ];

        $recentOrders = Order::latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentOrders'));
    }
}