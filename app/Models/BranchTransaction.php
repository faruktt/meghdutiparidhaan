<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BranchTransaction extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'amount'           => 'decimal:2',
        'transaction_date' => 'date',
    ];

    const TYPE_INCOME  = 'income';
    const TYPE_EXPENSE = 'expense';

    public static function defaultExpenseCategories(): array
    {
        return [
            'Shop Rent'                => 'দোকান / শো-রুম ভাড়া (Rent)',
            'Staff Salary'             => 'স্টাফ বেতন ও বোনাস (Salary)',
            'Electricity & Utility'    => 'বিদ্যুৎ ও ইউটিলিটি বিল (Utility)',
            'Internet & Phone'         => 'ইন্টারনেট ও ফোন বিল',
            'Food & Refreshment'       => 'আপ্যায়ন ও চা-নাস্তা (Entertainment)',
            'Packaging & Bags'         => 'প্যাকেজিং ও শপিং ব্যাগ',
            'Transportation & Courier' => 'পরিবহন ও কুরিয়ার খরচ',
            'Maintenance & Repair'     => 'মেরামত ও রক্ষণাবেক্ষণ',
            'Marketing & Promo'        => 'মার্কেটিং ও লোকাল বিজ্ঞাপন',
            'Cleaning & Supplies'      => 'ক্লিনিং ও স্টেশনারি মালামাল',
            'Other Expense'            => 'অন্যান্য খরচ (Miscellaneous)',
        ];
    }

    public static function defaultIncomeCategories(): array
    {
        return [
            'Extra Sales / Service' => 'অতিরিক্ত বিক্রয় / সার্ভিস আয়',
            'Delivery Charge'       => 'ডেলিভারি কালেকশন',
            'Scrap / Box Sell'      => 'পুরনো কার্টুন / স্ক্র্যাপ বিক্রয়',
            'Capital / Investment'  => 'মালিকের বিনিয়োগ / ক্যাপিটাল জমা',
            'Loan / Advance Cash'   => 'ঋণ / অগ্রিম ক্যাশ ইন',
            'Other Income'          => 'অন্যান্য বিবিধ আয়',
        ];
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function creator()
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }

    public function scopeIncome($query)
    {
        return $query->where('type', self::TYPE_INCOME);
    }

    public function scopeExpense($query)
    {
        return $query->where('type', self::TYPE_EXPENSE);
    }
}
