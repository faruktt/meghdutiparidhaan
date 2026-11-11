<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function staff()
    {
        return $this->hasMany(Admin::class, 'branch_id');
    }

    public function transactions()
    {
        return $this->hasMany(BranchTransaction::class);
    }

    public function incomes()
    {
        return $this->hasMany(BranchTransaction::class)->where('type', 'income');
    }

    public function expenses()
    {
        return $this->hasMany(BranchTransaction::class)->where('type', 'expense');
    }

    public function getTotalOrderSalesAttribute()
    {
        return $this->orders()->whereNotIn('status', ['cancelled', 'returned'])->sum('total');
    }

    public function getTotalOtherIncomeAttribute()
    {
        return $this->incomes()->sum('amount');
    }

    public function getTotalExpenseAttribute()
    {
        return $this->expenses()->sum('amount');
    }

    public function getNetBalanceAttribute()
    {
        return ($this->total_order_sales + $this->total_other_income) - $this->total_expense;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
