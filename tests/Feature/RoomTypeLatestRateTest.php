<?php

namespace Tests\Feature;

use App\Models\Rate;
use App\Models\RoomType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Tests the GET /api/room-types/latest-rates endpoint.
 *
 * Verifies that each room type is paired with its single most recent
 * rate (by valid_from date), that null is returned when no rates exist,
 * and that results are alphabetically ordered.
 */
class RoomTypeLatestRateTest extends TestCase
{
    use RefreshDatabase;

    /**
     * The most recent rate per room type is selected by valid_from descending.
     */
    public function test_latest_rates_returns_most_recent_rate_per_room_type(): void
    {
        $single = RoomType::create(['name' => 'Single']);
        $double = RoomType::create(['name' => 'Double']);

        Rate::factory()->create([
            'room_type_id' => $single->id,
            'price' => 100,
            'valid_from' => '2026-01-01',
        ]);
        $latestSingle = Rate::factory()->create([
            'room_type_id' => $single->id,
            'price' => 150,
            'valid_from' => '2026-06-01',
        ]);

        Rate::factory()->create([
            'room_type_id' => $double->id,
            'price' => 200,
            'valid_from' => '2026-03-01',
        ]);
        $latestDouble = Rate::factory()->create([
            'room_type_id' => $double->id,
            'price' => 250,
            'valid_from' => '2026-07-01',
        ]);

        $response = $this->getJson('/api/room-types/latest-rates');

        $response->assertOk()
            ->assertJsonCount(2, 'data')
            ->assertJsonFragment([
                'id' => $single->id,
                'name' => 'Single',
                'latest_rate' => [
                    'id' => $latestSingle->id,
                    'price' => 150.0,
                    'valid_from' => '2026-06-01',
                ],
            ])
            ->assertJsonFragment([
                'id' => $double->id,
                'name' => 'Double',
                'latest_rate' => [
                    'id' => $latestDouble->id,
                    'price' => 250.0,
                    'valid_from' => '2026-07-01',
                ],
            ]);
    }

    /**
     * A room type with no rates returns latest_rate as null.
     */
    public function test_latest_rates_returns_null_when_no_rates_exist(): void
    {
        $roomType = RoomType::create(['name' => 'Studio']);

        $response = $this->getJson('/api/room-types/latest-rates');

        $response->assertOk()
            ->assertJsonFragment([
                'id' => $roomType->id,
                'name' => 'Studio',
                'latest_rate' => null,
            ]);
    }

    /**
     * When no room types exist, the data array is empty.
     */
    public function test_latest_rates_returns_empty_array_when_no_room_types_exist(): void
    {
        $response = $this->getJson('/api/room-types/latest-rates');

        $response->assertOk()->assertJsonCount(0, 'data');
    }

    /**
     * When multiple rates share the same valid_from, one of them is returned.
     */
    public function test_latest_rates_picks_highest_valid_from_when_multiple_share_date(): void
    {
        $roomType = RoomType::create(['name' => 'Queen']);

        Rate::factory()->create([
            'room_type_id' => $roomType->id,
            'price' => 100,
            'valid_from' => '2026-06-01',
        ]);
        Rate::factory()->create([
            'room_type_id' => $roomType->id,
            'price' => 120,
            'valid_from' => '2026-06-01',
        ]);

        $response = $this->getJson('/api/room-types/latest-rates');

        $response->assertOk();

        $latestRate = $response->json('data')[0]['latest_rate'];
        $this->assertEquals('2026-06-01', $latestRate['valid_from']);
    }

    /**
     * Results are sorted alphabetically by room type name.
     */
    public function test_latest_rates_results_are_ordered_by_name(): void
    {
        RoomType::create(['name' => 'King']);
        RoomType::create(['name' => 'Deluxe']);

        $response = $this->getJson('/api/room-types/latest-rates');

        $response->assertOk();
        $data = $response->json('data');
        $names = array_column($data, 'name');
        $this->assertEquals(['Deluxe', 'King'], $names);
    }
}
