<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;

class AdminSeeder extends Seeder {
    public function run(): void {
        Admin::create([
            'email' => 'kfc@jgmail.org',
            'password' => Hash::make('4Xm"P!1x5'),
        ]);
    }
}
