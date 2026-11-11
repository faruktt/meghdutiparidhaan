<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StaffController extends Controller
{
    public function index()
    {
        $staffMembers = Admin::with(['role', 'customPermissions'])->latest()->get();
        return view('admin.staff.index', compact('staffMembers'));
    }

    public function create()
    {
        $roles = Role::all();
        $permissions = Permission::all()->groupBy('module');
        return view('admin.staff.create', compact('roles', 'permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'               => 'required|string|max:255',
            'email'              => 'required|email|max:255|unique:admins,email',
            'phone'              => 'nullable|string|max:20',
            'password'           => 'required|string|min:6',
            'role_id'            => 'nullable|exists:roles,id',
            'is_active'          => 'nullable|boolean',
            'custom_permissions'=> 'nullable|array',
            'custom_permissions.*' => 'exists:permissions,id',
        ]);

        $admin = Admin::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'phone'     => $request->phone,
            'password'  => Hash::make($request->password),
            'role_id'   => $request->role_id ?: null,
            'branch_id' => null,
            'is_active' => $request->has('is_active') ? true : false,
        ]);

        if ($request->has('custom_permissions')) {
            $admin->customPermissions()->sync($request->custom_permissions);
        }

        return redirect()->route('admin.staff.index')->with('success', 'নতুন স্টাফ সফলভাবে যুক্ত হয়েছে!');
    }

    public function edit(Admin $staff)
    {
        $roles = Role::all();
        $permissions = Permission::all()->groupBy('module');
        $staffPermissions = $staff->customPermissions->pluck('id')->toArray();
        return view('admin.staff.edit', compact('staff', 'roles', 'permissions', 'staffPermissions'));
    }

    public function update(Request $request, Admin $staff)
    {
        $request->validate([
            'name'               => 'required|string|max:255',
            'email'              => 'required|email|max:255|unique:admins,email,' . $staff->id,
            'phone'              => 'nullable|string|max:20',
            'password'           => 'nullable|string|min:6',
            'role_id'            => 'nullable|exists:roles,id',
            'is_active'          => 'nullable|boolean',
            'custom_permissions'=> 'nullable|array',
            'custom_permissions.*' => 'exists:permissions,id',
        ]);

        $data = [
            'name'      => $request->name,
            'email'     => $request->email,
            'phone'     => $request->phone,
            'role_id'   => $request->role_id ?: null,
            'branch_id' => null,
            'is_active' => $request->has('is_active') ? true : false,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $staff->update($data);

        $staff->customPermissions()->sync($request->custom_permissions ?? []);

        return redirect()->route('admin.staff.index')->with('success', 'স্টাফ তথ্য সফলভাবে আপডেট হয়েছে!');
    }

    public function toggleStatus(Admin $staff)
    {
        if (auth('admin')->id() === $staff->id) {
            return response()->json(['success' => false, 'message' => 'আপনি নিজের আইডি ইন-অ্যাক্টিভ করতে পারবেন না!'], 400);
        }

        $staff->is_active = !$staff->is_active;
        $staff->save();

        return response()->json([
            'success'   => true,
            'is_active' => $staff->is_active,
            'message'   => 'স্টাফ স্ট্যাটাস সফলভাবে পরিবর্তিত হয়েছে!'
        ]);
    }

    public function destroy(Admin $staff)
    {
        if (auth('admin')->id() === $staff->id) {
            return back()->with('error', 'আপনি নিজের অ্যাকাউন্ট মুছে ফেলতে পারবেন না!');
        }

        $staff->customPermissions()->detach();
        $staff->delete();

        return redirect()->route('admin.staff.index')->with('success', 'স্টাফ সফলভাবে মুছে ফেলা হয়েছে!');
    }
}
