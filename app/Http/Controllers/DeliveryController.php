<?php

namespace App\Http\Controllers;

use App\Models\DeliveryProfiles;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DeliveryController extends Controller
{
     public function index()
    {
        $deliveryRoleId = Role::where('name', 'delivery')->value('id');
        $riders = User::where('role_id', $deliveryRoleId)->latest()->get();

        return view('admin.delivery.index', compact('riders'));
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

        $deliveryRole = Role::where('name', 'delivery')->firstOrFail();

        DB::transaction(function () use ($data, $deliveryRole) {
            $user = User::create([
                'role_id' => $deliveryRole->id,
                'fullname' => $data['fullname'],
                'username' => $data['username'],
                'email' => $data['email'] ?? null,
                'phone' => $data['phone'] ?? null,
                'password' => Hash::make($data['password']),
            ]);

            DeliveryProfiles::create([
                'user_id' => $user->id,
                'is_online' => false,
            ]);
        });

        return back()->with('success', 'Delivery rider created.');
    }

    public function destroy(User $delivery)
    {
        $delivery->delete();

        return back()->with('success', 'Delivery rider removed.');
    }
}
