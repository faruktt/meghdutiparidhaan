<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function getImageUrlAttribute()
    {
        if (!$this->image) {
            return null;
        }

        if (\Illuminate\Support\Str::startsWith($this->image, ['http://', 'https://'])) {
            return $this->image;
        }

        if (\Illuminate\Support\Str::startsWith($this->image, 'images/banners/')) {
            return asset($this->image);
        }

        if (\Illuminate\Support\Str::startsWith($this->image, 'images/')) {
            return asset($this->image);
        }

        return asset('images/banners/' . $this->image);
    }
}
