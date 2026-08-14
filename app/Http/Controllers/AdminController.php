<?php
// app/Http/Controllers/AdminController.php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\Orders;
use App\Models\Category;
use App\Models\Products;
use App\Models\Extras;
use App\Models\AdminAction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_orders'    => Orders::count(),
            'pending_orders'  => Orders::where('status', 'pending')->count(),
            'total_products'  => Products::count(),
            'total_staff'     => User::whereHas('role', fn($q) => $q->where('name', 'staff'))->count(),
            'total_delivery'  => User::whereHas('role', fn($q) => $q->where('name', 'delivery'))->count(),
            'total_customers' => User::whereHas('role', fn($q) => $q->where('name', 'user'))->count(),
        ];

        $recentOrders = Orders::with('user')->latest()->take(5)->get();

        $staffAndDelivery = User::with('role')
            ->whereHas('role', fn($q) => $q->whereIn('name', ['staff', 'delivery']))
            ->latest()
            ->get();

        $recentUsers = $staffAndDelivery->take(5);

        $categories = Category::withCount('products')->latest()->get();
        $extras     = Extras::latest()->get();
        $products   = Products::with(['category', 'extras'])->latest()->get();

        return view('admin.dashboard', compact(
            'stats', 'recentOrders', 'recentUsers',
            'staffAndDelivery', 'categories', 'extras', 'products'
        ));
    }

    // ================= STAFF & DELIVERY =================

    public function storeUser(Request $request)
    {
        $validated = $request->validate([
            'fullname' => 'required|string|max:255',
            'phone'    => 'required|string|max:20|unique:users,phone',
            'email'    => 'required|email|unique:users,email',
            'username' => 'required|string|max:255|unique:users,username',
            'password' => 'required|string|min:6',
            'role'     => 'required|in:staff,delivery',
        ]);

        $role = Role::where('name', $validated['role'])->firstOrFail();

        $user = User::create([
            'fullname' => $validated['fullname'],
            'phone'    => $validated['phone'],
            'email'    => $validated['email'],
            'username' => $validated['username'],
            'password' => Hash::make($validated['password']),
            'role_id'  => $role->id,
        ]);

        AdminAction::create([
            'admin_id'    => Auth::id(),
            'action'      => 'create_user',
            'description' => "Created {$role->name} account '{$user->fullname}'",
        ]);

        return redirect()->route('admin.dashboard', ['tab' => 'staff'])
            ->with('success', ucfirst($role->name) . ' account created successfully.');
    }

    public function updateUser(Request $request, User $user)
    {
        $validated = $request->validate([
            'fullname' => 'required|string|max:255',
            'phone'    => 'required|string|max:20|unique:users,phone,' . $user->id,
            'email'    => 'required|email|unique:users,email,' . $user->id,
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'role'     => 'required|in:staff,delivery',
            'password' => 'nullable|string|min:6',
        ]);

        $role = Role::where('name', $validated['role'])->firstOrFail();

        $user->fullname = $validated['fullname'];
        $user->phone    = $validated['phone'];
        $user->email    = $validated['email'];
        $user->username = $validated['username'];
        $user->role_id  = $role->id;

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        AdminAction::create([
            'admin_id'    => Auth::id(),
            'action'      => 'update_user',
            'description' => "Updated account '{$user->fullname}'",
        ]);

        return redirect()->route('admin.dashboard', ['tab' => 'staff'])
            ->with('success', 'Account updated successfully.');
    }

    public function destroyUser(User $user)
    {
        $name = $user->fullname;
        $user->delete();

        AdminAction::create([
            'admin_id'    => Auth::id(),
            'action'      => 'delete_user',
            'description' => "Deleted account '{$name}'",
        ]);

        return redirect()->route('admin.dashboard', ['tab' => 'staff'])
            ->with('success', 'Account deleted.');
    }

    // ================= CATEGORIES =================

    public function storeCategory(Request $request)
    {
        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'type'         => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        Category::create($validated);

        AdminAction::create([
            'admin_id'    => Auth::id(),
            'action'      => 'create_category',
            'description' => "Created category '{$validated['name']}'",
        ]);

        return redirect()->route('admin.dashboard', ['tab' => 'menu'])
            ->with('success', 'Category created.');
    }

    public function updateCategory(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'type'         => 'nullable|string|max:255',
            'discribation' => 'nullable|string',
        ]);

        $category->update($validated);

        AdminAction::create([
            'admin_id'    => Auth::id(),
            'action'      => 'update_category',
            'description' => "Updated category '{$category->name}'",
        ]);

        return redirect()->route('admin.dashboard', ['tab' => 'menu'])
            ->with('success', 'Category updated.');
    }

    public function destroyCategory(Category $category)
    {
        if ($category->products()->exists()) {
            return redirect()->route('admin.dashboard', ['tab' => 'menu'])
                ->with('error', 'Cannot delete a category that still has products in it.');
        }

        $name = $category->name;
        $category->delete();

        AdminAction::create([
            'admin_id'    => Auth::id(),
            'action'      => 'delete_category',
            'description' => "Deleted category '{$name}'",
        ]);

        return redirect()->route('admin.dashboard', ['tab' => 'menu'])
            ->with('success', 'Category deleted.');
    }

    // ================= PRODUCTS =================

    public function storeProduct(Request $request)
    {
        $validated = $request->validate([
            'catagory_id'  => 'required|exists:catagori,id',
            'name'         => 'required|string|max:255',
            'discribtion'  => 'nullable|string',
            'price'        => 'required|numeric|min:0',
            'image'        => 'nullable|image|max:2048',
            'extras'       => 'nullable|array',
            'extras.*'     => 'exists:extras,id',
        ]);

        $validated['is_available'] = $request->boolean('is_available');

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $product = Products::create($validated);
        $product->extras()->sync($validated['extras'] ?? []);

        AdminAction::create([
            'admin_id'    => Auth::id(),
            'action'      => 'create_product',
            'description' => "Created product '{$product->name}'",
        ]);

        return redirect()->route('admin.dashboard', ['tab' => 'menu'])
            ->with('success', 'Product created.');
    }

    public function updateProduct(Request $request, Products $product)
    {
        $validated = $request->validate([
            'catagory_id'  => 'required|exists:catagori,id',
            'name'         => 'required|string|max:255',
            'discribtion'  => 'nullable|string',
            'price'        => 'required|numeric|min:0',
            'image'        => 'nullable|image|max:2048',
            'extras'       => 'nullable|array',
            'extras.*'     => 'exists:extras,id',
        ]);

        $validated['is_available'] = $request->boolean('is_available');

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($validated);
        $product->extras()->sync($validated['extras'] ?? []);

        AdminAction::create([
            'admin_id'    => Auth::id(),
            'action'      => 'update_product',
            'description' => "Updated product '{$product->name}'",
        ]);

        return redirect()->route('admin.dashboard', ['tab' => 'menu'])
            ->with('success', 'Product updated.');
    }

    public function destroyProduct(Products $product)
    {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $name = $product->name;
        $product->delete();

        AdminAction::create([
            'admin_id'    => Auth::id(),
            'action'      => 'delete_product',
            'description' => "Deleted product '{$name}'",
        ]);

        return redirect()->route('admin.dashboard', ['tab' => 'menu'])
            ->with('success', 'Product deleted.');
    }

    // ================= EXTRAS =================

    public function storeExtra(Request $request)
    {
        $validated = $request->validate([
            'name'  => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
        ]);

        $validated['is_available'] = $request->boolean('is_available');

        Extras::create($validated);

        AdminAction::create([
            'admin_id'    => Auth::id(),
            'action'      => 'create_extra',
            'description' => "Created extra '{$validated['name']}'",
        ]);

        return redirect()->route('admin.dashboard', ['tab' => 'menu'])
            ->with('success', 'Extra created.');
    }

    public function updateExtra(Request $request, Extras $extra)
    {
        $validated = $request->validate([
            'name'  => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
        ]);

        $validated['is_available'] = $request->boolean('is_available');

        $extra->update($validated);

        AdminAction::create([
            'admin_id'    => Auth::id(),
            'action'      => 'update_extra',
            'description' => "Updated extra '{$extra->name}'",
        ]);

        return redirect()->route('admin.dashboard', ['tab' => 'menu'])
            ->with('success', 'Extra updated.');
    }

    public function destroyExtra(Extras $extra)
    {
        $name = $extra->name;
        $extra->delete();

        AdminAction::create([
            'admin_id'    => Auth::id(),
            'action'      => 'delete_extra',
            'description' => "Deleted extra '{$name}'",
        ]);

        return redirect()->route('admin.dashboard', ['tab' => 'menu'])
            ->with('success', 'Extra deleted.');
    }
}