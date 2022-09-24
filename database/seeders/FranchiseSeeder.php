<?php

namespace Database\Seeders;

use App\Models\Franchisees;
use Database\Factories\FranchiseeFactory;
use Illuminate\Database\Seeder;

class FranchiseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Franchisees::factory()->count(10)->create();
    }
}
