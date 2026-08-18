<?php

namespace Database\Factories;

use App\Models\RoomType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<RoomType>
 */
class RoomTypeFactory extends Factory
{
    protected $model = RoomType::class;

    public function definition(): array
    {
        return [
            'name' => fake()->unique()->randomElement([
                'Single',
                'Double',
                'Twin',
                'Triple',
                'Queen',
                'King',
                'Suite',
                'Deluxe',
                'Penthouse',
                'Studio',
                'Presidential',
                'Family',
            ]),
        ];
    }
}
