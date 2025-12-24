<style>
.pos{position:fixed;top:0;left:0;right:0;bottom:0;display:flex;z-index:9999;background:var(--body-bg,#f1f5f9)}
.left{flex:1;display:flex;flex-direction:column;background:var(--card-bg);border-right:1px solid var(--card-border);min-width:0;overflow:hidden}
.top-bar{display:flex;align-items:center;justify-content:space-between;padding:12px 20px;background:var(--dark,#1e293b);color:#fff}
.logo{font-size:18px;font-weight:700}
.top-info{display:flex;gap:12px;align-items:center}
.top-badge{background:rgba(255,255,255,0.15);padding:6px 12px;border-radius:6px;font-size:13px}
.search-area{padding:16px 20px;border-bottom:1px solid var(--card-border)}
.search-row{display:flex;gap:10px;margin-bottom:12px}
.barcode-input{flex:1;height:48px;border:2px solid var(--card-border);border-radius:8px;padding:0 16px;font-size:16px;font-family:monospace;background:var(--input-bg);color:var(--input-text)}
.barcode-input:focus{outline:none;border-color:var(--primary)}
.scan-btn{width:48px;height:48px;background:var(--dark,#1e293b);border:none;border-radius:8px;color:#fff;font-size:20px;cursor:pointer}
.search-input{width:100%;height:42px;border:1px solid var(--card-border);border-radius:8px;padding:0 14px;font-size:14px;background:var(--input-bg);color:var(--input-text)}
.search-input:focus{outline:none;border-color:var(--primary)}
.products{flex:1;overflow-y:auto;padding:16px 20px;display:flex;flex-direction:column}
.category-tabs{display:flex;gap:8px;flex-wrap:wrap;margin-bottom:16px;padding-bottom:12px;border-bottom:1px solid var(--card-border)}
.cat-tab{padding:8px 16px;background:var(--body-bg);border:1px solid var(--card-border);border-radius:20px;font-size:13px;font-weight:600;cursor:pointer;white-space:nowrap;transition:all 0.2s}
.cat-tab:hover{border-color:var(--primary);color:var(--primary)}
.cat-tab.active{background:var(--primary);color:#fff;border-color:var(--primary)}
.cat-tab .count{background:rgba(0,0,0,0.1);padding:2px 6px;border-radius:10px;font-size:11px;margin-left:4px}
.cat-tab.active .count{background:rgba(255,255,255,0.2)}
.product-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(140px,1fr));gap:12px;flex:1;overflow-y:auto;align-content:start}
.product-card{background:var(--card-bg);border:1px solid var(--card-border);border-radius:10px;padding:12px;cursor:pointer;transition:all 0.2s;display:flex;flex-direction:column}
.product-card:hover{border-color:var(--primary);box-shadow:0 4px 12px rgba(0,0,0,0.08);transform:translateY(-2px)}
.product-card.out-of-stock{opacity:0.5;cursor:not-allowed}
.product-card .prod-img{width:100%;aspect-ratio:1;background:var(--body-bg);border-radius:8px;object-fit:cover;margin-bottom:10px;display:flex;align-items:center;justify-content:center;font-size:32px;color:var(--text-muted)}
.product-card .prod-img img{width:100%;height:100%;object-fit:cover;border-radius:8px}
.product-card .prod-name{font-weight:600;font-size:13px;margin-bottom:4px;line-height:1.3;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden}
.product-card .prod-price{color:var(--primary);font-weight:700;font-size:15px}
.product-card .prod-stock{font-size:11px;color:var(--text-muted);margin-top:4px}
.product-card .prod-stock.low{color:var(--danger)}
.no-products{text-align:center;color:var(--text-muted);padding:40px 20px}
.no-products .icon{font-size:48px;opacity:0.3;margin-bottom:12px}
.search-results{background:var(--card-bg);border:1px solid var(--card-border);border-radius:8px;box-shadow:0 10px 25px rgba(0,0,0,0.1);max-height:300px;overflow-y:auto;display:none}
.search-results.show{display:block}
.result-item{display:flex;align-items:center;gap:12px;padding:12px 16px;border-bottom:1px solid var(--card-border);cursor:pointer}
.result-item:hover{background:var(--body-bg)}
.result-item.out{opacity:0.5;cursor:not-allowed}
.result-img{width:44px;height:44px;background:var(--body-bg);border-radius:8px;object-fit:cover}
.result-info{flex:1}
.result-name{font-weight:600;margin-bottom:2px}
.result-sku{font-size:12px;color:var(--text-muted)}
.result-price{font-weight:700;color:var(--primary)}
.right{width:380px;flex-shrink:0;display:flex;flex-direction:column;background:var(--card-bg);overflow:hidden}
.cart-header{padding:16px 20px;background:linear-gradient(135deg,var(--primary),#2563eb);color:#fff}
.cart-title{font-size:18px;font-weight:700;display:flex;align-items:center;gap:10px}
.cart-count{background:rgba(255,255,255,0.2);padding:4px 12px;border-radius:12px;font-size:13px}
.customer-area{padding:12px 20px;border-bottom:1px solid var(--card-border);position:relative}
.customer-search-wrap{display:flex;gap:8px}
.customer-input{flex:1;height:40px;border:1px solid var(--card-border);border-radius:8px;padding:0 12px;font-size:14px;background:var(--input-bg);color:var(--input-text)}
.customer-add-btn{width:40px;height:40px;background:var(--primary);color:#fff;border:none;border-radius:8px;font-size:20px;font-weight:700;cursor:pointer}
.customer-results{position:absolute;top:100%;left:20px;right:20px;background:var(--card-bg);border:1px solid var(--card-border);border-radius:8px;box-shadow:0 4px 12px rgba(0,0,0,0.15);max-height:200px;overflow-y:auto;display:none;z-index:100}
.customer-results.show{display:block}
.cust-item{padding:12px;border-bottom:1px solid var(--card-border);cursor:pointer;display:flex;flex-direction:column;gap:2px}
.cust-item:hover{background:var(--body-bg)}
.cust-item:last-child{border-bottom:none}
.cust-name{font-weight:600;font-size:14px}
.cust-detail{font-size:12px;color:var(--text-muted)}
.selected-customer{display:flex;align-items:center;justify-content:space-between;background:var(--body-bg);border-radius:8px;padding:10px 12px;margin-top:8px}
.sel-cust-info{display:flex;flex-direction:column;gap:2px}
.sel-cust-name{font-weight:600;font-size:14px}
.sel-cust-detail{font-size:11px;color:var(--text-muted)}
.sel-cust-clear{width:24px;height:24px;background:var(--danger);color:#fff;border:none;border-radius:50%;font-size:12px;cursor:pointer}
.cart-items{flex:1;overflow-y:auto;padding:16px 20px}
.cart-empty{height:100%;display:flex;flex-direction:column;align-items:center;justify-content:center;color:var(--text-muted)}
.cart-empty .icon{font-size:48px;margin-bottom:12px;opacity:0.3}
.cart-item{background:var(--body-bg);border-radius:10px;padding:14px;margin-bottom:10px}
.item-row{display:flex;gap:12px;align-items:flex-start}
.item-img{width:44px;height:44px;background:var(--card-border);border-radius:8px;object-fit:cover}
.item-info{flex:1}
.item-name{font-weight:600;font-size:14px;margin-bottom:2px}
.item-variant{font-size:11px;color:#fff;background:#8b5cf6;padding:2px 8px;border-radius:4px;display:inline-block;margin-top:2px}
.item-price{font-size:13px;color:var(--text-muted)}
.item-stock{font-size:11px;color:var(--text-muted);margin-top:4px}
.item-stock.stock-low{color:#f59e0b}
.item-stock.stock-max{color:#ef4444;font-weight:600}
.cart-item.at-max{border-color:#fca5a5;background:rgba(254,202,202,0.1)}
.item-controls{display:flex;justify-content:space-between;align-items:center;margin-top:10px;padding-top:10px;border-top:1px dashed var(--card-border)}
.qty-control{display:flex;align-items:center;background:var(--card-bg);border:1px solid var(--card-border);border-radius:6px}
.qty-btn{width:32px;height:32px;border:none;background:none;font-size:16px;cursor:pointer;color:var(--text-primary)}
.qty-btn:hover{background:var(--body-bg)}
.qty-btn.disabled,.qty-btn:disabled{opacity:0.3;cursor:not-allowed;background:none !important}
.qty-input{width:50px;height:32px;text-align:center;font-weight:700;font-size:14px;border:none;background:transparent;color:var(--text-primary);-moz-appearance:textfield}
.qty-input::-webkit-outer-spin-button,.qty-input::-webkit-inner-spin-button{-webkit-appearance:none;margin:0}
.qty-value{width:36px;text-align:center;font-weight:600}
.item-total{font-size:16px;font-weight:700}
.item-remove{width:28px;height:28px;background:#fee2e2;border:none;border-radius:6px;color:var(--danger);cursor:pointer;margin-left:10px}
.cart-footer{padding:16px 20px;border-top:1px solid var(--card-border);background:var(--body-bg)}
.summary-row{display:flex;justify-content:space-between;padding:6px 0;font-size:14px}
.summary-row.discount{color:var(--success)}
.summary-row.total{font-size:22px;font-weight:800;padding:12px 0;border-top:2px solid var(--card-border);margin-top:8px}
.discount-row{display:flex;gap:8px;margin:10px 0}
.discount-input{flex:1;height:40px;border:1px solid var(--card-border);border-radius:8px;padding:0 12px;font-size:14px;background:var(--input-bg);color:var(--input-text)}
.discount-toggle{display:flex;border:1px solid var(--card-border);border-radius:8px;overflow:hidden}
.discount-toggle button{width:40px;height:38px;border:none;background:var(--card-bg);font-weight:600;cursor:pointer}
.discount-toggle button.active{background:var(--primary);color:#fff}
.pay-btn{width:100%;height:52px;background:linear-gradient(135deg,var(--success),#059669);color:#fff;border:none;border-radius:10px;font-size:17px;font-weight:700;cursor:pointer;margin-top:12px;display:flex;align-items:center;justify-content:center;gap:10px}
.pay-btn:disabled{background:var(--text-muted);cursor:not-allowed}
.action-row{display:flex;gap:8px;margin-top:10px}
.action-row button{flex:1;height:40px;border:none;border-radius:8px;font-weight:600;cursor:pointer}
.btn-hold{background:#fef3c7;color:#b45309}
.btn-clear{background:#fee2e2;color:var(--danger)}
.modal{position:fixed;inset:0;background:rgba(0,0,0,0.6);display:none;align-items:center;justify-content:center;z-index:10000;padding:20px}
.modal.show{display:flex}
.modal-box{background:var(--card-bg);border-radius:16px;width:100%;max-width:440px;overflow:hidden}
.modal-header{padding:16px 20px;border-bottom:1px solid var(--card-border);display:flex;justify-content:space-between;align-items:center}
.modal-header h3{font-size:18px;font-weight:700}
.modal-close{width:36px;height:36px;background:var(--body-bg);border:none;border-radius:8px;font-size:18px;cursor:pointer}
.modal-body{padding:20px}
.pay-total{text-align:center;background:linear-gradient(135deg,var(--primary),#2563eb);color:#fff;padding:24px;border-radius:12px;margin-bottom:20px}
.pay-total small{opacity:0.8}
.pay-total .amount{font-size:42px;font-weight:800}
.pay-methods{display:flex;gap:12px;margin-bottom:20px}
.pay-method{flex:1;padding:16px;border:2px solid var(--card-border);border-radius:12px;text-align:center;cursor:pointer}
.pay-method.active{border-color:var(--primary);background:rgba(59,130,246,0.05)}
.pay-method .icon{font-size:28px;margin-bottom:6px}
.pay-method .label{font-weight:600}
.cash-input{width:100%;height:56px;border:2px solid var(--card-border);border-radius:12px;font-size:28px;text-align:center;font-weight:700;background:var(--input-bg);color:var(--input-text)}
.cash-input:focus{outline:none;border-color:var(--primary)}
.quick-amounts{display:flex;flex-wrap:wrap;gap:8px;margin-top:12px}
.quick-btn{flex:1;min-width:70px;padding:10px;background:var(--body-bg);border:1px solid var(--card-border);border-radius:8px;font-weight:600;cursor:pointer}
.quick-btn:hover{border-color:var(--primary)}
.change-box{background:#d1fae5;padding:20px;border-radius:12px;text-align:center;margin-top:16px}
.change-box small{color:var(--text-muted)}
.change-box .amount{font-size:36px;font-weight:800;color:var(--success)}
.complete-btn{width:100%;height:52px;background:linear-gradient(135deg,var(--success),#059669);color:#fff;border:none;border-radius:12px;font-size:16px;font-weight:700;cursor:pointer;margin-top:20px}
.complete-btn:disabled{background:var(--text-muted)}
.form-group{margin-bottom:16px}
.form-group label{display:block;font-weight:600;font-size:13px;margin-bottom:6px;color:var(--text-primary)}
.form-input{width:100%;height:44px;border:1px solid var(--card-border);border-radius:8px;padding:0 12px;font-size:14px;background:var(--input-bg);color:var(--input-text)}
.form-input:focus{outline:none;border-color:var(--primary)}
.form-input.error{border-color:var(--danger)}
.form-error{display:block;color:var(--danger);font-size:11px;margin-top:4px}
.success-content{text-align:center;padding:20px}
.success-icon{width:80px;height:80px;background:#d1fae5;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 20px;font-size:40px;color:var(--success)}
.success-content h3{font-size:24px;margin-bottom:8px}
.success-content .invoice{color:var(--text-muted);font-size:16px;margin-bottom:16px}
.success-btns{display:flex;gap:12px}
.success-btns button{flex:1;height:46px;border-radius:10px;font-weight:600;cursor:pointer}
.btn-print{background:var(--primary);color:#fff;border:none}
.btn-new{background:var(--card-bg);border:1px solid var(--card-border)}
.toast{position:fixed;top:20px;left:50%;transform:translateX(-50%) translateY(-100px);padding:14px 24px;border-radius:10px;font-weight:600;z-index:10001;opacity:0;transition:all 0.3s}
.toast.show{transform:translateX(-50%) translateY(0);opacity:1}
.toast.success{background:var(--success);color:#fff}
.toast.error{background:var(--danger);color:#fff}
.toast.warning{background:#f59e0b;color:#fff}
@media(max-width:800px){.pos{flex-direction:column}.left{flex:1;min-height:50%}.right{width:100%;flex:1;min-height:50%}}
.camera-modal{position:fixed;inset:0;background:rgba(0,0,0,0.95);z-index:10002;display:none;flex-direction:column;padding:16px}
.camera-modal.show{display:flex}
.camera-header{display:flex;justify-content:space-between;align-items:center;margin-bottom:16px}
.camera-header h3{font-size:18px;font-weight:600;color:#fff}
.camera-close{background:rgba(255,255,255,0.1);border:none;color:#fff;width:40px;height:40px;border-radius:50%;font-size:20px;cursor:pointer}
.camera-view{flex:1;display:flex;align-items:center;justify-content:center}
.camera-viewport{width:100%;max-width:400px;aspect-ratio:4/3;background:#000;border-radius:12px;overflow:hidden;position:relative}
.camera-viewport video{width:100%;height:100%;object-fit:cover}
.camera-viewport canvas{position:absolute;top:0;left:0;width:100%;height:100%}
.camera-viewport canvas.drawingBuffer{display:none}
.scan-overlay{position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);width:80%;height:35%;border:3px solid #22c55e;border-radius:10px;box-shadow:0 0 0 9999px rgba(0,0,0,0.5);pointer-events:none;z-index:5}
.scan-overlay::before{content:'';position:absolute;left:0;right:0;height:3px;background:#22c55e;animation:scanAnim 1.5s ease-in-out infinite}
@keyframes scanAnim{0%,100%{top:0}50%{top:calc(100% - 3px)}}
.scan-detected{position:absolute;bottom:16px;left:50%;transform:translateX(-50%);background:#22c55e;color:#fff;padding:10px 20px;border-radius:8px;font-weight:600;display:none;z-index:10}
.scan-detected.show{display:block}
.camera-btns{display:flex;gap:10px;justify-content:center;margin-top:16px}
.camera-btn{padding:10px 20px;background:rgba(255,255,255,0.1);color:#fff;border:none;border-radius:8px;font-weight:600;cursor:pointer}
.qr-modal{position:fixed;inset:0;background:rgba(0,0,0,0.8);z-index:10002;display:none;align-items:center;justify-content:center;padding:20px}
.qr-modal.show{display:flex}
.qr-content{background:var(--card-bg,#fff);border-radius:16px;max-width:360px;width:100%;overflow:hidden}
.qr-header{display:flex;justify-content:space-between;align-items:center;padding:16px 20px;border-bottom:1px solid var(--card-border,#e5e7eb)}
.qr-header h3{font-size:16px;font-weight:600}
.qr-header button{width:32px;height:32px;border:none;background:var(--body-bg,#f3f4f6);border-radius:50%;font-size:16px;cursor:pointer}
.qr-body{padding:24px;text-align:center}
.qr-code{background:#fff;padding:16px;border-radius:12px;display:inline-block;margin-bottom:16px}
.qr-code canvas{display:block}
.qr-url{font-size:11px;color:var(--text-muted,#6b7280);word-break:break-all;margin-bottom:12px;padding:8px;background:var(--body-bg,#f3f4f6);border-radius:6px;font-family:monospace}
.qr-hint{font-size:13px;color:var(--text-muted,#6b7280);margin-bottom:16px}
.qr-status{display:flex;align-items:center;justify-content:center;gap:8px;font-size:12px;color:#22c55e}
.qr-dot{width:8px;height:8px;background:#22c55e;border-radius:50%;animation:pulse 1.5s ease-in-out infinite}
@keyframes pulse{0%,100%{opacity:1}50%{opacity:0.3}}
</style>

<div class="pos">
<div class="left">
<div class="top-bar">
<div class="logo">🛒 QuickPOS</div>
<div class="top-info">
<span class="top-badge">{{ $session->session_code }}</span>
@if($warehouseName)<span class="top-badge">📦 {{ $warehouseName }}</span>@endif
<button type="button" class="top-badge" style="border:none;cursor:pointer;background:rgba(255,255,255,0.25)" onclick="showQR()">📱 Mobile</button>
<a href="{{ route('admin.pos.sessions') }}" class="top-badge" style="text-decoration:none;background:rgba(255,255,255,0.25)">✕ Exit</a>
</div>
</div>
<div class="search-area">
<div class="search-row">
<input type="text" id="barcodeInput" class="barcode-input" placeholder="Scan barcode or type & Enter" autofocus>
<button type="button" class="scan-btn" onclick="openCamera()">📷</button>
</div>
<input type="text" id="searchInput" class="search-input" placeholder="Search products...">
<div id="searchResults" class="search-results"></div>
</div>
<div class="products">
<div class="category-tabs" id="categoryTabs">
<div class="cat-tab active" data-id="all" onclick="loadCategory('all')">All Products</div>
@forelse($categories as $cat)
<div class="cat-tab" data-id="{{ $cat->id }}" onclick="loadCategory({{ $cat->id }})">{{ $cat->name }}@if($cat->product_count > 0) <span class="count">{{ $cat->product_count }}</span>@endif</div>
@empty
<!-- No categories found -->
@endforelse
</div>
<div class="product-grid" id="productGrid">
<div class="no-products"><div class="icon">📦</div><p>Loading products...</p></div>
</div>
</div>
</div>

<div class="right">
<div class="cart-header"><div class="cart-title">🛒 Cart <span class="cart-count" id="cartCount">0</span></div></div>
<div class="customer-area">
<div class="customer-search-wrap">
<input type="text" id="customerSearch" class="customer-input" placeholder="Search customer by email/phone..." autocomplete="off">
<button type="button" class="customer-add-btn" onclick="openCustomerModal()">+</button>
</div>
<div class="customer-results" id="customerResults"></div>
<div class="selected-customer" id="selectedCustomer" style="display:none">
<div class="sel-cust-info">
<span class="sel-cust-name" id="selCustName"></span>
<span class="sel-cust-detail" id="selCustDetail"></span>
</div>
<button type="button" class="sel-cust-clear" onclick="clearCustomer()">✕</button>
</div>
<input type="hidden" id="customerId" value="">
</div>
<div class="cart-items" id="cartItems"><div class="cart-empty"><div class="icon">🛒</div><p>Cart is empty</p></div></div>
<div class="cart-footer">
<div class="summary-row"><span>Subtotal</span><span id="subtotalDisplay">₹0.00</span></div>
<div class="discount-row">
<input type="number" id="discountInput" class="discount-input" placeholder="Discount" min="0" step="0.01">
<div class="discount-toggle"><button type="button" id="discFixedBtn" class="active">₹</button><button type="button" id="discPercentBtn">%</button></div>
</div>
<div class="summary-row discount" id="discountRow" style="display:none"><span>Discount</span><span id="discountDisplay">-₹0.00</span></div>
<div class="summary-row" id="taxRow"><span id="taxLabel">Tax</span><span id="taxDisplay">₹0.00</span></div>
<div class="summary-row total"><span>Total</span><span id="totalDisplay">₹0.00</span></div>
<button type="button" class="pay-btn" id="payBtn" disabled>💳 Pay ₹<span id="payAmount">0.00</span></button>
<div class="action-row"><button type="button" class="btn-hold" id="holdBtn" disabled>📋 Hold</button><button type="button" class="btn-clear" id="clearBtn" disabled>🗑 Clear</button></div>
</div>
</div>
</div>

<div class="modal" id="paymentModal">
<div class="modal-box">
<div class="modal-header"><h3>💳 Payment</h3><button type="button" class="modal-close" onclick="closePayment()">✕</button></div>
<div class="modal-body">
<div class="pay-total"><small>Total Amount</small><div class="amount" id="payModalTotal">₹0.00</div></div>
<div class="pay-methods">
<div class="pay-method active" data-method="cash" onclick="selectMethod('cash')"><div class="icon">💵</div><div class="label">Cash</div></div>
<div class="pay-method" data-method="card" onclick="selectMethod('card')"><div class="icon">💳</div><div class="label">Card</div></div>
<div class="pay-method" data-method="upi" onclick="selectMethod('upi')"><div class="icon">📱</div><div class="label">UPI</div></div>
</div>
<div id="cashSection">
<input type="number" id="cashReceived" class="cash-input" placeholder="0.00" min="0" step="0.01">
<div class="quick-amounts">
<button type="button" class="quick-btn" onclick="setQuickAmount(100)">₹100</button>
<button type="button" class="quick-btn" onclick="setQuickAmount(200)">₹200</button>
<button type="button" class="quick-btn" onclick="setQuickAmount(500)">₹500</button>
<button type="button" class="quick-btn" onclick="setQuickAmount(1000)">₹1000</button>
<button type="button" class="quick-btn" onclick="setQuickAmount(2000)">₹2000</button>
</div>
<div class="change-box" id="changeBox" style="display:none"><small>Change Due</small><div class="amount" id="changeAmount">₹0.00</div></div>
</div>
<button type="button" class="complete-btn" id="completeBtn" onclick="completeSale()">✓ Complete Sale</button>
</div>
</div>
</div>

<div class="modal" id="successModal">
<div class="modal-box">
<div class="modal-body">
<div class="success-content">
<div class="success-icon">✓</div>
<h3>Payment Successful!</h3>
<div class="invoice" id="successInvoice">INV-0001</div>
<div class="change-box" id="successChange" style="display:none"><small>Change</small><div class="amount" id="successChangeAmt">₹0.00</div></div>
<div class="success-btns" style="margin-top:20px">
<button type="button" class="btn-print" onclick="printReceipt()">🖨 Print Receipt</button>
<button type="button" class="btn-new" onclick="newSale()">+ New Sale</button>
</div>
</div>
</div>
</div>
</div>

<!-- Hidden Receipt for Printing -->
<div id="receiptPrintArea" style="display:none"></div>
<iframe id="receiptFrame" style="display:none"></iframe>

<div class="modal" id="customerModal">
<div class="modal-box" style="max-width:380px">
<div class="modal-header"><h3>👤 Add Customer</h3><button type="button" class="modal-close" onclick="closeCustomerModal()">✕</button></div>
<div class="modal-body">
<div class="form-group">
<label>Name <span style="color:var(--danger)">*</span></label>
<input type="text" id="newCustName" class="form-input" placeholder="Customer name">
</div>
<div class="form-group">
<label>Email <span style="color:var(--danger)">*</span></label>
<input type="email" id="newCustEmail" class="form-input" placeholder="email@example.com">
<small class="form-error" id="emailError"></small>
</div>
<div class="form-group">
<label>Phone <span style="color:var(--danger)">*</span></label>
<input type="tel" id="newCustPhone" class="form-input" placeholder="Phone number">
<small class="form-error" id="phoneError"></small>
</div>
<button type="button" class="complete-btn" onclick="saveCustomer()">✓ Save Customer</button>
</div>
</div>
</div>

<div class="camera-modal" id="cameraModal">
<div class="camera-header"><h3>📷 Scan Barcode</h3><button type="button" class="camera-close" onclick="closeCamera()">✕</button></div>
<div class="camera-view">
<div class="camera-viewport" id="cameraViewport">
<div class="scan-overlay"></div>
<div class="scan-detected" id="scanDetected"></div>
</div>
</div>
<div class="camera-btns"><button type="button" class="camera-btn" onclick="flipCamera()">🔄 Flip</button></div>
</div>

<div class="toast" id="toast"></div>

<div class="qr-modal" id="qrModal">
<div class="qr-content">
<div class="qr-header">
<h3>📱 Mobile Scanner</h3>
<button type="button" onclick="closeQR()">✕</button>
</div>
<div class="qr-body">
<div class="qr-code" id="qrCode"></div>
<p class="qr-url" id="qrUrl"></p>
<p class="qr-hint">Scan this QR code with your phone to use it as a barcode scanner</p>
<div class="qr-status"><span class="qr-dot"></span> Listening for scans...</div>
</div>
</div>
</div>

<script>
const CSRF=document.querySelector('meta[name="csrf-token"]').content;
const TAX={{ $settings->default_tax_rate ?? 0 }};
let cart=[],discountType='fixed',paymentMethod='cash',lastSale=null;
let lastCode='',lastTime=0;

// Audio beep for scans
var audioCtx=null;
function beep(ok){
    try{
        if(!audioCtx)audioCtx=new(window.AudioContext||window.webkitAudioContext)();
        var o=audioCtx.createOscillator(),g=audioCtx.createGain();
        o.connect(g);g.connect(audioCtx.destination);
        o.frequency.value=ok?1200:400;o.type='sine';g.gain.value=0.2;
        o.start();o.stop(audioCtx.currentTime+(ok?0.1:0.25));
    }catch(e){}
}

// Category & Product Grid
var currentCategory = 'all';

function loadCategory(catId){
    currentCategory = catId;
    
    // Update active tab
    document.querySelectorAll('.cat-tab').forEach(function(t){
        t.classList.remove('active');
        if(t.dataset.id == catId) t.classList.add('active');
    });
    
    // Load products
    var grid = document.getElementById('productGrid');
    grid.innerHTML = '<div class="no-products"><div class="icon">⏳</div><p>Loading...</p></div>';
    
    fetch('{{ route("admin.pos.products.category") }}?category_id=' + catId)
    .then(function(r){ return r.json(); })
    .then(function(products){
        if(products.length === 0){
            grid.innerHTML = '<div class="no-products"><div class="icon">📦</div><p>No products in this category</p></div>';
            return;
        }
        
        grid.innerHTML = products.map(function(p){
            var stockClass = p.stock <= 0 ? 'out-of-stock' : '';
            var stockText = p.stock <= 0 ? 'Out of stock' : (p.stock <= 5 ? p.stock + ' left' : 'In stock');
            var stockColor = p.stock <= 0 ? 'low' : (p.stock <= 5 ? 'low' : '');
            var displayName = p.name + (p.variant_name ? ' <span style="color:#8b5cf6;font-size:11px;display:block;">» ' + p.variant_name + '</span>' : '');
            
            return '<div class="product-card ' + stockClass + '" onclick="' + (p.stock > 0 ? 'addProductFromGrid(' + JSON.stringify(p).replace(/"/g, '&quot;') + ')' : '') + '">' +
                '<div class="prod-img">' + (p.image ? '<img src="' + p.image + '">' : '📦') + '</div>' +
                '<div class="prod-name">' + displayName + '</div>' +
                '<div class="prod-price">₹' + parseFloat(p.price).toFixed(2) + '</div>' +
                '<div class="prod-stock ' + stockColor + '">' + stockText + '</div>' +
            '</div>';
        }).join('');
    })
    .catch(function(){
        grid.innerHTML = '<div class="no-products"><div class="icon">❌</div><p>Error loading products</p></div>';
    });
}

function addProductFromGrid(p){
    addToCart(p);
    beep(true);
}

// Load all products on page load
document.addEventListener('DOMContentLoaded', function(){
    loadCategory('all');
});

// Barcode input - Enter key
document.getElementById('barcodeInput').addEventListener('keydown',function(e){
    if(e.key==='Enter'){
        e.preventDefault();
        var code = this.value.trim();
        this.value = '';
        if(code) processBarcode(code);
    }
});

// Auto-scan for hardware scanners (fast typing)
var scanBuffer = '';
var scanTimeout = null;
document.getElementById('barcodeInput').addEventListener('input',function(){
    clearTimeout(scanTimeout);
    var code = this.value.trim();
    if(code.length >= 3){
        scanTimeout = setTimeout(function(){
            document.getElementById('barcodeInput').value = '';
            processBarcode(code);
        }, 150); // Fast - hardware scanners type quickly
    }
});

// Product cache for instant repeat scans
var productCache = {};

function processBarcode(code){
    if(!code) return;
    
    // Check if product already in cart by barcode - INSTANT increment
    for(var i = 0; i < cart.length; i++){
        if(cart[i].barcode === code || cart[i].sku === code){
            incrementQty(i);
            return;
        }
    }
    
    // Check cache for instant add
    if(productCache[code]){
        addProductToCart(productCache[code], code);
        return;
    }
    
    // Fetch from server (first time only)
    fetch('{{ route("admin.pos.scan") }}', {
        method: 'POST',
        headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF},
        body: JSON.stringify({barcode: code})
    })
    .then(function(r){ return r.json(); })
    .then(function(d){
        if(d.success){
            // Cache it
            productCache[code] = d.product;
            
            // Check if same product already in cart
            for(var i = 0; i < cart.length; i++){
                if(cart[i].id === d.product.id && (cart[i].variant_id || null) === (d.product.variant_id || null)){
                    cart[i].barcode = code; // Remember this barcode
                    incrementQty(i);
                    return;
                }
            }
            
            // Add new
            addProductToCart(d.product, code);
        } else {
            beep(false);
            toast('Not found: ' + code, 'error');
            if(navigator.vibrate) navigator.vibrate([50,50,50]);
        }
    })
    .catch(function(){ beep(false); toast('Scan error', 'error'); });
}

function addProductToCart(p, barcode){
    cart.push({
        id: p.id,
        name: p.name,
        variant_id: p.variant_id || null,
        variant_name: p.variant_name || null,
        price: parseFloat(p.price),
        qty: 1,
        stock: p.stock || 999,
        image: p.image,
        barcode: barcode,
        sku: p.sku || null
    });
    renderCart();
    beep(true);
    toast('✓ ' + p.name, 'success');
    if(navigator.vibrate) navigator.vibrate(50);
}

function incrementQty(index){
    if(cart[index].qty >= cart[index].stock){
        beep(false);
        toast('Max stock: ' + cart[index].stock, 'error');
        return;
    }
    cart[index].qty++;
    updateCartItem(index);
    beep(true);
    toast(cart[index].name + ' (' + cart[index].qty + ')', 'success');
    if(navigator.vibrate) navigator.vibrate(50);
}

function updateCartItem(index){
    // Update just the qty display and totals - no full re-render
    var item = cart[index];
    var cartItems = document.getElementById('cartItems');
    var cartItem = cartItems.children[index];
    if(cartItem){
        var qtyInput = cartItem.querySelector('.qty-input');
        var totalSpan = cartItem.querySelector('.item-total');
        if(qtyInput) qtyInput.value = item.qty;
        if(totalSpan) totalSpan.textContent = '₹' + (item.price * item.qty).toFixed(2);
    }
    document.getElementById('cartCount').textContent = cart.reduce(function(s,i){return s+i.qty;},0);
    calculateTotals();
}

let searchTimer=null;
document.getElementById('searchInput').addEventListener('input',function(){clearTimeout(searchTimer);const q=this.value.trim();if(q.length<2){document.getElementById('searchResults').classList.remove('show');return;}searchTimer=setTimeout(()=>searchProducts(q),300);});

function searchProducts(q){
fetch('{{ route("admin.pos.search") }}?q='+encodeURIComponent(q)).then(r=>r.json()).then(products=>{
const sr=document.getElementById('searchResults');
if(products.length===0){sr.innerHTML='<div style="padding:20px;text-align:center;color:var(--text-muted)">No products found</div>';}
else{sr.innerHTML=products.map(p=>{
    var variantBadge = p.variant_name ? `<span style="background:#8b5cf6;color:#fff;padding:2px 6px;border-radius:4px;font-size:10px;margin-left:6px;">${p.variant_name}</span>` : '';
    return `<div class="result-item ${p.stock<=0?'out':''}" onclick="${p.stock>0?`addToCart(${JSON.stringify(p).replace(/"/g,'&quot;')})`:''}">
        ${p.image?`<img src="${p.image}" class="result-img">`:'<div class="result-img" style="display:flex;align-items:center;justify-content:center">📦</div>'}
        <div class="result-info">
            <div class="result-name">${p.name}${variantBadge}</div>
            <div class="result-sku">${p.sku||''} • Stock: ${p.stock}</div>
        </div>
        <div><div class="result-price">₹${parseFloat(p.price).toFixed(2)}</div></div>
    </div>`;
}).join('');}
sr.classList.add('show');
});
}
document.addEventListener('click',function(e){if(!e.target.closest('.search-area'))document.getElementById('searchResults').classList.remove('show');});

function addToCart(p,qty=1){
// Check if product has 0 stock
if(p.stock <= 0){
    toast('❌ Out of stock!','error');
    beep(false);
    return;
}

const ex=cart.find(i=>i.id===p.id&&(i.variant_id||null)===(p.variant_id||null));
if(ex){
    // Check if already at max
    if(ex.qty >= ex.stock){
        toast('⚠️ Max stock reached: '+ex.stock,'error');
        beep(false);
        return;
    }
    // Check if adding qty would exceed stock
    if(ex.qty + qty > ex.stock){
        const canAdd = ex.stock - ex.qty;
        if(canAdd > 0){
            ex.qty += canAdd;
            toast('Added '+canAdd+' (max stock: '+ex.stock+')','warning');
        }else{
            toast('⚠️ Max stock reached: '+ex.stock,'error');
            beep(false);
            return;
        }
    }else{
        ex.qty += qty;
        toast(p.name+' ('+ex.qty+'/'+ex.stock+')','success');
    }
    renderCart();
}else{
    // New item - check if qty exceeds stock
    const addQty = Math.min(qty, p.stock);
    cart.push({id:p.id,name:p.name,variant_id:p.variant_id||null,variant_name:p.variant_name||null,price:parseFloat(p.price),qty:addQty,stock:p.stock||999,image:p.image,barcode:p.barcode||null,sku:p.sku||null,tax_rate:p.tax_rate||0,tax_name:p.tax_name||''});
    renderCart();
    toast('✓ '+p.name+(p.variant_name?' ('+p.variant_name+')':''),'success');
}
document.getElementById('searchInput').value='';document.getElementById('searchResults').classList.remove('show');document.getElementById('barcodeInput').focus();
}

function updateQty(i,d){
    const item=cart[i];
    const nq=item.qty+d;
    if(nq<=0){
        cart.splice(i,1);
        toast('Removed from cart','success');
    }else if(nq>item.stock){
        toast('⚠️ Max stock: '+item.stock,'error');
        beep(false);
        return;
    }else{
        item.qty=nq;
    }
    renderCart();
}
function setQty(i,val){
    const item=cart[i];
    const nq=parseInt(val)||1;
    if(nq<=0){
        cart.splice(i,1);
        toast('Removed from cart','success');
    }else if(nq>item.stock){
        toast('⚠️ Max stock: '+item.stock+'. Set to max.','warning');
        item.qty=item.stock;
    }else{
        item.qty=nq;
    }
    renderCart();
}
function removeItem(i){cart.splice(i,1);toast('Removed','success');renderCart();}

function renderCart(){
const c=document.getElementById('cartItems');
if(cart.length===0){c.innerHTML='<div class="cart-empty"><div class="icon">🛒</div><p>Cart is empty</p><p style="font-size:12px">Scan barcode or search products</p></div>';}
else{c.innerHTML=cart.map((it,i)=>{
    const atMax = it.qty >= it.stock;
    const lowStock = it.stock <= 5;
    const stockClass = atMax ? 'stock-max' : (lowStock ? 'stock-low' : '');
    const taxBadge = it.tax_name ? `<span style="background:#f59e0b;color:#fff;padding:2px 6px;border-radius:3px;font-size:9px;margin-left:5px;">${it.tax_name}</span>` : '';
    return `<div class="cart-item ${atMax ? 'at-max' : ''}">
<div class="item-row">
${it.image?`<img src="${it.image}" class="item-img">`:'<div class="item-img" style="display:flex;align-items:center;justify-content:center">📦</div>'}
<div class="item-info">
<div class="item-name">${it.name}</div>
${it.variant_name?`<div class="item-variant">${it.variant_name}</div>`:''}
<div class="item-price">₹${it.price.toFixed(2)} ${taxBadge}</div>
<div class="item-stock ${stockClass}">📦 Stock: ${it.stock} ${atMax ? '(MAX)' : ''}</div>
</div>
<button type="button" class="item-remove" onclick="removeItem(${i})">✕</button>
</div>
<div class="item-controls">
<div class="qty-control">
<button type="button" class="qty-btn" onclick="updateQty(${i},-1)">−</button>
<input type="number" class="qty-input" value="${it.qty}" min="1" max="${it.stock}" onchange="setQty(${i},this.value)" onclick="this.select()">
<button type="button" class="qty-btn ${atMax ? 'disabled' : ''}" onclick="updateQty(${i},1)" ${atMax ? 'disabled' : ''}>+</button>
</div>
<span class="item-total">₹${(it.price*it.qty).toFixed(2)}</span>
</div>
</div>`;
}).join('');}
document.getElementById('cartCount').textContent=cart.reduce((s,i)=>s+i.qty,0);
calculateTotals();
}

function calculateTotals(){
const sub=cart.reduce((s,i)=>s+(i.price*i.qty),0);
const di=parseFloat(document.getElementById('discountInput').value)||0;
const da=discountType==='percent'?(sub*di/100):di;

// Calculate tax per item and collect tax names
let totalTax = 0;
const taxBreakdown = {};
cart.forEach(i=>{
    const itemTotal = i.price * i.qty;
    const itemTax = itemTotal * (i.tax_rate || 0) / 100;
    totalTax += itemTax;
    if(i.tax_name && itemTax > 0){
        if(!taxBreakdown[i.tax_name]) taxBreakdown[i.tax_name] = 0;
        taxBreakdown[i.tax_name] += itemTax;
    }
});

// Apply discount proportionally to tax
const taxAfterDiscount = sub > 0 ? totalTax * (1 - da/sub) : 0;
const tot=sub-da+taxAfterDiscount;
document.getElementById('subtotalDisplay').textContent='₹'+sub.toFixed(2);
document.getElementById('discountDisplay').textContent='-₹'+da.toFixed(2);
document.getElementById('discountRow').style.display=da>0?'flex':'none';

// Build tax label with breakdown
const taxNames = Object.keys(taxBreakdown);
let taxLabel = 'Tax';
if(taxNames.length === 1){
    taxLabel = taxNames[0];
}else if(taxNames.length > 1){
    taxLabel = 'Tax (' + taxNames.join(', ') + ')';
}
document.getElementById('taxLabel').textContent = taxLabel;
document.getElementById('taxDisplay').textContent='₹'+taxAfterDiscount.toFixed(2);
document.getElementById('taxRow').style.display = taxAfterDiscount > 0 ? 'flex' : 'none';

document.getElementById('totalDisplay').textContent='₹'+tot.toFixed(2);
document.getElementById('payAmount').textContent=tot.toFixed(2);
const has=cart.length>0;
document.getElementById('payBtn').disabled=!has;
document.getElementById('holdBtn').disabled=!has;
document.getElementById('clearBtn').disabled=!has;
return{subtotal:sub,discountAmt:da,tax:taxAfterDiscount,total:tot};
}

document.getElementById('discountInput').addEventListener('input',calculateTotals);
document.getElementById('discFixedBtn').addEventListener('click',function(){discountType='fixed';this.classList.add('active');document.getElementById('discPercentBtn').classList.remove('active');calculateTotals();});
document.getElementById('discPercentBtn').addEventListener('click',function(){discountType='percent';this.classList.add('active');document.getElementById('discFixedBtn').classList.remove('active');calculateTotals();});
document.getElementById('clearBtn').addEventListener('click',function(){cart=[];document.getElementById('discountInput').value='';clearCustomer();renderCart();});
document.getElementById('holdBtn').addEventListener('click',function(){
if(cart.length===0)return;
const custId = document.getElementById('customerId').value || null;
const custName = selectedCustomer ? selectedCustomer.name : null;
const sub=cart.reduce((s,i)=>s+(i.price*i.qty),0);
fetch('{{ route("admin.pos.hold") }}',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':CSRF},body:JSON.stringify({cart:cart,customer_id:custId,customer_name:custName,subtotal:sub})})
.then(r=>r.json()).then(d=>{if(d.success){cart=[];document.getElementById('discountInput').value='';clearCustomer();renderCart();toast('Bill held','success');}else{toast('Error','error');}}).catch(()=>toast('Error','error'));
});
document.getElementById('payBtn').addEventListener('click',function(){const t=calculateTotals();document.getElementById('payModalTotal').textContent='₹'+t.total.toFixed(2);document.getElementById('cashReceived').value='';document.getElementById('changeBox').style.display='none';document.getElementById('paymentModal').classList.add('show');});

function closePayment(){document.getElementById('paymentModal').classList.remove('show');}
function selectMethod(m){paymentMethod=m;document.querySelectorAll('.pay-method').forEach(el=>el.classList.remove('active'));document.querySelector(`.pay-method[data-method="${m}"]`).classList.add('active');document.getElementById('cashSection').style.display=m==='cash'?'block':'none';updateCompleteBtn();}
function setQuickAmount(a){document.getElementById('cashReceived').value=a;updateCashChange();}
document.getElementById('cashReceived').addEventListener('input',updateCashChange);

function updateCashChange(){const t=calculateTotals();const r=parseFloat(document.getElementById('cashReceived').value)||0;const ch=r-t.total;if(ch>=0&&r>0){document.getElementById('changeAmount').textContent='₹'+ch.toFixed(2);document.getElementById('changeBox').style.display='block';}else{document.getElementById('changeBox').style.display='none';}updateCompleteBtn();}
function updateCompleteBtn(){const t=calculateTotals();const r=parseFloat(document.getElementById('cashReceived').value)||0;document.getElementById('completeBtn').disabled=paymentMethod==='cash'&&r<t.total;}

function completeSale(){
const t=calculateTotals();
const custId = document.getElementById('customerId').value || null;
const custName = selectedCustomer ? selectedCustomer.name : null;
const cr=parseFloat(document.getElementById('cashReceived').value)||0;
fetch('{{ route("admin.pos.complete") }}',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':CSRF},body:JSON.stringify({cart:cart,customer_id:custId,customer_name:custName,discount_amount:t.discountAmt,payment_method:paymentMethod,cash_received:paymentMethod==='cash'?cr:null})})
.then(r=>r.json()).then(d=>{
if(d.success){lastSale=d.sale;document.getElementById('successInvoice').textContent=d.sale.invoice_no;
if(paymentMethod==='cash'&&cr>t.total){document.getElementById('successChangeAmt').textContent='₹'+(cr-t.total).toFixed(2);document.getElementById('successChange').style.display='block';}else{document.getElementById('successChange').style.display='none';}
document.getElementById('paymentModal').classList.remove('show');document.getElementById('successModal').classList.add('show');}
else{toast(d.message||'Error','error');}
}).catch(()=>toast('Error','error'));
}

function printReceipt(){
    if(!lastSale) return;
    
    // Get store settings from PHP
    const store = {
        name: '{{ $settings->store_name ?? "EchoPx Store" }}',
        address: '{{ $settings->store_address ?? "" }}',
        phone: '{{ $settings->store_phone ?? "" }}',
        gstin: '{{ $settings->store_gstin ?? "" }}',
        footer: '{{ $settings->receipt_footer ?? "Thank you for shopping!" }}'
    };
    
    // Build receipt text with fixed-width alignment
    const W = 42; // characters width for 80mm
    const line = '-'.repeat(W);
    const dline = '='.repeat(W);
    
    const center = (t) => {
        const pad = Math.floor((W - t.length) / 2);
        return ' '.repeat(Math.max(0, pad)) + t;
    };
    const row = (l, r) => {
        const space = W - l.length - r.length;
        return l + ' '.repeat(Math.max(1, space)) + r;
    };
    
    let r = '';
    r += center(store.name) + '\n';
    if(store.address) r += center(store.address) + '\n';
    if(store.phone) r += center('Tel: ' + store.phone) + '\n';
    if(store.gstin) r += center('GSTIN: ' + store.gstin) + '\n';
    r += dline + '\n';
    r += row('Invoice:', lastSale.invoice_no) + '\n';
    r += row('Date:', new Date(lastSale.created_at).toLocaleString('en-IN')) + '\n';
    if(lastSale.customer_name) r += row('Customer:', lastSale.customer_name) + '\n';
    r += row('Cashier:', '{{ $admin->name ?? "Admin" }}') + '\n';
    r += line + '\n';
    
    // Items from cart (we still have cart data)
    cart.forEach(it => {
        let name = it.name;
        if(it.variant_name) name += ' (' + it.variant_name + ')';
        if(name.length > W) name = name.substring(0, W-2) + '..';
        r += name + '\n';
        r += row(it.qty + ' x ' + it.price.toFixed(2), (it.qty * it.price).toFixed(2)) + '\n';
    });
    
    r += line + '\n';
    const totals = calculateTotals();
    r += row('Subtotal:', totals.subtotal.toFixed(2)) + '\n';
    if(totals.discountAmt > 0) r += row('Discount:', '-' + totals.discountAmt.toFixed(2)) + '\n';
    if(totals.tax > 0) r += row('Tax:', totals.tax.toFixed(2)) + '\n';
    r += dline + '\n';
    r += row('TOTAL:', 'Rs.' + totals.total.toFixed(2)) + '\n';
    r += line + '\n';
    r += row('Payment:', paymentMethod.toUpperCase()) + '\n';
    
    if(paymentMethod === 'cash') {
        const cr = parseFloat(document.getElementById('cashReceived').value) || totals.total;
        r += row('Received:', cr.toFixed(2)) + '\n';
        r += row('Change:', Math.max(0, cr - totals.total).toFixed(2)) + '\n';
    }
    
    r += line + '\n';
    r += center(lastSale.invoice_no) + '\n';
    r += center(store.footer) + '\n';
    r += center(new Date().toLocaleString('en-IN')) + '\n';
    
    // Create print window
    const printHtml = `<!DOCTYPE html>
<html><head><title>Receipt</title>
<style>
@page { size: 80mm auto; margin: 0; }
@media print { html,body { width: 80mm; } }
* { margin:0; padding:0; }
body { font-family: 'Courier New', monospace; font-size: 12px; width: 80mm; padding: 3mm; }
pre { font-family: inherit; font-size: inherit; white-space: pre; line-height: 1.4; }
</style></head>
<body><pre>${r}</pre>
<script>window.onload=function(){window.print();window.onafterprint=function(){window.close();}}<\/script>
</body></html>`;
    
    const printWin = window.open('', '_blank', 'width=350,height=600');
    printWin.document.write(printHtml);
    printWin.document.close();
}
function newSale(){cart=[];document.getElementById('discountInput').value='';clearCustomer();renderCart();document.getElementById('successModal').classList.remove('show');document.getElementById('barcodeInput').focus();}
function toast(m,t){const to=document.getElementById('toast');to.textContent=m;to.className='toast show '+t;setTimeout(()=>to.classList.remove('show'),1800);}

// Customer Search/Select/Create
var selectedCustomer = null;
var custSearchTimer = null;

document.getElementById('customerSearch').addEventListener('input', function(){
    clearTimeout(custSearchTimer);
    var q = this.value.trim();
    if(q.length < 2){
        document.getElementById('customerResults').classList.remove('show');
        return;
    }
    custSearchTimer = setTimeout(function(){ searchCustomers(q); }, 300);
});

function searchCustomers(q){
    fetch('{{ route("admin.pos.customers.search") }}?q=' + encodeURIComponent(q))
    .then(r => r.json())
    .then(customers => {
        var cr = document.getElementById('customerResults');
        if(customers.length === 0){
            cr.innerHTML = '<div class="cust-item" style="color:var(--text-muted);cursor:default">No customers found</div>';
        } else {
            cr.innerHTML = customers.map(c => `<div class="cust-item" onclick="selectCustomer(${c.id}, '${c.name.replace(/'/g,"\\'")}', '${c.email||''}', '${c.phone||''}')">
                <span class="cust-name">${c.name}</span>
                <span class="cust-detail">${c.email||''} ${c.phone ? '• '+c.phone : ''}</span>
            </div>`).join('');
        }
        cr.classList.add('show');
    });
}

function selectCustomer(id, name, email, phone){
    selectedCustomer = {id: id, name: name, email: email, phone: phone};
    document.getElementById('customerId').value = id;
    document.getElementById('selCustName').textContent = name;
    document.getElementById('selCustDetail').textContent = [email, phone].filter(Boolean).join(' • ');
    document.getElementById('selectedCustomer').style.display = 'flex';
    document.getElementById('customerSearch').style.display = 'none';
    document.querySelector('.customer-add-btn').style.display = 'none';
    document.getElementById('customerResults').classList.remove('show');
}

function clearCustomer(){
    selectedCustomer = null;
    document.getElementById('customerId').value = '';
    document.getElementById('customerSearch').value = '';
    document.getElementById('customerSearch').style.display = 'block';
    document.querySelector('.customer-add-btn').style.display = 'block';
    document.getElementById('selectedCustomer').style.display = 'none';
}

document.addEventListener('click', function(e){
    if(!e.target.closest('.customer-area')) document.getElementById('customerResults').classList.remove('show');
});

function openCustomerModal(){
    document.getElementById('customerModal').classList.add('show');
    document.getElementById('newCustName').value = '';
    document.getElementById('newCustEmail').value = '';
    document.getElementById('newCustPhone').value = '';
    document.getElementById('emailError').textContent = '';
    document.getElementById('phoneError').textContent = '';
    document.getElementById('newCustName').focus();
}

function closeCustomerModal(){
    document.getElementById('customerModal').classList.remove('show');
}

function saveCustomer(){
    var name = document.getElementById('newCustName').value.trim();
    var email = document.getElementById('newCustEmail').value.trim();
    var phone = document.getElementById('newCustPhone').value.trim();
    
    // Clear errors
    document.getElementById('emailError').textContent = '';
    document.getElementById('phoneError').textContent = '';
    document.getElementById('newCustEmail').classList.remove('error');
    document.getElementById('newCustPhone').classList.remove('error');
    
    if(!name || !email || !phone){
        toast('Name, email and phone required', 'error');
        return;
    }
    
    fetch('{{ route("admin.pos.customers.create") }}', {
        method: 'POST',
        headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF},
        body: JSON.stringify({ name: name, email: email, phone: phone })
    })
    .then(r => r.json())
    .then(d => {
        if(d.success){
            selectCustomer(d.customer.id, d.customer.name, d.customer.email, d.customer.phone);
            closeCustomerModal();
            toast('Customer created', 'success');
        } else {
            if(d.errors){
                if(d.errors.email){
                    document.getElementById('emailError').textContent = d.errors.email;
                    document.getElementById('newCustEmail').classList.add('error');
                }
                if(d.errors.phone){
                    document.getElementById('phoneError').textContent = d.errors.phone;
                    document.getElementById('newCustPhone').classList.add('error');
                }
            }
            toast(d.message || 'Error', 'error');
        }
    })
    .catch(function(){ toast('Error', 'error'); });
}
document.addEventListener('click',function(e){if(!e.target.closest('.modal')&&!e.target.closest('input')&&!e.target.closest('button'))document.getElementById('barcodeInput').focus();});
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/quagga/0.12.1/quagga.min.js"></script>
<script>
// Camera Scanner
var camOn=false,camFace='environment',camInitialized=false;

function openCamera(){
document.getElementById('cameraModal').classList.add('show');
document.body.style.overflow='hidden';
setTimeout(startCam,200);
}

function closeCamera(){
stopCam();
document.getElementById('cameraModal').classList.remove('show');
document.body.style.overflow='';
document.getElementById('barcodeInput').focus();
}

function flipCamera(){
camFace=camFace==='environment'?'user':'environment';
stopCam();
setTimeout(startCam,300);
}

function onBarcodeDetected(r){
if(r && r.codeResult && r.codeResult.code && r.codeResult.code.length >= 4){
    var code = r.codeResult.code;
    var det = document.getElementById('scanDetected');
    
    // Process the barcode
    processBarcode(code);
    
    // Show result after a moment
    setTimeout(function(){
        var item = null;
        for(var i = 0; i < cart.length; i++){
            if(cart[i].barcode === code){
                item = cart[i];
                break;
            }
        }
        if(item){
            det.textContent = item.name + ' (' + item.qty + ')';
        } else {
            det.textContent = '✓ ' + code;
        }
        det.classList.add('show');
    }, 150);
    
    if(navigator.vibrate) navigator.vibrate(100);
    
    Quagga.pause();
    setTimeout(function(){
        det.classList.remove('show');
        if(camOn) Quagga.start();
    }, 1200);
}
}

function startCam(){
if(camOn||typeof Quagga==='undefined')return;

// Remove any existing handler first
if(camInitialized){
Quagga.offDetected(onBarcodeDetected);
}

Quagga.init({
inputStream:{
type:"LiveStream",
target:document.getElementById('cameraViewport'),
constraints:{width:1280,height:720,facingMode:camFace}
},
decoder:{readers:["ean_reader","ean_8_reader","code_128_reader","code_39_reader","upc_reader"]},
locate:true
},function(err){
if(err){
console.error('Camera init error:',err);
toast('Camera failed','error');
closeCamera();
return;
}
Quagga.start();
camOn=true;
camInitialized=true;
Quagga.onDetected(onBarcodeDetected);
});
}

function stopCam(){
if(camOn&&typeof Quagga!=='undefined'){
Quagga.offDetected(onBarcodeDetected);
Quagga.stop();
camOn=false;
}
// Clean up
var vp=document.getElementById('cameraViewport');
if(vp){
var v=vp.querySelector('video');
var c=vp.querySelector('canvas');
if(v)v.remove();
if(c)c.remove();
}
}

document.addEventListener('keydown',function(e){
if(e.key==='Escape'&&document.getElementById('cameraModal').classList.contains('show'))closeCamera();
if(e.key==='Escape'&&document.getElementById('qrModal').classList.contains('show'))closeQR();
});

// Mobile Scanner QR Code
var scannerUrl = '{{ url("/pos/scanner/" . $session->session_code) }}';
var polling = false;
var pollInterval = null;

function showQR(){
    document.getElementById('qrModal').classList.add('show');
    document.getElementById('qrUrl').textContent = scannerUrl;
    generateQR(scannerUrl);
    startPolling();
}

function closeQR(){
    document.getElementById('qrModal').classList.remove('show');
    stopPolling();
}

function generateQR(url){
    var qrDiv = document.getElementById('qrCode');
    qrDiv.innerHTML = '';
    
    // Simple QR using external API (fallback)
    var img = document.createElement('img');
    img.src = 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=' + encodeURIComponent(url);
    img.width = 200;
    img.height = 200;
    img.alt = 'QR Code';
    qrDiv.appendChild(img);
}

function startPolling(){
    if(polling) return;
    polling = true;
    pollInterval = setInterval(pollForScans, 500);
}

function stopPolling(){
    polling = false;
    if(pollInterval){
        clearInterval(pollInterval);
        pollInterval = null;
    }
}

function pollForScans(){
    fetch('{{ route("admin.pos.poll") }}')
    .then(function(r){ return r.json(); })
    .then(function(d){
        if(d.scans && d.scans.length > 0){
            d.scans.forEach(function(barcode){
                processBarcode(barcode);
            });
        }
    })
    .catch(function(){});
}

// Start polling automatically when page loads
startPolling();
</script>
