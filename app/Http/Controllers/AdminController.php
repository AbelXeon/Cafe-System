<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\User;
use App\Models\Orders;
use App\Models\categories;
use App\Models\Products;
use App\Models\Extras;
use App\Models\AdminAction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_orders'      => Orders::count(),
            'pending_orders'    => Orders::where('status', 'pending')->count(),
            'total_products'    => Products::count(),
            'total_staff'       => User::whereHas('role', fn($q) => $q->where('name', 'staff'))->count(),
            'total_delivery'    => User::whereHas('role', fn($q) => $q->where('name', 'delivery'))->count(),
            'total_customers'   => User::whereHas('role', fn($q) => $q->where('name', 'user'))->count(),
        ];

        $recentOrders = Orders::with('user')->latest()->take(5)->get();
        $recentUsers  = User::with('role')->latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentOrders', 'recentUsers'));
    }

    public function users(Request $request)
    {
        $roleFilter = $request->query('role'); // 'staff' or 'delivery'

        $users = User::with('role')
            ->whereHas('role', function ($q) use ($roleFilter) {
                if ($roleFilter) {
                    $q->where('name', $roleFilter);
                } else {
                    $q->whereIn('name', ['staff', 'delivery']);
                }
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $roles = Role::whereIn('name', ['staff', 'delivery'])->get();

        return view('admin.users', compact('users', 'roles', 'roleFilter'));
    }

    public function storeUser(Request $request)
    {
        $validated = $request->validate([
            'fullname' => 'required|string|max:255',
            'phone'    => 'required|string|max:20|unique:users,phone',
            'email'    => 'required|email|unique:users,email',
            'username' => 'required|string|max:255|unique:users,username',
            'password' => 'required|string|min:6',
            'role_id'  => 'required|exists:roles,id',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $user = User::create($validated);

        AdminAction::create([
            'admin_id'    => Auth::id(),
            'action'      => 'create_user',
            'description' => "Created user '{$user->fullname}' with role '{$user->role->name}'",
        ]);

        return back()->with('success', 'User created successfully.');
    }

    public function updateUser(Request $request, User $user)
    {
        $validated = $request->validate([
            'fullname' => 'required|string|max:255',
            'phone'    => 'required|string|max:20|unique:users,phone,' . $user->id,
            'email'    => 'required|email|unique:users,email,' . $user->id,
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'role_id'  => 'required|exists:roles,id',
        ]);

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($request->password);
        }

        $user->update($validated);

        AdminAction::create([
            'admin_id'    => Auth::id(),
            'action'      => 'update_user',
            'description' => "Updated user '{$user->fullname}'",
        ]);

        return back()->with('success', 'User updated successfully.');
    }

    public function destroyUser(User $user)
    {
        $name = $user->fullname;
        $user->delete();

        AdminAction::create([
            'admin_id'    => Auth::id(),
            'action'      => 'delete_user',
            'description' => "Deleted user '{$name}'",
        ]);

        return back()->with('success', 'User deleted successfully.');
    }
}
