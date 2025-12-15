<style>
    .detail-page { max-width: 700px; margin: 0 auto; padding: 20px; }
    .detail-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px; flex-wrap: wrap; gap: 16px; }
    .detail-header-left { display: flex; align-items: center; gap: 16px; }
    .btn-back { width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; background: var(--card-bg); border: 1px solid var(--card-border); border-radius: 10px; color: var(--text-secondary); text-decoration: none; }
    .btn-back:hover { background: var(--body-bg); color: var(--text-primary); }
    .btn-back svg { width: 20px; height: 20px; }
    .detail-header h1 { font-size: 24px; font-weight: 700; color: var(--text-primary); margin: 0; }
    .btn-edit { display: inline-flex; align-items: center; gap: 8px; background: linear-gradient(135deg, var(--primary), var(--primary-hover)); color: #fff; padding: 10px 20px; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 14px; }
    .btn-edit:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3); color: #fff; }
    .btn-edit svg { width: 18px; height: 18px; }
    .detail-card { background: var(--card-bg); border: 1px solid var(--card-border); border-radius: 16px; overflow: hidden; }
    .detail-card-header { padding: 20px 24px; border-bottom: 1px solid var(--card-border); background: var(--body-bg); display: flex; justify-content: space-between; align-items: center; }
    .detail-card-title { font-size: 16px; font-weight: 600; color: var(--text-primary); margin: 0; display: flex; align-items: center; gap: 10px; }
    .detail-card-title svg { width: 20px; height: 20px; color: var(--primary); }
    .status-badge { display: inline-flex; align-items: center; padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; }
    .status-badge.success { background: var(--success-light); color: var(--success); }
    .status-badge.danger { background: var(--danger-light); color: var(--danger); }
    .detail-card-body { padding: 24px; }
    .detail-row { display: flex; padding: 16px 0; border-bottom: 1px solid var(--card-border); }
    .detail-row:last-child { border-bottom: none; padding-bottom: 0; }
    .detail-row:first-child { padding-top: 0; }
    .detail-label { width: 140px; flex-shrink: 0; font-size: 14px; font-weight: 600; color: var(--text-muted); }
    .detail-value { flex: 1; font-size: 14px; color: var(--text-primary); }
    .detail-value.price { font-size: 18px; font-weight: 700; color: var(--success); }
    .detail-value.description { white-space: pre-wrap; line-height: 1.6; }
    .detail-value.empty { color: var(--text-muted); font-style: italic; }
</style>

<div class="detail-page">
    <div class="detail-header">
        <div class="detail-header-left">
            <a href="{{ route('admin.product.index') }}" class="btn-back"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M15 19l-7-7 7-7"></path></svg></a>
            <h1>{{ $product->name }}</h1>
        </div>
        <a href="{{ route('admin.product.edit', $product->id) }}" class="btn-edit"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>Edit</a>
    </div>

    <div class="detail-card">
        <div class="detail-card-header">
            <h2 class="detail-card-title"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>Product Details</h2>
            <span class="status-badge {{ $product->status_badge }}">{{ $product->status_label }}</span>
        </div>
        <div class="detail-card-body">
            <div class="detail-row"><div class="detail-label">ID</div><div class="detail-value">#{{ $product->id }}</div></div>
            <div class="detail-row"><div class="detail-label">Name</div><div class="detail-value">{{ $product->name }}</div></div>
            <div class="detail-row"><div class="detail-label">SKU</div><div class="detail-value">{{ $product->sku }}</div></div>
            <div class="detail-row"><div class="detail-label">Purchase Price</div><div class="detail-value price">₹{{ $product->formatted_purchase_price }}</div></div>
            <div class="detail-row"><div class="detail-label">Sale Price</div><div class="detail-value price">₹{{ $product->formatted_sale_price }}</div></div>
            <div class="detail-row"><div class="detail-label">MRP</div><div class="detail-value">{{ $product->mrp ? '₹' . $product->formatted_mrp : '-' }}</div></div>
            <div class="detail-row"><div class="detail-label">Description</div><div class="detail-value description {{ empty($product->description) ? 'empty' : '' }}">{{ $product->description ?: 'No description' }}</div></div>
            <div class="detail-row"><div class="detail-label">Created</div><div class="detail-value">{{ $product->created_at->format('M d, Y h:i A') }}</div></div>
            <div class="detail-row"><div class="detail-label">Updated</div><div class="detail-value">{{ $product->updated_at->format('M d, Y h:i A') }}</div></div>
        </div>
    </div>
</div>
