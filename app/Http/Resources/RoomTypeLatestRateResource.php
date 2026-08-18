<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Transforms a RoomType model into a representation that includes its latest rate.
 *
 * The `rates` relation must be pre-loaded with a single-element collection
 * (ordered by `valid_from` descending) for the nested {@see LatestRateResource}
 * to appear. Otherwise `latest_rate` is null.
 */
class RoomTypeLatestRateResource extends JsonResource
{
    /**
     * Convert the RoomType into an array for the API response.
     *
     * @return array{id: int, name: string, latest_rate: array{id: int, price: float, valid_from: string}|null}
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'latest_rate' => $this->relationLoaded('rates') && $this->resource->rates->isNotEmpty()
                ? new LatestRateResource($this->resource->rates->first())
                : null,
        ];
    }
}
