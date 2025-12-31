<style>
    .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 16px; }
    .page-header h1 { font-size: 24px; font-weight: 700; color: var(--text-primary); margin: 0; display: flex; align-items: center; gap: 10px; }
    .page-header h1 svg { width: 28px; height: 28px; color: var(--success); }
    
    .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 24px; }
    .stat-card { background: var(--card-bg); border: 1px solid var(--card-border); border-radius: 12px; padding: 20px; display: flex; align-items: center; gap: 16px; transition: all 0.2s; cursor: pointer; }
    .stat-card:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08); }
    .stat-card.active { border-color: var(--primary); box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2); }
    .stat-icon { width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .stat-icon svg { width: 24px; height: 24px; }
    .stat-icon.total { background: var(--success-light); color: var(--success); }
    .stat-icon.active { background: var(--success-light); color: var(--success); }
    .stat-icon.inactive { background: var(--danger-light); color: var(--danger); }
    .stat-content { flex: 1; }
    .stat-value { font-size: 28px; font-weight: 700; color: var(--text-primary); line-height: 1; }
    .stat-label { font-size: 13px; color: var(--text-muted); margin-top: 4px; }
    
    .table-card { background: var(--card-bg); border: 1px solid var(--card-border); border-radius: 12px; overflow: hidden; }
    .table-card-header { padding: 16px 20px; border-bottom: 1px solid var(--card-border); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; }
    .table-card-title { font-size: 16px; font-weight: 600; color: var(--text-primary); display: flex; align-items: center; gap: 8px; }
    .table-card-title svg { width: 20px; height: 20px; color: var(--text-muted); }
    .table-card-body { padding: 0; }
    
    .filter-section { display: flex; gap: 12px; align-items: center; flex-wrap: wrap; }
    .filter-select { padding: 8px 12px; border: 1px solid var(--input-border); border-radius: 8px; font-size: 13px; background: var(--input-bg); color: var(--input-text); cursor: pointer; min-width: 140px; }

    .student-cell { display: flex; align-items: center; gap: 12px; }
    .student-avatar { width: 55px; height: 55px; border-radius: 50%; object-fit: cover; background: var(--body-bg); border: 2px solid var(--card-border); flex-shrink: 0; }
    .student-info { display: flex; flex-direction: column; gap: 2px; }
    .student-name { font-weight: 600; color: var(--text-primary); font-size: 14px; }
    .student-name a.dt-clickable { color: var(--primary); text-decoration: none; font-weight: 600; }
    .student-name a.dt-clickable:hover { text-decoration: underline; }
    .student-id { font-size: 11px; color: var(--text-muted); }
    .year-badge { display: inline-flex; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; background: #dbeafe; color: #1e40af; }
    .status-active { background: #dcfce7; color: #166534; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; }
    .status-inactive { background: #e5e7eb; color: #4b5563; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; }

    /* Rollback Section */
    .rollback-section {
        background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
        border: 1px solid #fbbf24; border-radius: 16px; padding: 24px; margin-bottom: 24px;
        display: flex; justify-content: space-between; align-items: center; gap: 24px; flex-wrap: wrap;
        box-shadow: 0 4px 6px -1px rgba(251, 191, 36, 0.1);
    }
    .rollback-info { display: flex; align-items: flex-start; gap: 16px; flex: 1; }
    .rollback-icon {
        width: 48px; height: 48px; border-radius: 12px;
        background: linear-gradient(135deg, #f59e0b, #d97706);
        display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    }
    .rollback-icon svg { width: 24px; height: 24px; color: #fff; }
    .rollback-text h3 { margin: 0 0 4px 0; font-size: 16px; font-weight: 700; color: #92400e; }
    .rollback-text p { margin: 0; font-size: 13px; color: #a16207; line-height: 1.5; }
    .btn-rollback {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        color: #fff; border: none; padding: 14px 28px; border-radius: 10px;
        font-weight: 600; font-size: 14px; cursor: pointer;
        display: inline-flex; align-items: center; gap: 10px;
        box-shadow: 0 4px 14px rgba(245, 158, 11, 0.4); transition: all 0.3s ease;
    }
    .btn-rollback:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(245, 158, 11, 0.5); }
    .btn-rollback svg { width: 18px; height: 18px; }

    /* Modal Overlay */
    .modal-overlay {
        position: fixed; top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(0, 0, 0, 0.6); backdrop-filter: blur(4px);
        display: none; justify-content: center; align-items: center; z-index: 10000; padding: 20px;
    }
    .modal-overlay.active { display: flex; animation: fadeIn 0.2s ease; }
    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }

    .modal-box {
        background: var(--card-bg, #fff); border-radius: 20px; width: 100%; max-width: 480px;
        overflow: hidden; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); animation: slideUp 0.3s ease;
    }
    @keyframes slideUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }

    .modal-header { padding: 24px 24px 20px; display: flex; align-items: center; gap: 16px; border-bottom: 1px solid var(--card-border, #e5e7eb); }
    .modal-header-icon { width: 56px; height: 56px; border-radius: 16px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .modal-header-icon.warning { background: linear-gradient(135deg, #fef3c7, #fde68a); }
    .modal-header-icon.warning svg { color: #d97706; }
    .modal-header-icon.processing { background: linear-gradient(135deg, #dbeafe, #bfdbfe); }
    .modal-header-icon.processing svg { color: #2563eb; }
    .modal-header-icon.success { background: linear-gradient(135deg, #dcfce7, #bbf7d0); }
    .modal-header-icon.success svg { color: #16a34a; }
    .modal-header-icon.error { background: linear-gradient(135deg, #fee2e2, #fecaca); }
    .modal-header-icon.error svg { color: #dc2626; }
    .modal-header-icon svg { width: 28px; height: 28px; }
    .modal-header-text h3 { margin: 0 0 4px 0; font-size: 18px; font-weight: 700; color: var(--text-primary, #1f2937); }
    .modal-header-text p { margin: 0; font-size: 13px; color: var(--text-muted, #6b7280); }
    .modal-body { padding: 24px; }

    /* Confirm List */
    .confirm-list { list-style: none; padding: 0; margin: 0; }
    .confirm-list li { padding: 12px 16px; margin-bottom: 8px; background: var(--body-bg, #f9fafb); border-radius: 10px; display: flex; align-items: center; gap: 12px; font-size: 14px; color: var(--text-primary, #374151); }
    .confirm-list li:last-child { margin-bottom: 0; }
    .confirm-list li svg { width: 20px; height: 20px; color: #f59e0b; flex-shrink: 0; }
    .confirm-warning { background: #fef2f2; border: 1px solid #fecaca; border-radius: 10px; padding: 12px 16px; margin-top: 16px; display: flex; align-items: flex-start; gap: 10px; font-size: 13px; color: #991b1b; }
    .confirm-warning svg { width: 18px; height: 18px; flex-shrink: 0; margin-top: 1px; }

    /* Progress Steps */
    .progress-steps { margin-bottom: 24px; }
    .progress-step { display: flex; align-items: center; gap: 14px; padding: 14px 0; border-bottom: 1px solid var(--card-border, #e5e7eb); }
    .progress-step:last-child { border-bottom: none; }
    .progress-step-icon { width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; transition: all 0.3s ease; }
    .progress-step-icon.pending { background: #f3f4f6; color: #9ca3af; }
    .progress-step-icon.active { background: #dbeafe; color: #2563eb; animation: pulse 1.5s infinite; }
    .progress-step-icon.done { background: #dcfce7; color: #16a34a; }
    .progress-step-icon svg { width: 18px; height: 18px; }
    @keyframes pulse { 0%, 100% { opacity: 1; } 50% { opacity: 0.6; } }
    .progress-step-text { flex: 1; }
    .progress-step-text .title { font-size: 14px; font-weight: 600; color: var(--text-primary, #374151); }
    .progress-step-text .subtitle { font-size: 12px; color: var(--text-muted, #6b7280); margin-top: 2px; }
    .progress-bar-container { background: #e5e7eb; border-radius: 8px; height: 8px; overflow: hidden; margin-top: 8px; }
    .progress-bar-fill { height: 100%; border-radius: 8px; background: linear-gradient(90deg, #3b82f6, #2563eb); transition: width 0.5s ease; }

    /* Result Stats */
    .result-stats { display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px; margin-bottom: 20px; }
    .result-stat { background: var(--body-bg, #f9fafb); border-radius: 12px; padding: 16px; text-align: center; }
    .result-stat-value { font-size: 32px; font-weight: 700; color: #10b981; }
    .result-stat-label { font-size: 12px; color: var(--text-muted, #6b7280); margin-top: 4px; }
    .result-details { list-style: none; padding: 0; margin: 0; }
    .result-details li { display: flex; align-items: center; gap: 10px; padding: 10px 0; border-bottom: 1px solid var(--card-border, #e5e7eb); font-size: 14px; color: var(--text-primary, #374151); }
    .result-details li:last-child { border-bottom: none; }
    .result-details li svg { width: 18px; height: 18px; color: #10b981; }

    /* Modal Footer */
    .modal-footer { padding: 16px 24px; border-top: 1px solid var(--card-border, #e5e7eb); display: flex; gap: 12px; justify-content: flex-end; }
    .modal-btn { padding: 12px 24px; border-radius: 10px; font-weight: 600; font-size: 14px; cursor: pointer; border: none; transition: all 0.2s; }
    .modal-btn.secondary { background: #f3f4f6; color: #374151; }
    .modal-btn.secondary:hover { background: #e5e7eb; }
    .modal-btn.primary { background: linear-gradient(135deg, #f59e0b, #d97706); color: #fff; }
    .modal-btn.primary:hover { box-shadow: 0 4px 12px rgba(245, 158, 11, 0.4); }
    .modal-btn.success { background: linear-gradient(135deg, #10b981, #059669); color: #fff; }
    .modal-btn.success:hover { box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4); }

    @media (max-width: 768px) {
        .rollback-section { flex-direction: column; align-items: stretch; }
        .btn-rollback { width: 100%; justify-content: center; }
        .result-stats { grid-template-columns: 1fr; }
    }
</style>

<div style="padding: 20px;">
    <div class="page-header">
        <h1><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>Completed University Students</h1>
    </div>

    <div class="stats-grid">
        <div class="stat-card active" data-filter="all" onclick="filterByStatus('')">
            <div class="stat-icon total"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></div>
            <div class="stat-content"><div class="stat-value">{{ $stats['total'] ?? 0 }}</div><div class="stat-label">Total Completed</div></div>
        </div>
        <div class="stat-card" data-filter="1" onclick="filterByStatus('1')">
            <div class="stat-icon active"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></div>
            <div class="stat-content"><div class="stat-value">{{ $stats['active'] ?? 0 }}</div><div class="stat-label">Active</div></div>
        </div>
        <div class="stat-card" data-filter="0" onclick="filterByStatus('0')">
            <div class="stat-icon inactive"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></div>
            <div class="stat-content"><div class="stat-value">{{ $stats['inactive'] ?? 0 }}</div><div class="stat-label">Inactive</div></div>
        </div>
    </div>

    <div class="rollback-section">
        <div class="rollback-info">
            <div class="rollback-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" /></svg></div>
            <div class="rollback-text">
                <h3>New Academic Year Rollback</h3>
                <p>Process all in-progress students: mark as completed, promote year, remove portal access, set inactive.</p>
            </div>
        </div>
        <button type="button" class="btn-rollback" onclick="showConfirmModal()">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" /></svg>
            Start Rollback
        </button>
    </div>

    <div class="table-card">
        <div class="table-card-header">
            <div class="table-card-title"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>Completed Students List</div>
            <div class="filter-section">
                <select class="filter-select" id="completedYearFilter" data-dt-filter="completed_year" data-dt-table="completedStudentsTable">
                    <option value="">All Completed Years</option>
                    @php $currentYear = date('Y'); @endphp
                    @for($y = $currentYear; $y >= $currentYear - 10; $y--)
                        <option value="{{ $y }}">{{ $y }}</option>
                    @endfor
                </select>
                <select class="filter-select" id="statusFilter" data-dt-filter="status" data-dt-table="completedStudentsTable">
                    <option value="">All Status</option>
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
                <select class="filter-select" id="yearFilter" data-dt-filter="university_year_of_study" data-dt-table="completedStudentsTable">
                    <option value="">All Study Years</option>
                    @foreach($yearsOfStudy as $key => $label)<option value="{{ $key }}">{{ $label }}</option>@endforeach
                </select>
            </div>
        </div>
        <div class="table-card-body">
            <table id="completedStudentsTable" class="dt-table dt-search dt-perpage" data-route="{{ route('admin.studentsponsorship.university-students.completed.data') }}">
                <thead><tr>
                    <th data-col="_row_num" style="width:50px;">#</th>
                    <th class="dt-sort dt-clickable" data-col="name" data-render="student_cell">Student Name</th>
                    <th class="dt-sort" data-col="university_year_of_study" data-render="year_badge">Study Year</th>
                    <th data-col="current_state" data-render="state_badge">State</th>
                    <th class="dt-sort" data-col="completed_year" data-render="completed_year_cell">Completed Year</th>
                    <th data-col="university_name">University</th>
                    <th data-col="active" data-render="status_badge">Status</th>
                    <th data-col="actions" data-render="actions" style="width:80px;">Actions</th>
                </tr></thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

<!-- Confirmation Modal -->
<div class="modal-overlay" id="confirmModal">
    <div class="modal-box">
        <div class="modal-header">
            <div class="modal-header-icon warning"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" /></svg></div>
            <div class="modal-header-text"><h3>Confirm Rollback</h3><p>This action will affect in-progress students</p></div>
        </div>
        <div class="modal-body">
            <ul class="confirm-list">
                <li><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>Mark selected students as <strong>Completed</strong></li>
                <li><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 10.5L12 3m0 0l7.5 7.5M12 3v18" /></svg>Promote study year by <strong>+1</strong></li>
                <li><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M22 10.5h-6m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 0110.374 21c-2.331 0-4.512-.645-6.374-1.766z" /></svg>Remove portal access & <strong>delete user accounts</strong></li>
                <li><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" /></svg>Set students to <strong>Inactive</strong></li>
            </ul>
            <div class="confirm-warning"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" /></svg><span>This action cannot be undone. Please make sure you have a backup.</span></div>
        </div>
        <div class="modal-footer">
            <button class="modal-btn secondary" onclick="hideModal('confirmModal')">Cancel</button>
            <button class="modal-btn primary" onclick="showYearSelect()">Next: Select Year</button>
        </div>
    </div>
</div>

<!-- Year Selection Modal -->
<div class="modal-overlay" id="yearSelectModal">
    <div class="modal-box">
        <div class="modal-header">
            <div class="modal-header-icon" style="background: linear-gradient(135deg, #dbeafe, #bfdbfe);"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="color:#2563eb;"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" /></svg></div>
            <div class="modal-header-text"><h3>Select Completed Year</h3><p>Choose the academic year for this rollback</p></div>
        </div>
        <div class="modal-body">
            <div style="margin-bottom:20px;">
                <label style="display:block;font-weight:600;margin-bottom:8px;color:var(--text-primary);">Completed Year <span style="color:#dc2626;">*</span></label>
                <select id="rollbackCompletedYear" style="width:100%;padding:12px 16px;border:1px solid var(--input-border,#d1d5db);border-radius:10px;font-size:14px;background:var(--input-bg,#fff);color:var(--input-text,#374151);">
                    @php $currentYear = date('Y'); @endphp
                    @for($y = $currentYear; $y >= $currentYear - 5; $y--)
                        <option value="{{ $y }}" {{ $y == $currentYear ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
                <div style="font-size:12px;color:var(--text-muted,#6b7280);margin-top:6px;">This year will be saved with each student record</div>
            </div>
            <div style="background:linear-gradient(135deg, #ecfdf5, #d1fae5);border:1px solid #6ee7b7;border-radius:10px;padding:16px;">
                <div style="font-size:13px;color:#065f46;">
                    <strong>Summary:</strong> All in-progress students will be marked as completed for year <strong id="summaryYear">{{ date('Y') }}</strong>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="modal-btn secondary" onclick="hideModal('yearSelectModal');showModal('confirmModal')">Back</button>
            <button class="modal-btn primary" onclick="startRollback()">Start Rollback</button>
        </div>
    </div>
</div>

<!-- Progress Modal -->
<div class="modal-overlay" id="progressModal">
    <div class="modal-box">
        <div class="modal-header">
            <div class="modal-header-icon processing"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" /></svg></div>
            <div class="modal-header-text"><h3>Processing Rollback</h3><p>Please wait while we process all students...</p></div>
        </div>
        <div class="modal-body">
            <div class="progress-steps">
                <div class="progress-step"><div class="progress-step-icon pending" id="step1Icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" /></svg></div><div class="progress-step-text"><div class="title">Finding Students</div><div class="subtitle" id="step1Text">Searching for in-progress students...</div></div></div>
                <div class="progress-step"><div class="progress-step-icon pending" id="step2Icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 0110.374 21c-2.331 0-4.512-.645-6.374-1.766z" /></svg></div><div class="progress-step-text"><div class="title">Removing Portal Access</div><div class="subtitle" id="step2Text">Waiting...</div></div></div>
                <div class="progress-step"><div class="progress-step-icon pending" id="step3Icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 10.5L12 3m0 0l7.5 7.5M12 3v18" /></svg></div><div class="progress-step-text"><div class="title">Promoting Years</div><div class="subtitle" id="step3Text">Waiting...</div></div></div>
                <div class="progress-step"><div class="progress-step-icon pending" id="step4Icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg></div><div class="progress-step-text"><div class="title">Finalizing</div><div class="subtitle" id="step4Text">Waiting...</div></div></div>
            </div>
            <div class="progress-bar-container"><div class="progress-bar-fill" id="progressBar" style="width: 0%"></div></div>
        </div>
    </div>
</div>

<!-- Result Modal -->
<div class="modal-overlay" id="resultModal">
    <div class="modal-box">
        <div class="modal-header">
            <div class="modal-header-icon success" id="resultIcon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg></div>
            <div class="modal-header-text"><h3 id="resultTitle">Rollback Complete!</h3><p id="resultSubtitle">All students have been processed successfully</p></div>
        </div>
        <div class="modal-body">
            <div class="result-stats">
                <div class="result-stat"><div class="result-stat-value" id="resultStudents">0</div><div class="result-stat-label">Students Processed</div></div>
                <div class="result-stat"><div class="result-stat-value" id="resultUsers">0</div><div class="result-stat-label">Portal Accounts Removed</div></div>
            </div>
            <ul class="result-details">
                <li><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>All students marked as completed</li>
                <li><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>Years promoted by +1</li>
                <li><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>Portal access removed</li>
                <li><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>Students set to inactive</li>
            </ul>
        </div>
        <div class="modal-footer"><button class="modal-btn success" onclick="finishRollback()">Done</button></div>
    </div>
</div>

@include('core::datatable')

<script>
var defaultAvatar = "data:image/svg+xml;base64,PHN2ZyB2aWV3Qm94PSIwIDAgMjIwMCAyMjAwIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciPjxyZWN0IGZpbGw9IiNGRkYiIHdpZHRoPSIyMjAwIiBoZWlnaHQ9IjIyMDAiLz48cGF0aCBmaWxsPSIjOEI1Q0Y2IiBkPSJNMTkwMywxMTAwYzAsMjE1LjUyLTg0LjkxLDQxMS4yMS0yMjMuMSw1NTUuNDRDMTUzMy43NCwxODA4LjAxLDEzMjcuOTYsMTkwMywxMTAwLDE5MDNzLTQzMy43NC05NC45OS01NzkuOS0yNDcuNTZDMzgxLjkxLDE1MTEuMjEsMjk3LDEzMTUuNTIsMjk3LDExMDBjMC00NDMuNDgsMzU5LjUyLTgwMyw4MDMtODAzUzE5MDMsNjU2LjUyLDE5MDMsMTEwMHoiLz48Y2lyY2xlIGZpbGw9IiNGRkYiIGN4PSIxMTAwIiBjeT0iODE1IiByPSIzMjgiLz48cGF0aCBmaWxsPSIjRkZGIiBkPSJNMTY3OS45LDE2NTUuNDRDMTUzMy43NCwxODA4LjAxLDEzMjcuOTYsMTkwMywxMTAwLDE5MDNzLTQzMy43NC05NC45OS01NzkuOS0yNDcuNTZjODIuNTQtMjQwLjkzLDMxMS00MTQuMTIsNTc5LjktNDE0LjEyUzE1OTcuMzYsMTQxNC41MSwxNjc5LjksMTY1NS40NHoiLz48L3N2Zz4=";
var yearLabels = {1:'1st Year',2:'2nd Year',3:'3rd Year',4:'4th Year',5:'5th Year',6:'6th Year'};

window.dtRenders = window.dtRenders || {};

window.dtRenders.student_cell = function(value, row) {
    var avatar = row.profile_photo_url || defaultAvatar;
    var nameHtml = row._show_url ? '<a href="' + row._show_url + '" class="dt-clickable">' + (value || '-') + '</a>' : (value || '-');
    return '<div class="student-cell"><img src="' + avatar + '" alt="" class="student-avatar" onerror="this.src=\'' + defaultAvatar + '\'"><div class="student-info"><div class="student-name">' + nameHtml + '</div><div class="student-id">ID: ' + (row.university_internal_id || '-') + '</div></div></div>';
};

window.dtRenders.year_badge = function(value) { return value ? '<span class="year-badge">' + (yearLabels[value] || 'Year ' + value) + '</span>' : '-'; };
window.dtRenders.completed_year_cell = function(value) { return value ? '<span style="background:#E0E7FF;color:#3730A3;padding:4px 10px;border-radius:12px;font-size:11px;font-weight:600;">' + value + '</span>' : '<span style="color:#9CA3AF;">-</span>'; };
window.dtRenders.state_badge = function(value) { return value === 'complete' ? '<span style="background:#D1FAE5;color:#065F46;padding:4px 10px;border-radius:12px;font-size:11px;font-weight:600;">✓ Complete</span>' : '<span style="background:#FEF3C7;color:#92400E;padding:4px 10px;border-radius:12px;font-size:11px;font-weight:600;">In Progress</span>'; };
window.dtRenders.status_badge = function(value) { return (value == 1 || value === true) ? '<span class="status-active">✓ Active</span>' : '<span class="status-inactive">⊘ Inactive</span>'; };
window.dtRenders.actions = function(value, row) { return row._show_url ? '<a href="' + row._show_url + '" style="padding:6px;border-radius:6px;background:#dbeafe;color:#2563eb;display:inline-flex;" title="View"><svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg></a>' : ''; };

function filterByStatus(status) {
    document.getElementById('statusFilter').value = status;
    document.getElementById('statusFilter').dispatchEvent(new Event('change', { bubbles: true }));
    document.querySelectorAll('.stat-card').forEach(function(c) { c.classList.toggle('active', (status === '' && c.dataset.filter === 'all') || c.dataset.filter === status); });
}

function showModal(id) { document.getElementById(id).classList.add('active'); }
function hideModal(id) { document.getElementById(id).classList.remove('active'); }
function showConfirmModal() { showModal('confirmModal'); }
function showYearSelect() { hideModal('confirmModal'); showModal('yearSelectModal'); }

// Update summary when year changes
document.addEventListener('DOMContentLoaded', function() {
    var yearSelect = document.getElementById('rollbackCompletedYear');
    if (yearSelect) {
        yearSelect.addEventListener('change', function() {
            document.getElementById('summaryYear').textContent = this.value;
        });
    }
});

function setStep(n, status, text) {
    var icon = document.getElementById('step' + n + 'Icon');
    icon.className = 'progress-step-icon ' + status;
    if (text) document.getElementById('step' + n + 'Text').textContent = text;
    if (status === 'done') icon.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>';
}

function startRollback() {
    var completedYear = document.getElementById('rollbackCompletedYear').value;
    
    hideModal('yearSelectModal');
    showModal('progressModal');
    
    [1,2,3,4].forEach(function(i) { setStep(i, 'pending', i === 1 ? 'Searching...' : 'Waiting...'); });
    document.getElementById('progressBar').style.width = '0%';
    
    setStep(1, 'active', 'Searching for in-progress students...');
    document.getElementById('progressBar').style.width = '10%';
    
    setTimeout(function() {
        setStep(1, 'done', 'Students found');
        document.getElementById('progressBar').style.width = '25%';
        setStep(2, 'active', 'Deleting user accounts...');
        
        fetch('{{ route("admin.studentsponsorship.university-students.rollback-all") }}', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json', 'Content-Type': 'application/json' },
            body: JSON.stringify({ completed_year: completedYear })
        })
        .then(function(r) { return r.json(); })
        .then(function(data) {
            if (data.success) {
                setStep(2, 'done', (data.users_deleted || 0) + ' accounts removed');
                document.getElementById('progressBar').style.width = '50%';
                
                setTimeout(function() {
                    setStep(3, 'active', 'Updating years...');
                    document.getElementById('progressBar').style.width = '70%';
                    
                    setTimeout(function() {
                        setStep(3, 'done', 'Years promoted');
                        document.getElementById('progressBar').style.width = '85%';
                        setStep(4, 'active', 'Finalizing...');
                        
                        setTimeout(function() {
                            setStep(4, 'done', 'Complete');
                            document.getElementById('progressBar').style.width = '100%';
                            
                            setTimeout(function() {
                                hideModal('progressModal');
                                document.getElementById('resultStudents').textContent = data.count;
                                document.getElementById('resultUsers').textContent = data.users_deleted || 0;
                                document.getElementById('resultIcon').className = 'modal-header-icon success';
                                document.getElementById('resultIcon').innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>';
                                document.getElementById('resultTitle').textContent = 'Rollback Complete!';
                                document.getElementById('resultSubtitle').textContent = data.count + ' students marked complete for year ' + (data.completed_year || completedYear);
                                showModal('resultModal');
                            }, 500);
                        }, 400);
                    }, 400);
                }, 400);
            } else {
                hideModal('progressModal');
                document.getElementById('resultIcon').className = 'modal-header-icon error';
                document.getElementById('resultIcon').innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" /></svg>';
                document.getElementById('resultTitle').textContent = 'Rollback Failed';
                document.getElementById('resultSubtitle').textContent = data.message || 'An error occurred';
                document.getElementById('resultStudents').textContent = '0';
                document.getElementById('resultUsers').textContent = '0';
                showModal('resultModal');
            }
        })
        .catch(function(err) {
            hideModal('progressModal');
            document.getElementById('resultIcon').className = 'modal-header-icon error';
            document.getElementById('resultTitle').textContent = 'Error';
            document.getElementById('resultSubtitle').textContent = err.message;
            showModal('resultModal');
        });
    }, 800);
}

function finishRollback() { hideModal('resultModal'); location.reload(); }
</script>
