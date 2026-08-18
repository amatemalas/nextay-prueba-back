<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rate extends Model
{
    protected $fillable = [
        'room_type_id',
        'price',
        'valid_from',
    ];

    public function roomType()
    {
        return $this->belongsTo(RoomType::class);
    }
}
