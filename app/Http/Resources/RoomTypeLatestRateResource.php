<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoomTypeLatestRateResource extends JsonResource
{
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
