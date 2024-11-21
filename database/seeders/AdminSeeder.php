<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run()
    {
        Admin::create([
            'usuario' => 'admin',
            'email' => 'admin@transfers.com',
            'password' => Hash::make('admin123'),
        ]);
    }
}
