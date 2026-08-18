<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Represents a pricing entry for a specific room type.
 *
 * A rate binds a price to a `valid_from` date, allowing the system
 * to track how room pricing evolves over time.
 */
class Rate extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_type_id',
        'price',
        'valid_from',
    ];

    /**
     * Get the room type that owns this rate.
     */
    public function roomType(): BelongsTo
    {
        return $this->belongsTo(RoomType::class);
    }
}
