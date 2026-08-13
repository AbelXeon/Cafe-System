<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::where('name', 'admin')->first();

        User::updateOrCreate(
            ['username' => env('ADMIN_USERNAME')],
            [
                'role_id'  => $adminRole->id,
                'fullname' => env('ADMIN_FULLNAME'),
                'email'    => env('ADMIN_EMAIL'),
                'phone'    => env('ADMIN_PHONE'),
                'password' => Hash::make(env('ADMIN_PASSWORD')),
            ]
        );
    }
}