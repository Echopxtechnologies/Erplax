<?php

namespace Modules\Website\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Modules\Website\Http\Controllers\Website\WebsiteController;
use Modules\Website\Http\Controllers\Ecommerce\EcommerceController;
use Modules\Website\Http\Controllers\Ecommerce\WebsiteAuthController;
use Modules\Website\Http\Controllers\Admin\AdminWebsiteController;
use Modules\Website\Http\Controllers\Admin\AdminOrderController;
use App\Http\Middleware\EnsureIsAdmin;

class RouteServiceProvider extends ServiceProvider
{
    protected string $moduleNamespace = 'Modules\\Website\\Http\\Controllers';

    public function boot(): void
    {
        parent::boot();
        
        $this->app->booted(function () {
            $this->registerDynamicRoutes();
        });
    }

    protected function registerDynamicRoutes(): void
    {
        // Get settings from database
        $sitePrefix = 'site';
        $shopPrefix = 'shop';
        $siteMode = 'both'; // website_only, ecommerce_only, both
        
        try {
            if (Schema::hasTable('website_settings')) {
                $settings = \DB::table('website_settings')->first();
                if ($settings) {
                    $sitePrefix = $settings->site_prefix ?? 'site';
                    $shopPrefix = $settings->shop_prefix ?? 'shop';
                    $siteMode = $settings->site_mode ?? 'both';
                }
            }
        } catch (\Exception $e) {
            // Use defaults if database not available
        }

        // Determine actual prefixes based on mode
        // ecommerce_only: shop at root, no site prefix needed
        // website_only: site at prefix, no shop
        // both: both have their prefixes
        
        $actualShopPrefix = $shopPrefix;
        $actualSitePrefix = $sitePrefix;
        
        if ($siteMode === 'ecommerce_only') {
            // Shop is primary - can be at root or with prefix
            // For simplicity, keep shop at its prefix but make it primary
            $actualShopPrefix = $shopPrefix; // or '' for root
        }
        
        Route::middleware('web')->group(function () use ($actualSitePrefix, $actualShopPrefix, $siteMode) {
            
            // Admin Routes (always available)
            Route::prefix('admin/website')
                ->middleware([EnsureIsAdmin::class])
                ->name('admin.website.')
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
            
            // API Routes for Cart/Wishlist (always available for ecommerce modes)
            if ($siteMode !== 'website_only') {
                Route::prefix('api')->group(function () {
                    Route::post('/cart/add', [EcommerceController::class, 'apiAddToCart']);
                    Route::post('/wishlist/toggle', [EcommerceController::class, 'apiToggleWishlist']);
                });
            }

            // Shop Routes - only if not website_only
            if ($siteMode !== 'website_only') {
                Route::prefix($actualShopPrefix)->name('website.')->group(function () {
                    Route::get('/', [EcommerceController::class, 'shop'])->name('shop');
                    Route::get('/product/{id}', [EcommerceController::class, 'product'])->name('product');
                    Route::post('/product/{id}/review', [EcommerceController::class, 'submitReview'])->name('product.review');
                    Route::get('/cart', [EcommerceController::class, 'cart'])->name('cart');
                    Route::get('/wishlist', [EcommerceController::class, 'wishlist'])->name('wishlist');
                    Route::get('/checkout', [EcommerceController::class, 'checkout'])->name('checkout');
                    Route::post('/checkout', [EcommerceController::class, 'placeOrder'])->name('checkout.place');
                    Route::get('/order-success/{order}', [EcommerceController::class, 'orderSuccess'])->name('order.success');
                });
            } else {
                // Website Only mode - redirect shop URLs to site home
                Route::prefix($actualShopPrefix)->group(function () use ($actualSitePrefix) {
                    Route::get('/', function() use ($actualSitePrefix) {
                        return redirect('/' . $actualSitePrefix);
                    })->name('website.shop');
                    Route::get('/{any}', function() use ($actualSitePrefix) {
                        return redirect('/' . $actualSitePrefix);
                    })->where('any', '.*');
                });
            }
            
            // Site Routes - only if not ecommerce_only
            if ($siteMode !== 'ecommerce_only') {
                Route::prefix($actualSitePrefix)->name('website.')->group(function () {
                    Route::get('/', [WebsiteController::class, 'home'])->name('site.home');
                    Route::get('/page/{slug}', [WebsiteController::class, 'page'])->name('page');
                    Route::post('/contact', [WebsiteController::class, 'submitContact'])->name('contact.submit');
                });
            } else {
                // Ecommerce Only mode - redirect site URLs to shop
                Route::prefix($actualSitePrefix)->group(function () use ($actualShopPrefix) {
                    Route::get('/', function() use ($actualShopPrefix) {
                        return redirect('/' . $actualShopPrefix);
                    })->name('website.site.home');
                    Route::get('/{any}', function() use ($actualShopPrefix) {
                        return redirect('/' . $actualShopPrefix);
                    })->where('any', '.*');
                });
            }
            
            // Auth Routes - under appropriate prefix based on mode
            $authPrefix = ($siteMode === 'ecommerce_only') ? $actualShopPrefix : $actualSitePrefix;
            Route::prefix($authPrefix)->name('website.')->group(function () {
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

            // Home redirect based on mode
            Route::get('/home', function() use ($siteMode) {
                if ($siteMode === 'ecommerce_only') {
                    return redirect()->route('website.shop');
                }
                return redirect()->route('website.site.home');
            })->name('website.home');
        });
    }

    public function map(): void
    {
        // Routes are registered in boot() dynamically
    }
}
