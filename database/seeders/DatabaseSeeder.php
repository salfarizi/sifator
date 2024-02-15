<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\User::factory(10)->create();

        \App\Models\User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'email_verified_at' => now(),
            'password' => bcrypt("admin"),
            'roles' => 'SUPER ADMIN', // password
            'remember_token' => Str::random(10),
        ]);
        \App\Models\Modal::factory()->create([
            'unique' => Str::orderedUuid(32),
            'modal' => 0
        ]);
        \App\Models\Menu::factory()->create([
            'unique' => Str::orderedUuid(32),
            'menu' => 'PENJUALAN',
        ]);
        \App\Models\Menu::factory()->create([
            'unique' => Str::orderedUuid(32),
            'menu' => 'PEMBELIAN',
        ]);
        \App\Models\Menu::factory()->create([
            'unique' => Str::orderedUuid(32),
            'menu' => 'MODAL',
        ]);
        \App\Models\Menu::factory()->create([
            'unique' => Str::orderedUuid(32),
            'menu' => 'REGISTER ORDER',
        ]);
        \App\Models\Menu::factory()->create([
            'unique' => Str::orderedUuid(32),
            'menu' => 'LAPORAN',
        ]);
        \App\Models\Menu::factory()->create([
            'unique' => Str::orderedUuid(32),
            'menu' => 'SETTING',
        ]);
    }
}
