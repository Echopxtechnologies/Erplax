<style>
    .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 16px; }
    .page-header h1 { font-size: 24px; font-weight: 700; color: var(--text-primary); margin: 0; display: flex; align-items: center; gap: 10px; }
    .page-header h1 svg { width: 28px; height: 28px; color: var(--primary); }
    .btn-add { display: inline-flex; align-items: center; gap: 8px; background: linear-gradient(135deg, var(--primary), var(--primary-hover)); color: #fff; padding: 10px 20px; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 14px; box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3); transition: all 0.2s; }
    .btn-add:hover { transform: translateY(-2px); box-shadow: 0 6px 16px rgba(59, 130, 246, 0.4); color: #fff; }
    .btn-add svg { width: 18px; height: 18px; }
    
    .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 24px; }
    .stat-card { background: var(--card-bg); border: 1px solid var(--card-border); border-radius: 12px; padding: 20px; display: flex; align-items: center; gap: 16px; transition: all 0.2s; cursor: pointer; }
    .stat-card:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08); }
    .stat-card.active { border-color: var(--primary); box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2); }
    .stat-icon { width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .stat-icon svg { width: 24px; height: 24px; }
    .stat-icon.total { background: var(--primary-light); color: var(--primary); }
    .stat-icon.active { background: var(--success-light); color: var(--success); }
    .stat-icon.inactive { background: var(--danger-light); color: var(--danger); }
    .stat-icon.sponsored { background: #fef3c7; color: #d97706; }
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
    .filter-select:focus { outline: none; border-color: var(--primary); }

    /* Student Cell Custom Style */
    .student-cell { display: flex; align-items: center; gap: 12px; }
    .student-avatar { width: 55px; height: 55px; border-radius: 50%; object-fit: cover; background: var(--body-bg); border: 2px solid var(--card-border); flex-shrink: 0; }
    .student-info { display: flex; flex-direction: column; gap: 2px; }
    .student-name { font-weight: 600; color: var(--text-primary); font-size: 14px; }
    .student-name a.dt-clickable { color: var(--primary); text-decoration: none; font-weight: 600; }
    .student-name a.dt-clickable:hover { text-decoration: underline; }
    .student-id { font-size: 11px; color: var(--text-muted); }
    
    /* Year Badge */
    .year-badge { display: inline-flex; align-items: center; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; background: #dbeafe; color: #1e40af; white-space: nowrap; }
    
    /* Status Badge */
    .status-active { background: #dcfce7; color: #166534; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; }
    .status-inactive { background: #e5e7eb; color: #4b5563; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; }
    
    /* Sponsor Badge */
    .sponsor-badge { display: inline-flex; align-items: center; gap: 4px; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 600; background: #fef3c7; color: #92400e; }
    .sponsor-badge.none { background: #f3f4f6; color: #6b7280; }
    
    /* Mobile Responsive */
    @media (max-width: 768px) {
        div[style*="padding: 20px"] { padding: 12px !important; }
        
        .page-header { flex-direction: column; align-items: flex-start; gap: 12px; }
        .page-header h1 { font-size: 20px; }
        .page-header h1 svg { width: 24px; height: 24px; }
        .btn-add { width: 100%; justify-content: center; padding: 12px 20px; }
        
        .stats-grid { grid-template-columns: 1fr 1fr; gap: 12px; }
        .stat-card { padding: 16px; gap: 12px; }
        .stat-icon { width: 40px; height: 40px; }
        .stat-icon svg { width: 20px; height: 20px; }
        .stat-value { font-size: 24px; }
        .stat-label { font-size: 12px; }
        
        .table-card { border-radius: 8px; }
        .table-card-header { padding: 12px 16px; flex-direction: column; align-items: flex-start; }
        .table-card-title { font-size: 14px; }
        .filter-section { width: 100%; }
        .filter-select { flex: 1; min-width: auto; }
        
        .student-cell { gap: 8px; }
        .student-avatar { width: 45px; height: 45px; }
        .student-name { font-size: 13px; }
        .student-id { font-size: 10px; }
        
        .year-badge, .status-active, .status-inactive { padding: 3px 8px; font-size: 11px; }
    }
    
    @media (max-width: 480px) {
        .stats-grid { grid-template-columns: repeat(2, 1fr); gap: 8px; }
        .stat-card { padding: 12px 8px; flex-direction: column; text-align: center; gap: 8px; }
        .stat-icon { width: 36px; height: 36px; }
        .stat-value { font-size: 20px; }
        .stat-label { font-size: 11px; }
    }
</style>

<div style="padding: 20px;">
    <!-- Header -->
    <div class="page-header">
        <h1>
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
            {{ $pageTitle ?? 'University Students' }}
        </h1>
        <a href="{{ route('admin.studentsponsorship.university-students.create') }}" class="btn-add">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4"></path></svg>
            Add New Student
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="stats-grid">
        <div class="stat-card active" data-filter="all" onclick="filterByStatus('')">
            <div class="stat-icon total"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path></svg></div>
            <div class="stat-content"><div class="stat-value" id="stat-total">{{ $stats['total'] ?? 0 }}</div><div class="stat-label">Total Students</div></div>
        </div>
        <div class="stat-card" data-filter="1" onclick="filterByStatus('1')">
            <div class="stat-icon active"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></div>
            <div class="stat-content"><div class="stat-value" id="stat-active">{{ $stats['active'] ?? 0 }}</div><div class="stat-label">Active</div></div>
        </div>
        <div class="stat-card" data-filter="0" onclick="filterByStatus('0')">
            <div class="stat-icon inactive"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></div>
            <div class="stat-content"><div class="stat-value" id="stat-inactive">{{ $stats['inactive'] ?? 0 }}</div><div class="stat-label">Inactive</div></div>
        </div>
    </div>

    <!-- Table Card -->
    <div class="table-card">
        <div class="table-card-header">
            <div class="table-card-title">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                Student List
            </div>
            
            <!-- Filters -->
            <div class="filter-section">
                <input type="hidden" id="currentStateFilter" data-dt-filter="current_state" data-dt-table="universityStudentsTable" value="{{ $currentState ?? 'inprogress' }}">
                
                <select class="filter-select" id="statusFilter" data-dt-filter="status" data-dt-table="universityStudentsTable">
                    <option value="">All Status</option>
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
                
                <select class="filter-select" id="yearFilter" data-dt-filter="year_of_study" data-dt-table="universityStudentsTable">
                    <option value="">All Years</option>
                    @foreach($yearsOfStudy as $key => $label)
                        <option value="{{ $key }}">{{ $label }}</option>
                    @endforeach
                </select>
                
                @if($universities->count() > 0)
                <select class="filter-select" id="universityFilter" data-dt-filter="university_id" data-dt-table="universityStudentsTable">
                    <option value="">All Universities</option>
                    @foreach($universities as $uni)
                        <option value="{{ $uni->id }}">{{ $uni->name }}</option>
                    @endforeach
                </select>
                @endif
                
                @if($programs->count() > 0)
                <select class="filter-select" id="programFilter" data-dt-filter="program_id" data-dt-table="universityStudentsTable">
                    <option value="">All Programs</option>
                    @foreach($programs as $prog)
                        <option value="{{ $prog->id }}">{{ $prog->name }}</option>
                    @endforeach
                </select>
                @endif
            </div>
        </div>
        <div class="table-card-body">
            <!-- DataTable with all features: search, export (CSV, XLSX, PDF), import, per page, checkbox -->
            <table id="universityStudentsTable" 
                   class="dt-table dt-search dt-export dt-import dt-perpage dt-checkbox" 
                   data-route="{{ route('admin.studentsponsorship.university-students.data') }}"
                   data-import-route="{{ route('admin.studentsponsorship.university-students.import') }}"
                   data-template-route="{{ route('admin.studentsponsorship.university-students.template') }}">
                <thead>
                    <tr>
                        <th data-col="_row_num" style="width:50px;">#</th>
                        <th class="dt-sort dt-clickable" data-col="name" data-render="student_cell">Student Name</th>
                        <th data-col="university_name">University</th>
                        <th data-col="program_name">Program</th>
                        <th class="dt-sort" data-col="university_year_of_study" data-render="year_badge">Year</th>
                        <th data-col="current_state" data-render="state_badge">State</th>
                        <th data-col="sponsors_names" data-render="sponsors_cell">Sponsors</th>
                        <th class="dt-sort" data-col="active" data-render="status_badge">Status</th>
                        <th data-render="actions" style="width:100px;">Actions</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

@include('core::datatable')

<script>
    // Default avatar as base64 encoded SVG
    var defaultAvatar = "data:image/svg+xml;base64,PHN2ZyB2aWV3Qm94PSIwIDAgMjIwMCAyMjAwIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciPjxyZWN0IGZpbGw9IiNGRkZGRkYiIHdpZHRoPSIyMjAwIiBoZWlnaHQ9IjIyMDAiLz48ZGVmcz48cmFkaWFsR3JhZGllbnQgaWQ9ImcxIiBjeD0iMzMuNyUiIGN5PSIzMy41JSIgcj0iNTcuMSUiPjxzdG9wIG9mZnNldD0iMCIgc3RvcC1jb2xvcj0iIzYzREZGQyIvPjxzdG9wIG9mZnNldD0iMSIgc3RvcC1jb2xvcj0iIzNGN0NEMSIvPjwvcmFkaWFsR3JhZGllbnQ+PHJhZGlhbEdyYWRpZW50IGlkPSJnMiIgY3g9IjQ2LjUlIiBjeT0iMzEuOCUiIHI9IjIxLjglIj48c3RvcCBvZmZzZXQ9IjAiIHN0b3AtY29sb3I9IiNGRkZGRkYiLz48c3RvcCBvZmZzZXQ9IjEiIHN0b3AtY29sb3I9IiNEMUQxRDEiLz48L3JhZGlhbEdyYWRpZW50PjxyYWRpYWxHcmFkaWVudCBpZD0iZzMiIGN4PSI0My45JSIgY3k9IjY2LjIlIiByPSIzMC43JSI+PHN0b3Agb2Zmc2V0PSIwIiBzdG9wLWNvbG9yPSIjRkZGRkZGIi8+PHN0b3Agb2Zmc2V0PSIxIiBzdG9wLWNvbG9yPSIjRDFEMUQxIi8+PC9yYWRpYWxHcmFkaWVudD48L2RlZnM+PHBhdGggZmlsbD0idXJsKCNnMSkiIGQ9Ik0xOTAzLDExMDBjMCwyMTUuNTItODQuOTEsNDExLjIxLTIyMy4xLDU1NS40NEMxNTMzLjc0LDE4MDguMDEsMTMyNy45NiwxOTAzLDExMDAsMTkwM3MtNDMzLjc0LTk0Ljk5LTU3OS45LTI0Ny41NkMzODEuOTEsMTUxMS4yMSwyOTcsMTMxNS41MiwyOTcsMTEwMGMwLTQ0My40OCwzNTkuNTItODAzLDgwMy04MDNTMTkwMyw2NTYuNTIsMTkwMywxMTAweiIvPjxjaXJjbGUgZmlsbD0idXJsKCNnMikiIGN4PSIxMTAwIiBjeT0iODE1IiByPSIzMjgiLz48cGF0aCBmaWxsPSJ1cmwoI2czKSIgZD0iTTE2NzkuOSwxNjU1LjQ0QzE1MzMuNzQsMTgwOC4wMSwxMzI3Ljk2LDE5MDMsMTEwMCwxOTAzcy00MzMuNzQtOTQuOTktNTc5LjktMjQ3LjU2YzgyLjU0LTI0MC45MywzMTEtNDE0LjEyLDU3OS45LTQxNC4xMlMxNTk3LjM2LDE0MTQuNTEsMTY3OS45LDE2NTUuNDR6Ii8+PC9zdmc+";

    // Year labels for display
    var yearLabels = {
        '1Y1S': 'Year 1 - Sem 1',
        '1Y2S': 'Year 1 - Sem 2',
        '2Y1S': 'Year 2 - Sem 1',
        '2Y2S': 'Year 2 - Sem 2',
        '3Y1S': 'Year 3 - Sem 1',
        '3Y2S': 'Year 3 - Sem 2',
        '4Y1S': 'Year 4 - Sem 1',
        '4Y2S': 'Year 4 - Sem 2',
        '5Y1S': 'Year 5 - Sem 1',
        '5Y2S': 'Year 5 - Sem 2'
    };

    // Custom renders
    window.dtRenders = window.dtRenders || {};
    
    // Student cell with photo, name, ID
    window.dtRenders.student_cell = function(value, row) {
        var avatar = row.profile_photo_url || defaultAvatar;
        var nameHtml = row._show_url 
            ? '<a href="' + row._show_url + '" class="dt-clickable">' + (value || '-') + '</a>' 
            : (value || '-');
        return '<div class="student-cell">' +
            '<img src="' + avatar + '" alt="" class="student-avatar" onerror="this.src=\'' + defaultAvatar + '\'">' +
            '<div class="student-info">' +
                '<div class="student-name">' + nameHtml + '</div>' +
                '<div class="student-id">ID: ' + (row.university_internal_id || '-') + '</div>' +
            '</div>' +
        '</div>';
    };
    
    // Year badge
    window.dtRenders.year_badge = function(value, row) {
        if (!value) return '-';
        var label = yearLabels[value] || value;
        return '<span class="year-badge">' + label + '</span>';
    };
    
    // Status badge
    window.dtRenders.status_badge = function(value, row) {
        if (value == 1 || value === true) {
            return '<span class="status-active">✓ Active</span>';
        }
        return '<span class="status-inactive">⊘ Inactive</span>';
    };

    // Sponsors cell
    window.dtRenders.sponsors_cell = function(value, row) {
        var sponsors = row.sponsors || [];
        if (!sponsors.length) {
            return '<span style="color:var(--text-muted);">-</span>';
        }
        
        var html = '<div style="display:flex;flex-wrap:wrap;gap:4px;">';
        sponsors.slice(0, 2).forEach(function(s) {
            html += '<span style="background:#10b98115;color:#10b981;padding:2px 8px;border-radius:12px;font-size:11px;font-weight:500;">' + 
                s.name + '</span>';
        });
        if (sponsors.length > 2) {
            html += '<span style="background:#e5e7eb;color:#6b7280;padding:2px 8px;border-radius:12px;font-size:11px;">+' + (sponsors.length - 2) + ' more</span>';
        }
        html += '</div>';
        return html;
    };
    
    // State badge (In Progress / Complete)
    window.dtRenders.state_badge = function(value, row) {
        if (value === 'complete') {
            return '<span style="display:inline-flex;align-items:center;gap:4px;padding:4px 10px;border-radius:20px;font-size:11px;font-weight:600;background:#dcfce7;color:#166534;">✓ Complete</span>';
        }
        return '<span style="display:inline-flex;align-items:center;gap:4px;padding:4px 10px;border-radius:20px;font-size:11px;font-weight:600;background:#fef3c7;color:#92400e;">◐ In Progress</span>';
    };
    
    // Filter by status (from stat cards) - DataTable v2.0 API
    function filterByStatus(status) {
        document.getElementById('statusFilter').value = status;
        
        // Trigger change event
        var event = new Event('change', { bubbles: true });
        document.getElementById('statusFilter').dispatchEvent(event);
        
        // Update active card
        document.querySelectorAll('.stat-card').forEach(function(card) {
            card.classList.remove('active');
            if ((status === '' && card.dataset.filter === 'all') || card.dataset.filter === status) {
                card.classList.add('active');
            }
        });
    }
    
    // Initialize
    document.addEventListener('DOMContentLoaded', function() {
        var allCard = document.querySelector('.stat-card[data-filter="all"]');
        if (allCard) allCard.classList.add('active');
        
        // Ensure current_state is sent with DataTable requests
        var table = document.getElementById('universityStudentsTable');
        if (table && table.dtInstance) {
            var currentState = table.dataset.currentState || 'inprogress';
            table.dtInstance.addFilter('current_state', currentState);
        }
    });
    
    // Hook into DataTable initialization to add current_state filter
    window.dtBeforeLoad = window.dtBeforeLoad || [];
    window.dtBeforeLoad.push(function(tableId, params) {
        if (tableId === 'universityStudentsTable') {
            var table = document.getElementById('universityStudentsTable');
            params.current_state = table ? (table.dataset.currentState || 'inprogress') : 'inprogress';
        }
        return params;
    });
</script>
