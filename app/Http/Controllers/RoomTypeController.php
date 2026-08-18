<?php

namespace App\Http\Controllers;

use App\Http\Resources\RoomTypeLatestRateResource;
use App\Http\Resources\RoomTypeSummaryResource;
use App\Models\RoomType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Log;

/**
 * Handles room type listing endpoints for the hotel pricing API.
 */
class RoomTypeController extends Controller
{
    /**
     * Return every room type with its rate count and average price.
     *
     * Fetches all room types ordered by name, annotated with the total
     * number of associated rates and the average price across those rates.
     * Room types with no rates return a count of 0 and a null average.
     */
    public function summary(): AnonymousResourceCollection|JsonResponse
    {
        try {
            $roomTypes = RoomType::select('room_types.id', 'room_types.name')
                ->withCount('rates')
                ->withAvg('rates', 'price')
                ->orderBy('room_types.name')
                ->get();

            return RoomTypeSummaryResource::collection($roomTypes);
        } catch (\Exception $e) {
            Log::error('Error al obtener el resumen de tipos de habitación: '.$e->getMessage());

            return response()->json([
                'message' => 'No pudimos cargar la información de los tipos de habitación. Por favor, inténtelo de nuevo más tarde.',
            ], 500);
        }
    }

    /**
     * Return every room type paired with its most recent rate.
     *
     * Eager-loads at most one rate per room type, selected by the
     * latest `valid_from` date (descending). Room types without any
     * rates receive a null `latest_rate` value.
     */
    public function latestRates(): AnonymousResourceCollection|JsonResponse
    {
        try {
            $roomTypes = RoomType::select('room_types.id', 'room_types.name')
                ->with(['rates' => function ($query) {
                    $query->select('id', 'room_type_id', 'price', 'valid_from')
                        ->orderByDesc('valid_from')
                        ->limit(1);
                }])
                ->orderBy('room_types.name')
                ->get();

            return RoomTypeLatestRateResource::collection($roomTypes);
        } catch (\Exception $e) {
            Log::error('Error al obtener las tarifas más recientes: '.$e->getMessage());

            return response()->json([
                'message' => 'No pudimos cargar las tarifas más recientes de los tipos de habitación. Por favor, inténtelo de nuevo más tarde.',
            ], 500);
        }
    }
}
