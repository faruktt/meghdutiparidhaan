<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'value'            => 'decimal:2',
        'min_order_amount' => 'decimal:2',
        'max_discount'     => 'decimal:2',
        'is_active'        => 'boolean',
        'starts_at'        => 'date',
        'expires_at'       => 'date',
    ];

    public function isValid($cartTotal)
    {
        if (!$this->is_active) return false;
        if ($this->expires_at && $this->expires_at->isPast()) return false;
        if ($this->starts_at && $this->starts_at->isFuture()) return false;
        if ($this->usage_limit && $this->used_count >= $this->usage_limit) return false;
        if ($cartTotal < $this->min_order_amount) return false;
        return true;
    }

    public function calculateDiscount($cartTotal)
    {
        if ($this->type === 'percentage') {
            $discount = ($cartTotal * $this->value) / 100;
            return $this->max_discount ? min($discount, $this->max_discount) : $discount;
        }
        return min($this->value, $cartTotal);
    }
}
