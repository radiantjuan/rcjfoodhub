<?php

namespace Database\Seeders;

use Faker\Factory;
use Illuminate\Database\Seeder;

class SiteSettingsSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $faker = Factory::create('en_PH');
        $store_info = [
            [
                'machine_name' => 'app.printer_name',
                'name' => 'Thermal Printer Name',
                'value' => 'POS-80',
            ],
            [
                'machine_name' => 'app.store_name',
                'name' => 'Store Name',
                'value' => $faker->name() . ' & ' . $faker->name() . ' Food Hub',
            ],
            [
                'machine_name' => 'app.store_address',
                'name' => 'Store Address',
                'value' => $faker->address(),
            ],
            [
                'machine_name' => 'app.store_phone',
                'name' => 'Store Phone',
                'value' => $faker->phoneNumber(),
            ],
            [
                'machine_name' => 'app.store_email',
                'name' => 'Store Email',
                'value' => $faker->safeEmail(),
            ],
            [
                'machine_name' => 'app.store_website',
                'name' => 'Store Website',
                'value' => $faker->url(),
            ],
        ];

        foreach ($store_info as $store) {
            $SiteSettings = new \App\Models\SiteSettings();
            $SiteSettings->name = $store['name'];
            $SiteSettings->machine_name = $store['machine_name'];
            $SiteSettings->value = $store['value'];
            $SiteSettings->save();
        }
    }
}
