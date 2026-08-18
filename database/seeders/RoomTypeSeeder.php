<?php

namespace Database\Seeders;

use App\Models\RoomType;
use Illuminate\Database\Seeder;

class RoomTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            'Single',
            'Double',
            'Twin',
            'Queen',
            'King',
            'Suite',
            'Deluxe',
            'Penthouse',
        ];

        foreach ($types as $name) {
            RoomType::firstOrCreate(['name' => $name]);
        }
    }
}
