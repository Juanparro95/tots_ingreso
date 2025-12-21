<?php

namespace Database\Factories;

use App\Models\Space;
use Illuminate\Database\Eloquent\Factories\Factory;

class SpaceFactory extends Factory
{
    protected $model = Space::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->words(3, true),
            'description' => $this->faker->sentence(),
            'capacity' => $this->faker->numberBetween(5, 100),
            'location' => $this->faker->address(),
            'hourly_rate' => $this->faker->numberBetween(50, 500),
        ];
    }
}
