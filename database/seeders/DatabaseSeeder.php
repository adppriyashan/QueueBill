<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create Administrative Console User
        $admin = User::create([
            'name' => 'Administrator',
            'email' => 'admin@queuebill.com',
            'password' => Hash::make('password'),
        ]);
    }
}
