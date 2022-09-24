<?php

namespace Database\Seeders;

use App\Models\Supplies;
use Illuminate\Database\Seeder;

class SuppliesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Supplies::factory()->count(20)->create();
    }
}
