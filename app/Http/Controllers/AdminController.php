<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\User;
use App\Models\categories;
use App\Models\Products;
use App\Models\Extras;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function index() {
        // Simple check for role
        if(auth()->user()->role->name !== 'admin') return abort(403);
        return view('admin.dashboard');
    }

    public function manageUsers() {
        $roles = Role::whereIn('name', ['staff', 'delivery'])->get();
        $users = User::whereIn('role_id', $roles->pluck('id'))->get();
        return view('admin.users', compact('roles', 'users'));
    }

    public function storeUser(Request $request) {
        $request->validate([
            'fullname' => 'required',
            'username' => 'required|unique:users',
            'role_id'  => 'required',
            'password' => 'required|min:4'
        ]);

        User::create([
            'fullname' => $request->fullname,
            'username' => $request->username,
            'email'    => $request->username . "@system.com",
            'phone'    => $request->phone ?? '0000',
            'role_id'  => $request->role_id,
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Staff/Delivery created successfully!');
    }

    public function manageProducts() {
        $categories = categories::all();
        $products = Products::with('category')->get();
        $extras = Extras::all();
        return view('admin.products', compact('categories', 'products', 'extras'));
    }

    public function storeProduct(Request $request) {
        $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'image' => 'image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }

        Products::create([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'description' => $request->description,
            'price' => $request->price,
            'image' => $imagePath,
            'is_available' => true
        ]);

        return back()->with('success', 'Product added!');
    }

    public function storeExtra(Request $request) {
        Extras::create($request->all());
        return back()->with('success', 'Extra added!');
    }
}
