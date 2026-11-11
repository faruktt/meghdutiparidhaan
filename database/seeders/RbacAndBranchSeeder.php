<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Branch;
use App\Models\Role;
use App\Models\Permission;
use App\Models\Admin;
use App\Models\Order;

class RbacAndBranchSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Default Branch
        $mainBranch = Branch::firstOrCreate(
            ['code' => 'BANANI'],
            [
                'name'      => 'Banani Branch',
                'code'      => 'BANANI',
                'phone'     => '01711000000',
                'email'     => 'banani@sogo.com',
                'address'   => 'House #42, Road #11, Block D, Banani, Dhaka-1213',
                'is_active' => true,
            ]
        );

        Branch::firstOrCreate(
            ['code' => 'DHANMONDI'],
            [
                'name'      => 'Dhanmondi Branch',
                'code'      => 'DHANMONDI',
                'phone'     => '01722000000',
                'email'     => 'dhanmondi@sogo.com',
                'address'   => 'Satmasjid Road, Dhanmondi, Dhaka',
                'is_active' => true,
            ]
        );

        Branch::firstOrCreate(
            ['code' => 'UTTARA'],
            [
                'name'      => 'Uttara Branch',
                'code'      => 'UTTARA',
                'phone'     => '01733000000',
                'email'     => 'uttara@sogo.com',
                'address'   => 'Sector 3, Uttara, Dhaka',
                'is_active' => true,
            ]
        );

        // 2. Permissions definition
        $permissions = [
            // Dashboard
            ['name' => 'View Dashboard',          'slug' => 'view_dashboard',          'module' => 'Dashboard'],
            // POS
            ['name' => 'Access POS',              'slug' => 'access_pos',              'module' => 'POS & Sales'],
            ['name' => 'Create POS Order',        'slug' => 'create_pos_order',        'module' => 'POS & Sales'],
            // Orders
            ['name' => 'View Orders',             'slug' => 'view_orders',             'module' => 'Orders'],
            ['name' => 'Update Order Status',     'slug' => 'update_order_status',     'module' => 'Orders'],
            ['name' => 'Print Order Invoice',     'slug' => 'print_invoice',           'module' => 'Orders'],
            ['name' => 'Delete Orders',           'slug' => 'delete_orders',           'module' => 'Orders'],
            // Catalog
            ['name' => 'View Products',           'slug' => 'view_products',           'module' => 'Products & Catalog'],
            ['name' => 'Manage Products',         'slug' => 'manage_products',         'module' => 'Products & Catalog'],
            ['name' => 'Manage Categories',       'slug' => 'manage_categories',       'module' => 'Products & Catalog'],
            ['name' => 'Manage Attributes',       'slug' => 'manage_attributes',       'module' => 'Products & Catalog'],
            // Branches
            ['name' => 'View Branches',           'slug' => 'view_branches',           'module' => 'Branches'],
            ['name' => 'Manage Branches',         'slug' => 'manage_branches',         'module' => 'Branches'],
            // Staff & Roles
            ['name' => 'View Staff',              'slug' => 'view_staff',              'module' => 'Staff & Roles'],
            ['name' => 'Manage Staff',            'slug' => 'manage_staff',            'module' => 'Staff & Roles'],
            ['name' => 'Manage Roles',            'slug' => 'manage_roles',            'module' => 'Staff & Roles'],
            // Reports
            ['name' => 'View Sales Report',       'slug' => 'view_sales_report',       'module' => 'Reports'],
            ['name' => 'View Stock Report',       'slug' => 'view_stock_report',       'module' => 'Reports'],
            ['name' => 'View Purchases Report',   'slug' => 'view_purchase_report',    'module' => 'Reports'],
            // Settings
            ['name' => 'Manage General Settings', 'slug' => 'manage_settings',         'module' => 'Settings'],
            ['name' => 'Manage Banners',          'slug' => 'manage_banners',          'module' => 'Settings'],
            ['name' => 'Manage Order Statuses',   'slug' => 'manage_order_statuses',   'module' => 'Settings'],
            ['name' => 'Manage Shipping Zones',   'slug' => 'manage_shipping',         'module' => 'Settings'],
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['slug' => $perm['slug']], $perm);
        }

        // 3. Roles
        $superAdminRole = Role::firstOrCreate(
            ['slug' => 'super-admin'],
            [
                'name'        => 'Super Admin',
                'slug'        => 'super-admin',
                'description' => 'Full access to all system features and all branches',
            ]
        );
        $allPermIds = Permission::pluck('id')->toArray();
        $superAdminRole->permissions()->sync($allPermIds);

        $branchManagerRole = Role::firstOrCreate(
            ['slug' => 'branch-manager'],
            [
                'name'        => 'Branch Manager',
                'slug'        => 'branch-manager',
                'description' => 'Can manage POS, Orders and View Reports for assigned branch',
            ]
        );
        $managerPerms = Permission::whereIn('slug', [
            'view_dashboard', 'access_pos', 'create_pos_order', 'view_orders',
            'update_order_status', 'print_invoice', 'view_products',
            'view_sales_report', 'view_stock_report'
        ])->pluck('id')->toArray();
        $branchManagerRole->permissions()->sync($managerPerms);

        $cashierRole = Role::firstOrCreate(
            ['slug' => 'pos-cashier'],
            [
                'name'        => 'POS Cashier',
                'slug'        => 'pos-cashier',
                'description' => 'Can create orders in POS and print invoices',
            ]
        );
        $cashierPerms = Permission::whereIn('slug', [
            'access_pos', 'create_pos_order', 'print_invoice'
        ])->pluck('id')->toArray();
        $cashierRole->permissions()->sync($cashierPerms);

        // 4. Update Super Admin user
        $admin = Admin::first();
        if ($admin) {
            $admin->update([
                'role_id'   => $superAdminRole->id,
                'branch_id' => null, // null means all branches
            ]);
        }

        // 5. Link any existing POS orders without branch to Main Branch
        Order::whereNull('branch_id')->where('source', 'pos')->update([
            'branch_id' => $mainBranch->id
        ]);
    }
}
