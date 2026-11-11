<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingZone extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'charge'    => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function getAreasArrayAttribute()
    {
        return $this->areas ? json_decode($this->areas, true) : [];
    }
}
