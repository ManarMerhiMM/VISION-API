<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder // Creates 5 admin accounts for VISION team.
{
    public function run(): void
    {
        User::insert([
            [
                'is_admin' => true,
                'username' => 'Manar Merhi',
                'email' => 'manarmerhi80@gmail.com',
                'password' => Hash::make('password'),
                'gender' => 'male',
                'is_activated' => true,
                'account_type' => 'normal',
                'date_of_birth' => '2005-01-26',
                'created_at' => now(),
            ],
            [
                'is_admin' => true,
                'username' => 'Malek Shibli',
                'email' => 'shiblimalek9@gmail.com',
                'password' => Hash::make('password'),
                'gender' => 'male',
                'is_activated' => true,
                'account_type' => 'normal',
                'date_of_birth' => '2004-08-05',
                'created_at' => now(),
            ],
            [
                'is_admin' => true,
                'username' => 'Mohammad El Halabi',
                'email' => 'elhalabimohammad6@gmail.com',
                'password' => Hash::make('password'),
                'gender' => 'male',
                'is_activated' => true,
                'account_type' => 'normal',
                'date_of_birth' => '2003-07-22',
                'created_at' => now(),
            ],
            [
                'is_admin' => true,
                'username' => 'Abdulrahman Nakouzi',
                'email' => 'abedelrahman.nakouzi@gmail.com',
                'password' => Hash::make('password'),
                'gender' => 'male',
                'is_activated' => true,
                'account_type' => 'normal',
                'date_of_birth' => '2003-03-03',
                'created_at' => now(),
            ],
            [
                'is_admin' => true,
                'username' => 'Mohammad Shaaban',
                'email' => 'Shaabanmohammad302@gmail.com',
                'password' => Hash::make('password'),
                'gender' => 'male',
                'is_activated' => true,
                'account_type' => 'normal',
                'date_of_birth' => '2003-11-22',
                'created_at' => now(),
            ],
        ]);
    }
}