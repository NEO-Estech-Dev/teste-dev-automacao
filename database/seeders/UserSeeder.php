<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'type' => 'recruiter',
            'password' => Hash::make('12345678'),
            'created_at' => now()->format('Y-m-d H:i:s'),
        ]);
        User::factory(99)->create();
    }
}
