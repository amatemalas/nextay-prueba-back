<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoomTypeSummaryResource extends JsonResource
{
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
