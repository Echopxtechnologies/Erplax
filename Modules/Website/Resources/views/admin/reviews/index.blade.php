<style>
.reviews-page { padding: 0; }
.page-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px; flex-wrap: wrap; gap: 16px; }
.page-header h1 { font-size: 24px; font-weight: 700; color: #0f172a; margin: 0; display: flex; align-items: center; gap: 12px; }
.page-header h1 svg { width: 28px; height: 28px; color: #ec4899; }

.stats-row { display: flex; gap: 16px; margin-bottom: 24px; flex-wrap: wrap; }
.stat-pill { display: flex; align-items: center; gap: 8px; padding: 10px 20px; background: #fff; border: 1px solid #e5e7eb; border-radius: 10px; font-size: 14px; font-weight: 500; color: #64748b; text-decoration: none; transition: all 0.2s; }
.stat-pill:hover { border-color: #6366f1; }
.stat-pill.active { background: #6366f1; border-color: #6366f1; color: #fff; }
.stat-pill .count { padding: 2px 8px; background: rgba(0,0,0,0.1); border-radius: 6px; font-weight: 700; font-size: 13px; }
.stat-pill.active .count { background: rgba(255,255,255,0.2); }

.reviews-list { display: flex; flex-direction: column; gap: 16px; }

.review-card { background: #fff; border: 1px solid #e5e7eb; border-radius: 16px; overflow: hidden; }
.review-header { display: flex; align-items: center; justify-content: space-between; padding: 20px 24px; border-bottom: 1px solid #f1f5f9; gap: 16px; flex-wrap: wrap; }
.reviewer-info { display: flex; align-items: center; gap: 14px; }
.reviewer-avatar { width: 48px; height: 48px; border-radius: 12px; background: linear-gradient(135deg, #667eea, #764ba2); color: #fff; display: flex; align-items: center; justify-content: center; font-size: 20px; font-weight: 700; flex-shrink: 0; }
.reviewer-details h4 { font-size: 16px; font-weight: 600; color: #0f172a; margin: 0 0 4px; }
.reviewer-meta { display: flex; align-items: center; gap: 12px; font-size: 13px; color: #64748b; flex-wrap: wrap; }
.verified-badge { display: inline-flex; align-items: center; gap: 4px; padding: 2px 8px; background: #dcfce7; color: #15803d; border-radius: 4px; font-size: 11px; font-weight: 600; }
.review-status { padding: 6px 14px; border-radius: 8px; font-size: 12px; font-weight: 600; }
.review-status.pending { background: #fef3c7; color: #b45309; }
.review-status.approved { background: #dcfce7; color: #15803d; }
.review-status.rejected { background: #fee2e2; color: #dc2626; }

.review-body { padding: 20px 24px; }
.product-link { display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; background: #f1f5f9; border-radius: 8px; font-size: 13px; color: #475569; text-decoration: none; margin-bottom: 16px; }
.product-link:hover { background: #e2e8f0; }
.review-rating { display: flex; gap: 2px; margin-bottom: 12px; }
.review-rating .star { font-size: 20px; color: #e2e8f0; }
.review-rating .star.filled { color: #fbbf24; }
.review-title { font-size: 16px; font-weight: 600; color: #0f172a; margin-bottom: 8px; }
.review-text { font-size: 14px; color: #475569; line-height: 1.7; }
.review-date { font-size: 12px; color: #94a3b8; margin-top: 12px; }

.admin-reply { margin-top: 20px; padding: 16px; background: #f8fafc; border-left: 3px solid #6366f1; border-radius: 0 8px 8px 0; }
.admin-reply-label { font-size: 12px; font-weight: 600; color: #6366f1; margin-bottom: 8px; }
.admin-reply-text { font-size: 14px; color: #374151; }

.review-actions { display: flex; gap: 8px; padding: 16px 24px; background: #f8fafc; border-top: 1px solid #f1f5f9; flex-wrap: wrap; }
.btn { display: inline-flex; align-items: center; gap: 6px; padding: 10px 18px; border-radius: 8px; font-size: 13px; font-weight: 600; border: none; cursor: pointer; transition: all 0.2s; text-decoration: none; }
.btn-approve { background: #10b981; color: #fff; }
.btn-approve:hover { background: #059669; }
.btn-reject { background: #f1f5f9; color: #64748b; }
.btn-reject:hover { background: #e2e8f0; color: #374151; }
.btn-reply { background: #fff; border: 1px solid #e5e7eb; color: #475569; }
.btn-reply:hover { border-color: #6366f1; color: #6366f1; }
.btn-delete { background: #fff; border: 1px solid #fecaca; color: #dc2626; }
.btn-delete:hover { background: #fee2e2; }

.reply-form { margin-top: 12px; display: none; }
.reply-form.open { display: block; }
.reply-form textarea { width: 100%; padding: 12px; border: 1px solid #e5e7eb; border-radius: 8px; font-size: 14px; resize: vertical; min-height: 80px; }
.reply-form textarea:focus { outline: none; border-color: #6366f1; }
.reply-form-actions { display: flex; gap: 8px; margin-top: 12px; }

.empty-state { text-align: center; padding: 60px 24px; background: #fff; border: 1px solid #e5e7eb; border-radius: 16px; }
.empty-state svg { width: 64px; height: 64px; color: #d1d5db; margin-bottom: 16px; }
.empty-state h3 { font-size: 18px; font-weight: 600; color: #374151; margin-bottom: 8px; }
.empty-state p { font-size: 14px; color: #64748b; }

.alert { padding: 12px 16px; border-radius: 8px; margin-bottom: 16px; font-size: 14px; }
.alert-success { background: #d1fae5; color: #065f46; }
</style>

<div class="reviews-page">
    <div class="page-header">
        <h1>
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
            Product Reviews
        </h1>
        <a href="{{ route('admin.website.index') }}" class="btn btn-reply">← Back to Dashboard</a>
    </div>

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="stats-row">
        <a href="{{ route('admin.website.reviews') }}" class="stat-pill {{ $status === 'all' ? 'active' : '' }}">
            All <span class="count">{{ $stats['total'] }}</span>
        </a>
        <a href="{{ route('admin.website.reviews', ['status' => 'pending']) }}" class="stat-pill {{ $status === 'pending' ? 'active' : '' }}">
            Pending <span class="count">{{ $stats['pending'] }}</span>
        </a>
        <a href="{{ route('admin.website.reviews', ['status' => 'approved']) }}" class="stat-pill {{ $status === 'approved' ? 'active' : '' }}">
            Approved <span class="count">{{ $stats['approved'] }}</span>
        </a>
        <a href="{{ route('admin.website.reviews', ['status' => 'rejected']) }}" class="stat-pill {{ $status === 'rejected' ? 'active' : '' }}">
            Rejected <span class="count">{{ $stats['rejected'] }}</span>
        </a>
    </div>

    <div class="reviews-list">
        @forelse($reviews as $review)
        <div class="review-card">
            <div class="review-header">
                <div class="reviewer-info">
                    <div class="reviewer-avatar">{{ strtoupper(substr($review->reviewer_name, 0, 1)) }}</div>
                    <div class="reviewer-details">
                        <h4>{{ $review->reviewer_name }}</h4>
                        <div class="reviewer-meta">
                            @if($review->reviewer_email)
                            <span>{{ $review->reviewer_email }}</span>
                            @endif
                            @if($review->is_verified_purchase)
                            <span class="verified-badge">✓ Verified Purchase</span>
                            @endif
                        </div>
                    </div>
                </div>
                <span class="review-status {{ $review->status }}">{{ ucfirst($review->status) }}</span>
            </div>
            
            <div class="review-body">
                @if($review->product)
                <a href="#" class="product-link">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    {{ $review->product->name }}
                </a>
                @endif
                
                <div class="review-rating">
                    @for($i = 1; $i <= 5; $i++)
                        <span class="star {{ $i <= $review->rating ? 'filled' : '' }}">★</span>
                    @endfor
                </div>
                
                @if($review->title)
                <h5 class="review-title">{{ $review->title }}</h5>
                @endif
                
                <p class="review-text">{{ $review->review }}</p>
                
                <div class="review-date">{{ $review->created_at->format('d M Y, h:i A') }}</div>
                
                @if($review->admin_reply)
                <div class="admin-reply">
                    <div class="admin-reply-label">Your Reply:</div>
                    <p class="admin-reply-text">{{ $review->admin_reply }}</p>
                </div>
                @endif
            </div>
            
            <div class="review-actions">
                @if($review->status === 'pending')
                <form action="{{ route('admin.website.reviews.approve', $review->id) }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn btn-approve">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        Approve
                    </button>
                </form>
                <form action="{{ route('admin.website.reviews.reject', $review->id) }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn btn-reject">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                        Reject
                    </button>
                </form>
                @endif
                
                <button type="button" class="btn btn-reply" onclick="this.closest('.review-card').querySelector('.reply-form').classList.toggle('open')">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
                    {{ $review->admin_reply ? 'Edit Reply' : 'Reply' }}
                </button>
                
                <form action="{{ route('admin.website.reviews.delete', $review->id) }}" method="POST" style="display:inline; margin-left: auto;" onsubmit="return confirm('Delete this review?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-delete">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        Delete
                    </button>
                </form>
            </div>
            
            <div class="reply-form" style="padding: 0 24px 24px;">
                <form action="{{ route('admin.website.reviews.reply', $review->id) }}" method="POST">
                    @csrf
                    <textarea name="reply" placeholder="Write your reply...">{{ $review->admin_reply }}</textarea>
                    <div class="reply-form-actions">
                        <button type="submit" class="btn btn-approve">Save Reply</button>
                        <button type="button" class="btn btn-reject" onclick="this.closest('.reply-form').classList.remove('open')">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
        @empty
        <div class="empty-state">
            <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
            <h3>No Reviews Found</h3>
            <p>{{ $status !== 'all' ? 'No ' . $status . ' reviews.' : 'Customers haven\'t left any reviews yet.' }}</p>
        </div>
        @endforelse
    </div>

    @if($reviews->hasPages())
    <div style="margin-top: 24px;">
        {{ $reviews->appends(['status' => $status])->links() }}
    </div>
    @endif
</div>
