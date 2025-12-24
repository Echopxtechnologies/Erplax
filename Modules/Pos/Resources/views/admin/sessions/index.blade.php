<style>
.pos-header{display:flex;justify-content:space-between;align-items:center;margin-bottom:24px;flex-wrap:wrap;gap:16px}
.pos-header h1{font-size:24px;font-weight:700;color:var(--text-primary);margin:0;display:flex;align-items:center;gap:10px}
.pos-header h1 svg{width:28px;height:28px;color:var(--primary)}
.stats-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:16px;margin-bottom:24px}
.stat-card{background:var(--card-bg);border:1px solid var(--card-border);border-radius:12px;padding:20px;display:flex;align-items:center;gap:16px}
.stat-icon{width:48px;height:48px;border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0}
.stat-icon svg{width:24px;height:24px}
.stat-icon.blue{background:var(--primary-light);color:var(--primary)}
.stat-icon.green{background:var(--success-light);color:var(--success)}
.stat-icon.orange{background:var(--warning-light);color:var(--warning)}
.stat-icon.purple{background:#f3e8ff;color:#9333ea}
.stat-value{font-size:28px;font-weight:700;color:var(--text-primary);line-height:1}
.stat-label{font-size:13px;color:var(--text-muted);margin-top:4px}
.session-card{background:var(--card-bg);border:1px solid var(--card-border);border-radius:12px;padding:24px;margin-bottom:24px}
.session-card-header{display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;flex-wrap:wrap;gap:12px}
.session-card-title{font-size:18px;font-weight:600;color:var(--text-primary);display:flex;align-items:center;gap:10px}
.status-badge{display:inline-flex;align-items:center;gap:6px;padding:6px 14px;border-radius:20px;font-size:13px;font-weight:600}
.status-badge.active{background:var(--success-light);color:var(--success)}
.status-badge.inactive{background:var(--body-bg);color:var(--text-muted)}
.session-info{display:flex;gap:32px;flex-wrap:wrap;margin-bottom:20px}
.session-info-item label{display:block;font-size:12px;color:var(--text-muted);text-transform:uppercase;font-weight:600;margin-bottom:4px}
.session-info-item span{font-size:18px;font-weight:700;color:var(--text-primary)}
.session-actions{display:flex;gap:12px;align-items:flex-end;flex-wrap:wrap}
.form-group label{display:block;font-size:13px;font-weight:600;color:var(--text-primary);margin-bottom:6px}
.form-input{height:44px;border:1px solid var(--input-border);border-radius:8px;padding:0 14px;font-size:15px;width:160px;background:var(--input-bg);color:var(--input-text)}
.form-input:focus{outline:none;border-color:var(--primary)}
.btn-session{display:inline-flex;align-items:center;gap:8px;padding:12px 24px;border-radius:8px;font-weight:600;font-size:14px;border:none;cursor:pointer;transition:all 0.2s}
.btn-session.start{background:linear-gradient(135deg,var(--success),#059669);color:#fff;box-shadow:0 4px 12px rgba(16,185,129,0.3)}
.btn-session.close{background:linear-gradient(135deg,var(--danger),#dc2626);color:#fff;box-shadow:0 4px 12px rgba(239,68,68,0.3)}
.btn-session:hover{transform:translateY(-2px)}
.btn-session svg{width:18px;height:18px}
.no-session{display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:20px}
.no-session-text h3{font-size:18px;color:var(--text-primary);margin-bottom:4px}
.no-session-text p{color:var(--text-muted);font-size:14px}
.table-card{background:var(--card-bg);border:1px solid var(--card-border);border-radius:12px;overflow:hidden}
.table-card-header{padding:16px 20px;border-bottom:1px solid var(--card-border)}
.table-card-title{font-size:16px;font-weight:600;color:var(--text-primary);display:flex;align-items:center;gap:8px}
.table-card-title svg{width:20px;height:20px;color:var(--text-muted)}
.alert{padding:12px 16px;border-radius:8px;margin-bottom:20px;font-weight:500}
.alert-success{background:var(--success-light);color:var(--success)}
.alert-error{background:var(--danger-light);color:var(--danger)}
</style>

<div style="padding:20px">
<div class="pos-header"><h1><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>Sessions</h1>
<button type="button" onclick="location.reload()" style="background:var(--primary);color:#fff;border:none;padding:10px 16px;border-radius:8px;font-weight:600;cursor:pointer;display:flex;align-items:center;gap:6px"><svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>Refresh</button>
</div>

@if(session('success'))<div class="alert alert-success">✓ {{ session('success') }}</div>@endif
@if(session('error'))<div class="alert alert-error">✕ {{ session('error') }}</div>@endif

<div class="stats-grid">
<div class="stat-card"><div class="stat-icon green"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></div><div class="stat-content"><div class="stat-value">₹{{ number_format($stats['sessionSales'], 0) }}</div><div class="stat-label">Session Sales</div></div></div>
<div class="stat-card"><div class="stat-icon blue"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg></div><div class="stat-content"><div class="stat-value">{{ $stats['sessionCount'] }}</div><div class="stat-label">Transactions</div></div></div>
<div class="stat-card"><div class="stat-icon orange"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z"></path></svg></div><div class="stat-content"><div class="stat-value">₹{{ number_format($stats['cashInHand'], 0) }}</div><div class="stat-label">Cash in Hand</div></div></div>
<div class="stat-card"><div class="stat-icon purple"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></div><div class="stat-content"><div class="stat-value">{{ $activeSession ? $activeSession->opened_at->diffForHumans(null, true) : '0h' }}</div><div class="stat-label">Session Time</div></div></div>
</div>

<div class="session-card">
<div class="session-card-header"><div class="session-card-title">🎛️ Session Control</div>@if($activeSession)<span class="status-badge active">● Active</span>@else<span class="status-badge inactive">○ No Session</span>@endif</div>
@if($activeSession)
<form action="{{ route('admin.pos.sessions.close') }}" method="POST">@csrf
<div class="session-info">
<div class="session-info-item"><label>Session Code</label><span style="font-family:monospace;color:var(--primary)">{{ $activeSession->session_code }}</span></div>
<div class="session-info-item"><label>Opened At</label><span>{{ $activeSession->opened_at->format('d M, h:i A') }}</span></div>
<div class="session-info-item"><label>Opening Cash</label><span>₹{{ number_format($activeSession->opening_cash, 0) }}</span></div>
<div class="session-info-item"><label>Total Sales</label><span style="color:var(--success)">₹{{ number_format($stats['sessionSales'], 0) }}</span></div>
<div class="session-info-item"><label>Transactions</label><span>{{ $stats['sessionCount'] }}</span></div>
<div class="session-info-item"><label>Cash Sales</label><span>₹{{ number_format($stats['sessionCash'], 0) }}</span></div>
</div>
<div class="session-actions">
<div class="form-group"><label>Closing Cash</label><input type="number" name="closing_cash" class="form-input" step="0.01" min="0" value="{{ $activeSession->opening_cash + $stats['sessionCash'] }}"></div>
<button type="submit" class="btn-session close"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>Close Session</button>
</div>
</form>
@else
<form action="{{ route('admin.pos.sessions.open') }}" method="POST">@csrf
<div class="no-session">
<div class="no-session-text"><h3>No Active Session</h3><p>Start a new session to begin accepting payments</p></div>
<div class="session-actions">
<div class="form-group"><label>Opening Cash</label><input type="number" name="opening_cash" class="form-input" step="0.01" min="0" placeholder="0.00"></div>
<button type="submit" class="btn-session start"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"></path></svg>Start Session</button>
</div>
</div>
</form>
@endif
</div>

<div class="table-card">
<div class="table-card-header"><div class="table-card-title"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>Session History</div></div>
<div style="padding:0">
<table id="sessionsTable" class="dt-table dt-search dt-perpage" data-route="{{ route('admin.pos.sessions.data') }}">
<thead><tr>
<th class="dt-sort" data-col="id">ID</th>
<th class="dt-sort" data-col="session_code">Session Code</th>
<th class="dt-sort" data-col="opened_at" data-render="datetime">Opened</th>
<th data-col="opening_cash" data-render="amount">Opening</th>
<th data-col="total_sales" data-render="amount">Sales</th>
<th data-col="sales_count">Count</th>
<th data-col="closing_cash" data-render="amount">Closing</th>
<th data-col="difference" data-render="diff">Difference</th>
<th class="dt-sort" data-col="status" data-render="badge">Status</th>
</tr></thead>
<tbody></tbody>
</table>
</div>
</div>
</div>

@include('core::datatable')
<script>
window.dtRenders = window.dtRenders || {};
window.dtRenders.amount = function(v,r){return v===null||v===undefined?'-':'₹'+parseFloat(v).toLocaleString('en-IN');};
window.dtRenders.diff = function(v,r){if(v===null||v===undefined)return'-';var c=parseFloat(v)>=0?'var(--success)':'var(--danger)';var p=parseFloat(v)>=0?'+':'';return '<span style="color:'+c+';font-weight:600;">'+p+'₹'+parseFloat(v).toLocaleString('en-IN')+'</span>';};
window.dtRenders.datetime = function(v,r){if(!v)return'-';var d=new Date(v);return d.toLocaleDateString('en-IN',{day:'2-digit',month:'short',year:'numeric'})+'<br><small style="color:var(--text-muted);">'+d.toLocaleTimeString('en-IN',{hour:'2-digit',minute:'2-digit'})+'</small>';};

// Auto-refresh page every 30 seconds if session is active
@if($activeSession)
setInterval(function(){ location.reload(); }, 30000);
@endif
</script>
