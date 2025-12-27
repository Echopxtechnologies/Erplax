<?php

namespace Modules\Ecommerce\Http\Controllers\Ecommerce;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\Routing\Controller;
use Modules\Ecommerce\Models\WebsiteSetting;

class WebsiteAuthController extends Controller
{
    /**
     * Show login page
     */
    public function showLogin(Request $request)
    {
        if (Auth::check()) {
            return redirect()->route('ecommerce.account');
        }
        
        // Store intended URL
        if ($request->has('redirect')) {
            session(['url.intended' => $request->redirect]);
        }
        
        $settings = WebsiteSetting::instance();
        return view('ecommerce::public.auth.login', compact('settings'));
    }

    /**
     * Login using Laravel Auth
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        $email = strtolower(trim($request->email));
        
        // Rate limiting (fail-safe - skip if cache unavailable)
        $key = Str::lower($email) . '|' . $request->ip();
        try {
            if (RateLimiter::tooManyAttempts($key, 5)) {
                $seconds = RateLimiter::availableIn($key);
                throw ValidationException::withMessages([
                    'email' => "Too many attempts. Try again in {$seconds} seconds.",
                ]);
            }
        } catch (\Exception $e) {
            // Skip rate limiting if cache is unavailable
            if (!($e instanceof ValidationException)) {
                \Log::warning('Rate limiter cache error: ' . $e->getMessage());
            } else {
                throw $e;
            }
        }

        // Attempt login with Laravel Auth
        if (Auth::attempt(['email' => $email, 'password' => $request->password], $request->boolean('remember'))) {
            try { RateLimiter::clear($key); } catch (\Exception $e) {}
            $request->session()->regenerate();
            
            // Ensure customer record exists
            $this->ensureCustomerExists(Auth::user());
            
            // Redirect to intended URL or account
            $intended = session('url.intended');
            if ($intended && !str_contains($intended, 'login') && !str_contains($intended, 'register') && !str_contains($intended, '/admin')) {
                session()->forget('url.intended');
                return redirect($intended)->with('success', 'Welcome back!');
            }
            
            return redirect()->route('ecommerce.account')->with('success', 'Welcome back!');
        }

        try { RateLimiter::hit($key); } catch (\Exception $e) {}
        return back()->withErrors(['email' => 'Invalid email or password.'])->withInput();
    }

    /**
     * Show register page
     */
    public function showRegister(Request $request)
    {
        if (Auth::check()) {
            return redirect()->route('ecommerce.account');
        }
        
        if ($request->has('redirect')) {
            session(['url.intended' => $request->redirect]);
        }
        
        $settings = WebsiteSetting::instance();
        return view('ecommerce::public.auth.register', compact('settings'));
    }

    /**
     * Register - create in users table + customers table
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $email = strtolower(trim($request->email));

        // Check if email exists in customers
        $existingCustomer = DB::table('customers')->where('email', $email)->first();
        if ($existingCustomer) {
            return back()->withErrors(['email' => 'This email is already registered.'])->withInput();
        }

        // Create in users table
        $userId = DB::table('users')->insertGetId([
            'name' => $request->name,
            'email' => $email,
            'password' => Hash::make($request->password),
            'is_active' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Create in customers table (same email, linked)
        DB::table('customers')->insert([
            'name' => $request->name,
            'email' => $email,
            'phone' => $request->phone,
            'customer_type' => 'individual',
            'active' => 1,
            'is_website_user' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Login using Laravel Auth
        Auth::loginUsingId($userId);
        $request->session()->regenerate();
        
        // Redirect to intended URL or account
        $intended = session('url.intended');
        if ($intended && !str_contains($intended, 'login') && !str_contains($intended, 'register') && !str_contains($intended, '/admin')) {
            session()->forget('url.intended');
            return redirect($intended)->with('success', 'Account created successfully!');
        }

        return redirect()->route('ecommerce.account')->with('success', 'Account created!');
    }

    /**
     * Logout using Laravel Auth
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('ecommerce.shop')->with('success', 'Logged out.');
    }

    /**
     * Account page
     */
    public function account()
    {
        if (!Auth::check()) {
            return redirect()->route('ecommerce.login');
        }

        $settings = WebsiteSetting::instance();
        $user = Auth::user();
        
        // Get linked customer by email
        $customer = DB::table('customers')->where('email', $user->email)->first();
        
        // Ensure customer exists
        if (!$customer) {
            $customer = $this->ensureCustomerExists($user);
        }

        return view('ecommerce::public.auth.account', compact('settings', 'user', 'customer'));
    }

    /**
     * Update profile
     */
    public function updateProfile(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('ecommerce.login');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        $user = Auth::user();

        // Update users table
        DB::table('users')->where('id', $user->id)->update([
            'name' => $request->name,
            'updated_at' => now(),
        ]);

        // Update customers table
        DB::table('customers')->where('email', $user->email)->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Profile updated!');
    }

    /**
     * Update shipping address
     */
    public function updateShipping(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('ecommerce.login');
        }

        $user = Auth::user();

        DB::table('customers')->where('email', $user->email)->update([
            'shipping_address' => $request->shipping_address,
            'shipping_city' => $request->shipping_city,
            'shipping_state' => $request->shipping_state,
            'shipping_zip_code' => $request->shipping_zip_code,
            'shipping_country' => $request->shipping_country ?? 'India',
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Shipping address updated!');
    }

    /**
     * Update billing address
     */
    public function updateBilling(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('ecommerce.login');
        }

        $user = Auth::user();

        DB::table('customers')->where('email', $user->email)->update([
            'billing_street' => $request->billing_street,
            'billing_city' => $request->billing_city,
            'billing_state' => $request->billing_state,
            'billing_zip_code' => $request->billing_zip_code,
            'billing_country' => $request->billing_country ?? 'India',
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Billing address updated!');
    }

    /**
     * Change password
     */
    public function changePassword(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('ecommerce.login');
        }

        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        DB::table('users')->where('id', $user->id)->update([
            'password' => Hash::make($request->password),
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Password changed!');
    }
    
    /**
     * Ensure customer record exists for user
     */
    protected function ensureCustomerExists($user)
    {
        $customer = DB::table('customers')->where('email', $user->email)->first();
        
        if (!$customer) {
            $customerId = DB::table('customers')->insertGetId([
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone ?? null,
                'customer_type' => 'individual',
                'active' => 1,
                'is_website_user' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            return DB::table('customers')->find($customerId);
        }
        
        // Mark as website user if not already
        if (!$customer->is_website_user) {
            DB::table('customers')->where('id', $customer->id)->update([
                'is_website_user' => 1,
                'updated_at' => now(),
            ]);
        }
        
        return $customer;
    }
    
    /**
     * Get customer ID for current user (static helper)
     */
    public static function getCustomerId()
    {
        if (!Auth::check()) {
            return null;
        }
        
        $customer = DB::table('customers')->where('email', Auth::user()->email)->first();
        return $customer ? $customer->id : null;
    }
    
    /**
     * My Orders page
     */
    public function myOrders(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('ecommerce.login');
        }
        
        $user = Auth::user();
        $settings = WebsiteSetting::instance();
        
        // Get orders for this user
        $orders = \Modules\Ecommerce\Models\WebsiteOrder::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('ecommerce::public.auth.orders', compact('settings', 'user', 'orders'));
    }
    
    /**
     * Order detail page
     */
    public function orderDetail($id)
    {
        if (!Auth::check()) {
            return redirect()->route('ecommerce.login');
        }
        
        $user = Auth::user();
        $settings = WebsiteSetting::instance();
        
        // Get order for this user
        $order = \Modules\Ecommerce\Models\WebsiteOrder::where('id', $id)
            ->where('user_id', Auth::id())
            ->with(['items', 'statusHistory'])
            ->firstOrFail();
        
        // Get invoice for this order
        $invoice = DB::table('invoices')
            ->where('admin_note', 'like', '%' . $order->order_no . '%')
            ->first();
        
        return view('ecommerce::public.auth.order-detail', compact('settings', 'user', 'order', 'invoice'));
    }
    
    /**
     * Cancel order
     */
    public function cancelOrder($id)
    {
        if (!Auth::check()) {
            return redirect()->route('ecommerce.login');
        }
        
        $order = \Modules\Ecommerce\Models\WebsiteOrder::where('id', $id)
            ->where('user_id', Auth::id())
            ->first();
        
        if (!$order) {
            return redirect()->back()->with('error', 'Order not found');
        }
        
        if (!$order->canBeCancelled()) {
            return redirect()->back()->with('error', 'This order cannot be cancelled');
        }
        
        // Update order status
        $order->update([
            'status' => 'cancelled',
            'updated_at' => now(),
        ]);
        
        // Add status history
        \Modules\Ecommerce\Models\WebsiteOrderStatusHistory::create([
            'order_id' => $order->id,
            'status' => 'cancelled',
            'comment' => 'Cancelled by customer',
        ]);
        
        // Restore stock
        foreach ($order->items as $item) {
            $this->restoreStock($item->product_id, $item->variation_id, $item->qty, $order->order_no);
        }
        
        return redirect()->back()->with('success', 'Order cancelled successfully');
    }
    
    /**
     * Restore stock when order is cancelled
     */
    protected function restoreStock($productId, $variationId, $qty, $reference)
    {
        // Get product for unit_id
        $product = DB::table('products')->find($productId);
        $unitId = $product->unit_id ?? 1;
        
        // Determine stock column name in stock_levels table
        $stockColumn = 'qty'; // Default column name
        if (\Illuminate\Support\Facades\Schema::hasTable('stock_levels')) {
            if (\Illuminate\Support\Facades\Schema::hasColumn('stock_levels', 'quantity')) {
                $stockColumn = 'quantity';
            } elseif (\Illuminate\Support\Facades\Schema::hasColumn('stock_levels', 'qty')) {
                $stockColumn = 'qty';
            } elseif (\Illuminate\Support\Facades\Schema::hasColumn('stock_levels', 'stock_qty')) {
                $stockColumn = 'stock_qty';
            }
        }
        
        if ($variationId) {
            // For variations
            if (\Illuminate\Support\Facades\Schema::hasTable('stock_levels')) {
                DB::table('stock_levels')
                    ->where('product_id', $productId)
                    ->where('variation_id', $variationId)
                    ->increment($stockColumn, $qty);
            }
            
            if (\Illuminate\Support\Facades\Schema::hasColumn('product_variations', 'stock_qty')) {
                DB::table('product_variations')
                    ->where('id', $variationId)
                    ->increment('stock_qty', $qty);
            }
            
            // Add stock movement record
            if (\Illuminate\Support\Facades\Schema::hasTable('stock_movements')) {
                DB::table('stock_movements')->insert([
                    'reference_no' => 'WEB-CAN-' . $reference,
                    'product_id' => $productId,
                    'variation_id' => $variationId,
                    'warehouse_id' => 1,
                    'unit_id' => $unitId,
                    'qty' => $qty,
                    'base_qty' => $qty,
                    'movement_type' => 'IN',
                    'reference_type' => 'RETURN',
                    'reason' => 'Website Order Cancelled',
                    'notes' => 'Order cancelled: ' . $reference,
                    'created_by' => Auth::id(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        } else {
            // For simple products
            if (\Illuminate\Support\Facades\Schema::hasTable('stock_levels')) {
                DB::table('stock_levels')
                    ->where('product_id', $productId)
                    ->whereNull('variation_id')
                    ->increment($stockColumn, $qty);
            }
            
            if (\Illuminate\Support\Facades\Schema::hasColumn('products', 'stock_qty')) {
                DB::table('products')
                    ->where('id', $productId)
                    ->increment('stock_qty', $qty);
            }
            
            if (\Illuminate\Support\Facades\Schema::hasTable('stock_movements')) {
                DB::table('stock_movements')->insert([
                    'reference_no' => 'WEB-CAN-' . $reference,
                    'product_id' => $productId,
                    'variation_id' => null,
                    'warehouse_id' => 1,
                    'unit_id' => $unitId,
                    'qty' => $qty,
                    'base_qty' => $qty,
                    'movement_type' => 'IN',
                    'reference_type' => 'RETURN',
                    'reason' => 'Website Order Cancelled',
                    'notes' => 'Order cancelled: ' . $reference,
                    'created_by' => Auth::id(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
