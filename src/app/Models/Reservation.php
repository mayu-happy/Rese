<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $dates = ['reserved_at', 'canceled_at', 'checked_in_at'];

    protected $fillable = [
        'user_id',
        'shop_id',
        'reserved_at',
        'people',
        'status',
        'note',
        'canceled_at',
        'checked_in_at'
    ];

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }
}
