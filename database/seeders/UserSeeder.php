<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Hapus data tanpa truncate (agar foreign key tidak masalah)
        DB::table('users')->delete();
        
        // Reset auto increment
        DB::statement('ALTER TABLE users AUTO_INCREMENT = 1');
        
        // Insert data user
        DB::table('users')->insert([
            [
                'username' => 'admin',
                'name' => 'Administrator',
                'password' => Hash::make('password123'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'username' => 'staff',
                'name' => 'Staff User',
                'password' => Hash::make('password123'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'username' => 'manager',
                'name' => 'Manager',
                'password' => Hash::make('password123'),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
        
        $this->command->info('✅ Users seeded successfully!');
        $this->command->info('Username: admin, staff, manager');
        $this->command->info('Password: password123');
    }
}