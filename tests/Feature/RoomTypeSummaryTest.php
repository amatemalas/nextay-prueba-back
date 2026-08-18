<?php

namespace Tests\Feature;

use App\Models\Rate;
use App\Models\RoomType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoomTypeSummaryTest extends TestCase
{
    use RefreshDatabase;

    public function test_summary_returns_room_types_with_rate_count_and_average_price(): void
    {
        $single = RoomType::create(['name' => 'Single']);
        $double = RoomType::create(['name' => 'Double']);

        Rate::factory()->count(3)->create([
            'room_type_id' => $single->id,
            'price' => 100,
        ]);
        Rate::factory()->count(2)->create([
            'room_type_id' => $double->id,
            'price' => 200,
        ]);

        $response = $this->getJson('/api/room-types/summary');

        $response->assertOk()
            ->assertJsonCount(2, 'data')
            ->assertJsonFragment([
                'id' => $single->id,
                'name' => 'Single',
                'rates_count' => 3,
                'average_price' => 100.0,
            ])
            ->assertJsonFragment([
                'id' => $double->id,
                'name' => 'Double',
                'rates_count' => 2,
                'average_price' => 200.0,
            ]);
    }

    public function test_summary_returns_zero_count_and_null_average_for_room_type_without_rates(): void
    {
        $penthouse = RoomType::create(['name' => 'Penthouse']);

        $response = $this->getJson('/api/room-types/summary');

        $response->assertOk()
            ->assertJsonFragment([
                'id' => $penthouse->id,
                'name' => 'Penthouse',
                'rates_count' => 0,
                'average_price' => null,
            ]);
    }

    public function test_summary_returns_empty_array_when_no_room_types_exist(): void
    {
        $response = $this->getJson('/api/room-types/summary');

        $response->assertOk()->assertJsonCount(0, 'data');
    }

    public function test_summary_averages_multiple_different_prices(): void
    {
        $roomType = RoomType::create(['name' => 'Suite']);

        Rate::factory()->create([
            'room_type_id' => $roomType->id,
            'price' => 100,
        ]);
        Rate::factory()->create([
            'room_type_id' => $roomType->id,
            'price' => 200,
        ]);
        Rate::factory()->create([
            'room_type_id' => $roomType->id,
            'price' => 300,
        ]);

        $response = $this->getJson('/api/room-types/summary');

        $response->assertOk()
            ->assertJsonFragment([
                'id' => $roomType->id,
                'name' => 'Suite',
                'rates_count' => 3,
                'average_price' => 200.0,
            ]);
    }

    public function test_summary_results_are_ordered_by_name(): void
    {
        RoomType::create(['name' => 'King']);
        RoomType::create(['name' => 'Deluxe']);

        $response = $this->getJson('/api/room-types/summary');

        $response->assertOk();
        $data = $response->json('data');
        $names = array_column($data, 'name');
        $this->assertEquals(['Deluxe', 'King'], $names);
    }
}
