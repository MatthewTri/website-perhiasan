<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        // Create Admin User
        User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('123123'),
            'is_admin' => true,
        ]);

        // Create Regular User
        User::create([
            'name' => 'User',
            'email' => 'user@gmail.com',
            'password' => Hash::make('123123'),
            'is_admin' => false,
        ]);

        // Create Parent Categories
        $categories = [
            ['name' => 'Rings', 'slug' => 'rings', 'parent_id' => null],
            ['name' => 'Necklaces', 'slug' => 'necklaces', 'parent_id' => null],
            ['name' => 'Bracelets', 'slug' => 'bracelets', 'parent_id' => null],
            ['name' => 'Earrings', 'slug' => 'earrings', 'parent_id' => null],
            ['name' => 'Stones', 'slug' => 'stones', 'parent_id' => null],
        ];

        foreach ($categories as $category) {
            \App\Models\Category::create($category);
        }
    }
}
