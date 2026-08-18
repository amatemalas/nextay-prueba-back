<?php

namespace App\Http\Controllers;

use App\Http\Resources\RoomTypeLatestRateResource;
use App\Http\Resources\RoomTypeSummaryResource;
use App\Models\RoomType;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class RoomTypeController extends Controller
{
    public function summary(): AnonymousResourceCollection
    {
        $roomTypes = RoomType::select('room_types.id', 'room_types.name')
            ->withCount('rates')
            ->withAvg('rates', 'price')
            ->orderBy('room_types.name')
            ->get();

        return RoomTypeSummaryResource::collection($roomTypes);
    }

    public function latestRates(): AnonymousResourceCollection
    {
        $roomTypes = RoomType::select('room_types.id', 'room_types.name')
            ->with(['rates' => function ($query) {
                $query->select('id', 'room_type_id', 'price', 'valid_from')
                    ->orderByDesc('valid_from')
                    ->limit(1);
            }])
            ->orderBy('room_types.name')
            ->get();

        return RoomTypeLatestRateResource::collection($roomTypes);
    }
}
