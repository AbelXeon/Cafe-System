<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StaffController extends Controller
{
    public function index()
    {
        $staffRoleId = Role::where('name', 'staff')->value('id');
        $staff = User::where('role_id', $staffRoleId)->latest()->get();

        return view('admin.staff.index', compact('staff'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'fullname' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username'],
            'email' => ['nullable', 'email', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:20'],
            'password' => ['required', 'string', 'min:6'],
        ]);

        $staffRole = Role::where('name', 'staff')->firstOrFail();

        User::create([
            'role_id' => $staffRole->id,
            'fullname' => $data['fullname'],
            'username' => $data['username'],
            'email' => $data['email'] ?? null,
            'phone' => $data['phone'] ?? null,
            'password' => Hash::make($data['password']),
        ]);

        return back()->with('success', 'Staff member created.');
    }

    public function destroy(User $staff)
    {
        $staff->delete();

        return back()->with('success', 'Staff member removed.');
    }
}
