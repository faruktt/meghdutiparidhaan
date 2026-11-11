<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LandingPage extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'landing_page_products')
                    ->withPivot('sort_order')
                    ->orderByPivot('sort_order');
    }

    public function getBannerImageUrlAttribute()
    {
        return $this->banner_image ? asset('images/landing/' . $this->banner_image) : null;
    }
}
