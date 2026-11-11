<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderStatus extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'is_default' => 'boolean',
        'is_active'  => 'boolean',
        'sort_order' => 'integer',
    ];

    public static function getActiveStatuses()
    {
        return static::where('is_active', true)->orderBy('sort_order')->get();
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'status', 'slug');
    }
}
