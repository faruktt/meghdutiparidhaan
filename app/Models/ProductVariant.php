<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'stock'            => 'integer',
        'price_adjustment' => 'decimal:2',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function color()
    {
        return $this->belongsTo(Color::class);
    }

    public function size()
    {
        return $this->belongsTo(Size::class);
    }

    public function getDisplayInfoAttribute()
    {
        $parts = [];
        if ($this->color) $parts[] = $this->color->name;
        if ($this->size)  $parts[] = 'Size: ' . $this->size->name;
        return implode(', ', $parts);
    }

    public function getImageUrlAttribute()
    {
        if (!$this->image) {
            return null;
        }
        if (str_starts_with($this->image, 'http://') || str_starts_with($this->image, 'https://')) {
            return $this->image;
        }
        return asset('images/products/' . $this->image);
    }
}
