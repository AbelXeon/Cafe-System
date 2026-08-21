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
        $categories = Category::orderBy('name')->get();
        $products = Product::with('category')->latest()->get();
        $extras = Extra::latest()->get();
        $staff = User::with('role')
            ->whereHas('role', fn ($q) => $q->whereIn('name', ['staff', 'delivery']))
            ->latest()
            ->get();
        $roles = Role::whereIn('name', ['staff', 'delivery'])->get();

        return view('admin.dashboard', compact('categories', 'products', 'extras', 'staff', 'roles'));
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