<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Transforms a Rate model into a lightweight representation.
 *
 * Used as a nested resource inside {@see RoomTypeLatestRateResource}.
 */
class LatestRateResource extends JsonResource
{
    /**
     * Convert the Rate into an array for the API response.
     *
     * @return array{id: int, price: float, valid_from: string}
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'price' => (float) $this->price,
            'valid_from' => $this->valid_from,
        ];
    }
}
