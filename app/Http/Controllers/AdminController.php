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
        $stats = [
            'total_users'     => User::count(),
            'total_products'  => Product::count(),
            'total_orders'    => Order::count(),
            'pending_orders'  => Order::where('status', 'pending')->count(),
        ];

        $roles = Role::all();
        $categories = Category::all();
        $products = Product::with('category', 'extras')->latest()->get();
        $staffMembers = User::with('role')->whereHas('role', function ($q) {
            $q->whereIn('name', ['staff', 'delivery', 'admin']);
        })->latest()->get();
        $recentOrders = Order::with('user')->latest()->take(10)->get();
        $extras = Extra::all();

        return view('admin.Dashboard', compact('stats', 'roles', 'categories', 'products', 'staffMembers', 'recentOrders', 'extras'));
    }

    public function storeUser(Request $request)
    {
        $validated = $request->validate([
            'role_id'  => ['required', 'exists:roles,id'],
            'fullname' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:100', 'unique:users,username'],
            'email'    => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone'    => ['required', 'string', 'max:20'],
            'password' => ['required', 'string', 'min:6'],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return back()->with('success', 'Staff / Delivery user created successfully!');
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete yourself!');
        }
        $user->delete();
        return back()->with('success', 'User deleted successfully.');
    }

    public function storeCategory(Request $request)
    {
        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:100'],
            'type'        => ['required', 'in:food,drink,dessert,other'],
            'description' => ['nullable', 'string'],
        ]);

        Category::create($validated);

        return back()->with('success', 'Category created successfully!');
    }

    public function storeProduct(Request $request)
    {
        $validated = $request->validate([
            'category_id'  => ['required', 'exists:categories,id'],
            'name'         => ['required', 'string', 'max:255'],
            'description'  => ['nullable', 'string'],
            'price'        => ['required', 'numeric', 'min:0'],
            'is_available' => ['nullable', 'boolean'],
            'images'       => ['nullable', 'array', 'max:3'],
            'images.*'     => ['image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'extras'       => ['nullable', 'array'],
            'extras.*'     => ['exists:extras,id'],
        ]);

        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $path = $file->store('products', 'public');
                $imagePaths[] = $path;
            }
        }

        $product = Product::create([
            'category_id'  => $validated['category_id'],
            'name'         => $validated['name'],
            'description'  => $validated['description'] ?? null,
            'price'        => $validated['price'],
            'image'        => json_encode($imagePaths),
            'is_available' => $request->has('is_available') ? 1 : 0,
        ]);

        if (!empty($validated['extras'])) {
            $product->extras()->sync($validated['extras']);
        }

        return back()->with('success', 'Product created successfully!');
    }

    public function deleteProduct($id)
    {
        $product = Product::findOrFail($id);
        
        // Clean stored images
        $images = json_decode($product->image, true);
        if (is_array($images)) {
            foreach ($images as $img) {
                Storage::disk('public')->delete($img);
            }
        }

        $product->delete();
        return back()->with('success', 'Product removed successfully.');
    }

    public function storeExtra(Request $request)
    {
        $validated = $request->validate([
            'name'         => ['required', 'string', 'max:100'],
            'price'        => ['required', 'numeric', 'min:0'],
            'is_available' => ['nullable', 'boolean'],
        ]);

        $validated['is_available'] = $request->has('is_available') ? 1 : 0;

        Extra::create($validated);

        return back()->with('success', 'Extra add-on created successfully!');
    }
}