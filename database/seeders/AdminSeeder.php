<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Administrator PAO',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('Monitoring112233@'),
            'role' => 'user', 
        ]);
    }
}