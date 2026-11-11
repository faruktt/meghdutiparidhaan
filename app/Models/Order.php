<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'subtotal'         => 'decimal:2',
        'shipping_charge'  => 'decimal:2',
        'discount_amount'  => 'decimal:2',
        'total'            => 'decimal:2',
        'confirmed_at'     => 'datetime',
        'shipped_at'       => 'datetime',
        'delivered_at'     => 'datetime',
    ];

    const STATUS_PENDING    = 'pending';
    const STATUS_CONFIRMED  = 'confirmed';
    const STATUS_PROCESSING = 'processing';
    const STATUS_SHIPPED    = 'shipped';
    const STATUS_DELIVERED  = 'delivered';
    const STATUS_CANCELLED  = 'cancelled';
    const STATUS_RETURNED   = 'returned';

    public static function getStatuses()
    {
        try {
            $statuses = OrderStatus::where('is_active', true)->orderBy('sort_order')->pluck('name', 'slug')->toArray();
            if (!empty($statuses)) {
                return $statuses;
            }
        } catch (\Throwable $e) {}

        return [
            self::STATUS_PENDING    => 'Pending',
            self::STATUS_CONFIRMED  => 'Confirmed',
            self::STATUS_PROCESSING => 'Processing',
            self::STATUS_SHIPPED    => 'Shipped',
            self::STATUS_DELIVERED  => 'Delivered',
            self::STATUS_CANCELLED  => 'Cancelled',
            self::STATUS_RETURNED   => 'Returned',
        ];
    }

    public static function generateOrderNumber()
    {
        return 'ORD-' . strtoupper(uniqid());
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function shippingZone()
    {
        return $this->belongsTo(ShippingZone::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function statusModel()
    {
        return $this->belongsTo(OrderStatus::class, 'status', 'slug');
    }

    public function getStatusBadgeColorAttribute()
    {
        if ($this->statusModel && $this->statusModel->color) {
            return $this->statusModel->color;
        }

        return match ($this->status) {
            'pending'    => '#d97706',
            'confirmed'  => '#2563eb',
            'processing' => '#7c3aed',
            'shipped'    => '#0284c7',
            'delivered'  => '#16a34a',
            'cancelled'  => '#dc2626',
            'returned'   => '#c2410c',
            default      => '#6b7280',
        };
    }

    public function getStatusBadgeBgAttribute()
    {
        if ($this->statusModel && $this->statusModel->bg_color) {
            return $this->statusModel->bg_color;
        }

        return match ($this->status) {
            'pending'    => '#fef3c7',
            'confirmed'  => '#dbeafe',
            'processing' => '#ede9fe',
            'shipped'    => '#e0f2fe',
            'delivered'  => '#dcfce7',
            'cancelled'  => '#fee2e2',
            'returned'   => '#ffedd5',
            default      => '#f3f4f6',
        };
    }

    public function getStatusRowBgAttribute()
    {
        if ($this->statusModel && $this->statusModel->row_bg) {
            return $this->statusModel->row_bg;
        }

        return match ($this->status) {
            'pending'    => '#fffdf0',
            'confirmed'  => '#f8fafc',
            'processing' => '#faf5ff',
            'shipped'    => '#f0f9ff',
            'delivered'  => '#f0fdf4',
            'cancelled'  => '#fef2f2',
            'returned'   => '#fff7ed',
            default      => '#ffffff',
        };
    }

    public function getStatusLabelAttribute()
    {
        if ($this->statusModel && $this->statusModel->name) {
            return $this->statusModel->name;
        }
        return self::getStatuses()[$this->status] ?? ucfirst($this->status);
    }

    public function getCourierNameFormattedAttribute()
    {
        return match ($this->courier_name) {
            'steadfast' => 'Steadfast',
            'pathao'    => 'Pathao',
            'redx'      => 'RedX',
            default     => ucfirst($this->courier_name ?? ''),
        };
    }
}
