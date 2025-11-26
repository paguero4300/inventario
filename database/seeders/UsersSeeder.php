<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    public function run(): void
    {
        // Check if user exists before creating to avoid duplicates if re-running
        if (!User::where('email', 'admin@fence.com')->exists()) {
            $admin = User::create([
                'name' => 'Admin User',
                'email' => 'admin@fence.com',
                'password' => Hash::make('password'),
                'is_active' => true,
            ]);
            $admin->assignRole('admin');
        }

        // Ensure super_admin role for Luis if he exists (created via command line)
        $luis = User::where('email', 'luis@alva.pe')->first();
        if ($luis) {
            $luis->assignRole('super_admin');
        }
    }
}
