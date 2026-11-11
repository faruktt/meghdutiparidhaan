<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    public function index()
    {
        $branches = Branch::withCount(['orders', 'staff'])
            ->withSum(['orders as total_sales' => function ($q) {
                $q->whereNotIn('status', ['cancelled', 'returned']);
            }], 'total')
            ->withSum(['expenses as total_expenses'], 'amount')
            ->withSum(['incomes as total_other_income'], 'amount')
            ->latest()
            ->get();
        return view('admin.branches.index', compact('branches'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'code'      => 'required|string|max:50|unique:branches,code',
            'phone'     => 'nullable|string|max:20',
            'email'     => 'nullable|email|max:255',
            'address'   => 'nullable|string|max:500',
            'is_active' => 'nullable|boolean',
        ]);

        Branch::create([
            'name'      => $request->name,
            'code'      => strtoupper(trim($request->code)),
            'phone'     => $request->phone,
            'email'     => $request->email,
            'address'   => $request->address,
            'is_active' => $request->has('is_active') ? true : false,
        ]);

        return redirect()->route('admin.branches.index')->with('success', 'নতুন ব্রাঞ্চ সফলভাবে তৈরি হয়েছে!');
    }

    public function update(Request $request, Branch $branch)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'code'      => 'required|string|max:50|unique:branches,code,' . $branch->id,
            'phone'     => 'nullable|string|max:20',
            'email'     => 'nullable|email|max:255',
            'address'   => 'nullable|string|max:500',
            'is_active' => 'nullable|boolean',
        ]);

        $branch->update([
            'name'      => $request->name,
            'code'      => strtoupper(trim($request->code)),
            'phone'     => $request->phone,
            'email'     => $request->email,
            'address'   => $request->address,
            'is_active' => $request->has('is_active') ? true : false,
        ]);

        return redirect()->route('admin.branches.index')->with('success', 'ব্রাঞ্চ তথ্য সফলভাবে আপডেট হয়েছে!');
    }

    public function toggleStatus(Branch $branch)
    {
        $branch->is_active = !$branch->is_active;
        $branch->save();

        return response()->json([
            'success'   => true,
            'is_active' => $branch->is_active,
            'message'   => 'ব্রাঞ্চ স্ট্যাটাস পরিবর্তিত হয়েছে!'
        ]);
    }

    public function destroy(Branch $branch)
    {
        if ($branch->orders()->count() > 0) {
            return back()->with('error', 'এই ব্রাঞ্চে অর্ডার রেকর্ড বিদ্যমান থাকায় এটি ডিলিট করা সম্ভব নয়। আপনি এটিকে ইন-অ্যাক্টিভ (Inactive) করতে পারেন।');
        }

        $branch->staff()->update(['branch_id' => null]);
        $branch->delete();

        return redirect()->route('admin.branches.index')->with('success', 'ব্রাঞ্চ সফলভাবে মুছে ফেলা হয়েছে!');
    }
}
