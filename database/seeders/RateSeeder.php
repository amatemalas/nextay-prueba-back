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

        $monthsBack = 3;
        $monthsForward = 8;

        foreach ($prices as $typeName => $basePrice) {
            $roomType = RoomType::where('name', $typeName)->first();

            if (! $roomType) {
                continue;
            }

            for ($i = -$monthsBack; $i <= $monthsForward; $i++) {
                Rate::create([
                    'room_type_id' => $roomType->id,
                    'price' => $basePrice,
                    'valid_from' => now()->addMonths($i)->format('Y-m-d'),
                ]);
            }
        }
    }
}
