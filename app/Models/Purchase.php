<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'total_amount'  => 'decimal:2',
        'paid_amount'   => 'decimal:2',
        'purchase_date' => 'date',
    ];

    public static function generatePurchaseNumber()
    {
        return 'PUR-' . strtoupper(uniqid());
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function items()
    {
        return $this->hasMany(PurchaseItem::class);
    }

    public function getDueAmountAttribute()
    {
        return $this->total_amount - $this->paid_amount;
    }
}
