<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin Account
        User::factory()->create([
            'name'              => 'Admin',
            'email'             => 'admin@officone.com',
            'password'          => bcrypt('admin123'),
            'role'              => 'admin',
            'email_verified_at' => now(),
        ]);

        // Regular User Account
        User::factory()->create([
            'name'              => 'John Regan',
            'email'             => 'user@officone.com',
            'password'          => bcrypt('user1234'),
            'role'              => 'user',
            'email_verified_at' => now(),
        ]);

        $this->call([
            ProductSeeder::class,
            OrderAndReviewSeeder::class,
        ]);
    }
}
