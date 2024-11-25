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
            'usuario' => 'admin1',
            'email' => 'admin1@transfers.com',
            'password' => Hash::make('admin123'),
        ]);
    }
}
