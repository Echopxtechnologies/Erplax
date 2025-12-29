@php 
    $student = $student ?? null;
@endphp
<style>
    .form-page { max-width: 1000px; margin: 0 auto; padding: 20px; }
    .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; }
    .page-header h1 { font-size: 24px; font-weight: 700; color: var(--text-primary); margin: 0; display: flex; align-items: center; gap: 10px; }
    .page-header h1 svg { width: 28px; height: 28px; color: var(--primary); }
    .btn-back { padding: 10px 20px; background: var(--card-bg); border: 1px solid var(--card-border); border-radius: 8px; color: var(--text-secondary); text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 8px; }
    .btn-back:hover { background: var(--body-bg); color: var(--text-primary); }
    
    /* Tabs */
    .tabs-container { background: var(--card-bg); border: 1px solid var(--card-border); border-radius: 12px; margin-bottom: 20px; }
    .tabs-nav { display: flex; border-bottom: 1px solid var(--card-border); overflow-x: auto; background: var(--body-bg); border-radius: 12px 12px 0 0; }
    .tab-btn { padding: 16px 24px; font-size: 14px; font-weight: 600; color: var(--text-muted); border: none; background: none; cursor: pointer; white-space: nowrap; display: flex; align-items: center; gap: 8px; border-bottom: 3px solid transparent; margin-bottom: -1px; transition: all 0.2s; }
    .tab-btn:hover { color: var(--text-primary); background: rgba(59, 130, 246, 0.05); }
    .tab-btn.active { color: var(--primary); border-bottom-color: var(--primary); background: var(--card-bg); }
    .tab-btn svg { width: 18px; height: 18px; }
    .tab-content { display: none; padding: 24px; position: relative; }
    .tab-content.active { display: block; overflow: visible; }
    
    /* Form */
    .form-section { margin-bottom: 32px; }
    .form-section:last-child { margin-bottom: 0; }
    .section-title { font-size: 16px; font-weight: 600; color: var(--primary); margin-bottom: 20px; padding-bottom: 10px; border-bottom: 2px solid var(--primary-light); display: flex; align-items: center; gap: 8px; }
    .section-title svg { width: 20px; height: 20px; }
    
    .form-row { display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-bottom: 20px; }
    .form-row-3 { grid-template-columns: repeat(3, 1fr); }
    .form-row-4 { grid-template-columns: repeat(4, 1fr); }
    .form-group { margin-bottom: 0; }
    .form-group.full-width { grid-column: span 2; }
    
    .form-label { display: block; font-size: 14px; font-weight: 600; color: var(--text-primary); margin-bottom: 8px; }
    .form-label .required { color: var(--danger); }
    .form-label .hint { font-weight: 400; color: var(--text-muted); font-size: 12px; }
    
    .form-input, .form-select, .form-textarea { width: 100%; padding: 10px 14px; font-size: 14px; background: var(--input-bg); border: 1px solid var(--input-border); border-radius: 8px; color: var(--input-text); transition: all 0.2s; }
    .form-input:focus, .form-select:focus, .form-textarea:focus { outline: none; border-color: var(--primary); box-shadow: 0 0 0 3px var(--primary-light); }
    .form-textarea { min-height: 100px; resize: vertical; }
    .form-error { font-size: 12px; color: var(--danger); margin-top: 4px; }
    
    /* Hide number input spinners - all browsers */
    input[type="number"]::-webkit-inner-spin-button,
    input[type="number"]::-webkit-outer-spin-button { -webkit-appearance: none !important; margin: 0 !important; }
    input[type="number"] { -moz-appearance: textfield !important; appearance: textfield !important; }
    
    /* Searchable Select */
    .searchable-select { position: relative; }
    .searchable-select .ss-display { width: 100%; padding: 10px 14px; padding-right: 36px; font-size: 14px; background: var(--input-bg, #fff); border: 1px solid var(--input-border, #ccc); border-radius: 8px; color: var(--input-text, #333); cursor: pointer; min-height: 42px; display: flex; align-items: center; }
    .searchable-select .ss-display.placeholder { color: var(--text-muted, #999); }
    .searchable-select .ss-arrow { position: absolute; right: 12px; top: 50%; transform: translateY(-50%); pointer-events: none; color: var(--text-muted); font-size: 10px; cursor: pointer; }
    .searchable-select .ss-dropdown { display: none; position: absolute; top: 100%; left: 0; right: 0; background: var(--card-bg, #fff); border: 1px solid var(--input-border, #ccc); border-radius: 8px; margin-top: 4px; z-index: 9999; box-shadow: 0 4px 12px rgba(0,0,0,0.15); }
    .searchable-select.open .ss-dropdown { display: block; }
    .searchable-select .ss-search { width: 100%; padding: 10px 14px; font-size: 14px; border: none; border-bottom: 1px solid var(--input-border, #ccc); border-radius: 8px 8px 0 0; background: var(--body-bg, #f9fafb); color: var(--input-text, #333); outline: none; }
    .searchable-select .ss-search::placeholder { color: var(--text-muted, #999); }
    .searchable-select .ss-options { max-height: 200px; overflow-y: auto; }
    .searchable-select .ss-option { padding: 10px 14px; cursor: pointer; font-size: 14px; color: var(--text-primary, #333); }
    .searchable-select .ss-option:hover, .searchable-select .ss-option.highlighted { background: var(--primary-light, #e0e7ff); color: var(--primary, #4F46E5); }
    .searchable-select .ss-option.selected { background: var(--primary, #4F46E5); color: #fff; }
    .searchable-select .ss-no-results { padding: 10px 14px; color: var(--text-muted, #999); font-size: 13px; }
    
    /* Locked/readonly select */
    .searchable-select.locked .ss-display { background: var(--body-bg, #f3f4f6); cursor: not-allowed; }
    .searchable-select.locked .ss-arrow { display: none; }
    
    .input-group { display: flex; }
    .input-group-text { padding: 10px 14px; background: var(--body-bg); border: 1px solid var(--input-border); border-right: none; border-radius: 8px 0 0 8px; color: var(--text-muted); font-size: 14px; }
    .input-group .form-input { border-radius: 0 8px 8px 0; }
    .input-group .form-select { border-radius: 0 8px 8px 0; flex: 1; }
    
    .input-with-btn { display: flex; gap: 8px; }
    .input-with-btn .form-select { flex: 1; }
    .btn-add-inline { padding: 10px 16px; background: var(--success); color: #fff; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; white-space: nowrap; }
    .btn-add-inline:hover { background: var(--success-hover); }
    
    /* Photo Upload */
    .photo-upload { display: flex; align-items: flex-start; gap: 20px; }
    .photo-preview { width: 120px; height: 120px; border-radius: 12px; background: var(--body-bg); border: 2px dashed var(--card-border); display: flex; align-items: center; justify-content: center; overflow: hidden; flex-shrink: 0; }
    .photo-preview img { width: 100%; height: 100%; object-fit: cover; }
    .photo-preview svg { width: 40px; height: 40px; color: var(--text-muted); }
    .photo-input { flex: 1; }
    
    /* Report Cards Table - DataTable Style */
    .report-cards-table { width: 100%; border-collapse: collapse; margin-top: 10px; background: var(--card-bg); border-radius: 8px; overflow: hidden; }
    .report-cards-table th { text-align: left; padding: 14px 16px; background: var(--primary); color: #fff; font-weight: 600; font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px; }
    .report-cards-table td { padding: 14px 16px; border-bottom: 1px solid var(--card-border); font-size: 14px; }
    .report-cards-table tbody tr:hover { background: var(--primary-light); }
    .report-cards-table tbody tr:last-child td { border-bottom: none; }
    .btn-sm { padding: 6px 12px; font-size: 12px; border-radius: 6px; text-decoration: none; border: none; cursor: pointer; display: inline-flex; align-items: center; gap: 4px; margin-right: 4px; }
    .btn-view { background: var(--primary); color: #fff; }
    .btn-view:hover { background: var(--primary-hover); }
    .btn-delete { background: var(--danger); color: #fff; }
    .btn-delete:hover { background: #dc2626; }
    .btn-upload { display: inline-flex; align-items: center; gap: 8px; padding: 12px 20px; background: var(--primary); color: #fff; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; }
    .btn-upload:hover { background: var(--primary-hover); }
    
    /* Report Cards DataTable */
    .rc-dt-container { background: var(--card-bg); border: 1px solid var(--card-border); border-radius: 12px; overflow: hidden; }
    .rc-dt-header { display: flex; justify-content: space-between; align-items: center; padding: 16px 20px; border-bottom: 1px solid var(--card-border); background: var(--body-bg); flex-wrap: wrap; gap: 12px; }
    .rc-dt-title { font-size: 15px; font-weight: 600; color: var(--text-primary); display: flex; align-items: center; gap: 8px; }
    .rc-dt-search input { border-radius: 6px; }
    .rc-dt-body { overflow-x: auto; }
    .rc-datatable { width: 100%; border-collapse: collapse; }
    .rc-datatable thead th { text-align: left; padding: 14px 16px; background: linear-gradient(135deg, var(--primary), var(--primary-hover)); color: #fff; font-weight: 600; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; white-space: nowrap; cursor: pointer; user-select: none; }
    .rc-datatable thead th:hover { background: var(--primary-hover); }
    .rc-datatable thead th[data-sort]:after { content: ' ↕'; opacity: 0.5; font-size: 10px; }
    .rc-datatable thead th.sort-asc:after { content: ' ↑'; opacity: 1; }
    .rc-datatable thead th.sort-desc:after { content: ' ↓'; opacity: 1; }
    .rc-datatable tbody td { padding: 14px 16px; border-bottom: 1px solid var(--card-border); font-size: 14px; vertical-align: middle; }
    .rc-datatable tbody tr:hover { background: rgba(59, 130, 246, 0.05); }
    .rc-datatable tbody tr:last-child td { border-bottom: none; }
    .rc-datatable tbody tr.empty-row:hover { background: transparent; }
    .rc-dt-footer { display: flex; justify-content: space-between; align-items: center; padding: 12px 20px; border-top: 1px solid var(--card-border); background: var(--body-bg); font-size: 13px; color: var(--text-muted); }
    
    /* Term Badges */
    .term-badge { display: inline-flex; align-items: center; padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: 600; }
    .term-badge.term-term1 { background: #DBEAFE; color: #1E40AF; }
    .term-badge.term-term2 { background: #D1FAE5; color: #065F46; }
    .term-badge.term-term3 { background: #FEF3C7; color: #92400E; }
    
    /* Action Buttons */
    .action-btns { display: flex; gap: 6px; }
    .btn-action { width: 32px; height: 32px; border-radius: 6px; border: none; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; transition: all 0.2s; }
    .btn-action svg { width: 16px; height: 16px; }
    .btn-action.btn-view { background: var(--primary-light); color: var(--primary); }
    .btn-action.btn-view:hover { background: var(--primary); color: #fff; }
    .btn-action.btn-delete { background: var(--danger-light); color: var(--danger); }
    .btn-action.btn-delete:hover { background: var(--danger); color: #fff; }
    
    /* Internal ID */
    .internal-id-box { display: flex; align-items: center; gap: 12px; }
    .internal-id-value { background: var(--primary-light); color: var(--primary); padding: 10px 16px; border-radius: 8px; font-weight: 700; font-size: 16px; }
    .btn-edit-id { padding: 8px 12px; background: var(--card-bg); border: 1px solid var(--card-border); border-radius: 6px; color: var(--text-muted); cursor: pointer; font-size: 12px; }
    .btn-edit-id:hover { background: var(--body-bg); }
    
    /* Form Actions */
    .form-actions { display: flex; gap: 12px; padding: 20px 24px; background: var(--body-bg); border-top: 1px solid var(--card-border); }
    .btn-submit { padding: 12px 28px; background: linear-gradient(135deg, var(--primary), var(--primary-hover)); color: #fff; border: none; border-radius: 8px; font-size: 14px; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; }
    .btn-submit:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3); }
    .btn-cancel { padding: 12px 28px; background: var(--card-bg); color: var(--text-secondary); border: 1px solid var(--card-border); border-radius: 8px; text-decoration: none; font-weight: 600; }
    .btn-cancel:hover { background: var(--body-bg); }
    
    /* Report Cards */
    .report-cards-list { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 16px; }
    .report-card-item { background: var(--body-bg); border: 1px solid var(--card-border); border-radius: 8px; padding: 16px; display: flex; flex-direction: column; gap: 8px; }
    .report-card-item .name { font-weight: 600; color: var(--text-primary); }
    .report-card-item .actions { display: flex; gap: 8px; }
    .report-card-item .btn-sm { padding: 6px 12px; font-size: 12px; border-radius: 6px; text-decoration: none; }
    .btn-view { background: var(--primary-light); color: var(--primary); }
    .btn-delete { background: var(--danger-light); color: var(--danger); border: none; cursor: pointer; }
    
    .upload-box { border: 2px dashed var(--card-border); border-radius: 8px; padding: 24px; text-align: center; background: var(--body-bg); }
    .upload-box input[type="file"] { display: none; }
    .upload-box label { cursor: pointer; color: var(--primary); font-weight: 600; }
    
    .alert-info { background: var(--primary-light); border: 1px solid rgba(59, 130, 246, 0.2); border-radius: 8px; padding: 16px; color: var(--primary); margin-bottom: 20px; }
    
    /* Age-Grade Warning */
    .age-grade-warning { display: none; background: #FEF3C7; border: 1px solid #F59E0B; border-radius: 6px; padding: 10px 12px; margin-top: 8px; font-size: 13px; color: #92400E; }
    
    /* Mobile Responsive */
    @media (max-width: 992px) {
        .form-row-3, .form-row-4 { grid-template-columns: repeat(2, 1fr); }
    }
    
    @media (max-width: 768px) {
        .form-page { padding: 12px; }
        
        /* Header */
        .page-header { flex-direction: column; align-items: flex-start; gap: 12px; }
        .page-header h1 { font-size: 20px; }
        .page-header h1 svg { width: 24px; height: 24px; }
        .btn-back { padding: 8px 16px; font-size: 13px; }
        
        /* Tabs */
        .tabs-nav { overflow-x: auto; -webkit-overflow-scrolling: touch; }
        .tabs-nav::-webkit-scrollbar { display: none; }
        .tab-btn { padding: 12px 14px; font-size: 12px; min-width: max-content; }
        .tab-btn svg { width: 16px; height: 16px; }
        .tab-content { padding: 16px; }
        
        /* Form */
        .form-row, .form-row-3, .form-row-4 { grid-template-columns: 1fr; gap: 16px; }
        .form-group.full-width { grid-column: span 1; }
        .form-section { margin-bottom: 24px; }
        .section-title { font-size: 14px; }
        
        .form-label { font-size: 13px; margin-bottom: 6px; }
        .form-input, .form-select, .form-textarea { padding: 10px 12px; font-size: 14px; }
        
        /* Photo Upload */
        .photo-upload { flex-direction: column; align-items: center; }
        .photo-preview { width: 100px; height: 100px; }
        .photo-input { width: 100%; }
        
        /* Input with button */
        .input-with-btn { flex-direction: column; gap: 8px; }
        .input-with-btn .form-select, 
        .input-with-btn .searchable-select { width: 100% !important; flex: none !important; }
        .btn-add-inline { width: 100%; justify-content: center; }
        
        /* Searchable Select */
        .searchable-select { width: 100% !important; }
        .searchable-select .ss-display { padding: 10px 12px; font-size: 14px; }
        .searchable-select .ss-dropdown { max-height: 200px; }
        .searchable-select .ss-search { padding: 10px 12px; }
        .searchable-select .ss-option { padding: 10px 12px; }
        
        /* Internal ID */
        .internal-id-box { flex-direction: column; align-items: flex-start; gap: 8px; }
        .internal-id-value { font-size: 14px; padding: 8px 12px; }
        
        /* Form Actions */
        .form-actions { flex-direction: column; padding: 16px; }
        .btn-submit, .btn-cancel { width: 100%; justify-content: center; text-align: center; }
        
        /* Report Cards DataTable */
        .rc-dt-header { flex-direction: column; align-items: stretch; gap: 12px; padding: 12px 16px; }
        .rc-dt-search input { width: 100%; }
        .rc-datatable thead th { padding: 10px 12px; font-size: 11px; }
        .rc-datatable tbody td { padding: 10px 12px; font-size: 13px; }
        .btn-action { width: 28px; height: 28px; }
        .btn-action svg { width: 14px; height: 14px; }
        .action-btns { gap: 4px; }
        .term-badge { padding: 3px 8px; font-size: 11px; }
        .rc-dt-footer { flex-direction: column; gap: 8px; text-align: center; padding: 10px 16px; }
        
        /* Upload Form */
        .upload-form-box { padding: 16px; }
        .upload-form-box h4 { font-size: 13px; }
        .btn-upload { width: 100%; justify-content: center; padding: 10px 16px; font-size: 13px; }
        
        /* Alerts */
        .alert-info, .alert-errors { padding: 12px; font-size: 13px; }
        .age-grade-warning { padding: 8px 10px; font-size: 12px; }
    }
    
    @media (max-width: 480px) {
        .form-page { padding: 8px; }
        .page-header h1 { font-size: 18px; }
        
        .tabs-container { border-radius: 8px; }
        .tab-btn { padding: 10px 12px; font-size: 11px; }
        .tab-content { padding: 12px; }
        
        .section-title { font-size: 13px; padding-bottom: 8px; margin-bottom: 16px; }
        .section-title svg { width: 16px; height: 16px; }
        
        .form-row { gap: 12px; margin-bottom: 12px; }
        .form-input, .form-select, .form-textarea { padding: 9px 10px; font-size: 13px; }
        
        .photo-preview { width: 80px; height: 80px; }
        
        /* Report Cards DataTable - Card View */
        .rc-datatable thead { display: none; }
        .rc-datatable tbody { display: block; }
        .rc-datatable tbody tr.report-card-row {
            display: block;
            margin-bottom: 12px; 
            border: 1px solid var(--card-border); 
            border-radius: 8px; 
            padding: 12px;
            background: var(--card-bg);
        }
        .rc-datatable tbody tr.report-card-row td { 
            display: block;
            padding: 6px 0; 
            border: none;
        }
        .rc-datatable tbody tr.report-card-row td:first-child { 
            font-weight: 600;
            padding-bottom: 8px;
            margin-bottom: 8px;
            border-bottom: 1px solid var(--card-border);
        }
        .rc-datatable tbody tr.report-card-row td:last-child {
            padding-top: 8px;
            margin-top: 8px;
            border-top: 1px solid var(--card-border);
        }
        .action-btns { justify-content: flex-start; }
    }
</style>

<div class="form-page">
    <!-- Header -->
    <div class="page-header">
        <h1>
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M12 14l9-5-9-5-9 5 9 5z"></path>
                <path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path>
            </svg>
            {{ $isEdit ? 'Edit School Student' : 'Register School Student' }}
        </h1>
        <a href="{{ route('admin.studentsponsorship.school-students.index') }}" class="btn-back">
            ← Back to List
        </a>
    </div>

    @if($errors->any())
        <div class="alert-errors" style="background:var(--danger-light);border:1px solid rgba(239,68,68,0.2);border-radius:8px;padding:16px;margin-bottom:20px;">
            <strong style="color:var(--danger);">Please fix the following errors:</strong>
            <ul style="margin:8px 0 0 20px;color:var(--danger);">
                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif

    @if(session('success'))
        <div style="background:var(--success-light);border:1px solid rgba(34,197,94,0.2);border-radius:8px;padding:16px;margin-bottom:20px;color:var(--success);">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ $isEdit ? route('admin.studentsponsorship.school-students.update', $student?->hash_id) : route('admin.studentsponsorship.school-students.store') }}" 
          method="POST" enctype="multipart/form-data" id="studentForm">
        @csrf
        @if($isEdit) @method('PUT') @endif

        <div class="tabs-container">
            <!-- Tabs Navigation -->
            <div class="tabs-nav">
                <button type="button" class="tab-btn active" data-tab="student-info">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    Student Info
                </button>
                <button type="button" class="tab-btn" data-tab="sponsorship">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                    Sponsorship
                </button>
                <button type="button" class="tab-btn" data-tab="bank-info">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                    Bank Info
                </button>
                <button type="button" class="tab-btn" data-tab="family-info">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    Family Info
                </button>
                <button type="button" class="tab-btn" data-tab="additional-info">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Additional Info
                </button>
                @if($isEdit)
                <button type="button" class="tab-btn" data-tab="report-cards">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Report Cards
                </button>
                @endif
            </div>

            <!-- Tab: Student Info -->
            <div class="tab-content active" id="tab-student-info">
                <div class="form-section">
                    <h3 class="section-title">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        Basic Information
                    </h3>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Full Name <span class="required">*</span></label>
                            <input type="text" name="full_name" class="form-input" value="{{ old('full_name', $student?->full_name ?? '') }}" placeholder="Enter student's full name" required>
                            @error('full_name')<div class="form-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">Country</label>
                            @php
                                $sriLanka = $countries->firstWhere('short_name', 'Sri Lanka') ?? $countries->firstWhere('country_id', 144);
                                $sriLankaId = $sriLanka->country_id ?? 144;
                            @endphp
                            <input type="text" class="form-input" value="Sri Lanka" readonly style="background: var(--body-bg); cursor: not-allowed;">
                            <input type="hidden" name="country_id" id="countrySelect" value="{{ $sriLankaId }}" data-calling-code="94">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Email Address</label>
                            <input type="email" name="email" class="form-input" value="{{ old('email', $student?->email ?? '') }}" placeholder="student@example.com">
                            @error('email')<div class="form-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">Address</label>
                            <textarea name="address" class="form-textarea" rows="2" placeholder="Complete address">{{ old('address', $student?->address ?? '') }}</textarea>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Phone Number</label>
                            <div class="input-group">
                                <span class="input-group-text" id="phonePrefix">+94</span>
                                <input type="text" name="phone" class="form-input" value="{{ old('phone', $student?->phone ?? '') }}" placeholder="Enter phone number">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">City / District</label>
                            <input type="text" name="city" class="form-input" value="{{ old('city', $student?->city ?? '') }}" placeholder="City / District">
                        </div>
                    </div>

                    <div class="form-row form-row-3">
                        <div class="form-group">
                            <label class="form-label">Date of Birth</label>
                            <input type="date" name="dob" id="dobField" class="form-input" value="{{ old('dob', $student?->dob?->format('Y-m-d') ?? '') }}" onchange="calculateAge()">
                            @error('dob')<div class="form-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">Age <span class="required">*</span></label>
                            <input type="text" name="age" id="ageField" class="form-input" inputmode="numeric" pattern="[0-9]*" value="{{ old('age', $student?->age ?? '') }}" required>
                            @error('age')<div class="form-error">{{ $message }}</div>@enderror
                            <small style="color:var(--text-muted);margin-top:4px;display:block;">Auto-calculated from DOB or enter manually</small>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Postal Code</label>
                            <input type="text" name="postal_code" class="form-input" value="{{ old('postal_code', $student?->postal_code ?? '') }}" placeholder="Postal code">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Profile Photo</label>
                            <div class="photo-upload">
                                <div class="photo-preview" id="photoPreview">
                                    @if($isEdit && $student->hasProfilePhoto())
                                        <img src="{{ $student->profile_photo_url }}" alt="Photo" id="currentPhoto">
                                    @else
                                        <svg viewBox="0 0 2200 2200" style="width:100%;height:100%;" id="defaultAvatar">
                                            <rect fill="#FFFFFF" width="2200" height="2200"/>
                                            <defs>
                                                <radialGradient id="grad1" cx="33.7%" cy="33.5%" r="57.1%">
                                                    <stop offset="0" stop-color="#63DFFC"/>
                                                    <stop offset="1" stop-color="#3F7CD1"/>
                                                </radialGradient>
                                                <radialGradient id="grad2" cx="46.5%" cy="31.8%" r="21.8%">
                                                    <stop offset="0" stop-color="#FFFFFF"/>
                                                    <stop offset="1" stop-color="#D1D1D1"/>
                                                </radialGradient>
                                                <radialGradient id="grad3" cx="43.9%" cy="66.2%" r="30.7%">
                                                    <stop offset="0" stop-color="#FFFFFF"/>
                                                    <stop offset="1" stop-color="#D1D1D1"/>
                                                </radialGradient>
                                            </defs>
                                            <path fill="url(#grad1)" d="M1903,1100c0,215.52-84.91,411.21-223.1,555.44C1533.74,1808.01,1327.96,1903,1100,1903s-433.74-94.99-579.9-247.56C381.91,1511.21,297,1315.52,297,1100c0-443.48,359.52-803,803-803S1903,656.52,1903,1100z"/>
                                            <circle fill="url(#grad2)" cx="1100" cy="815" r="328"/>
                                            <path fill="url(#grad3)" d="M1679.9,1655.44C1533.74,1808.01,1327.96,1903,1100,1903s-433.74-94.99-579.9-247.56c82.54-240.93,311-414.12,579.9-414.12S1597.36,1414.51,1679.9,1655.44z"/>
                                        </svg>
                                    @endif
                                </div>
                                <div class="photo-input">
                                    <input type="file" name="profile_photo" id="profilePhotoInput" class="form-input" accept="image/jpeg,image/png,image/jpg" onchange="previewPhoto(this)">
                                    <small style="color:var(--text-muted);display:block;margin-top:4px;">Max 2MB. JPG, PNG supported.</small>
                                    @if($isEdit && $student->hasProfilePhoto())
                                        <button type="button" onclick="removeProfilePhoto()" class="btn-remove-photo" style="margin-top:8px;padding:6px 12px;background:#EF4444;color:#fff;border:none;border-radius:6px;cursor:pointer;font-size:12px;">
                                            <svg style="width:14px;height:14px;vertical-align:middle;margin-right:4px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            Remove Photo
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h3 class="section-title">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 14l9-5-9-5-9 5 9 5z"></path></svg>
                        School Information
                    </h3>
                    
                    <!-- Internal ID and School Student ID in same row - both manual -->
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Internal ID <span class="required">*</span></label>
                            <input type="text" name="school_internal_id" class="form-input" value="{{ old('school_internal_id', $student?->school_internal_id ?? '') }}" placeholder="Enter Internal ID" required>
                            @error('school_internal_id')<div class="form-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">School Student ID <span class="required">*</span></label>
                            <input type="text" name="school_student_id" class="form-input numeric-only" value="{{ old('school_student_id', $student?->school_student_id ?? '') }}" placeholder="Enter School Student ID" required>
                            @error('school_student_id')<div class="form-error">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    
                    <!-- School Name in separate row -->
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">School Name</label>
                            <div class="input-with-btn">
                                <select name="school_id" id="schoolSelect" class="form-select">
                                    <option value="">Select School</option>
                                    @foreach($schools as $school)
                                        <option value="{{ $school->id }}" {{ old('school_id', $student?->school_id ?? '') == $school->id ? 'selected' : '' }}>{{ $school->name }}</option>
                                    @endforeach
                                </select>
                                <button type="button" class="btn-add-inline" onclick="addNewSchool()">+</button>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">School Type</label>
                            <select name="school_type" class="form-select">
                                <option value="">Select School Type</option>
                                @foreach($schoolTypes as $key => $label)
                                    <option value="{{ $key }}" {{ old('school_type', $student?->school_type ?? '') == $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Grade / Class <span class="required">*</span></label>
                            <select name="grade" class="form-select" required>
                                <option value="">Select Grade</option>
                                @foreach($grades as $key => $label)
                                    <option value="{{ $key }}" {{ old('grade', $student?->grade ?? '') == $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('grade')<div class="form-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">Current State <span class="required">*</span></label>
                            <select name="current_state" class="form-select" required>
                                <option value="inprogress" {{ old('current_state', $student?->current_state ?? 'inprogress') == 'inprogress' ? 'selected' : '' }}>In Progress</option>
                                <option value="complete" {{ old('current_state', $student?->current_state ?? '') == 'complete' ? 'selected' : '' }}>Complete</option>
                            </select>
                        </div>
                    </div>
                    
                    <!-- Grade Mismatch Reason (shown when age is 1 year off) -->
                    <div class="form-row" id="gradeMismatchRow" style="display: none;">
                        <div class="form-group" style="flex: 1;">
                            <label class="form-label">Grade Mismatch Reason <span class="required">*</span></label>
                            <input type="text" name="grade_mismatch_reason" id="gradeMismatchReason" class="form-input" 
                                   value="{{ old('grade_mismatch_reason', $student?->grade_mismatch_reason ?? '') }}"
                                   placeholder="Please explain why student's age doesn't match the grade">
                            <small class="form-hint" id="mismatchHint">Required when student age is outside expected range for the grade</small>
                            @error('grade_mismatch_reason')<div class="form-error">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab: Sponsorship -->
            <div class="tab-content" id="tab-sponsorship">
                <div class="form-section">
                    <h3 class="section-title">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                        Sponsorship Details
                    </h3>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Sponsorship Start Date</label>
                            <input type="date" name="sponsorship_start_date" class="form-input" value="{{ old('sponsorship_start_date', $student?->sponsorship_start_date?->format('Y-m-d') ?? '') }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Sponsorship End Date</label>
                            <input type="date" name="sponsorship_end_date" class="form-input" value="{{ old('sponsorship_end_date', $student?->sponsorship_end_date?->format('Y-m-d') ?? '') }}">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Introduced By</label>
                            <input type="text" name="introduced_by" class="form-input" value="{{ old('introduced_by', $student?->introduced_by ?? '') }}" placeholder="Name of introducer">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Introducer's Phone</label>
                            <input type="text" name="introducer_phone" class="form-input" value="{{ old('introducer_phone', $student?->introducer_phone ?? '') }}" placeholder="Phone number">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab: Bank Info -->
            <div class="tab-content" id="tab-bank-info">
                <div class="form-section">
                    <h3 class="section-title">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                        Bank Details
                    </h3>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Bank Name</label>
                            <div class="input-with-btn">
                                <select name="bank_id" id="bankSelect" class="form-select">
                                    <option value="">Select Bank</option>
                                    @foreach($banks as $bank)
                                        <option value="{{ $bank->id }}" {{ old('bank_id', $student?->bank_id ?? '') == $bank->id ? 'selected' : '' }}>{{ $bank->name }}</option>
                                    @endforeach
                                </select>
                                <button type="button" class="btn-add-inline" onclick="addNewBank()">+</button>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Bank Account Number</label>
                            <input type="text" name="bank_account_number" class="form-input numeric-only" value="{{ old('bank_account_number', $student?->bank_account_number ?? '') }}" placeholder="Account number">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Bank Branch Number</label>
                            <input type="text" name="bank_branch_number" class="form-input numeric-only" value="{{ old('bank_branch_number', $student?->bank_branch_number ?? '') }}" placeholder="Branch number/code">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Bank Branch Information</label>
                            <textarea name="bank_branch_info" class="form-textarea" rows="2" placeholder="Additional branch details">{{ old('bank_branch_info', $student?->bank_branch_info ?? '') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab: Family Info -->
            <div class="tab-content" id="tab-family-info">
                <div class="form-section">
                    <h3 class="section-title">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        Family Information
                    </h3>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Father's Name</label>
                            <input type="text" name="father_name" class="form-input" value="{{ old('father_name', $student?->father_name ?? '') }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Father's Income</label>
                            <input type="text" name="father_income" class="form-input decimal-only" value="{{ old('father_income', $student?->father_income ?? '') }}" placeholder="0.00">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Mother's Name</label>
                            <input type="text" name="mother_name" class="form-input" value="{{ old('mother_name', $student?->mother_name ?? '') }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Mother's Income</label>
                            <input type="text" name="mother_income" class="form-input decimal-only" value="{{ old('mother_income', $student?->mother_income ?? '') }}" placeholder="0.00">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Guardian's Name</label>
                            <input type="text" name="guardian_name" class="form-input" value="{{ old('guardian_name', $student?->guardian_name ?? '') }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Guardian's Income</label>
                            <input type="text" name="guardian_income" class="form-input decimal-only" value="{{ old('guardian_income', $student?->guardian_income ?? '') }}" placeholder="0.00">
                        </div>
                    </div>

                    <div class="form-group full-width">
                        <label class="form-label">Background Information</label>
                        <textarea name="background_info" class="form-textarea" rows="4" placeholder="Family background, special circumstances, etc.">{{ old('background_info', $student?->background_info ?? '') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Tab: Additional Info -->
            <div class="tab-content" id="tab-additional-info">
                <div class="form-section">
                    <h3 class="section-title">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Comments & Notes
                    </h3>
                    
                    <div class="form-group">
                        <label class="form-label">Internal Comment <span class="hint">These comments are only visible to staff/administrators.</span></label>
                        <textarea name="internal_comment" class="form-textarea" rows="4" placeholder="Staff-only notes...">{{ old('internal_comment', $student?->internal_comment ?? '') }}</textarea>
                    </div>

                    <div class="form-group" style="margin-top:20px;">
                        <label class="form-label">External Comment <span class="hint">These comments are visible to students and sponsors.</span></label>
                        <textarea name="external_comment" class="form-textarea" rows="4" placeholder="Public notes...">{{ old('external_comment', $student?->external_comment ?? '') }}</textarea>
                    </div>
                </div>
            </div>

            @if($isEdit)
            <!-- Tab: Report Cards -->
            <div class="tab-content" id="tab-report-cards">
                <div class="form-section">
                    <h3 class="section-title">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        Report Cards
                    </h3>
                    
                    @if(!$isEdit)
                        <div class="alert-info" style="padding:16px;background:#DBEAFE;border:1px solid #3B82F6;border-radius:8px;color:#1E40AF;">
                            <strong>Note:</strong> Please save the student first before uploading report cards.
                        </div>
                    @else
                        <!-- Upload Message -->
                        <div id="rcUploadMessage" style="display:none;padding:12px 16px;border-radius:8px;margin-bottom:16px;"></div>
                        
                        <!-- Upload Form -->
                        <div style="margin-bottom:24px;padding:20px;background:var(--body-bg);border-radius:8px;border:1px solid var(--card-border);">
                            <h4 style="margin-bottom:16px;font-size:14px;font-weight:600;display:flex;align-items:center;gap:8px;">
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width:20px;height:20px;color:var(--primary);"><path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                Upload New Report Card
                            </h4>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label">Filename <span class="required">*</span></label>
                                    <input type="text" id="rcFilename" class="form-input" placeholder="e.g. Term 1 Report 2024">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Term <span class="required">*</span></label>
                                    <select id="rcTerm" class="form-select" style="height:42px;">
                                        <option value="">Select Term</option>
                                        <option value="Term1">Term 1</option>
                                        <option value="Term2">Term 2</option>
                                        <option value="Term3">Term 3</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label">Upload Date <span class="required">*</span></label>
                                    <input type="date" id="rcUploadDate" class="form-input" value="{{ date('Y-m-d') }}">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">File (PDF/Image) <span class="required">*</span></label>
                                    <input type="file" id="rcFileInput" class="form-input" accept=".pdf,.jpg,.jpeg,.png">
                                    <small style="color:var(--text-muted);margin-top:4px;display:block;">Max 5MB</small>
                                </div>
                            </div>
                            
                            <button type="button" id="rcUploadBtn" onclick="uploadReportCardFile()" style="display:inline-flex;align-items:center;gap:8px;padding:12px 20px;background:var(--primary);color:#fff;border:none;border-radius:8px;font-weight:600;cursor:pointer;margin-top:8px;">
                                <svg id="rcUploadIcon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width:18px;height:18px;"><path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                <span id="rcUploadBtnText">Upload Report Card</span>
                            </button>
                        </div>
                        
                        <!-- Report Cards List -->
                        @php
                            $reportCards = \DB::table('school_report_cards')
                                ->where('student_school_id', $student->school_student_id)
                                ->orderBy('created_on', 'desc')
                                ->get();
                        @endphp
                        
                        <div style="background:var(--card-bg);border:1px solid var(--card-border);border-radius:12px;overflow:hidden;">
                            <div style="display:flex;justify-content:space-between;align-items:center;padding:16px 20px;border-bottom:1px solid var(--card-border);background:var(--body-bg);">
                                <div style="font-size:15px;font-weight:600;color:var(--text-primary);display:flex;align-items:center;gap:8px;">
                                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width:20px;height:20px;color:var(--primary);"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    Uploaded Report Cards
                                    <span style="background:var(--primary-light);color:var(--primary);padding:2px 10px;border-radius:20px;font-size:12px;font-weight:700;">{{ $reportCards->count() }}</span>
                                </div>
                            </div>
                            
                            <div style="overflow-x:auto;">
                                @if($reportCards->count() > 0)
                                    <table style="width:100%;border-collapse:collapse;">
                                        <thead>
                                            <tr style="background:linear-gradient(135deg, var(--primary), var(--primary-hover));">
                                                <th style="text-align:left;padding:14px 16px;color:#fff;font-weight:600;font-size:12px;text-transform:uppercase;">Filename</th>
                                                <th style="text-align:left;padding:14px 16px;color:#fff;font-weight:600;font-size:12px;text-transform:uppercase;">Term</th>
                                                <th style="text-align:left;padding:14px 16px;color:#fff;font-weight:600;font-size:12px;text-transform:uppercase;">Upload Date</th>
                                                <th style="text-align:left;padding:14px 16px;color:#fff;font-weight:600;font-size:12px;text-transform:uppercase;">Size</th>
                                                <th style="text-align:center;padding:14px 16px;color:#fff;font-weight:600;font-size:12px;text-transform:uppercase;">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($reportCards as $rc)
                                                <tr style="border-bottom:1px solid var(--card-border);">
                                                    <td style="padding:14px 16px;font-size:14px;font-weight:500;">{{ $rc->filename }}</td>
                                                    <td style="padding:14px 16px;font-size:14px;">
                                                        @php
                                                            $termColors = ['term1'=>'#DBEAFE','term2'=>'#D1FAE5','term3'=>'#FEF3C7'];
                                                            $termTextColors = ['term1'=>'#1E40AF','term2'=>'#065F46','term3'=>'#92400E'];
                                                            $tc = strtolower(str_replace(' ', '', $rc->term));
                                                        @endphp
                                                        <span style="padding:4px 10px;border-radius:20px;font-size:12px;font-weight:600;background:{{ $termColors[$tc] ?? '#E5E7EB' }};color:{{ $termTextColors[$tc] ?? '#374151' }};">
                                                            {{ str_replace(['Term1','Term2','Term3'], ['Term 1','Term 2','Term 3'], $rc->term) }}
                                                        </span>
                                                    </td>
                                                    <td style="padding:14px 16px;font-size:14px;">{{ \Carbon\Carbon::parse($rc->upload_date)->format('M d, Y') }}</td>
                                                    <td style="padding:14px 16px;font-size:14px;">{{ $rc->file_size ? number_format($rc->file_size / 1024, 1) . ' KB' : '-' }}</td>
                                                    <td style="padding:14px 16px;text-align:center;">
                                                        <div style="display:flex;gap:6px;justify-content:center;">
                                                            <a href="{{ route('admin.studentsponsorship.school-students.view-report-card', [$student->hash_id, $rc->id]) }}" target="_blank" style="width:32px;height:32px;border-radius:6px;background:var(--primary-light);color:var(--primary);display:inline-flex;align-items:center;justify-content:center;text-decoration:none;" title="View">
                                                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width:16px;height:16px;"><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                                            </a>
                                                            <a href="{{ route('admin.studentsponsorship.school-students.download-report-card', [$student->hash_id, $rc->id]) }}" style="width:32px;height:32px;border-radius:6px;background:#D1FAE5;color:#065F46;display:inline-flex;align-items:center;justify-content:center;text-decoration:none;" title="Download">
                                                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width:16px;height:16px;"><path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                                            </a>
                                                            <button type="button" onclick="deleteReportCardFile({{ $rc->id }})" style="width:32px;height:32px;border-radius:6px;background:var(--danger-light);color:var(--danger);border:none;cursor:pointer;display:inline-flex;align-items:center;justify-content:center;" title="Delete">
                                                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width:16px;height:16px;"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @else
                                    <div style="text-align:center;padding:50px 20px;color:var(--text-muted);">
                                        <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" style="width:48px;height:48px;margin:0 auto 12px;display:block;opacity:0.5;"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        No report cards uploaded yet
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Form Actions -->
            <div class="form-actions">
                <button type="submit" class="btn-submit">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width:18px;height:18px;"><path d="M5 13l4 4L19 7"></path></svg>
                    {{ $isEdit ? 'Update Student' : 'Save Student' }}
                </button>
                <a href="{{ route('admin.studentsponsorship.school-students.index') }}" class="btn-cancel">Cancel</a>
            </div>
        </div>
    </form>
</div>

<script>
// Tab switching
document.querySelectorAll('.tab-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
        this.classList.add('active');
        document.getElementById('tab-' + this.dataset.tab).classList.add('active');
    });
});

// Photo preview
function previewPhoto(input) {
    var preview = document.getElementById('photoPreview');
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = '<img src="' + e.target.result + '" alt="Preview">';
        };
        reader.readAsDataURL(input.files[0]);
    }
}

// Remove profile photo
@if($isEdit && $student)
function removeProfilePhoto() {
    if (!confirm('Are you sure you want to remove the profile photo?')) {
        return;
    }
    
    fetch('{{ route("admin.studentsponsorship.school-students.remove-photo", $student->hash_id) }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    })
    .then(function(response) {
        return response.json();
    })
    .then(function(data) {
        if (data.success) {
            // Replace with default avatar
            var preview = document.getElementById('photoPreview');
            preview.innerHTML = '<svg viewBox="0 0 2200 2200" style="width:100%;height:100%;"><rect fill="#FFFFFF" width="2200" height="2200"/><defs><radialGradient id="grad1" cx="33.7%" cy="33.5%" r="57.1%"><stop offset="0" stop-color="#63DFFC"/><stop offset="1" stop-color="#3F7CD1"/></radialGradient><radialGradient id="grad2" cx="46.5%" cy="31.8%" r="21.8%"><stop offset="0" stop-color="#FFFFFF"/><stop offset="1" stop-color="#D1D1D1"/></radialGradient><radialGradient id="grad3" cx="43.9%" cy="66.2%" r="30.7%"><stop offset="0" stop-color="#FFFFFF"/><stop offset="1" stop-color="#D1D1D1"/></radialGradient></defs><path fill="url(#grad1)" d="M1903,1100c0,215.52-84.91,411.21-223.1,555.44C1533.74,1808.01,1327.96,1903,1100,1903s-433.74-94.99-579.9-247.56C381.91,1511.21,297,1315.52,297,1100c0-443.48,359.52-803,803-803S1903,656.52,1903,1100z"/><circle fill="url(#grad2)" cx="1100" cy="815" r="328"/><path fill="url(#grad3)" d="M1679.9,1655.44C1533.74,1808.01,1327.96,1903,1100,1903s-433.74-94.99-579.9-247.56c82.54-240.93,311-414.12,579.9-414.12S1597.36,1414.51,1679.9,1655.44z"/></svg>';
            
            // Hide remove button
            var removeBtn = document.querySelector('.btn-remove-photo');
            if (removeBtn) removeBtn.style.display = 'none';
            
            alert('Photo removed successfully');
        } else {
            alert(data.message || 'Failed to remove photo');
        }
    })
    .catch(function(error) {
        console.error('Error:', error);
        alert('Failed to remove photo');
    });
}
@endif

// Age to Grade mapping (Sri Lankan education system)
var gradeAgeMapping = {
    '1': {min: 5, max: 6, label: 'Grade 1'},
    '2': {min: 6, max: 7, label: 'Grade 2'},
    '3': {min: 7, max: 8, label: 'Grade 3'},
    '4': {min: 8, max: 9, label: 'Grade 4'},
    '5': {min: 9, max: 10, label: 'Grade 5'},
    '6': {min: 10, max: 11, label: 'Grade 6'},
    '7': {min: 11, max: 12, label: 'Grade 7'},
    '8': {min: 12, max: 13, label: 'Grade 8'},
    '9': {min: 13, max: 14, label: 'Grade 9'},
    '10': {min: 14, max: 15, label: 'Grade 10'},
    '11': {min: 15, max: 16, label: 'O/L (Grade 11)'},
    '12': {min: 16, max: 17, label: 'A/L1 (Grade 12)'},
    '13': {min: 17, max: 18, label: 'A/L2 (Grade 13)'},
    '14': {min: 18, max: 19, label: 'A/L Final (Grade 14)'}
};

// Get suggested grade for age
function getSuggestedGrade(age) {
    for (var grade in gradeAgeMapping) {
        if (age >= gradeAgeMapping[grade].min && age <= gradeAgeMapping[grade].max) {
            return grade;
        }
    }
    return null;
}

// Check if age matches grade
function isAgeGradeMatch(age, grade) {
    if (!age || !grade || !gradeAgeMapping[grade]) return true;
    var mapping = gradeAgeMapping[grade];
    return age >= mapping.min && age <= mapping.max;
}

// Calculate age from DOB and suggest grade
function calculateAge() {
    var dob = document.querySelector('input[name="dob"]').value;
    if (dob) {
        var today = new Date();
        var birth = new Date(dob);
        var age = today.getFullYear() - birth.getFullYear();
        var m = today.getMonth() - birth.getMonth();
        if (m < 0 || (m === 0 && today.getDate() < birth.getDate())) age--;
        document.getElementById('ageField').value = age;
        
        // Suggest grade based on age
        var suggestedGrade = getSuggestedGrade(age);
        if (suggestedGrade) {
            var gradeSelect = document.querySelector('select[name="grade"]');
            if (gradeSelect && !gradeSelect.value) {
                gradeSelect.value = suggestedGrade;
            }
        }
        
        // Validate current selection
        validateAgeGrade();
    }
}

// Validate age matches grade
function validateAgeGrade() {
    var age = parseInt(document.getElementById('ageField').value);
    var gradeSelect = document.querySelector('select[name="grade"]');
    var grade = gradeSelect ? gradeSelect.value : '';
    var warningEl = document.getElementById('ageGradeWarning');
    var mismatchRow = document.getElementById('gradeMismatchRow');
    var mismatchInput = document.getElementById('gradeMismatchReason');
    var submitBtn = document.querySelector('button[type="submit"]');
    
    if (!warningEl) {
        warningEl = document.createElement('div');
        warningEl.id = 'ageGradeWarning';
        warningEl.className = 'age-grade-warning';
        gradeSelect.parentNode.appendChild(warningEl);
    }
    
    if (age && grade && gradeAgeMapping[grade]) {
        var mapping = gradeAgeMapping[grade];
        
        if (age < mapping.min) {
            // Too young - BLOCK
            warningEl.innerHTML = '<strong>❌ Not Allowed:</strong> Age ' + age + ' is too young for ' + mapping.label + '. Minimum age is ' + mapping.min + '.';
            warningEl.style.display = 'block';
            warningEl.style.background = '#FEE2E2';
            warningEl.style.borderColor = '#EF4444';
            warningEl.style.color = '#991B1B';
            mismatchRow.style.display = 'none';
            mismatchInput.required = false;
            if (submitBtn) submitBtn.disabled = true;
        } else if (age > mapping.max + 1) {
            // 2+ years older - BLOCK
            warningEl.innerHTML = '<strong>❌ Not Allowed:</strong> Age ' + age + ' is too old for ' + mapping.label + '. Maximum allowed is ' + (mapping.max + 1) + ' (1 year older with reason).';
            warningEl.style.display = 'block';
            warningEl.style.background = '#FEE2E2';
            warningEl.style.borderColor = '#EF4444';
            warningEl.style.color = '#991B1B';
            mismatchRow.style.display = 'none';
            mismatchInput.required = false;
            if (submitBtn) submitBtn.disabled = true;
        } else if (age === mapping.max + 1) {
            // Exactly 1 year older - Allow with reason
            warningEl.innerHTML = '<strong>⚠️ Age Mismatch:</strong> Age ' + age + ' is 1 year older than expected for ' + mapping.label + ' (Expected Age ' + mapping.min + '-' + mapping.max + ')';
            warningEl.style.display = 'block';
            warningEl.style.background = '#FEF3C7';
            warningEl.style.borderColor = '#F59E0B';
            warningEl.style.color = '#92400E';
            mismatchRow.style.display = 'block';
            mismatchInput.required = true;
            document.getElementById('mismatchHint').textContent = 'Student is 1 year older than expected. Please provide a reason.';
            if (submitBtn) submitBtn.disabled = false;
        } else {
            // Age within range (min to max) - OK
            warningEl.style.display = 'none';
            mismatchRow.style.display = 'none';
            mismatchInput.required = false;
            mismatchInput.value = '';
            if (submitBtn) submitBtn.disabled = false;
        }
    } else {
        warningEl.style.display = 'none';
        if (mismatchRow) {
            mismatchRow.style.display = 'none';
            mismatchInput.required = false;
        }
        if (submitBtn) submitBtn.disabled = false;
    }
}

// Listen for grade change
document.addEventListener('DOMContentLoaded', function() {
    var gradeSelect = document.querySelector('select[name="grade"]');
    if (gradeSelect) {
        gradeSelect.addEventListener('change', validateAgeGrade);
    }
    var ageField = document.getElementById('ageField');
    if (ageField) {
        ageField.addEventListener('change', validateAgeGrade);
        ageField.addEventListener('input', validateAgeGrade);
    }
    // Initial validation - check if reason should be shown on edit
    validateAgeGrade();
    
    // If editing and has mismatch reason, keep it visible
    @if($isEdit && $student?->grade_mismatch_reason)
    document.getElementById('gradeMismatchRow').style.display = 'block';
    @endif
});

// Add new school
function addNewSchool() {
    var name = prompt('Enter new school name:');
    if (name && name.trim()) {
        fetch('{{ route("admin.studentsponsorship.school-students.add-school") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ name: name.trim() })
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                var select = document.getElementById('schoolSelect');
                var option = new Option(data.name, data.id, true, true);
                select.add(option);
                select.value = data.id;
                // Refresh searchable select
                if (select.searchableSelect) {
                    select.searchableSelect.options = Array.from(select.options);
                    select.searchableSelect.updateDisplay();
                }
                alert('School "' + data.name + '" added successfully!');
            }
        })
        .catch(err => {
            alert('Error adding school');
        });
    }
}

// Add new bank
function addNewBank() {
    var name = prompt('Enter new bank name:');
    if (name && name.trim()) {
        fetch('{{ route("admin.studentsponsorship.school-students.add-bank") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ name: name.trim() })
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                var select = document.getElementById('bankSelect');
                var option = new Option(data.name, data.id, true, true);
                select.add(option);
                select.value = data.id;
                // Refresh searchable select
                if (select.searchableSelect) {
                    select.searchableSelect.options = Array.from(select.options);
                    select.searchableSelect.updateDisplay();
                }
                alert('Bank "' + data.name + '" added successfully!');
            } else {
                alert(data.message || 'Failed to add bank');
            }
        })
        .catch(err => {
            alert('Error adding bank');
        });
    }
}

// Update phone prefix - always +94 for Sri Lanka
function updatePhonePrefix() {
    var prefix = document.getElementById('phonePrefix');
    if (prefix) {
        prefix.textContent = '+94';
    }
}

// Initialize phone prefix and numeric inputs on page load
document.addEventListener('DOMContentLoaded', function() {
    updatePhonePrefix();
    
    // Numeric-only inputs (integers only)
    document.querySelectorAll('.numeric-only').forEach(function(input) {
        input.addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
        input.addEventListener('keypress', function(e) {
            if (!/[0-9]/.test(e.key)) {
                e.preventDefault();
            }
        });
    });
    
    // Decimal-only inputs (numbers with decimal point)
    document.querySelectorAll('.decimal-only').forEach(function(input) {
        input.addEventListener('input', function(e) {
            // Allow only numbers and one decimal point
            var value = this.value;
            value = value.replace(/[^0-9.]/g, '');
            // Ensure only one decimal point
            var parts = value.split('.');
            if (parts.length > 2) {
                value = parts[0] + '.' + parts.slice(1).join('');
            }
            this.value = value;
        });
        input.addEventListener('keypress', function(e) {
            var char = e.key;
            if (char === '.' && this.value.includes('.')) {
                e.preventDefault();
            } else if (!/[0-9.]/.test(char)) {
                e.preventDefault();
            }
        });
    });
});

// ============ SEARCHABLE SELECT ============
class SearchableSelect {
    constructor(selectEl) {
        this.select = selectEl;
        this.options = Array.from(selectEl.options);
        this.wrapper = document.createElement('div');
        this.wrapper.className = 'searchable-select';
        this.selectedValue = selectEl.value;
        
        // Check if inside input-with-btn wrapper
        this.hasAddButton = selectEl.parentNode.classList.contains('input-with-btn');
        
        // Create display input (shows selected value)
        this.display = document.createElement('div');
        this.display.className = 'ss-display';
        
        // Arrow icon
        this.arrow = document.createElement('span');
        this.arrow.className = 'ss-arrow';
        this.arrow.innerHTML = '▼';
        
        // Dropdown container
        this.dropdown = document.createElement('div');
        this.dropdown.className = 'ss-dropdown';
        
        // Search input inside dropdown
        this.searchInput = document.createElement('input');
        this.searchInput.type = 'text';
        this.searchInput.className = 'ss-search';
        this.searchInput.placeholder = 'Type to search...';
        this.searchInput.autocomplete = 'off';
        
        // Options container
        this.optionsContainer = document.createElement('div');
        this.optionsContainer.className = 'ss-options';
        
        this.dropdown.appendChild(this.searchInput);
        this.dropdown.appendChild(this.optionsContainer);
        
        // Build structure - handle input-with-btn case
        this.select.style.display = 'none';
        
        if (this.hasAddButton) {
            // Insert wrapper in place of select, keep button after
            var parent = this.select.parentNode;
            parent.insertBefore(this.wrapper, this.select);
            this.wrapper.appendChild(this.display);
            this.wrapper.appendChild(this.arrow);
            this.wrapper.appendChild(this.dropdown);
            this.wrapper.appendChild(this.select);
            // Make wrapper take flex space
            this.wrapper.style.flex = '1';
        } else {
            this.select.parentNode.insertBefore(this.wrapper, this.select);
            this.wrapper.appendChild(this.display);
            this.wrapper.appendChild(this.arrow);
            this.wrapper.appendChild(this.dropdown);
            this.wrapper.appendChild(this.select);
        }
        
        // Set initial value
        this.updateDisplay();
        this.renderOptions('');
        this.bindEvents();
    }
    
    updateDisplay() {
        var currentValue = String(this.select.value);
        if (currentValue && currentValue !== '') {
            var selectedOpt = this.options.find(o => String(o.value) === currentValue);
            if (selectedOpt && selectedOpt.value) {
                this.display.textContent = selectedOpt.text;
                this.display.classList.remove('placeholder');
            } else {
                this.display.textContent = this.options[0]?.text || 'Select...';
                this.display.classList.add('placeholder');
            }
        } else {
            this.display.textContent = this.options[0]?.text || 'Select...';
            this.display.classList.add('placeholder');
        }
    }
    
    renderOptions(filter) {
        this.optionsContainer.innerHTML = '';
        var filtered = this.options.filter(o => {
            if (!o.value) return false; // Skip empty option
            return o.text.toLowerCase().includes(filter.toLowerCase());
        });
        
        if (filtered.length === 0) {
            this.optionsContainer.innerHTML = '<div class="ss-no-results">No results found</div>';
            return;
        }
        
        filtered.forEach(opt => {
            var div = document.createElement('div');
            div.className = 'ss-option' + (opt.value === this.select.value ? ' selected' : '');
            div.textContent = opt.text;
            div.dataset.value = opt.value;
            div.addEventListener('click', (e) => {
                e.stopPropagation();
                this.selectOption(opt);
            });
            this.optionsContainer.appendChild(div);
        });
    }
    
    selectOption(opt) {
        this.select.value = opt.value;
        this.updateDisplay();
        this.close();
        // Trigger change event
        this.select.dispatchEvent(new Event('change', { bubbles: true }));
    }
    
    open() {
        if (this.wrapper.classList.contains('open')) return;
        this.wrapper.classList.add('open');
        this.searchInput.value = '';
        this.renderOptions('');
        setTimeout(() => this.searchInput.focus(), 10);
    }
    
    close() {
        this.wrapper.classList.remove('open');
        this.searchInput.value = '';
    }
    
    bindEvents() {
        // Click on display opens dropdown
        this.display.addEventListener('click', (e) => {
            e.stopPropagation();
            if (this.wrapper.classList.contains('open')) {
                this.close();
            } else {
                this.open();
            }
        });
        
        this.arrow.addEventListener('click', (e) => {
            e.stopPropagation();
            if (this.wrapper.classList.contains('open')) {
                this.close();
            } else {
                this.open();
            }
        });
        
        // Typing in search filters options
        this.searchInput.addEventListener('input', () => {
            this.renderOptions(this.searchInput.value);
        });
        
        // Prevent closing when clicking inside dropdown
        this.dropdown.addEventListener('click', (e) => {
            e.stopPropagation();
        });
        
        // Close on click outside
        document.addEventListener('click', (e) => {
            if (!this.wrapper.contains(e.target)) {
                this.close();
            }
        });
        
        // Keyboard navigation
        this.searchInput.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                this.close();
            } else if (e.key === 'Enter') {
                e.preventDefault();
                var highlighted = this.optionsContainer.querySelector('.ss-option.highlighted') || this.optionsContainer.querySelector('.ss-option');
                if (highlighted) {
                    var opt = this.options.find(o => o.value === highlighted.dataset.value);
                    if (opt) this.selectOption(opt);
                }
            } else if (e.key === 'ArrowDown' || e.key === 'ArrowUp') {
                e.preventDefault();
                var opts = Array.from(this.optionsContainer.querySelectorAll('.ss-option'));
                var current = this.optionsContainer.querySelector('.ss-option.highlighted');
                var idx = current ? opts.indexOf(current) : -1;
                if (current) current.classList.remove('highlighted');
                if (e.key === 'ArrowDown') {
                    idx = (idx + 1) % opts.length;
                } else {
                    idx = idx <= 0 ? opts.length - 1 : idx - 1;
                }
                if (opts[idx]) {
                    opts[idx].classList.add('highlighted');
                    opts[idx].scrollIntoView({ block: 'nearest' });
                }
            }
        });
    }
}

// Initialize searchable selects on page load
document.addEventListener('DOMContentLoaded', function() {
    // Make ALL selects searchable
    var allSelects = document.querySelectorAll('select.form-select');
    allSelects.forEach(function(el) {
        // Skip current_state as it only has 2 options, and skip rcTerm (report card term)
        if (el.name !== 'current_state' && el.id !== 'rcTerm') {
            var ss = new SearchableSelect(el);
            el.searchableSelect = ss; // Store reference for later use
        }
    });
});

// ============ REPORT CARD FUNCTIONS ============
@if($isEdit && $student)

// Allowed file types and max size
var ALLOWED_TYPES = ['application/pdf', 'image/jpeg', 'image/png'];
var ALLOWED_EXTENSIONS = ['pdf', 'jpg', 'jpeg', 'png'];
var MAX_FILE_SIZE = 5 * 1024 * 1024; // 5MB

function validateUploadFile(file) {
    // Check file extension
    var ext = file.name.split('.').pop().toLowerCase();
    if (!ALLOWED_EXTENSIONS.includes(ext)) {
        return { valid: false, error: 'Invalid file type. Allowed: PDF, JPG, PNG' };
    }
    
    // Check MIME type
    if (!ALLOWED_TYPES.includes(file.type)) {
        return { valid: false, error: 'Invalid file type. Allowed: PDF, JPG, PNG' };
    }
    
    // Check file size
    if (file.size > MAX_FILE_SIZE) {
        return { valid: false, error: 'File too large. Maximum 5MB allowed.' };
    }
    
    // Check for empty file
    if (file.size === 0) {
        return { valid: false, error: 'File appears to be empty.' };
    }
    
    return { valid: true };
}

function sanitizeFilename(name) {
    // Remove dangerous characters
    return name.replace(/[<>:"\/\\|?*\x00-\x1F]/g, '').trim();
}

function uploadReportCardFile() {
    var msgDiv = document.getElementById('rcUploadMessage');
    var btn = document.getElementById('rcUploadBtn');
    var btnText = document.getElementById('rcUploadBtnText');
    
    var filename = sanitizeFilename(document.getElementById('rcFilename').value.trim());
    var term = document.getElementById('rcTerm').value;
    var uploadDate = document.getElementById('rcUploadDate').value;
    var fileInput = document.getElementById('rcFileInput');
    
    // Validate filename
    if (!filename || filename.length < 2) {
        showRcMessage('Please enter a valid filename (at least 2 characters)', 'error');
        document.getElementById('rcFilename').focus();
        return;
    }
    if (filename.length > 255) {
        showRcMessage('Filename is too long (max 255 characters)', 'error');
        return;
    }
    
    // Validate term
    if (!term || !['Term1', 'Term2', 'Term3'].includes(term)) {
        showRcMessage('Please select a valid term', 'error');
        document.getElementById('rcTerm').focus();
        return;
    }
    
    // Validate date
    if (!uploadDate) {
        showRcMessage('Please select upload date', 'error');
        return;
    }
    var selectedDate = new Date(uploadDate);
    var today = new Date();
    today.setHours(23, 59, 59, 999);
    if (selectedDate > today) {
        showRcMessage('Upload date cannot be in the future', 'error');
        return;
    }
    
    // Validate file
    if (!fileInput.files || fileInput.files.length === 0) {
        showRcMessage('Please select a file to upload', 'error');
        return;
    }
    
    var file = fileInput.files[0];
    var fileValidation = validateUploadFile(file);
    if (!fileValidation.valid) {
        showRcMessage(fileValidation.error, 'error');
        return;
    }
    
    // Show loading
    btn.disabled = true;
    btnText.textContent = 'Uploading...';
    msgDiv.style.display = 'none';
    
    var formData = new FormData();
    formData.append('_token', '{{ csrf_token() }}');
    formData.append('report_card', file);
    formData.append('title', filename);
    formData.append('term', term);
    formData.append('upload_date', uploadDate);
    
    fetch('{{ route("admin.studentsponsorship.school-students.upload-report-card", $student->hash_id) }}', {
        method: 'POST',
        body: formData
    })
    .then(function(response) {
        return response.json();
    })
    .then(function(data) {
        if (data.success) {
            showRcMessage('Report card uploaded successfully! Reloading...', 'success');
            setTimeout(function() {
                location.reload();
            }, 1000);
        } else {
            showRcMessage(data.message || 'Upload failed', 'error');
            btn.disabled = false;
            btnText.textContent = 'Upload Report Card';
        }
    })
    .catch(function(error) {
        console.error('Upload error:', error);
        showRcMessage('Upload failed: ' + error.message, 'error');
        btn.disabled = false;
        btnText.textContent = 'Upload Report Card';
    });
}

function deleteReportCardFile(reportCardId) {
    if (!confirm('Delete this report card?')) return;
    
    fetch('{{ url("admin/studentsponsorship/school-students/".$student->hash_id."/report-cards") }}/' + reportCardId, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        }
    })
    .then(function(response) {
        return response.json();
    })
    .then(function(data) {
        if (data.success) {
            showRcMessage('Report card deleted! Reloading...', 'success');
            setTimeout(function() {
                location.reload();
            }, 1000);
        } else {
            showRcMessage(data.message || 'Delete failed', 'error');
        }
    })
    .catch(function(error) {
        showRcMessage('Delete failed: ' + error.message, 'error');
    });
}

function showRcMessage(message, type) {
    var msgDiv = document.getElementById('rcUploadMessage');
    msgDiv.style.display = 'block';
    if (type === 'success') {
        msgDiv.style.background = '#D1FAE5';
        msgDiv.style.border = '1px solid #10B981';
        msgDiv.style.color = '#065F46';
    } else {
        msgDiv.style.background = '#FEE2E2';
        msgDiv.style.border = '1px solid #EF4444';
        msgDiv.style.color = '#991B1B';
    }
    msgDiv.textContent = message;
}
@endif
</script>
