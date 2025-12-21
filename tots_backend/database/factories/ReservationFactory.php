<?php

namespace Database\Factories;

use App\Models\Reservation;
use App\Models\Space;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReservationFactory extends Factory
{
    protected $model = Reservation::class;

    public function definition(): array
    {
        $startTime = $this->faker->dateTimeThisMonth();
        $endTime = \DateTime::createFromFormat('Y-m-d H:i:s', $startTime->format('Y-m-d H:i:s'))
            ->modify('+2 hours');

        return [
            'space_id' => Space::factory(),
            'user_id' => User::factory(),
            'event_name' => $this->faker->words(3, true),
            'start_time' => $startTime,
            'end_time' => $endTime,
            'notes' => $this->faker->optional()->sentence(),
        ];
    }
}
