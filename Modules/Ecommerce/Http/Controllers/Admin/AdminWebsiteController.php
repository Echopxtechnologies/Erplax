<?php

namespace Modules\Ecommerce\Http\Controllers\Admin;

use App\Http\Controllers\Admin\AdminController;
use Modules\Ecommerce\Models\WebsiteSetting;
use Modules\Ecommerce\Models\WebsiteOrder;
use Modules\Ecommerce\Models\ProductReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminWebsiteController extends AdminController
{
    /**
     * Dashboard
     */
    public function index()
    {
        $settings = WebsiteSetting::instance();
        
        // Basic stats
        $stats = [
            'is_active' => $settings->is_active,
            'site_mode' => $settings->getSiteModeLabel(),
            'site_url' => $settings->getPublicUrl(),     // Full site URL with prefix (for website)
            'shop_url' => $settings->getShopFullUrl(),   // Full shop URL with shop prefix
            'base_url' => $settings->getBaseUrl(),       // Base URL without prefix
            'has_logo' => !empty($settings->site_logo),
            'has_favicon' => !empty($settings->site_favicon),
        ];

        // Order stats
        $today = Carbon::today();
        $thisMonth = Carbon::now()->startOfMonth();
        $lastMonth = Carbon::now()->subMonth()->startOfMonth();
        $lastMonthEnd = Carbon::now()->subMonth()->endOfMonth();

        $orderStats = [
            'total' => WebsiteOrder::count(),
            'today' => WebsiteOrder::whereDate('created_at', $today)->count(),
            'this_month' => WebsiteOrder::where('created_at', '>=', $thisMonth)->count(),
            'pending' => WebsiteOrder::where('status', 'pending')->count(),
            'processing' => WebsiteOrder::whereIn('status', ['confirmed', 'processing', 'shipped'])->count(),
            'delivered' => WebsiteOrder::where('status', 'delivered')->count(),
        ];

        // Revenue stats
        $revenueStats = [
            'total' => WebsiteOrder::where('status', '!=', 'cancelled')->sum('total'),
            'today' => WebsiteOrder::whereDate('created_at', $today)->where('status', '!=', 'cancelled')->sum('total'),
            'this_month' => WebsiteOrder::where('created_at', '>=', $thisMonth)->where('status', '!=', 'cancelled')->sum('total'),
            'last_month' => WebsiteOrder::whereBetween('created_at', [$lastMonth, $lastMonthEnd])->where('status', '!=', 'cancelled')->sum('total'),
        ];

        // Calculate growth
        $revenueStats['growth'] = $revenueStats['last_month'] > 0 
            ? round((($revenueStats['this_month'] - $revenueStats['last_month']) / $revenueStats['last_month']) * 100, 1)
            : ($revenueStats['this_month'] > 0 ? 100 : 0);

        // Daily revenue for last 7 days (for chart)
        $dailyRevenue = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $revenue = WebsiteOrder::whereDate('created_at', $date)
                ->where('status', '!=', 'cancelled')
                ->sum('total');
            $dailyRevenue[] = [
                'date' => $date->format('D'),
                'full_date' => $date->format('M d'),
                'revenue' => (float) $revenue,
                'orders' => WebsiteOrder::whereDate('created_at', $date)->count(),
            ];
        }

        // Best selling products (from order items)
        $bestSellers = DB::table('website_order_items')
            ->select(
                'website_order_items.product_id', 
                'website_order_items.product_name', 
                DB::raw('SUM(website_order_items.qty) as total_qty'), 
                DB::raw('SUM(website_order_items.total) as total_revenue')
            )
            ->join('website_orders', 'website_orders.id', '=', 'website_order_items.order_id')
            ->where('website_orders.status', '!=', 'cancelled')
            ->groupBy('website_order_items.product_id', 'website_order_items.product_name')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get();

        // Recent orders
        $recentOrders = WebsiteOrder::with('items')
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        // Pending reviews count
        $pendingReviews = 0;
        if (DB::getSchemaBuilder()->hasTable('product_reviews')) {
            $pendingReviews = ProductReview::where('status', 'pending')->count();
        }

        // Order status distribution
        $statusDistribution = WebsiteOrder::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        return view('ecommerce::admin.index', compact(
            'settings', 'stats', 'orderStats', 'revenueStats', 
            'dailyRevenue', 'bestSellers', 'recentOrders', 
            'pendingReviews', 'statusDistribution'
        ));
    }

    /**
     * Settings page
     */
    public function settings()
    {
        $settings = WebsiteSetting::instance();
        
        $modes = [
            'website_only' => 'Website Only',
            'ecommerce_only' => 'Ecommerce Only',
            'both' => 'Both (Website + Ecommerce)',
        ];

        return view('ecommerce::admin.settings', compact('settings', 'modes'));
    }

    /**
     * Update settings
     */
    public function updateSettings(Request $request)
    {
        $validated = $request->validate([
            // Store Info
            'site_name' => 'nullable|string|max:255',
            'site_url' => 'nullable|url|max:255',
            'shop_prefix' => 'nullable|string|max:50|regex:/^[a-zA-Z0-9\-_]+$/',
            'contact_email' => 'nullable|email|max:255',
            'contact_phone' => 'nullable|string|max:50',
            'is_active' => 'nullable|boolean',
            'site_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            
            // Shipping
            'shipping_fee' => 'nullable|numeric|min:0',
            'free_shipping_min' => 'nullable|numeric|min:0',
            'delivery_days' => 'nullable|string|max:50',
            
            // COD
            'cod_enabled' => 'nullable|boolean',
            'cod_fee' => 'nullable|numeric|min:0',
            'cod_max_amount' => 'nullable|numeric|min:0',
            
            // Online Payment
            'online_payment_enabled' => 'nullable|boolean',
            'online_payment_label' => 'nullable|string|max:100',
            
            // Orders
            'order_prefix' => 'nullable|string|max:20',
            'invoice_prefix' => 'nullable|string|max:20',
            'min_order_amount' => 'nullable|numeric|min:0',
            'guest_checkout' => 'nullable|boolean',
            
            // Tax
            'tax_included_in_price' => 'nullable|boolean',
            'show_tax_breakup' => 'nullable|boolean',
            
            // Store Info
            'store_address' => 'nullable|string|max:500',
            'store_city' => 'nullable|string|max:100',
            'store_state' => 'nullable|string|max:100',
            'store_pincode' => 'nullable|string|max:20',
            'store_gstin' => 'nullable|string|max:20',
            
            // Email Notifications
            'order_notification_email' => 'nullable|email|max:255',
            'send_customer_order_email' => 'nullable|boolean',
            'send_admin_order_alert' => 'nullable|boolean',
            'invoice_footer' => 'nullable|string|max:500',
        ]);

        $settings = WebsiteSetting::instance();

        // Handle logo upload
        if ($request->hasFile('site_logo')) {
            if ($settings->site_logo) {
                Storage::disk('public')->delete($settings->site_logo);
            }
            $validated['site_logo'] = $request->file('site_logo')->store('website', 'public');
        }

        // Handle boolean fields (checkboxes)
        $validated['is_active'] = $request->has('is_active') ? 1 : 0;
        $validated['cod_enabled'] = $request->has('cod_enabled') ? 1 : 0;
        $validated['online_payment_enabled'] = $request->has('online_payment_enabled') ? 1 : 0;
        $validated['guest_checkout'] = $request->has('guest_checkout') ? 1 : 0;
        $validated['tax_included_in_price'] = $request->has('tax_included_in_price') ? 1 : 0;
        $validated['show_tax_breakup'] = $request->has('show_tax_breakup') ? 1 : 0;
        $validated['send_customer_order_email'] = $request->has('send_customer_order_email') ? 1 : 0;
        $validated['send_admin_order_alert'] = $request->has('send_admin_order_alert') ? 1 : 0;

        // Don't update file fields if not uploaded
        if (!$request->hasFile('site_logo')) unset($validated['site_logo']);

        $settings->update($validated);

        return redirect()->route('admin.ecommerce.settings')->with('success', 'Settings updated successfully!');
    }

    /**
     * Remove logo
     */
    public function removeLogo()
    {
        $settings = WebsiteSetting::instance();
        if ($settings->site_logo) {
            Storage::disk('public')->delete($settings->site_logo);
            $settings->update(['site_logo' => null]);
        }
        return response()->json(['success' => true]);
    }

    /**
     * Remove favicon
     */
    public function removeFavicon()
    {
        $settings = WebsiteSetting::instance();
        if ($settings->site_favicon) {
            Storage::disk('public')->delete($settings->site_favicon);
            $settings->update(['site_favicon' => null]);
        }
        return response()->json(['success' => true]);
    }
}
