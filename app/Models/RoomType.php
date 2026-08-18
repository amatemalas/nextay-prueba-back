<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Represents a hotel room type (e.g. Single, Double, Suite).
 *
 * Each room type has many pricing rates that define how its price
 * changes over time.
 */
class RoomType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    /**
     * Get all pricing rates belonging to this room type.
     *
     * Rates are cascade-deleted when the room type is removed.
     */
    public function rates(): HasMany
    {
        return $this->hasMany(Rate::class);
    }
}
