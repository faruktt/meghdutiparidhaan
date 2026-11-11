<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'is_active'       => 'boolean',
        'is_featured'     => 'boolean',
        'is_new_arrival'  => 'boolean',
        'is_top_selling'  => 'boolean',
        'regular_price'   => 'decimal:2',
        'sale_price'      => 'decimal:2',
    ];

    public function scopeNewArrival($query)
    {
        return $query->where('is_new_arrival', true);
    }

    public function scopeTopSelling($query)
    {
        return $query->where('is_top_selling', true);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function subcategory()
    {
        return $this->belongsTo(Category::class, 'subcategory_id');
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function reviews()
    {
        return $this->hasMany(ProductReview::class)->where('is_approved', true)->latest();
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function landingPages()
    {
        return $this->belongsToMany(LandingPage::class, 'landing_page_products')
                    ->withPivot('sort_order');
    }

    public function getThumbnailUrlAttribute()
    {
        if (!$this->thumbnail) {
            // Elegant placeholder matching arjobd shirt aesthetic
            return 'https://images.unsplash.com/photo-1596755094514-f87e34085b2c?auto=format&fit=crop&w=800&q=80';
        }

        if (str_starts_with($this->thumbnail, 'http://') || str_starts_with($this->thumbnail, 'https://')) {
            return $this->thumbnail;
        }

        return asset('images/products/' . $this->thumbnail);
    }

    public function getCurrentPriceAttribute()
    {
        return $this->sale_price ?? $this->regular_price;
    }

    public function getDiscountPercentAttribute()
    {
        if ($this->sale_price && $this->regular_price > 0) {
            return round((($this->regular_price - $this->sale_price) / $this->regular_price) * 100);
        }
        return 0;
    }

    public function getTotalStockAttribute()
    {
        return $this->variants->sum('stock');
    }

    public function getAvailableColorsAttribute()
    {
        return $this->variants->where('stock', '>', 0)->pluck('color_id')->unique()->filter();
    }

    public function getAvailableSizesAttribute()
    {
        return $this->variants->where('stock', '>', 0)->pluck('size_id')->unique()->filter();
    }

    public function getAverageRatingAttribute()
    {
        return $this->reviews->avg('rating') ?? 0;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }
}
