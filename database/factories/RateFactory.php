<?php

namespace Database\Factories;

use App\Models\Rate;
use App\Models\RoomType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Rate>
 */
class RateFactory extends Factory
{
    protected $model = Rate::class;

    public function definition(): array
    {
        return [
            'room_type_id' => RoomType::factory(),
            'price' => fake()->randomFloat(2, 40, 500),
            'valid_from' => fake()->dateTimeBetween('-3 months', '+3 months')->format('Y-m-d'),
        ];
    }
}
