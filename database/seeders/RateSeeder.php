<?php

namespace Database\Seeders;

use App\Models\Rate;
use App\Models\RoomType;
use Illuminate\Database\Seeder;

class RateSeeder extends Seeder
{
    public function run(): void
    {
        $prices = [
            'Single' => 80,
            'Double' => 120,
            'Twin' => 130,
            'Queen' => 150,
            'King' => 190,
            'Suite' => 300,
            'Deluxe' => 350,
            'Penthouse' => 500,
        ];

        foreach ($prices as $typeName => $basePrice) {
            $roomType = RoomType::where('name', $typeName)->first();

            if (! $roomType) {
                continue;
            }

            Rate::create([
                'room_type_id' => $roomType->id,
                'price' => $basePrice,
                'valid_from' => now()->subMonth()->format('Y-m-d'),
            ]);

            Rate::create([
                'room_type_id' => $roomType->id,
                'price' => $basePrice * 1.2,
                'valid_from' => now()->addMonth()->format('Y-m-d'),
            ]);
        }
    }
}
