<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@rcjfoodhub.ph',
            'password' => Hash::make('password'),
            'role_id' => 1,
            'franchisees_id' => 1
        ]);
        User::factory()->count(5)->create();
    }
}
