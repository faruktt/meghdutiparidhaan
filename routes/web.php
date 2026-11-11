<?php

use Illuminate\Support\Facades\Route;

// Frontend Controllers
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CustomerAuthController;
use App\Http\Controllers\LandingPageFrontendController;
use App\Http\Controllers\TrackOrderController;
use App\Http\Controllers\WishlistController;

// Admin Controllers
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\AttributeController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\PosController;
use App\Http\Controllers\Admin\PurchaseController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\ShippingZoneController;
use App\Http\Controllers\Admin\LandingPageController as AdminLandingPageController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\VisitorReportController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\OrderStatusController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\DraftOrderController;
use App\Http\Controllers\Admin\CourierController;
use App\Http\Controllers\Admin\PopupBannerController;

/*
|--------------------------------------------------------------------------
| Public & Customer Routes (Frontend)
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
Route::get('/product/{slug}', [ShopController::class, 'show'])->name('product.show');
Route::post('/product/{slug}/review', [ShopController::class, 'storeReview'])->name('product.review');
Route::get('/quick-view/{id}', [ShopController::class, 'quickView'])->name('product.quickview');
Route::get('/track-order', [TrackOrderController::class, 'index'])->name('track.order');
Route::get('/page/{slug}', [HomeController::class, 'showPage'])->name('page.show');

// Landing Pages
Route::get('/landing/{slug}', [LandingPageFrontendController::class, 'show'])->name('landing.show');
Route::post('/landing/{slug}/order', [LandingPageFrontendController::class, 'order'])->name('landing.order');

// Cart
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove/{key}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

// Wishlist
Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
Route::post('/wishlist/toggle', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
Route::delete('/wishlist/remove/{productId}', [WishlistController::class, 'remove'])->name('wishlist.remove');
Route::post('/wishlist/clear', [WishlistController::class, 'clear'])->name('wishlist.clear');

// Checkout
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout/save-draft', [CheckoutController::class, 'saveDraft'])->name('checkout.draft');
Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');
Route::get('/order-success/{orderNumber}', [CheckoutController::class, 'success'])->name('checkout.success');

// Customer Auth (web guard)
Route::middleware('guest:web')->group(function () {
    Route::get('/login', [CustomerAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [CustomerAuthController::class, 'login'])->name('login.post');
    Route::get('/register', [CustomerAuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [CustomerAuthController::class, 'register'])->name('register.post');
});

Route::middleware('auth:web')->group(function () {
    Route::post('/logout', [CustomerAuthController::class, 'logout'])->name('logout');
    Route::get('/account/dashboard', [CustomerAuthController::class, 'dashboard'])->name('account.dashboard');
    Route::get('/account/orders/{orderNumber}', [CustomerAuthController::class, 'orderDetails'])->name('account.order.details');
    Route::post('/account/profile', [CustomerAuthController::class, 'updateProfile'])->name('account.profile.update');
});


/*
|--------------------------------------------------------------------------
| Admin Routes (admin guard)
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->group(function () {

    // Admin Auth
    Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.post');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

    // Protected Admin Routes
    Route::middleware('admin')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

        // Admin Profile & Password Management
        Route::get('profile', [ProfileController::class, 'edit'])->name('profile');
        Route::put('profile', [ProfileController::class, 'updateProfile'])->name('profile.update');
        Route::put('profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

        // Products
        Route::resource('products', ProductController::class);
        Route::delete('products/images/{image}', [ProductController::class, 'deleteImage'])->name('products.images.delete');

        // Categories
        Route::resource('categories', CategoryController::class)->except(['show', 'create', 'edit']);

        // Attributes (Colors & Sizes)
        Route::get('attributes/colors', [AttributeController::class, 'colors'])->name('colors.index');
        Route::post('attributes/colors', [AttributeController::class, 'storeColor'])->name('colors.store');
        Route::put('attributes/colors/{color}', [AttributeController::class, 'updateColor'])->name('colors.update');
        Route::delete('attributes/colors/{color}', [AttributeController::class, 'destroyColor'])->name('colors.destroy');

        Route::get('attributes/sizes', [AttributeController::class, 'sizes'])->name('sizes.index');
        Route::post('attributes/sizes', [AttributeController::class, 'storeSize'])->name('sizes.store');
        Route::put('attributes/sizes/{size}', [AttributeController::class, 'updateSize'])->name('sizes.update');
        Route::delete('attributes/sizes/{size}', [AttributeController::class, 'destroySize'])->name('sizes.destroy');

        // Orders
        Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
        Route::get('orders/{order}', [OrderController::class, 'show'])->name('orders.show');
        Route::patch('orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.status');
        Route::delete('orders/{order}', [OrderController::class, 'destroy'])->name('orders.destroy');
        Route::get('orders/{order}/invoice', [OrderController::class, 'invoice'])->name('orders.invoice');
        Route::post('orders/{order}/send-to-courier', [OrderController::class, 'sendToCourier'])->name('orders.send_to_courier');
        Route::post('orders/fraud-check-phone', [OrderController::class, 'checkPhoneFraud'])->name('orders.check_phone_fraud');

        // Draft / Incomplete Orders
        Route::get('draft-orders', [DraftOrderController::class, 'index'])->name('draft_orders.index');
        Route::get('draft-orders/{draftOrder}', [DraftOrderController::class, 'show'])->name('draft_orders.show');
        Route::post('draft-orders/{draftOrder}/convert', [DraftOrderController::class, 'convert'])->name('draft_orders.convert');
        Route::delete('draft-orders/{draftOrder}', [DraftOrderController::class, 'destroy'])->name('draft_orders.destroy');

        // Courier & Fraud Checker Settings
        Route::get('couriers', [CourierController::class, 'index'])->name('couriers.index');
        Route::post('couriers', [CourierController::class, 'update'])->name('couriers.update');

        // POS (Point of Sale)
        Route::get('pos', [PosController::class, 'index'])->name('pos.index');
        Route::get('pos/product-variants', [PosController::class, 'getProductVariants'])->name('pos.variants');
        Route::post('pos/order', [PosController::class, 'placeOrder'])->name('pos.order');

        // Suppliers & Purchases
        Route::resource('suppliers', SupplierController::class)->except(['show', 'create', 'edit']);
        Route::resource('purchases', PurchaseController::class)->only(['index', 'create', 'store', 'show']);

        // Landing Pages
        Route::resource('landing', AdminLandingPageController::class);

        // Banners & Sliders
        Route::post('banners/{banner}/toggle-status', [BannerController::class, 'toggleStatus'])->name('banners.toggle-status');
        Route::resource('banners', BannerController::class)->except(['show', 'create', 'edit']);

        // Popup Banner (Modal Advert / Promo)
        Route::get('popup', [PopupBannerController::class, 'index'])->name('popup.index');
        Route::post('popup', [PopupBannerController::class, 'update'])->name('popup.update');
        Route::delete('popup/image', [PopupBannerController::class, 'removeImage'])->name('popup.remove-image');

        // Shipping Zones
        Route::resource('shipping', ShippingZoneController::class)->except(['show', 'create', 'edit']);

        // Reports
        Route::get('reports/sales', [ReportController::class, 'sales'])->name('reports.sales');
        Route::get('reports/stock', [ReportController::class, 'stock'])->name('reports.stock');
        Route::get('reports/purchase', [ReportController::class, 'purchase'])->name('reports.purchase');
        Route::get('reports/visitors', [VisitorReportController::class, 'index'])->name('reports.visitors');

        // Customers
        Route::get('customers', [CustomerController::class, 'index'])->name('customers.index');
        Route::get('customers/{customer}', [CustomerController::class, 'show'])->name('customers.show');

        // Staff & Role Permissions Management (RBAC)
        Route::resource('roles', RoleController::class)->except(['show']);
        Route::post('staff/{staff}/toggle-status', [StaffController::class, 'toggleStatus'])->name('staff.toggle-status');
        Route::resource('staff', StaffController::class)->except(['show']);

        // Settings & Configurations
        Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
        Route::post('settings', [SettingController::class, 'update'])->name('settings.update');
        Route::resource('order-statuses', OrderStatusController::class)->except(['show', 'create', 'edit']);

        // Dynamic Pages (CMS)
        Route::post('pages/{page}/toggle-status', [PageController::class, 'toggleStatus'])->name('pages.toggle-status');
        Route::resource('pages', PageController::class)->except(['show']);
    });
});
