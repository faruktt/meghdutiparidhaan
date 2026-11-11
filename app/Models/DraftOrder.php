<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DraftOrder extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'cart_data'       => 'array',
        'subtotal'        => 'decimal:2',
        'shipping_charge' => 'decimal:2',
        'total'           => 'decimal:2',
    ];

    public function shippingZone()
    {
        return $this->belongsTo(ShippingZone::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function getItemsCountAttribute()
    {
        if (empty($this->cart_data) || !is_array($this->cart_data)) {
            return 0;
        }
        return array_sum(array_column($this->cart_data, 'quantity'));
    }
}

