<style>
.sv *{box-sizing:border-box}
.sv-bread{display:flex;align-items:center;gap:8px;font-size:13px;color:#64748b;margin-bottom:12px}
.sv-bread a{color:#3b82f6;text-decoration:none}
.sv-bread svg{width:14px;height:14px}
.sv-layout{display:grid;grid-template-columns:220px 1fr;gap:16px;align-items:start}
.sv-side{display:flex;flex-direction:column;gap:12px}
.sv-card{background:#fff;border:1px solid #e2e8f0;border-radius:10px;padding:14px}
.sv-card-title{font-size:13px;font-weight:700;color:#1e293b;margin-bottom:10px;display:flex;align-items:center;gap:6px}
.sv-card-title svg{width:16px;height:16px;color:#64748b}
.sv-qa{display:flex;align-items:center;gap:10px;padding:10px;background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;margin-bottom:8px;cursor:pointer;width:100%;text-align:left;transition:all .15s}
.sv-qa:last-child{margin-bottom:0}
.sv-qa:hover{background:#f1f5f9;transform:translateX(2px)}
.sv-qa-icon{width:32px;height:32px;border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0}
.sv-qa-icon svg{width:16px;height:16px}
.sv-qa-icon.blue{background:#dbeafe;color:#2563eb}
.sv-qa-icon.green{background:#dcfce7;color:#16a34a}
.sv-qa-icon.orange{background:#fef3c7;color:#d97706}
.sv-qa-text{flex:1}
.sv-qa-text strong{display:block;font-size:12px;color:#1e293b}
.sv-qa-text span{font-size:10px;color:#94a3b8}
.sv-header{background:linear-gradient(135deg,#1e3a5f 0%,#3b82f6 100%);border-radius:12px;padding:18px;color:#fff;margin-bottom:12px;position:relative;overflow:hidden}
.sv-header::before{content:'';position:absolute;top:-50%;right:-15%;width:200px;height:200px;background:rgba(255,255,255,.08);border-radius:50%}
.sv-h-row{display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:14px;position:relative;z-index:1}
.sv-h-title{display:flex;align-items:center;gap:12px}
.sv-h-icon{width:44px;height:44px;background:rgba(255,255,255,.15);border-radius:10px;display:flex;align-items:center;justify-content:center}
.sv-h-icon svg{width:22px;height:22px}
.sv-h-info h1{font-size:20px;font-weight:700;margin:0 0 2px 0}
.sv-h-info p{font-size:13px;opacity:.85;margin:0}
.sv-badges{display:flex;gap:6px}
.sv-badge{padding:4px 10px;border-radius:12px;font-size:11px;font-weight:600;background:rgba(255,255,255,.2)}
.sv-badge.active{background:#10b981}
.sv-stats{display:grid;grid-template-columns:repeat(4,1fr);gap:10px;position:relative;z-index:1}
.sv-stat{background:rgba(255,255,255,.12);border-radius:8px;padding:10px 12px}
.sv-stat-label{font-size:9px;text-transform:uppercase;letter-spacing:.5px;opacity:.7;margin-bottom:2px}
.sv-stat-value{font-size:15px;font-weight:700}
.sv-stat-value.warn{color:#fbbf24}
.sv-stat-value.danger{color:#f87171}
.sv-stat-sub{font-size:9px;opacity:.6}
.sv-actions{display:flex;gap:8px;margin-top:14px;position:relative;z-index:1;flex-wrap:wrap}
.sv-btn{display:inline-flex;align-items:center;gap:5px;padding:7px 12px;border-radius:6px;font-size:12px;font-weight:600;text-decoration:none;border:none;cursor:pointer}
.sv-btn svg{width:14px;height:14px}
.sv-btn-w{background:#fff;color:#1e3a5f}
.sv-btn-g{background:rgba(255,255,255,.15);color:#fff;border:1px solid rgba(255,255,255,.3)}
.sv-infos{display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-bottom:12px}
.sv-info{background:#fff;border:1px solid #e2e8f0;border-radius:10px;padding:14px}
.sv-info-h{display:flex;align-items:center;gap:8px;margin-bottom:10px;padding-bottom:8px;border-bottom:1px solid #f1f5f9}
.sv-info-icon{width:30px;height:30px;border-radius:8px;display:flex;align-items:center;justify-content:center}
.sv-info-icon svg{width:15px;height:15px}
.sv-info-icon.blue{background:#dbeafe;color:#2563eb}
.sv-info-icon.purple{background:#ede9fe;color:#7c3aed}
.sv-info-icon.green{background:#dcfce7;color:#16a34a}
.sv-info-t{font-size:13px;font-weight:600;color:#1e293b}
.sv-info-row{display:flex;justify-content:space-between;padding:6px 0;font-size:12px;border-bottom:1px dashed #f1f5f9}
.sv-info-row:last-child{border:none}
.sv-info-row .l{color:#64748b}
.sv-info-row .v{color:#1e293b;font-weight:500}
.sv-tabs{background:#fff;border:1px solid #e2e8f0;border-radius:10px;overflow:hidden}
.sv-tabs-nav{display:flex;background:#f8fafc;border-bottom:1px solid #e2e8f0}
.sv-tab-btn{flex:1;padding:10px 12px;font-size:12px;font-weight:600;color:#64748b;background:none;border:none;cursor:pointer;position:relative}
.sv-tab-btn:hover{color:#1e293b;background:#fff}
.sv-tab-btn.active{color:#3b82f6;background:#fff}
.sv-tab-btn.active::after{content:'';position:absolute;bottom:0;left:12px;right:12px;height:2px;background:#3b82f6}
.sv-tab-btn .c{display:inline-flex;min-width:18px;height:18px;padding:0 5px;background:#e2e8f0;border-radius:9px;font-size:10px;margin-left:4px;align-items:center;justify-content:center}
.sv-tab-btn.active .c{background:#dbeafe;color:#2563eb}
.sv-tab-c{display:none;padding:14px}
.sv-tab-c.active{display:block}
.sv-tab-h{display:flex;justify-content:space-between;align-items:center;margin-bottom:12px}
.sv-tab-t{font-size:14px;font-weight:600;color:#1e293b}
.sv-add{display:inline-flex;align-items:center;gap:4px;padding:6px 10px;background:#3b82f6;color:#fff;border:none;border-radius:6px;font-size:11px;font-weight:600;cursor:pointer}
.sv-add svg{width:12px;height:12px}
.sv-rec{background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;padding:12px;margin-bottom:10px;border-left:3px solid #e2e8f0}
.sv-rec.completed{border-left-color:#10b981}
.sv-rec.scheduled{border-left-color:#3b82f6}
.sv-rec.in_progress{border-left-color:#f59e0b}
.sv-rec-h{display:flex;justify-content:space-between;align-items:center;margin-bottom:6px;flex-wrap:wrap;gap:6px}
.sv-rec-ref{font-size:13px;font-weight:700;color:#1e293b}
.sv-rec-st{padding:3px 8px;border-radius:4px;font-size:10px;font-weight:600}
.sv-rec-st.completed{background:#dcfce7;color:#16a34a}
.sv-rec-st.scheduled{background:#dbeafe;color:#2563eb}
.sv-rec-st.in_progress{background:#fef3c7;color:#d97706}
.sv-rec-st.sent{background:#dcfce7;color:#16a34a}
.sv-rec-st.paid{background:#dcfce7;color:#16a34a}
.sv-rec-st.unpaid{background:#fee2e2;color:#dc2626}
.sv-rec-st.partial{background:#fef3c7;color:#d97706}
.sv-rec-st.overdue{background:#fee2e2;color:#dc2626}
.sv-rec.paid{border-left-color:#10b981}
.sv-rec.unpaid{border-left-color:#dc2626}
.sv-rec.partial{border-left-color:#f59e0b}
.sv-rec-m{display:flex;gap:12px;font-size:11px;color:#64748b;margin-bottom:6px;flex-wrap:wrap}
.sv-rec-m svg{width:12px;height:12px}
.sv-rec-m span{display:flex;align-items:center;gap:3px}
.sv-rec-r{font-size:12px;color:#475569;margin-bottom:8px}
.sv-rec-a{display:flex;gap:6px;flex-wrap:wrap;margin-top:8px}
/* Action buttons with icons and text */
.sv-btn-x{padding:6px 10px;font-size:11px;border-radius:6px;border:none;cursor:pointer;font-weight:600;display:inline-flex;align-items:center;gap:4px;transition:all .2s}
.sv-btn-x svg{width:14px;height:14px}
.sv-btn-x:hover{opacity:.85;transform:translateY(-1px)}
.sv-btn-x.e{background:#fef3c7;color:#d97706}
.sv-btn-x.d{background:#fee2e2;color:#dc2626}
.sv-btn-x.v{background:#dbeafe;color:#2563eb}
.sv-btn-x.mail{background:#eff6ff;color:#3b82f6}
.sv-btn-x.inv{background:#f3e8ff;color:#7c3aed}
.sv-btn-x.info{background:#e0e7ff;color:#4338ca}
.sv-empty{text-align:center;padding:30px 15px;color:#94a3b8}
.sv-empty-i{width:50px;height:50px;margin:0 auto 10px;background:#f1f5f9;border-radius:50%;display:flex;align-items:center;justify-content:center}
.sv-empty-i svg{width:22px;height:22px;opacity:.5}
.sv-empty p{margin-bottom:10px;font-size:13px}
.sv-modal{display:none;position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(0,0,0,.5);z-index:1000;align-items:center;justify-content:center;padding:15px}
.sv-modal.active{display:flex}
.sv-modal-box{background:#fff;border-radius:12px;width:100%;max-width:680px;max-height:90vh;overflow:hidden;display:flex;flex-direction:column}
.sv-modal-h{display:flex;justify-content:space-between;align-items:center;padding:14px 18px;border-bottom:1px solid #e2e8f0;flex-shrink:0}
.sv-modal-h h3{font-size:16px;font-weight:600;margin:0}
.sv-modal-x{width:30px;height:30px;background:#f1f5f9;border:none;border-radius:8px;cursor:pointer;font-size:18px;color:#64748b}
.sv-modal-b{padding:18px;overflow-y:auto;flex:1}
.sv-modal-f{display:flex;justify-content:flex-end;gap:8px;padding:12px 18px;border-top:1px solid #e2e8f0;background:#f8fafc;flex-shrink:0}
.sv-alert{display:flex;gap:10px;padding:10px 12px;background:#eff6ff;border:1px solid #bfdbfe;border-radius:8px;margin-bottom:14px;font-size:12px;color:#1e40af}
.sv-alert svg{width:16px;height:16px;color:#2563eb;flex-shrink:0}
.sv-form-g{display:grid;grid-template-columns:1fr 1fr;gap:12px}
.sv-fg{margin-bottom:12px}
.sv-fg.full{grid-column:span 2}
.sv-fl{display:block;font-size:12px;font-weight:600;margin-bottom:4px;color:#1e293b}
.sv-fl .r{color:#dc2626}
.sv-fi,.sv-fs,.sv-ft{width:100%;padding:8px 10px;font-size:12px;border:1px solid #e2e8f0;border-radius:6px;box-sizing:border-box}
.sv-fi:focus,.sv-fs:focus,.sv-ft:focus{outline:none;border-color:#3b82f6}
.sv-ft{min-height:60px;resize:vertical}
.sv-fh{font-size:10px;color:#94a3b8;margin-top:2px}
.sv-mat{margin-top:14px;padding-top:14px;border-top:1px solid #e2e8f0}
.sv-mat-t{font-size:13px;font-weight:600;margin-bottom:10px}
.sv-mat-h{display:none}
.sv-mat-r{display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:12px;padding:14px;background:#f8fafc;border-radius:8px;border:1px solid #e2e8f0}
.sv-mat-field{display:flex;flex-direction:column;gap:4px}
.sv-mat-field.sv-mat-action{justify-content:flex-end}
.sv-mat-label{font-size:11px;font-weight:600;color:#64748b}
.sv-mat-r input,.sv-mat-r select{padding:8px 10px;font-size:12px;border:1px solid #e2e8f0;border-radius:6px;width:100%;box-sizing:border-box}
.sv-mat-r select{background:#fff}
.sv-mat-total{background:#f1f5f9 !important;font-weight:600;color:#16a34a}
.sv-mat-rm{width:100%;padding:8px;background:#fee2e2;color:#dc2626;border:none;border-radius:6px;cursor:pointer;font-size:14px;font-weight:600}
.sv-mat-rm:hover{background:#fecaca}
.sv-mat-add{padding:10px 14px;background:#dcfce7;color:#16a34a;border:none;border-radius:6px;font-size:12px;font-weight:600;cursor:pointer;margin-top:4px;width:100%}
.sv-mat-add:hover{background:#bbf7d0}
/* Multi-tax badges */
.sv-tax-cell{display:flex;flex-wrap:wrap;gap:4px;align-items:center;min-height:36px;padding:4px 0}
.sv-tax-badge{display:inline-flex;align-items:center;gap:3px;padding:4px 8px;background:linear-gradient(to bottom,#fef2f2,#fee2e2);border:1px solid #fecaca;border-radius:4px;font-size:10px;font-weight:600;color:#991b1b;white-space:nowrap}
.sv-tax-badge .sv-tax-rm{cursor:pointer;font-size:12px;opacity:.7;margin-left:2px}
.sv-tax-badge .sv-tax-rm:hover{opacity:1}
.sv-tax-add{display:inline-flex;align-items:center;justify-content:center;width:32px;height:32px;background:#f3f4f6;border:2px dashed #d1d5db;border-radius:6px;cursor:pointer;color:#6b7280;font-size:18px;font-weight:400}
.sv-tax-add:hover{background:#e5e7eb;color:#374151;border-color:#9ca3af}
/* Tax dropdown */
.sv-tax-dropdown{position:fixed;background:#fff;border:1px solid #e5e7eb;border-radius:8px;box-shadow:0 10px 25px rgba(0,0,0,.15);z-index:1001;min-width:180px;max-height:240px;overflow-y:auto;display:none}
.sv-tax-dropdown.show{display:block}
.sv-tax-dropdown-item{padding:10px 14px;cursor:pointer;font-size:12px;display:flex;justify-content:space-between;align-items:center;border-bottom:1px solid #f3f4f6}
.sv-tax-dropdown-item:last-child{border-bottom:none}
.sv-tax-dropdown-item:hover{background:#f9fafb}
.sv-tax-dropdown-item.selected{background:#eff6ff}
.sv-tax-dropdown-item .tax-rate{color:#6b7280;font-size:11px}
.sv-tax-dropdown-item .check-mark{color:#3b82f6;font-weight:bold}
.sv-btn-c{padding:8px 14px;background:#f1f5f9;color:#64748b;border:1px solid #e2e8f0;border-radius:6px;font-size:12px;font-weight:600;cursor:pointer}
.sv-btn-s{padding:8px 14px;background:#3b82f6;color:#fff;border:none;border-radius:6px;font-size:12px;font-weight:600;cursor:pointer}
.sv-paid-box{background:#f0fdf4;border:1px solid #bbf7d0;border-radius:8px;padding:12px;margin-top:14px}
.sv-paid-title{font-size:12px;font-weight:600;color:#166534;margin-bottom:10px;display:flex;align-items:center;gap:6px}
.sv-paid-title svg{width:16px;height:16px;color:#16a34a}
.sv-toggle{display:flex;align-items:center;gap:10px;margin-bottom:10px}
.sv-toggle input[type="checkbox"]{width:18px;height:18px;cursor:pointer}
.sv-toggle label{font-size:12px;color:#1e293b;cursor:pointer}
.sv-charge-row{display:none;margin-top:10px}
.sv-charge-row.show{display:block}
/* Desktop: Grid layout for materials */
@media(min-width:768px){
    .sv-mat-h{display:grid;grid-template-columns:2fr 70px 90px 140px 90px 1fr 40px;gap:8px;font-size:10px;font-weight:600;color:#64748b;margin-bottom:6px;padding:0 4px}
    .sv-mat-r{grid-template-columns:2fr 70px 90px 140px 90px 1fr 40px;padding:0;background:transparent;border:none;align-items:center}
    .sv-mat-field{display:contents}
    .sv-mat-label{display:none}
    .sv-mat-rm{width:36px;height:36px;padding:0}
    .sv-mat-add{width:auto}
}
@media(max-width:900px){.sv-layout{grid-template-columns:1fr}.sv-side{order:-1}.sv-stats{grid-template-columns:repeat(2,1fr)}.sv-infos{grid-template-columns:1fr}}
@media(max-width:639px){.sv-form-g{grid-template-columns:1fr}.sv-fg.full{grid-column:span 1}}
</style>

<div class="sv" data-service-id="{{ $service->id }}">
    <div class="sv-bread">
        <a href="{{ route('admin.service.index') }}">Services</a>
        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7"/></svg>
        <span>Contract #{{ $service->id }}</span>
    </div>

    <div class="sv-layout">
        <div class="sv-side">
            <div class="sv-card">
                <div class="sv-card-title">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    Quick Actions
                </div>
                <button class="sv-qa" onclick="openRecordModal()">
                    <div class="sv-qa-icon blue"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4"/></svg></div>
                    <div class="sv-qa-text"><strong>Add Service Record</strong><span>Log completed service</span></div>
                </button>
                <button class="sv-qa" onclick="openVisitModal()">
                    <div class="sv-qa-icon green"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg></div>
                    <div class="sv-qa-text"><strong>Schedule Visit</strong><span>Plan engineer visit</span></div>
                </button>
                <button class="sv-qa" onclick="sendReminder()">
                    <div class="sv-qa-icon orange"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg></div>
                    <div class="sv-qa-text"><strong>Send Reminder</strong><span>Email client now</span></div>
                </button>
            </div>
            <div class="sv-card">
                <div class="sv-card-title">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Recent Activity
                </div>
                @if($service->notifications->count() > 0)
                    @foreach($service->notifications->take(3) as $notif)
                    <div style="padding:8px 0;border-bottom:1px solid #f1f5f9;font-size:12px;">
                        <div style="font-weight:600;color:#1e293b;">{{ $notif->type_label ?? ucfirst($notif->type) }}</div>
                        <div style="font-size:10px;color:#94a3b8;">{{ $notif->sent_at?->diffForHumans() ?? $notif->created_at->diffForHumans() }}</div>
                    </div>
                    @endforeach
                @else
                    <div style="text-align:center;padding:15px;color:#94a3b8;font-size:12px;">No recent activity</div>
                @endif
            </div>
        </div>

        <div class="sv-main">
            <div class="sv-header">
                <div class="sv-h-row">
                    <div class="sv-h-title">
                        <div class="sv-h-icon"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg></div>
                        <div class="sv-h-info">
                            <h1>{{ $service->machine_name }}</h1>
                            <p>{{ $service->client->company ?? $service->client->name ?? 'Unknown' }}</p>
                        </div>
                    </div>
                    <div class="sv-badges">
                        <span class="sv-badge {{ $service->status }}">{{ ucfirst($service->status) }}</span>
                        <span class="sv-badge">{{ $service->frequency_label }}</span>
                    </div>
                </div>
                <div class="sv-stats">
                    <div class="sv-stat">
                        <div class="sv-stat-label">First Service</div>
                        <div class="sv-stat-value">{{ $service->first_service_date?->format('d M Y') ?? '-' }}</div>
                    </div>
                    <div class="sv-stat">
                        <div class="sv-stat-label">Last Service</div>
                        <div class="sv-stat-value">{{ $service->last_service_date?->format('d M Y') ?? 'Not yet' }}</div>
                    </div>
                    <div class="sv-stat">
                        <div class="sv-stat-label">Next Service</div>
                        <div class="sv-stat-value {{ $service->is_overdue ? 'danger' : ($service->days_left !== null && $service->days_left <= 7 ? 'warn' : '') }}">{{ $service->next_service_date?->format('d M Y') ?? '-' }}</div>
                        @if($service->days_left !== null)<div class="sv-stat-sub">{{ $service->days_left >= 0 ? $service->days_left.' days left' : abs($service->days_left).' days overdue' }}</div>@endif
                    </div>
                    <div class="sv-stat">
                        <div class="sv-stat-label">Total Services</div>
                        <div class="sv-stat-value">{{ $service->serviceRecords->count() }}</div>
                    </div>
                </div>
                <div class="sv-actions">
                    <a href="{{ route('admin.service.edit', $service->id) }}" class="sv-btn sv-btn-w"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>Edit</a>
                    <button class="sv-btn sv-btn-g" onclick="refreshDates()"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>Refresh</button>
                    <button class="sv-btn sv-btn-g" onclick="sendReminder()"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>Remind</button>
                    <a href="{{ route('admin.service.index') }}" class="sv-btn sv-btn-g">← Back</a>
                </div>
            </div>

            <div class="sv-infos">
                <div class="sv-info">
                    <div class="sv-info-h"><div class="sv-info-icon blue"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"/></svg></div><div class="sv-info-t">Equipment</div></div>
                    <div class="sv-info-row"><span class="l">Equipment No</span><span class="v">{{ $service->equipment_no ?? '-' }}</span></div>
                    <div class="sv-info-row"><span class="l">Model No</span><span class="v">{{ $service->model_no ?? '-' }}</span></div>
                    <div class="sv-info-row"><span class="l">Serial No</span><span class="v">{{ $service->serial_number ?? '-' }}</span></div>
                </div>
                <div class="sv-info">
                    <div class="sv-info-h"><div class="sv-info-icon purple"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg></div><div class="sv-info-t">Schedule</div></div>
                    <div class="sv-info-row"><span class="l">Frequency</span><span class="v">{{ $service->frequency_label }}</span></div>
                    <div class="sv-info-row"><span class="l">Reminder</span><span class="v">{{ $service->reminder_days ?? 15 }} days before</span></div>
                    <div class="sv-info-row">
                        <span class="l">Auto Reminder</span>
                        <span class="v">
                            @if($service->auto_reminder)
                                <span style="color:#16a34a;font-weight:600;">✓ Enabled</span>
                            @else
                                <span style="color:#dc2626;">✗ Disabled</span>
                            @endif
                        </span>
                    </div>
                </div>
                <div class="sv-info">
                    <div class="sv-info-h"><div class="sv-info-icon green"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg></div><div class="sv-info-t">Client</div></div>
                    <div class="sv-info-row"><span class="l">Company</span><span class="v">{{ $service->client->company ?? '-' }}</span></div>
                    <div class="sv-info-row"><span class="l">Contact</span><span class="v">{{ $service->client->name ?? '-' }}</span></div>
                    <div class="sv-info-row"><span class="l">Email</span><span class="v">{{ $service->client->email ?? '-' }}</span></div>
                </div>
            </div>

            <div class="sv-tabs">
                <div class="sv-tabs-nav">
                    <button class="sv-tab-btn active" data-tab="history">Service History <span class="c">{{ $service->serviceRecords->count() }}</span></button>
                    <button class="sv-tab-btn" data-tab="invoices">Invoices <span class="c">{{ $invoices->count() }}</span></button>
                    <button class="sv-tab-btn" data-tab="visits">Visits <span class="c">{{ $service->visits->count() }}</span></button>
                    <button class="sv-tab-btn" data-tab="notifs">Notifications <span class="c">{{ $service->notifications->count() }}</span></button>
                </div>

                <div class="sv-tab-c active" id="tab-history">
                    <div class="sv-tab-h"><span class="sv-tab-t">Service Records</span><button class="sv-add" onclick="openRecordModal()"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4"/></svg>Add</button></div>
                    @forelse($service->serviceRecords as $rec)
                    <div class="sv-rec {{ $rec->status }}">
                        <div class="sv-rec-h">
                            <span class="sv-rec-ref">{{ $rec->reference_no }}</span>
                            <div style="display:flex;gap:6px;align-items:center;">
                                @if($rec->invoice_id)<span class="sv-rec-st" style="background:#ede9fe;color:#7c3aed;">Invoice</span>@endif
                                <span class="sv-rec-st {{ $rec->status }}">{{ ucfirst(str_replace('_',' ',$rec->status)) }}</span>
                            </div>
                        </div>
                        <div class="sv-rec-m">
                            <span><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>{{ $rec->engineer->name ?? 'N/A' }}</span>
                            <span><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>{{ $rec->service_date?->format('d M Y') }}</span>
                            @if($rec->time_taken)<span><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>{{ $rec->time_taken_formatted ?? $rec->time_taken.'m' }}</span>@endif
                            @if($rec->is_paid && $rec->service_charge > 0)<span style="color:#16a34a;font-weight:600;">₹{{ number_format($rec->service_charge, 2) }}</span>@endif
                        </div>
                        @if($rec->remarks)<div class="sv-rec-r">{{ \Str::limit($rec->remarks, 80) }}</div>@endif
                        <div class="sv-rec-a">
                            <button class="sv-btn-x e" data-record-id="{{ $rec->id }}" onclick="editRecord(this)">
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                Edit
                            </button>
                            @if($rec->materials->count())
                            <button class="sv-btn-x info" onclick="viewMaterials({{ $rec->id }})" title="View materials used">
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                {{ $rec->materials->count() }} Mat
                            </button>
                            @endif
                            @if($rec->service_reference)
                            <span class="sv-btn-x info" style="cursor:default;">
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                {{ $rec->service_reference }}
                            </span>
                            @endif
                            @if($rec->status == 'completed')
                            <button class="sv-btn-x mail" data-record-id="{{ $rec->id }}" onclick="sendCompletedEmail(this)" title="Resend service completed email">
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                Email
                            </button>
                            @endif
                            @if($rec->invoice_id)
                            <button class="sv-btn-x inv" data-record-id="{{ $rec->id }}" onclick="sendInvoiceEmail(this)" title="Resend invoice email">
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                Invoice
                            </button>
                            @endif
                            <button class="sv-btn-x d" data-record-id="{{ $rec->id }}" onclick="deleteRecord(this)">
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                Delete
                            </button>
                        </div>
                    </div>
                    @empty
                    <div class="sv-empty"><div class="sv-empty-i"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg></div><p>No records</p><button class="sv-add" onclick="openRecordModal()">Add First</button></div>
                    @endforelse
                </div>

                <div class="sv-tab-c" id="tab-invoices">
                    <div class="sv-tab-h"><span class="sv-tab-t">Related Invoices</span></div>
                    @forelse($invoices as $inv)
                    <div class="sv-rec {{ $inv->status }}">
                        <div class="sv-rec-h">
                            <span class="sv-rec-ref">{{ $inv->invoice_number }}</span>
                            <div style="display:flex;gap:6px;align-items:center;">
                                @if(isset($inv->service_reference) && $inv->service_reference)
                                <span style="font-size:10px;background:#e0e7ff;color:#4338ca;padding:2px 6px;border-radius:4px;">{{ $inv->service_reference }}</span>
                                @endif
                                <span class="sv-rec-st {{ $inv->status }}">{{ ucfirst($inv->status) }}</span>
                                <span class="sv-rec-st {{ $inv->payment_status ?? 'unpaid' }}" style="background:{{ ($inv->payment_status ?? 'unpaid') == 'paid' ? '#dcfce7' : '#fef3c7' }};color:{{ ($inv->payment_status ?? 'unpaid') == 'paid' ? '#16a34a' : '#d97706' }};">{{ ucfirst($inv->payment_status ?? 'unpaid') }}</span>
                            </div>
                        </div>
                        <div class="sv-rec-m">
                            <span><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>{{ $inv->date ? \Carbon\Carbon::parse($inv->date)->format('d M Y') : '-' }}</span>
                            <span><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>Due: {{ $inv->due_date ? \Carbon\Carbon::parse($inv->due_date)->format('d M Y') : '-' }}</span>
                            <span style="font-weight:600;color:#16a34a;">₹{{ number_format($inv->subtotal ?? 0, 2) }}</span>
                        </div>
                        <div class="sv-rec-a">
                            <a href="{{ url('/admin/sales/invoices/' . $inv->id) }}" class="sv-btn-x v">View Invoice</a>
                            {{-- @if(($inv->payment_status ?? 'unpaid') == 'unpaid')
                            <button class="sv-btn-x e" onclick="markInvoicePaid({{ $inv->id }})">Mark Paid</button>
                            @endif --}}
                        </div>
                    </div>
                    @empty
                    <div class="sv-empty">
                        <div class="sv-empty-i"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg></div>
                        <p>No invoices yet</p>
                        <span style="font-size:11px;color:#94a3b8;">Invoices are created automatically for paid services</span>
                    </div>
                    @endforelse
                </div>

                <div class="sv-tab-c" id="tab-visits">
                    <div class="sv-tab-h"><span class="sv-tab-t">Visit Log</span><button class="sv-add" onclick="openVisitModal()"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4"/></svg>Add</button></div>
                    @forelse($service->visits as $v)
                    <div class="sv-rec {{ $v->status }}">
                        <div class="sv-rec-h"><span class="sv-rec-ref">{{ $v->visit_date?->format('d M Y') }}</span><span class="sv-rec-st {{ $v->status }}">{{ ucfirst($v->status) }}</span></div>
                        <div class="sv-rec-m"><span>{{ $v->engineer->name ?? 'N/A' }}</span>@if($v->check_in_time)<span>In: {{ $v->check_in_time }}</span>@endif</div>
                        @if($v->purpose)<div class="sv-rec-r">{{ $v->purpose }}</div>@endif
                        <div class="sv-rec-a">
                            <button class="sv-btn-x e">Edit</button>
                            <button class="sv-btn-x d" data-visit-id="{{ $v->id }}" onclick="deleteVisit(this)">Del</button>
                        </div>
                    </div>
                    @empty
                    <div class="sv-empty"><div class="sv-empty-i"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg></div><p>No visits</p><button class="sv-add" onclick="openVisitModal()">Schedule</button></div>
                    @endforelse
                </div>

                <div class="sv-tab-c" id="tab-notifs">
                    <div class="sv-tab-h"><span class="sv-tab-t">Email History</span></div>
                    @forelse($service->notifications as $n)
                    <div class="sv-rec">
                        <div class="sv-rec-h"><span class="sv-rec-ref">{{ $n->type_label ?? ucfirst($n->type) }}</span><span class="sv-rec-st {{ $n->status }}">{{ ucfirst($n->status) }}</span></div>
                        <div class="sv-rec-m"><span>{{ $n->email_to }}</span><span>{{ $n->sent_at?->format('d M Y') }}</span></div>
                        <div class="sv-rec-r">{{ $n->subject }}</div>
                    </div>
                    @empty
                    <div class="sv-empty"><div class="sv-empty-i"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg></div><p>No notifications</p></div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Record Modal -->
<div class="sv-modal" id="recordModal">
    <div class="sv-modal-box">
        <div class="sv-modal-h"><h3 id="recordModalTitle">Add Service Record</h3><button class="sv-modal-x" onclick="closeRecordModal()">×</button></div>
        <form id="recordForm" method="POST" action="{{ route('admin.service.records.store', $service->id) }}">
            @csrf
            <input type="hidden" name="_method" id="recordMethod" value="POST">
            <div class="sv-modal-b">
                <div class="sv-alert"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg><span><strong>Auto-Update:</strong> When "Completed", dates update automatically.</span></div>
                <div class="sv-form-g">
                    <div class="sv-fg"><label class="sv-fl">Engineer <span class="r">*</span></label><select name="engineer_id" id="rec_engineer_id" class="sv-fs" required><option value="">Select</option>@foreach($engineers as $e)<option value="{{ $e->id }}">{{ $e->name }}</option>@endforeach</select></div>
                    <div class="sv-fg"><label class="sv-fl">Type</label><input type="text" name="service_type" id="rec_service_type" class="sv-fi" value="Maintenance"></div>
                    <div class="sv-fg"><label class="sv-fl">Date <span class="r">*</span></label><input type="date" name="service_date" id="rec_service_date" class="sv-fi" value="{{ date('Y-m-d') }}" required></div>
                    <div class="sv-fg"><label class="sv-fl">Status <span class="r">*</span></label><select name="status" id="rec_status" class="sv-fs" required><option value="scheduled">Scheduled</option><option value="in_progress">In Progress</option><option value="completed" selected>Completed</option><option value="canceled">Canceled</option></select></div>
                    <div class="sv-fg"><label class="sv-fl">Time Taken (minutes)</label><input type="number" name="time_taken" id="rec_time_taken" class="sv-fi" min="0" placeholder="e.g. 60"></div>
                    <div class="sv-fg"><label class="sv-fl">Labor Cost</label><input type="number" name="labor_cost" id="rec_labor_cost" class="sv-fi" min="0" step="0.01" value="0"></div>
                    <div class="sv-fg full"><label class="sv-fl">Remarks</label><textarea name="remarks" id="rec_remarks" class="sv-ft" placeholder="Work done..."></textarea></div>
                </div>
                
                <!-- Paid Service Section -->
                <div class="sv-paid-box">
                    <div class="sv-paid-title"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>Billing</div>
                    <div class="sv-toggle">
                        <input type="checkbox" name="is_paid" id="rec_is_paid" value="1" onchange="togglePaidFields()">
                        <label for="rec_is_paid">This is a <strong>Paid Service</strong> (Create Invoice)</label>
                    </div>
                    <div class="sv-charge-row" id="chargeRow">
                        <label class="sv-fl">Service Charge (₹) <span class="r">*</span></label>
                        <input type="number" name="service_charge" id="rec_service_charge" class="sv-fi" min="0" step="0.01" value="0">
                        <div class="sv-fh">Invoice will be created automatically for paid services</div>
                    </div>
                </div>

                <div class="sv-mat">
                    <div class="sv-mat-t">Materials Used</div>
                    <div class="sv-mat-h"><span>Product</span><span>Qty</span><span>Price</span><span>Taxes</span><span>Total</span><span>Notes</span><span></span></div>
                    <div id="matCont">
                        <div class="sv-mat-r" data-index="0">
                            <div class="sv-mat-field">
                                <label class="sv-mat-label">Product</label>
                                <select name="materials[0][product_id]" onchange="selProd(this,0)">
                                    <option value="">Select Product</option>
                                    @foreach($products as $p)
                                    <option value="{{ $p->id }}" data-p="{{ $p->sale_price ?? $p->purchase_price ?? 0 }}">{{ $p->name }}@if(isset($p->sku) && $p->sku) ({{ $p->sku }})@endif</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="sv-mat-field">
                                <label class="sv-mat-label">Qty</label>
                                <input type="number" name="materials[0][quantity]" value="1" min="1" onchange="calcT(0)">
                            </div>
                            <div class="sv-mat-field">
                                <label class="sv-mat-label">Price</label>
                                <input type="number" name="materials[0][unit_price]" value="0" min="0" step="0.01" onchange="calcT(0)">
                            </div>
                            <div class="sv-mat-field">
                                <label class="sv-mat-label">Taxes</label>
                                <div class="sv-tax-cell">
                                    <input type="hidden" name="materials[0][tax_ids]" class="tax-ids-hidden" value="[]">
                                    <span class="sv-tax-add" onclick="showTaxDropdown(this, 0)">+</span>
                                </div>
                            </div>
                            <div class="sv-mat-field">
                                <label class="sv-mat-label">Total</label>
                                <input type="number" name="materials[0][total]" value="0" readonly class="sv-mat-total">
                            </div>
                            <div class="sv-mat-field">
                                <label class="sv-mat-label">Notes</label>
                                <input type="text" name="materials[0][notes]" placeholder="Notes">
                            </div>
                            <div class="sv-mat-field sv-mat-action">
                                <button type="button" class="sv-mat-rm" onclick="removeMat(this)">×</button>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="sv-mat-add" onclick="addMat()">+ Add Material</button>
                </div>

                <!-- Tax Dropdown (shared) -->
                <div class="sv-tax-dropdown" id="taxDropdown"></div>
                </div>
            </div>
            <div class="sv-modal-f"><button type="button" class="sv-btn-c" onclick="closeRecordModal()">Cancel</button><button type="submit" class="sv-btn-s">Save</button></div>
        </form>
    </div>
</div>

<!-- Visit Modal -->
<div class="sv-modal" id="visitModal">
    <div class="sv-modal-box">
        <div class="sv-modal-h"><h3>Schedule Visit</h3><button class="sv-modal-x" onclick="closeVisitModal()">×</button></div>
        <form action="{{ route('admin.service.visits.store', $service->id) }}" method="POST">
            @csrf
            <div class="sv-modal-b">
                <div class="sv-form-g">
                    <div class="sv-fg"><label class="sv-fl">Engineer</label><select name="engineer_id" class="sv-fs"><option value="">Select</option>@foreach($engineers as $e)<option value="{{ $e->id }}">{{ $e->name }}</option>@endforeach</select></div>
                    <div class="sv-fg"><label class="sv-fl">Status <span class="r">*</span></label><select name="status" class="sv-fs" required><option value="scheduled">Scheduled</option><option value="in_progress">In Progress</option><option value="completed">Completed</option></select></div>
                    <div class="sv-fg"><label class="sv-fl">Date <span class="r">*</span></label><input type="date" name="visit_date" class="sv-fi" value="{{ date('Y-m-d') }}" required></div>
                    <div class="sv-fg"><label class="sv-fl">Time</label><input type="time" name="visit_time" class="sv-fi"></div>
                    <div class="sv-fg full"><label class="sv-fl">Purpose</label><input type="text" name="purpose" class="sv-fi" placeholder="e.g. Maintenance"></div>
                    <div class="sv-fg full"><label class="sv-fl">Notes</label><textarea name="notes" class="sv-ft"></textarea></div>
                </div>
            </div>
            <div class="sv-modal-f"><button type="button" class="sv-btn-c" onclick="closeVisitModal()">Cancel</button><button type="submit" class="sv-btn-s">Save</button></div>
        </form>
    </div>
</div>

<!-- Hidden delete forms for fallback -->
@foreach($service->serviceRecords as $rec)
<form id="deleteRecordForm{{ $rec->id }}" action="{{ route('admin.service.records.delete', [$service->id, $rec->id]) }}" method="POST" style="display:none;">
    @csrf
    @method('DELETE')
</form>
@endforeach

@foreach($service->visits as $v)
<form id="deleteVisitForm{{ $v->id }}" action="{{ route('admin.service.visits.delete', [$service->id, $v->id]) }}" method="POST" style="display:none;">
    @csrf
    @method('DELETE')
</form>
@endforeach

<script>
// Get service ID from data attribute (more reliable)
var serviceId = document.querySelector('.sv').dataset.serviceId;
var editingRecordId = null;
var baseUrl = '/admin/service/' + serviceId;

console.log('Service ID:', serviceId);
console.log('Base URL:', baseUrl);

// Tab switching
document.querySelectorAll('.sv-tab-btn').forEach(function(b){
    b.addEventListener('click',function(){
        document.querySelectorAll('.sv-tab-btn').forEach(function(x){x.classList.remove('active')});
        document.querySelectorAll('.sv-tab-c').forEach(function(x){x.classList.remove('active')});
        this.classList.add('active');
        document.getElementById('tab-'+this.dataset.tab).classList.add('active');
    });
});

// Record Modal
function openRecordModal(){
    editingRecordId = null;
    document.getElementById('recordModalTitle').textContent = 'Add Service Record';
    document.getElementById('recordForm').action = baseUrl + '/records';
    document.getElementById('recordMethod').value = 'POST';
    resetRecordForm();
    document.getElementById('recordModal').classList.add('active');
}

function closeRecordModal(){
    document.getElementById('recordModal').classList.remove('active');
    resetRecordForm();
}

function resetRecordForm(){
    document.getElementById('rec_engineer_id').value = '';
    document.getElementById('rec_service_type').value = 'Maintenance';
    document.getElementById('rec_service_date').value = '{{ date("Y-m-d") }}';
    document.getElementById('rec_status').value = 'completed';
    document.getElementById('rec_time_taken').value = '';
    document.getElementById('rec_labor_cost').value = '0';
    document.getElementById('rec_remarks').value = '';
    document.getElementById('rec_is_paid').checked = false;
    document.getElementById('rec_service_charge').value = '0';
    togglePaidFields();
}

function editRecord(btn){
    var recordId = btn.dataset.recordId;
    editingRecordId = recordId;
    document.getElementById('recordModalTitle').textContent = 'Edit Service Record';
    document.getElementById('recordForm').action = baseUrl + '/records/' + recordId;
    document.getElementById('recordMethod').value = 'PUT';
    
    // Fetch record data
    fetch(baseUrl + '/records/' + recordId, {
        headers: {'Accept': 'application/json'}
    })
    .then(function(r){ return r.json(); })
    .then(function(d){
        if(d.success){
            var rec = d.record;
            document.getElementById('rec_engineer_id').value = rec.engineer_id || '';
            document.getElementById('rec_service_type').value = rec.service_type || 'Maintenance';
            document.getElementById('rec_service_date').value = rec.service_date ? rec.service_date.split('T')[0] : '';
            document.getElementById('rec_status').value = rec.status || 'completed';
            document.getElementById('rec_time_taken').value = rec.time_taken || '';
            document.getElementById('rec_labor_cost').value = rec.labor_cost || '0';
            document.getElementById('rec_remarks').value = rec.remarks || '';
            document.getElementById('rec_is_paid').checked = rec.is_paid == 1;
            document.getElementById('rec_service_charge').value = rec.service_charge || '0';
            togglePaidFields();
            document.getElementById('recordModal').classList.add('active');
        }
    })
    .catch(function(e){
        alert('Failed to load record');
        console.error(e);
    });
}

function togglePaidFields(){
    var isPaid = document.getElementById('rec_is_paid').checked;
    document.getElementById('chargeRow').classList.toggle('show', isPaid);
    if(!isPaid){
        document.getElementById('rec_service_charge').value = '0';
    }
}

// Visit Modal
function openVisitModal(){document.getElementById('visitModal').classList.add('active')}
function closeVisitModal(){document.getElementById('visitModal').classList.remove('active')}

// Close modal on overlay click
document.querySelectorAll('.sv-modal').forEach(function(m){
    m.addEventListener('click',function(e){if(e.target===m)m.classList.remove('active')});
});

// Materials
var mI = 1;
var currentTaxRowIndex = null;

var productsData = [
    @foreach($products as $p)
    {id: {{ $p->id }}, name: "{{ addslashes($p->name) }}", sku: "{{ addslashes($p->sku ?? '') }}", price: {{ $p->sale_price ?? $p->purchase_price ?? 0 }}},
    @endforeach
];

var taxesData = [
    @foreach($taxes as $t)
    {id: {{ $t->id }}, name: "{{ addslashes($t->name) }}", rate: {{ $t->rate }}},
    @endforeach
];

console.log('Products loaded:', productsData.length);
console.log('Taxes loaded:', taxesData.length);

function getProductOptions(){
    var opts = '<option value="">Select Product</option>';
    productsData.forEach(function(p){
        var label = p.name + (p.sku ? ' (' + p.sku + ')' : '');
        opts += '<option value="'+p.id+'" data-p="'+p.price+'">'+label+'</option>';
    });
    return opts;
}

function addMat(){
    var h = '<div class="sv-mat-r" data-index="'+mI+'">' +
        '<div class="sv-mat-field">' +
            '<label class="sv-mat-label">Product</label>' +
            '<select name="materials['+mI+'][product_id]" onchange="selProd(this,'+mI+')">'+getProductOptions()+'</select>' +
        '</div>' +
        '<div class="sv-mat-field">' +
            '<label class="sv-mat-label">Qty</label>' +
            '<input type="number" name="materials['+mI+'][quantity]" value="1" min="1" onchange="calcT('+mI+')">' +
        '</div>' +
        '<div class="sv-mat-field">' +
            '<label class="sv-mat-label">Price</label>' +
            '<input type="number" name="materials['+mI+'][unit_price]" value="0" min="0" step="0.01" onchange="calcT('+mI+')">' +
        '</div>' +
        '<div class="sv-mat-field">' +
            '<label class="sv-mat-label">Taxes</label>' +
            '<div class="sv-tax-cell">' +
                '<input type="hidden" name="materials['+mI+'][tax_ids]" class="tax-ids-hidden" value="[]">' +
                '<span class="sv-tax-add" onclick="showTaxDropdown(this, '+mI+')">+</span>' +
            '</div>' +
        '</div>' +
        '<div class="sv-mat-field">' +
            '<label class="sv-mat-label">Total</label>' +
            '<input type="number" name="materials['+mI+'][total]" value="0" readonly class="sv-mat-total">' +
        '</div>' +
        '<div class="sv-mat-field">' +
            '<label class="sv-mat-label">Notes</label>' +
            '<input type="text" name="materials['+mI+'][notes]" placeholder="Notes">' +
        '</div>' +
        '<div class="sv-mat-field sv-mat-action">' +
            '<button type="button" class="sv-mat-rm" onclick="removeMat(this)">×</button>' +
        '</div>' +
        '</div>';
    document.getElementById('matCont').insertAdjacentHTML('beforeend',h);
    mI++;
}

function removeMat(btn){
    btn.closest('.sv-mat-r').remove();
}

function selProd(s, i){
    var p = s.options[s.selectedIndex].dataset.p || 0;
    document.querySelector('[name="materials['+i+'][unit_price]"]').value = p;
    calcT(i);
}

function calcT(i){
    var row = document.querySelector('.sv-mat-r[data-index="'+i+'"]');
    if(!row) return;
    
    var q = parseFloat(row.querySelector('[name="materials['+i+'][quantity]"]').value) || 0;
    var p = parseFloat(row.querySelector('[name="materials['+i+'][unit_price]"]').value) || 0;
    var subtotal = q * p;
    
    // Get tax rate from selected taxes
    var taxIdsHidden = row.querySelector('.tax-ids-hidden');
    var taxIds = [];
    try { taxIds = JSON.parse(taxIdsHidden.value); } catch(e) {}
    
    var totalTaxRate = 0;
    taxIds.forEach(function(id){
        var tax = taxesData.find(function(t){ return t.id == id; });
        if(tax) totalTaxRate += tax.rate;
    });
    
    var taxAmount = subtotal * totalTaxRate / 100;
    var total = subtotal + taxAmount;
    
    row.querySelector('[name="materials['+i+'][total]"]').value = total.toFixed(2);
}

// Tax Dropdown Functions
function showTaxDropdown(btn, index){
    currentTaxRowIndex = index;
    var row = btn.closest('.sv-mat-r');
    var taxIdsHidden = row.querySelector('.tax-ids-hidden');
    var selectedIds = [];
    try { selectedIds = JSON.parse(taxIdsHidden.value); } catch(e) {}
    
    var dropdown = document.getElementById('taxDropdown');
    var rect = btn.getBoundingClientRect();
    dropdown.style.top = (rect.bottom + 5) + 'px';
    dropdown.style.left = Math.min(rect.left, window.innerWidth - 200) + 'px';
    
    var html = '';
    if(taxesData.length === 0){
        html = '<div style="padding:12px;color:#666;text-align:center;">No taxes available</div>';
    } else {
        taxesData.forEach(function(tax){
            var isSelected = selectedIds.includes(tax.id);
            html += '<div class="sv-tax-dropdown-item '+(isSelected?'selected':'')+'" data-tax-id="'+tax.id+'" onclick="toggleTax('+tax.id+')">' +
                '<span>'+tax.name+'</span>' +
                '<span style="display:flex;align-items:center;gap:6px;">' +
                    '<span class="tax-rate">'+tax.rate+'%</span>' +
                    (isSelected ? '<span class="check-mark">✓</span>' : '') +
                '</span>' +
            '</div>';
        });
    }
    
    dropdown.innerHTML = html;
    dropdown.classList.add('show');
}

function toggleTax(taxId){
    var row = document.querySelector('.sv-mat-r[data-index="'+currentTaxRowIndex+'"]');
    if(!row) return;
    
    var taxIdsHidden = row.querySelector('.tax-ids-hidden');
    var selectedIds = [];
    try { selectedIds = JSON.parse(taxIdsHidden.value); } catch(e) {}
    
    var idx = selectedIds.indexOf(taxId);
    if(idx > -1){
        selectedIds.splice(idx, 1);
    } else {
        selectedIds.push(taxId);
    }
    
    taxIdsHidden.value = JSON.stringify(selectedIds);
    renderTaxBadges(row, selectedIds);
    calcT(currentTaxRowIndex);
    
    // Update dropdown checkmarks
    showTaxDropdown(row.querySelector('.sv-tax-add'), currentTaxRowIndex);
}

function renderTaxBadges(row, taxIds){
    var taxCell = row.querySelector('.sv-tax-cell');
    var taxIdsHidden = taxCell.querySelector('.tax-ids-hidden');
    var rowIndex = row.dataset.index;
    
    var html = '<input type="hidden" name="materials['+rowIndex+'][tax_ids]" class="tax-ids-hidden" value=\''+JSON.stringify(taxIds)+'\'>';
    
    taxIds.forEach(function(id){
        var tax = taxesData.find(function(t){ return t.id == id; });
        if(tax){
            html += '<span class="sv-tax-badge">'+tax.rate+'% '+tax.name+
                '<span class="sv-tax-rm" onclick="removeTax(this, '+id+', '+rowIndex+')">×</span></span>';
        }
    });
    
    html += '<span class="sv-tax-add" onclick="showTaxDropdown(this, '+rowIndex+')">+</span>';
    taxCell.innerHTML = html;
}

function removeTax(btn, taxId, rowIndex){
    event.stopPropagation();
    var row = document.querySelector('.sv-mat-r[data-index="'+rowIndex+'"]');
    if(!row) return;
    
    var taxIdsHidden = row.querySelector('.tax-ids-hidden');
    var selectedIds = [];
    try { selectedIds = JSON.parse(taxIdsHidden.value); } catch(e) {}
    
    var idx = selectedIds.indexOf(taxId);
    if(idx > -1) selectedIds.splice(idx, 1);
    
    taxIdsHidden.value = JSON.stringify(selectedIds);
    renderTaxBadges(row, selectedIds);
    calcT(rowIndex);
}

// Close tax dropdown when clicking outside
document.addEventListener('click', function(e){
    if(!e.target.closest('.sv-tax-dropdown') && !e.target.closest('.sv-tax-add')){
        document.getElementById('taxDropdown').classList.remove('show');
    }
});

// Delete Record - Using form as primary method
function deleteRecord(btn){
    var recordId = btn.dataset.recordId;
    if(!recordId){
        alert('Record ID not found');
        return;
    }
    
    if(confirm('Delete this service record?')){
        // Use form-based delete (more reliable)
        var form = document.getElementById('deleteRecordForm' + recordId);
        if(form){
            form.submit();
        } else {
            // Fallback to fetch
            var url = baseUrl + '/records/' + recordId;
            console.log('Deleting record via URL:', url);
            
            fetch(url, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            })
            .then(function(r){ return r.json(); })
            .then(function(d){
                if(d.success){
                    location.reload();
                } else {
                    alert(d.message || 'Delete failed');
                }
            })
            .catch(function(e){
                alert('Delete failed: ' + e.message);
                console.error(e);
            });
        }
    }
}

// Delete Visit - Using form as primary method
function deleteVisit(btn){
    var visitId = btn.dataset.visitId;
    if(!visitId){
        alert('Visit ID not found');
        return;
    }
    
    if(confirm('Delete this visit?')){
        // Use form-based delete (more reliable)
        var form = document.getElementById('deleteVisitForm' + visitId);
        if(form){
            form.submit();
        } else {
            // Fallback to fetch
            var url = baseUrl + '/visits/' + visitId;
            
            fetch(url, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            })
            .then(function(r){ return r.json(); })
            .then(function(d){
                if(d.success){
                    location.reload();
                } else {
                    alert(d.message || 'Delete failed');
                }
            })
            .catch(function(e){
                alert('Delete failed: ' + e.message);
                console.error(e);
            });
        }
    }
}

function refreshDates(){
    if(confirm('Recalculate service dates?')){
        fetch(baseUrl + '/refresh-dates', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(function(r){ return r.json(); })
        .then(function(d){
            alert(d.message);
            if(d.success) location.reload();
        })
        .catch(function(e){
            alert('Failed to refresh dates');
            console.error(e);
        });
    }
}

function sendReminder(){
    if(confirm('Send reminder email to client?')){
        fetch(baseUrl + '/send-reminder', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(function(r){ return r.json(); })
        .then(function(d){
            alert(d.message);
            if(d.success) location.reload();
        })
        .catch(function(e){
            alert('Failed to send reminder');
            console.error(e);
        });
    }
}

function markInvoicePaid(invoiceId){
    if(confirm('Mark this invoice as paid?')){
        fetch('/admin/service/invoice/' + invoiceId + '/mark-paid', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(function(r){ return r.json(); })
        .then(function(d){
            if(d.success){
                alert('Invoice marked as paid');
                location.reload();
            } else {
                alert(d.message || 'Failed to update invoice');
            }
        })
        .catch(function(e){
            alert('Failed to update invoice');
            console.error(e);
        });
    }
}

function sendCompletedEmail(btn){
    var recordId = btn.dataset.recordId;
    if(confirm('Send service completed email to client?')){
        btn.disabled = true;
        btn.innerHTML = '...';
        
        fetch(baseUrl + '/send-completed-email/' + recordId, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(function(r){ return r.json(); })
        .then(function(d){
            alert(d.message);
            btn.disabled = false;
            btn.innerHTML = '📧';
            if(d.success) location.reload();
        })
        .catch(function(e){
            alert('Failed to send email');
            btn.disabled = false;
            btn.innerHTML = '📧';
            console.error(e);
        });
    }
}

function sendInvoiceEmail(btn){
    var recordId = btn.dataset.recordId;
    if(confirm('Send invoice email to client?')){
        btn.disabled = true;
        btn.innerHTML = '...';
        
        fetch(baseUrl + '/send-invoice-email/' + recordId, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(function(r){ return r.json(); })
        .then(function(d){
            alert(d.message);
            btn.disabled = false;
            btn.innerHTML = '📄';
            if(d.success) location.reload();
        })
        .catch(function(e){
            alert('Failed to send email');
            btn.disabled = false;
            btn.innerHTML = '📄';
            console.error(e);
        });
    }
}
</script>