<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Transforms a RoomType model into a summary representation.
 *
 * Expects the underlying model to carry `withCount('rates')` and
 * `withAvg('rates', 'price')` aggregates from the controller query.
 */
class RoomTypeSummaryResource extends JsonResource
{
    /**
     * Convert the RoomType into an array for the API response.
     *
     * @return array{id: int, name: string, rates_count: int, average_price: float|null}
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'rates_count' => $this->rates_count,
            'average_price' => $this->rates_avg_price !== null
                ? round($this->rates_avg_price, 2)
                : null,
        ];
    }
}
