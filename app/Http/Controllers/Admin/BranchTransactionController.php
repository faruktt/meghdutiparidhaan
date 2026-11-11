<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\BranchTransaction;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BranchTransactionController extends Controller
{
    public function index(Request $request)
    {
        $admin = auth('admin')->user();
        $branches = Branch::where('is_active', true)->orderBy('name')->get();

        $query = BranchTransaction::with(['branch', 'creator'])->latest('transaction_date')->latest('id');

        // Scoping by branch
        if ($admin && $admin->branch_id) {
            $query->where('branch_id', $admin->branch_id);
            $selectedBranchId = $admin->branch_id;
        } elseif ($request->filled('branch_id')) {
            $query->where('branch_id', $request->branch_id);
            $selectedBranchId = $request->branch_id;
        } else {
            $selectedBranchId = null;
        }

        // Filter by Type (income / expense)
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter by Category
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Filter by Payment Method
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        // Filter by Date Range
        if ($request->filled('date_from')) {
            $query->whereDate('transaction_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('transaction_date', '<=', $request->date_to);
        }

        // Filter by Search (title, reference_no, note)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('reference_no', 'like', "%{$search}%")
                  ->orWhere('note', 'like', "%{$search}%");
            });
        }

        // Clone query for metrics before pagination
        $statsQuery = clone $query;
        $totalIncome = (clone $statsQuery)->where('type', 'income')->sum('amount');
        $totalExpense = (clone $statsQuery)->where('type', 'expense')->sum('amount');
        $netBalance = $totalIncome - $totalExpense;

        $transactions = $query->paginate(20)->withQueryString();

        $expenseCategories = BranchTransaction::defaultExpenseCategories();
        $incomeCategories = BranchTransaction::defaultIncomeCategories();

        return view('admin.branch_transactions.index', compact(
            'transactions', 'branches', 'selectedBranchId',
            'totalIncome', 'totalExpense', 'netBalance',
            'expenseCategories', 'incomeCategories'
        ));
    }

    public function store(Request $request)
    {
        $admin = auth('admin')->user();

        $request->validate([
            'branch_id'        => 'required|exists:branches,id',
            'type'             => 'required|in:income,expense',
            'category'         => 'required|string|max:100',
            'title'            => 'required|string|max:255',
            'amount'           => 'required|numeric|min:0.01',
            'transaction_date' => 'required|date',
            'payment_method'   => 'required|string|max:50',
            'reference_no'     => 'nullable|string|max:100',
            'note'             => 'nullable|string|max:1000',
            'receipt_file'     => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        // Security check: staff locked to a branch cannot post to another branch
        $branchId = ($admin && $admin->branch_id) ? $admin->branch_id : $request->branch_id;

        $receiptPath = null;
        if ($request->hasFile('receipt_file')) {
            $file = $request->file('receipt_file');
            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/receipts'), $fileName);
            $receiptPath = 'uploads/receipts/' . $fileName;
        }

        BranchTransaction::create([
            'branch_id'        => $branchId,
            'type'             => $request->type,
            'category'         => $request->category,
            'title'            => $request->title,
            'amount'           => $request->amount,
            'transaction_date' => $request->transaction_date,
            'payment_method'   => $request->payment_method,
            'reference_no'     => $request->reference_no,
            'receipt_file'     => $receiptPath,
            'note'             => $request->note,
            'created_by'       => $admin->id,
        ]);

        $typeLabel = $request->type === 'income' ? 'আয় (Income)' : 'ব্যয় (Expense)';
        return back()->with('success', "ব্রাঞ্চের নতুন {$typeLabel} সফলভাবে যুক্ত করা হয়েছে!");
    }

    public function update(Request $request, BranchTransaction $branchTransaction)
    {
        $admin = auth('admin')->user();

        $request->validate([
            'branch_id'        => 'required|exists:branches,id',
            'type'             => 'required|in:income,expense',
            'category'         => 'required|string|max:100',
            'title'            => 'required|string|max:255',
            'amount'           => 'required|numeric|min:0.01',
            'transaction_date' => 'required|date',
            'payment_method'   => 'required|string|max:50',
            'reference_no'     => 'nullable|string|max:100',
            'note'             => 'nullable|string|max:1000',
            'receipt_file'     => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $branchId = ($admin && $admin->branch_id) ? $admin->branch_id : $request->branch_id;

        $data = [
            'branch_id'        => $branchId,
            'type'             => $request->type,
            'category'         => $request->category,
            'title'            => $request->title,
            'amount'           => $request->amount,
            'transaction_date' => $request->transaction_date,
            'payment_method'   => $request->payment_method,
            'reference_no'     => $request->reference_no,
            'note'             => $request->note,
        ];

        if ($request->hasFile('receipt_file')) {
            $file = $request->file('receipt_file');
            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/receipts'), $fileName);
            $data['receipt_file'] = 'uploads/receipts/' . $fileName;
        }

        $branchTransaction->update($data);

        return back()->with('success', 'লেনদেনের বিবরণ সফলভাবে আপডেট করা হয়েছে!');
    }

    public function destroy(BranchTransaction $branchTransaction)
    {
        if ($branchTransaction->receipt_file && file_exists(public_path($branchTransaction->receipt_file))) {
            @unlink(public_path($branchTransaction->receipt_file));
        }

        $branchTransaction->delete();
        return back()->with('success', 'লেনদেন সফলভাবে মুছে ফেলা হয়েছে!');
    }

    /**
     * Financial & Profit/Loss Report by Branch
     */
    public function report(Request $request)
    {
        $admin = auth('admin')->user();
        $branches = Branch::where('is_active', true)->orderBy('name')->get();

        $from = $request->from ? Carbon::parse($request->from)->startOfDay() : Carbon::now()->startOfMonth();
        $to   = $request->to   ? Carbon::parse($request->to)->endOfDay()     : Carbon::now()->endOfDay();

        $branchId = ($admin && $admin->branch_id) ? $admin->branch_id : $request->branch_id;

        // 1. Orders Revenue (POS Sales + Online if no branch)
        $ordersQuery = Order::whereBetween('created_at', [$from, $to])
            ->whereNotIn('status', ['cancelled', 'returned']);

        if ($branchId) {
            if ($branchId === 'online') {
                $ordersQuery->whereNull('branch_id');
            } else {
                $ordersQuery->where('branch_id', $branchId);
            }
        }
        $totalSalesRevenue = $ordersQuery->sum('total');

        // 2. Manual Income
        $incomeQuery = BranchTransaction::income()
            ->whereBetween('transaction_date', [$from->format('Y-m-d'), $to->format('Y-m-d')]);
        if ($branchId && $branchId !== 'online') {
            $incomeQuery->where('branch_id', $branchId);
        }
        $totalOtherIncome = $incomeQuery->sum('amount');

        // 3. Manual Expenses
        $expenseQuery = BranchTransaction::expense()
            ->whereBetween('transaction_date', [$from->format('Y-m-d'), $to->format('Y-m-d')]);
        if ($branchId && $branchId !== 'online') {
            $expenseQuery->where('branch_id', $branchId);
        }
        $totalExpenses = $expenseQuery->sum('amount');

        // Totals
        $grossIncome = $totalSalesRevenue + $totalOtherIncome;
        $netProfit   = $grossIncome - $totalExpenses;

        // 4. Branch-by-Branch breakdown comparison
        $branchBreakdown = [];
        foreach ($branches as $branch) {
            $branchSales = Order::where('branch_id', $branch->id)
                ->whereBetween('created_at', [$from, $to])
                ->whereNotIn('status', ['cancelled', 'returned'])
                ->sum('total');

            $branchOtherIncome = BranchTransaction::income()
                ->where('branch_id', $branch->id)
                ->whereBetween('transaction_date', [$from->format('Y-m-d'), $to->format('Y-m-d')])
                ->sum('amount');

            $branchExp = BranchTransaction::expense()
                ->where('branch_id', $branch->id)
                ->whereBetween('transaction_date', [$from->format('Y-m-d'), $to->format('Y-m-d')])
                ->sum('amount');

            $branchTotalIn = $branchSales + $branchOtherIncome;
            $branchNet = $branchTotalIn - $branchExp;

            $branchBreakdown[] = [
                'branch'       => $branch,
                'sales'        => $branchSales,
                'other_income' => $branchOtherIncome,
                'total_income' => $branchTotalIn,
                'expense'      => $branchExp,
                'net_profit'   => $branchNet,
            ];
        }

        // 5. Expense breakdown by Category
        $categoryExpenses = BranchTransaction::expense()
            ->whereBetween('transaction_date', [$from->format('Y-m-d'), $to->format('Y-m-d')])
            ->when($branchId && $branchId !== 'online', fn ($q) => $q->where('branch_id', $branchId))
            ->selectRaw('category, SUM(amount) as total_amount, COUNT(*) as count')
            ->groupBy('category')
            ->orderByDesc('total_amount')
            ->get();

        // 6. Recent transactions in this period
        $recentTransactions = BranchTransaction::with(['branch', 'creator'])
            ->whereBetween('transaction_date', [$from->format('Y-m-d'), $to->format('Y-m-d')])
            ->when($branchId && $branchId !== 'online', fn ($q) => $q->where('branch_id', $branchId))
            ->latest('transaction_date')
            ->take(15)
            ->get();

        $expenseCategories = BranchTransaction::defaultExpenseCategories();
        $incomeCategories  = BranchTransaction::defaultIncomeCategories();

        return view('admin.reports.branch_finance', compact(
            'branches', 'branchId', 'from', 'to',
            'totalSalesRevenue', 'totalOtherIncome', 'grossIncome',
            'totalExpenses', 'netProfit', 'branchBreakdown',
            'categoryExpenses', 'recentTransactions',
            'expenseCategories', 'incomeCategories'
        ));
    }
}
