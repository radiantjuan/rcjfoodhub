<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class FranchiseesFactory extends Factory {
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition() {
        return [
            'name' => $this->faker->municipality() . ' Branch',
            'location' => $this->faker->address(),
            'contact_person' => $this->faker->name(),
            'contact_number' => $this->faker->mobileNumber(),
            'is_inactive' => 0,
        ];
    }
}
