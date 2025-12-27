<?php

namespace Modules\Ecommerce\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Modules\Ecommerce\Http\Controllers\Ecommerce\EcommerceController;
use Modules\Ecommerce\Http\Controllers\Ecommerce\WebsiteAuthController;
use Modules\Ecommerce\Http\Controllers\Admin\AdminWebsiteController;
use Modules\Ecommerce\Http\Controllers\Admin\AdminOrderController;
use App\Http\Middleware\EnsureIsAdmin;

class RouteServiceProvider extends ServiceProvider
{
    protected string $moduleNamespace = 'Modules\\Ecommerce\\Http\\Controllers';

    public function boot(): void
    {
        parent::boot();
        
        $this->app->booted(function () {
            $this->registerDynamicRoutes();
        });
    }

    protected function registerDynamicRoutes(): void
    {
        // Get shop prefix from settings
        $shopPrefix = 'shop';
        
        try {
            if (Schema::hasTable('website_settings')) {
                $settings = \DB::table('website_settings')->first();
                if ($settings) {
                    $shopPrefix = $settings->shop_prefix ?? 'shop';
                }
            }
        } catch (\Exception $e) {
            // Use defaults if database not available
        }
        
        Route::middleware('web')->group(function () use ($shopPrefix) {
            
            // Admin Routes
            Route::prefix('admin/ecommerce')
                ->middleware([EnsureIsAdmin::class])
                ->name('admin.ecommerce.')
                ->group(function () {
                    Route::get('/', [AdminWebsiteController::class, 'index'])->name('index');
                    Route::get('/settings', [AdminWebsiteController::class, 'settings'])->name('settings');
                    Route::put('/settings', [AdminWebsiteController::class, 'updateSettings'])->name('settings.update');
                    Route::post('/remove-logo', [AdminWebsiteController::class, 'removeLogo'])->name('remove-logo');
                    Route::post('/remove-favicon', [AdminWebsiteController::class, 'removeFavicon'])->name('remove-favicon');
                    
                    // Order Management Routes
                    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders');
                    Route::get('/orders/{id}', [AdminOrderController::class, 'show'])->name('orders.show');
                    Route::post('/orders/{id}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.status');
                    Route::post('/orders/{id}/payment', [AdminOrderController::class, 'updatePayment'])->name('orders.payment');
                    Route::post('/orders/{id}/shipping', [AdminOrderController::class, 'addShipping'])->name('orders.shipping');
                    Route::post('/orders/{id}/confirm-delivery', [AdminOrderController::class, 'confirmDelivery'])->name('orders.confirm-delivery');
                    Route::get('/orders/{id}/invoice', [AdminOrderController::class, 'printInvoice'])->name('orders.invoice');
                    
                    // Review Management Routes
                    Route::get('/reviews', [AdminOrderController::class, 'reviews'])->name('reviews');
                    Route::post('/reviews/{id}/approve', [AdminOrderController::class, 'approveReview'])->name('reviews.approve');
                    Route::post('/reviews/{id}/reject', [AdminOrderController::class, 'rejectReview'])->name('reviews.reject');
                    Route::post('/reviews/{id}/reply', [AdminOrderController::class, 'replyReview'])->name('reviews.reply');
                    Route::delete('/reviews/{id}', [AdminOrderController::class, 'deleteReview'])->name('reviews.delete');
                });
            
            // API Routes for Cart/Wishlist
            Route::prefix('api')->group(function () {
                Route::post('/cart/add', [EcommerceController::class, 'apiAddToCart']);
                Route::post('/wishlist/toggle', [EcommerceController::class, 'apiToggleWishlist']);
            });

            // Shop Routes
            Route::prefix($shopPrefix)->name('ecommerce.')->group(function () {
                Route::get('/', [EcommerceController::class, 'shop'])->name('shop');
                Route::get('/product/{id}', [EcommerceController::class, 'product'])->name('product');
                Route::post('/product/{id}/review', [EcommerceController::class, 'submitReview'])->name('product.review');
                Route::get('/cart', [EcommerceController::class, 'cart'])->name('cart');
                Route::get('/wishlist', [EcommerceController::class, 'wishlist'])->name('wishlist');
                Route::get('/checkout', [EcommerceController::class, 'checkout'])->name('checkout');
                Route::post('/checkout', [EcommerceController::class, 'placeOrder'])->name('checkout.place');
                Route::get('/order-success/{order}', [EcommerceController::class, 'orderSuccess'])->name('order.success');
                
                // Auth Routes
                Route::get('/login', [WebsiteAuthController::class, 'showLogin'])->name('login');
                Route::post('/login', [WebsiteAuthController::class, 'login'])->name('login.post');
                Route::get('/register', [WebsiteAuthController::class, 'showRegister'])->name('register');
                Route::post('/register', [WebsiteAuthController::class, 'register'])->name('register.post');
                Route::post('/logout', [WebsiteAuthController::class, 'logout'])->name('logout');
                Route::get('/account', [WebsiteAuthController::class, 'account'])->name('account');
                Route::post('/account/profile', [WebsiteAuthController::class, 'updateProfile'])->name('account.profile');
                Route::post('/account/shipping', [WebsiteAuthController::class, 'updateShipping'])->name('account.shipping');
                Route::post('/account/billing', [WebsiteAuthController::class, 'updateBilling'])->name('account.billing');
                Route::post('/account/password', [WebsiteAuthController::class, 'changePassword'])->name('account.password');
                
                // Order Routes (for logged in customers)
                Route::get('/orders', [WebsiteAuthController::class, 'myOrders'])->name('orders');
                Route::get('/orders/{id}', [WebsiteAuthController::class, 'orderDetail'])->name('order.detail');
                Route::post('/orders/{id}/cancel', [WebsiteAuthController::class, 'cancelOrder'])->name('order.cancel');
            });
        });
    }

    public function map(): void
    {
        // Routes are registered in boot() dynamically
    }
}
