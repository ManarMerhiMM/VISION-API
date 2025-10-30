<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::insert([
            [
                'is_admin' => true,
                'username' => 'Manar Merhi',
                'email' => 'manarmerhi80@gmail.com',
                'password' => Hash::make('TooFunky1$'),
                'gender' => 'male',
                'is_activated' => true,
                'account_type' => 'normal',
                'caretaker_name' => 'System',
                'created_at' => now(),
            ],
            [
                'is_admin' => true,
                'username' => 'Malek Shibli',
                'email' => 'malek@vision.com',
                'password' => Hash::make('malek123'),
                'gender' => 'male',
                'is_activated' => true,
                'account_type' => 'visually impaired',
                'caretaker_name' => 'Sarah Johnson',
                'created_at' => now(),
            ],
            [
                'is_admin' => true,
                'username' => 'manar',
                'email' => 'manar@vision.com',
                'password' => Hash::make('manar123'),
                'gender' => 'female',
                'is_activated' => true,
                'account_type' => 'normal',
                'caretaker_name' => 'Mohammad Shaaban',
                'created_at' => now(),
            ],
            [
                'is_admin' => true,
                'username' => 'abdulrahman',
                'email' => 'abdulrahman@vision.com',
                'password' => Hash::make('abdul123'),
                'gender' => 'male',
                'is_activated' => true,
                'account_type' => 'visually impaired',
                'caretaker_name' => 'Ali Hamdan',
                'created_at' => now(),
            ],
            [
                'is_admin' => true,
                'username' => 'mohammad',
                'email' => 'mohammad@vision.com',
                'password' => Hash::make('mohammad123'),
                'gender' => 'male',
                'is_activated' => true,
                'account_type' => 'normal',
                'caretaker_name' => 'Layla Kassem',
                'created_at' => now(),
            ],
        ]);
    }
}
