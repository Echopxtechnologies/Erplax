<style>
.reviews-page { padding: 0; }
.page-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px; flex-wrap: wrap; gap: 16px; }
.page-header h1 { font-size: 24px; font-weight: 700; color: #0f172a; margin: 0; display: flex; align-items: center; gap: 12px; }
.page-header h1 svg { width: 28px; height: 28px; color: #f59e0b; }
.btn-back { display: inline-flex; align-items: center; gap: 6px; padding: 10px 18px; background: #fff; border: 1px solid #e5e7eb; border-radius: 8px; font-size: 13px; font-weight: 600; color: #475569; text-decoration: none; }
.btn-back:hover { border-color: #6366f1; color: #6366f1; }

/* Overview Cards */
.overview-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 28px; }
.overview-card { background: #fff; border-radius: 16px; padding: 24px; border: 1px solid #e5e7eb; position: relative; overflow: hidden; }
.overview-card::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 4px; }
.overview-card.total::before { background: linear-gradient(90deg, #6366f1, #8b5cf6); }
.overview-card.avg::before { background: linear-gradient(90deg, #f59e0b, #f97316); }
.overview-card.top::before { background: linear-gradient(90deg, #10b981, #059669); }
.overview-card.critical::before { background: linear-gradient(90deg, #ef4444, #dc2626); }
.overview-icon { width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-bottom: 16px; }
.overview-card.total .overview-icon { background: #eef2ff; color: #6366f1; }
.overview-card.avg .overview-icon { background: #fef3c7; color: #f59e0b; }
.overview-card.top .overview-icon { background: #d1fae5; color: #10b981; }
.overview-card.critical .overview-icon { background: #fee2e2; color: #ef4444; }
.overview-value { font-size: 32px; font-weight: 700; color: #0f172a; margin-bottom: 4px; }
.overview-label { font-size: 14px; color: #64748b; }

/* Rating Section */
.rating-section { display: grid; grid-template-columns: 1fr 2fr; gap: 24px; margin-bottom: 28px; }
.rating-overview { background: #fff; border-radius: 16px; padding: 24px; border: 1px solid #e5e7eb; text-align: center; }
.big-rating { font-size: 56px; font-weight: 700; color: #0f172a; line-height: 1; }
.big-stars { font-size: 24px; color: #fbbf24; margin: 8px 0; }
.total-reviews { font-size: 14px; color: #64748b; }
.rating-bars-card { background: #fff; border-radius: 16px; padding: 24px; border: 1px solid #e5e7eb; }
.rating-bars-card h3 { font-size: 16px; font-weight: 600; color: #0f172a; margin: 0 0 20px; }
.rating-bar-row { display: flex; align-items: center; gap: 12px; margin-bottom: 12px; }
.bar-label { font-size: 14px; font-weight: 600; color: #374151; width: 50px; }
.bar-label .star { color: #fbbf24; }
.bar-track { flex: 1; height: 10px; background: #f1f5f9; border-radius: 5px; overflow: hidden; }
.bar-fill { height: 100%; border-radius: 5px; }
.bar-fill.star5 { background: #10b981; }
.bar-fill.star4 { background: #84cc16; }
.bar-fill.star3 { background: #f59e0b; }
.bar-fill.star2 { background: #f97316; }
.bar-fill.star1 { background: #ef4444; }
.bar-count { font-size: 13px; color: #64748b; width: 40px; text-align: right; }

/* Section Headers */
.section-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px; margin-top: 32px; }
.section-title { font-size: 18px; font-weight: 700; color: #0f172a; display: flex; align-items: center; gap: 10px; }
.section-title .badge { padding: 4px 10px; border-radius: 6px; font-size: 12px; font-weight: 600; }
.section-title .badge.green { background: #d1fae5; color: #059669; }
.section-title .badge.red { background: #fee2e2; color: #dc2626; }

/* Reviews Grid */
.reviews-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px; margin-bottom: 32px; }
.review-card-compact { background: #fff; border: 1px solid #e5e7eb; border-radius: 14px; padding: 20px; }
.review-card-compact.critical { border-left: 4px solid #ef4444; }
.review-card-compact.top { border-left: 4px solid #10b981; }
.compact-header { display: flex; align-items: center; gap: 12px; margin-bottom: 12px; }
.compact-avatar { width: 40px; height: 40px; border-radius: 10px; background: linear-gradient(135deg, #667eea, #764ba2); color: #fff; display: flex; align-items: center; justify-content: center; font-size: 16px; font-weight: 700; }
.compact-info { flex: 1; }
.compact-name { font-size: 14px; font-weight: 600; color: #0f172a; }
.compact-meta { font-size: 12px; color: #94a3b8; }
.compact-rating { display: flex; gap: 2px; }
.compact-rating .star { font-size: 14px; color: #e2e8f0; }
.compact-rating .star.filled { color: #fbbf24; }
.compact-product { display: inline-block; padding: 4px 10px; background: #f8fafc; border-radius: 6px; font-size: 12px; color: #64748b; margin-bottom: 10px; }
.compact-text { font-size: 13px; color: #475569; line-height: 1.6; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
.compact-actions { display: flex; gap: 8px; margin-top: 12px; padding-top: 12px; border-top: 1px solid #f1f5f9; }
.compact-btn { padding: 6px 12px; border-radius: 6px; font-size: 12px; font-weight: 600; border: none; cursor: pointer; }
.compact-btn.delete { background: #fee2e2; color: #dc2626; }
.compact-btn.delete:hover { background: #dc2626; color: #fff; }
.compact-btn.reply { background: #eef2ff; color: #6366f1; }
.compact-btn.reply:hover { background: #6366f1; color: #fff; }
.compact-btn.approve { background: #d1fae5; color: #059669; }

/* Stats Pills */
.stats-row { display: flex; gap: 12px; margin-bottom: 20px; flex-wrap: wrap; }
.stat-pill { display: flex; align-items: center; gap: 8px; padding: 10px 18px; background: #fff; border: 1px solid #e5e7eb; border-radius: 10px; font-size: 14px; font-weight: 500; color: #64748b; text-decoration: none; }
.stat-pill:hover { border-color: #6366f1; }
.stat-pill.active { background: #6366f1; border-color: #6366f1; color: #fff; }
.stat-pill .count { padding: 2px 8px; background: rgba(0,0,0,0.08); border-radius: 6px; font-weight: 700; font-size: 13px; }
.stat-pill.active .count { background: rgba(255,255,255,0.2); }

/* Review Card Full */
.reviews-list { display: flex; flex-direction: column; gap: 16px; }
.review-card { background: #fff; border: 1px solid #e5e7eb; border-radius: 16px; overflow: hidden; }
.review-header { display: flex; align-items: center; justify-content: space-between; padding: 20px 24px; border-bottom: 1px solid #f1f5f9; flex-wrap: wrap; gap: 16px; }
.reviewer-info { display: flex; align-items: center; gap: 14px; }
.reviewer-avatar { width: 48px; height: 48px; border-radius: 12px; background: linear-gradient(135deg, #667eea, #764ba2); color: #fff; display: flex; align-items: center; justify-content: center; font-size: 20px; font-weight: 700; }
.reviewer-details h4 { font-size: 16px; font-weight: 600; color: #0f172a; margin: 0 0 4px; }
.reviewer-meta { display: flex; align-items: center; gap: 12px; font-size: 13px; color: #64748b; flex-wrap: wrap; }
.verified-badge { display: inline-flex; align-items: center; gap: 4px; padding: 2px 8px; background: #dcfce7; color: #15803d; border-radius: 4px; font-size: 11px; font-weight: 600; }
.review-status { padding: 6px 14px; border-radius: 8px; font-size: 12px; font-weight: 600; }
.review-status.pending { background: #fef3c7; color: #b45309; }
.review-status.approved { background: #dcfce7; color: #15803d; }
.review-status.rejected { background: #fee2e2; color: #dc2626; }
.review-body { padding: 20px 24px; }
.product-link { display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; background: #f1f5f9; border-radius: 8px; font-size: 13px; color: #475569; text-decoration: none; margin-bottom: 16px; }
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
.btn { display: inline-flex; align-items: center; gap: 6px; padding: 10px 18px; border-radius: 8px; font-size: 13px; font-weight: 600; border: none; cursor: pointer; text-decoration: none; }
.btn-approve { background: #10b981; color: #fff; }
.btn-approve:hover { background: #059669; }
.btn-reject { background: #f1f5f9; color: #64748b; }
.btn-reject:hover { background: #e2e8f0; }
.btn-reply { background: #fff; border: 1px solid #e5e7eb; color: #475569; }
.btn-reply:hover { border-color: #6366f1; color: #6366f1; }
.btn-delete { background: #fff; border: 1px solid #fecaca; color: #dc2626; }
.btn-delete:hover { background: #fee2e2; }
.reply-form { padding: 0 24px 24px; display: none; }
.reply-form.open { display: block; }
.reply-form textarea { width: 100%; padding: 12px; border: 1px solid #e5e7eb; border-radius: 8px; font-size: 14px; resize: vertical; min-height: 80px; }
.reply-form textarea:focus { outline: none; border-color: #6366f1; }
.reply-form-actions { display: flex; gap: 8px; margin-top: 12px; }
.empty-state { text-align: center; padding: 60px 24px; background: #fff; border: 1px solid #e5e7eb; border-radius: 16px; }
.empty-state svg { width: 64px; height: 64px; color: #d1d5db; margin-bottom: 16px; }
.empty-state h3 { font-size: 18px; font-weight: 600; color: #374151; margin-bottom: 8px; }
.empty-state p { font-size: 14px; color: #64748b; }
.alert { padding: 14px 18px; border-radius: 10px; margin-bottom: 20px; font-size: 14px; font-weight: 500; }
.alert-success { background: #d1fae5; color: #065f46; }

@media (max-width: 1200px) { .overview-grid { grid-template-columns: repeat(2, 1fr); } .reviews-grid { grid-template-columns: 1fr; } }
@media (max-width: 768px) { .overview-grid { grid-template-columns: 1fr; } .rating-section { grid-template-columns: 1fr; } }
</style>

<div class="reviews-page">
    <div class="page-header">
        <h1>
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
            Reviews & Ratings
        </h1>
        <a href="{{ route('admin.ecommerce.index') }}" class="btn-back">← Back to Dashboard</a>
    </div>

    @if(session('success'))
    <div class="alert alert-success">✓ {{ session('success') }}</div>
    @endif

    {{-- Overview Cards --}}
    <div class="overview-grid">
        <div class="overview-card total">
            <div class="overview-icon"><svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg></div>
            <div class="overview-value">{{ $stats['total'] }}</div>
            <div class="overview-label">Total Reviews</div>
        </div>
        <div class="overview-card avg">
            <div class="overview-icon"><svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg></div>
            <div class="overview-value">{{ number_format($stats['avg_rating'] ?? 0, 1) }}</div>
            <div class="overview-label">Average Rating</div>
        </div>
        <div class="overview-card top">
            <div class="overview-icon"><svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"/></svg></div>
            <div class="overview-value">{{ $stats['top_reviews'] ?? 0 }}</div>
            <div class="overview-label">5-Star Reviews</div>
        </div>
        <div class="overview-card critical">
            <div class="overview-icon"><svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg></div>
            <div class="overview-value">{{ $stats['critical_reviews'] ?? 0 }}</div>
            <div class="overview-label">Critical (1-2★)</div>
        </div>
    </div>

    {{-- Rating Distribution --}}
    <div class="rating-section">
        <div class="rating-overview">
            <div class="big-rating">{{ number_format($stats['avg_rating'] ?? 0, 1) }}</div>
            <div class="big-stars">@for($i = 1; $i <= 5; $i++){{ $i <= round($stats['avg_rating'] ?? 0) ? '★' : '☆' }}@endfor</div>
            <div class="total-reviews">Based on {{ $stats['total'] }} reviews</div>
        </div>
        <div class="rating-bars-card">
            <h3>Rating Distribution</h3>
            @for($i = 5; $i >= 1; $i--)
            <div class="rating-bar-row">
                <span class="bar-label">{{ $i }} <span class="star">★</span></span>
                <div class="bar-track">
                    <div class="bar-fill star{{ $i }}" style="width: {{ $stats['total'] > 0 ? (($stats['rating_'.$i] ?? 0) / $stats['total'] * 100) : 0 }}%"></div>
                </div>
                <span class="bar-count">{{ $stats['rating_'.$i] ?? 0 }}</span>
            </div>
            @endfor
        </div>
    </div>

    {{-- Critical Reviews --}}
    @if(isset($criticalReviews) && $criticalReviews->count() > 0)
    <div class="section-header">
        <h2 class="section-title">
            <svg width="20" height="20" fill="none" stroke="#ef4444" stroke-width="2" viewBox="0 0 24 24"><path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            Critical Reviews <span class="badge red">Needs Attention</span>
        </h2>
    </div>
    <div class="reviews-grid">
        @foreach($criticalReviews->take(4) as $review)
        <div class="review-card-compact critical">
            <div class="compact-header">
                <div class="compact-avatar">{{ strtoupper(substr($review->reviewer_name, 0, 1)) }}</div>
                <div class="compact-info">
                    <div class="compact-name">{{ $review->reviewer_name }}</div>
                    <div class="compact-meta">{{ $review->created_at->diffForHumans() }}</div>
                </div>
                <div class="compact-rating">@for($i = 1; $i <= 5; $i++)<span class="star {{ $i <= $review->rating ? 'filled' : '' }}">★</span>@endfor</div>
            </div>
            @if($review->product)<div class="compact-product">📦 {{ Str::limit($review->product->name, 35) }}</div>@endif
            <p class="compact-text">{{ $review->review }}</p>
            <div class="compact-actions">
                <form action="{{ route('admin.ecommerce.reviews.delete', $review->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')<button type="submit" class="compact-btn delete">Delete</button></form>
                <button type="button" class="compact-btn reply" onclick="document.getElementById('reply-c-{{ $review->id }}').classList.toggle('open')">Reply</button>
            </div>
            <div class="reply-form" id="reply-c-{{ $review->id }}">
                <form action="{{ route('admin.ecommerce.reviews.reply', $review->id) }}" method="POST">@csrf
                    <textarea name="reply" placeholder="Address their concern..." rows="2">{{ $review->admin_reply }}</textarea>
                    <div class="reply-form-actions"><button type="submit" class="compact-btn approve">Send</button></div>
                </form>
            </div>
        </div>
        @endforeach
    </div>
    @endif

    {{-- Top Reviews --}}
    @if(isset($topReviews) && $topReviews->count() > 0)
    <div class="section-header">
        <h2 class="section-title">
            <svg width="20" height="20" fill="none" stroke="#10b981" stroke-width="2" viewBox="0 0 24 24"><path d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
            Top Reviews <span class="badge green">5 Stars</span>
        </h2>
    </div>
    <div class="reviews-grid">
        @foreach($topReviews->take(4) as $review)
        <div class="review-card-compact top">
            <div class="compact-header">
                <div class="compact-avatar">{{ strtoupper(substr($review->reviewer_name, 0, 1)) }}</div>
                <div class="compact-info">
                    <div class="compact-name">{{ $review->reviewer_name }}</div>
                    <div class="compact-meta">{{ $review->created_at->diffForHumans() }}</div>
                </div>
                <div class="compact-rating">@for($i = 1; $i <= 5; $i++)<span class="star {{ $i <= $review->rating ? 'filled' : '' }}">★</span>@endfor</div>
            </div>
            @if($review->product)<div class="compact-product">📦 {{ Str::limit($review->product->name, 35) }}</div>@endif
            <p class="compact-text">{{ $review->review }}</p>
            <div class="compact-actions">
                <button type="button" class="compact-btn reply" onclick="document.getElementById('reply-t-{{ $review->id }}').classList.toggle('open')">Thank & Reply</button>
            </div>
            <div class="reply-form" id="reply-t-{{ $review->id }}">
                <form action="{{ route('admin.ecommerce.reviews.reply', $review->id) }}" method="POST">@csrf
                    <textarea name="reply" placeholder="Thank them..." rows="2">{{ $review->admin_reply }}</textarea>
                    <div class="reply-form-actions"><button type="submit" class="compact-btn approve">Send</button></div>
                </form>
            </div>
        </div>
        @endforeach
    </div>
    @endif

    {{-- All Reviews --}}
    <div class="section-header">
        <h2 class="section-title">All Reviews</h2>
    </div>

    <div class="stats-row">
        <a href="{{ route('admin.ecommerce.reviews') }}" class="stat-pill {{ $status === 'all' ? 'active' : '' }}">All <span class="count">{{ $stats['total'] }}</span></a>
        <a href="{{ route('admin.ecommerce.reviews', ['status' => 'pending']) }}" class="stat-pill {{ $status === 'pending' ? 'active' : '' }}">Pending <span class="count">{{ $stats['pending'] }}</span></a>
        <a href="{{ route('admin.ecommerce.reviews', ['status' => 'approved']) }}" class="stat-pill {{ $status === 'approved' ? 'active' : '' }}">Approved <span class="count">{{ $stats['approved'] }}</span></a>
        <a href="{{ route('admin.ecommerce.reviews', ['status' => 'rejected']) }}" class="stat-pill {{ $status === 'rejected' ? 'active' : '' }}">Rejected <span class="count">{{ $stats['rejected'] }}</span></a>
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
                            @if($review->reviewer_email)<span>{{ $review->reviewer_email }}</span>@endif
                            @if($review->is_verified_purchase)<span class="verified-badge">✓ Verified</span>@endif
                        </div>
                    </div>
                </div>
                <span class="review-status {{ $review->status }}">{{ ucfirst($review->status) }}</span>
            </div>
            <div class="review-body">
                @if($review->product)<a href="#" class="product-link">📦 {{ $review->product->name }}</a>@endif
                <div class="review-rating">@for($i = 1; $i <= 5; $i++)<span class="star {{ $i <= $review->rating ? 'filled' : '' }}">★</span>@endfor</div>
                @if($review->title)<h5 class="review-title">{{ $review->title }}</h5>@endif
                <p class="review-text">{{ $review->review }}</p>
                <div class="review-date">{{ $review->created_at->setTimezone('Asia/Kolkata')->format('d M Y, h:i A') }}</div>
                @if($review->admin_reply)
                <div class="admin-reply">
                    <div class="admin-reply-label">Your Reply:</div>
                    <p class="admin-reply-text">{{ $review->admin_reply }}</p>
                </div>
                @endif
            </div>
            <div class="review-actions">
                @if($review->status === 'pending')
                <form action="{{ route('admin.ecommerce.reviews.approve', $review->id) }}" method="POST" style="display:inline;">@csrf<button type="submit" class="btn btn-approve">✓ Approve</button></form>
                <form action="{{ route('admin.ecommerce.reviews.reject', $review->id) }}" method="POST" style="display:inline;">@csrf<button type="submit" class="btn btn-reject">✕ Reject</button></form>
                @endif
                <button type="button" class="btn btn-reply" onclick="this.closest('.review-card').querySelector('.reply-form').classList.toggle('open')">{{ $review->admin_reply ? 'Edit Reply' : 'Reply' }}</button>
                <form action="{{ route('admin.ecommerce.reviews.delete', $review->id) }}" method="POST" style="display:inline; margin-left:auto;" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')<button type="submit" class="btn btn-delete">Delete</button></form>
            </div>
            <div class="reply-form">
                <form action="{{ route('admin.ecommerce.reviews.reply', $review->id) }}" method="POST">@csrf
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
            <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
            <h3>No Reviews Found</h3>
            <p>{{ $status !== 'all' ? 'No ' . $status . ' reviews.' : 'No reviews yet.' }}</p>
        </div>
        @endforelse
    </div>

    @if($reviews->hasPages())
    <div style="margin-top: 24px;">{{ $reviews->appends(['status' => $status])->links() }}</div>
    @endif
</div>
