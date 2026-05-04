<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $email    = env('ADMIN_EMAIL', 'admin@flyseastravels.com');
        $password = env('ADMIN_PASSWORD', 'flyseas2026');

        User::updateOrCreate(
            ['email' => $email],
            [
                'name'     => 'FlySeas Admin',
                'password' => Hash::make($password),
                'is_admin' => true,
            ]
        );

        $this->command->info("Admin user ready: {$email}");
    }
}
