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
    .stat-icon.active-icon { background: var(--success-light); color: var(--success); }
    .stat-icon.individual { background: #f3e8ff; color: #9333ea; }
    .stat-icon.company { background: #fef3c7; color: #d97706; }
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

    /* Sponsor Cell Custom Style */
    .sponsor-cell { display: flex; align-items: center; gap: 12px; }
    .sponsor-avatar { width: 48px; height: 48px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 18px; color: #fff; flex-shrink: 0; }
    .sponsor-avatar.individual { background: linear-gradient(135deg, #8b5cf6, #a78bfa); }
    .sponsor-avatar.company { background: linear-gradient(135deg, #f97316, #fb923c); }
    .sponsor-info { display: flex; flex-direction: column; gap: 2px; }
    .sponsor-name { font-weight: 600; color: var(--text-primary); font-size: 14px; }
    .sponsor-name a.dt-clickable { color: var(--primary); text-decoration: none; font-weight: 600; }
    .sponsor-name a.dt-clickable:hover { text-decoration: underline; }
    .sponsor-id { font-size: 11px; color: var(--text-muted); }
    .sponsor-occupation { font-size: 11px; color: var(--text-muted); }
    
    /* Type Badge */
    .type-badge { display: inline-flex; align-items: center; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; white-space: nowrap; }
    .type-individual { background: #f3e8ff; color: #9333ea; }
    .type-company { background: #fef3c7; color: #d97706; }
    
    /* Status Badge */
    .status-active { background: #dcfce7; color: #166534; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; }
    .status-inactive { background: #e5e7eb; color: #4b5563; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; }
    
    /* Contact Display */
    .contact-email { color: var(--primary); text-decoration: none; display: block; font-size: 13px; }
    .contact-email:hover { text-decoration: underline; }
    .contact-phone { color: #16a34a; font-size: 13px; display: flex; align-items: center; gap: 4px; }
    
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
        
        .sponsor-cell { gap: 8px; }
        .sponsor-avatar { width: 40px; height: 40px; font-size: 16px; }
        .sponsor-name { font-size: 13px; }
        .sponsor-id { font-size: 10px; }
        
        .type-badge, .status-active, .status-inactive { padding: 3px 8px; font-size: 11px; }
        .contact-email, .contact-phone { font-size: 12px; }
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
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" /></svg>
            {{ $pageTitle ?? 'Sponsors' }}
        </h1>
        <a href="{{ route('admin.studentsponsorship.sponsors.create') }}" class="btn-add">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4"></path></svg>
            Add New Sponsor
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="stats-grid">
        <div class="stat-card active" data-filter="all" onclick="filterByStatus('')">
            <div class="stat-icon total"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" /></svg></div>
            <div class="stat-content"><div class="stat-value" id="stat-total">{{ $stats['total'] ?? 0 }}</div><div class="stat-label">Total Sponsors</div></div>
        </div>
        <div class="stat-card" data-filter="1" onclick="filterByStatus('1')">
            <div class="stat-icon active-icon"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></div>
            <div class="stat-content"><div class="stat-value" id="stat-active">{{ $stats['active'] ?? 0 }}</div><div class="stat-label">Active</div></div>
        </div>
        <div class="stat-card" data-filter="individual" onclick="filterByType('individual')">
            <div class="stat-icon individual"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" /></svg></div>
            <div class="stat-content"><div class="stat-value" id="stat-individual">{{ $stats['individual'] ?? 0 }}</div><div class="stat-label">Individual</div></div>
        </div>
        <div class="stat-card" data-filter="company" onclick="filterByType('company')">
            <div class="stat-icon company"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008z" /></svg></div>
            <div class="stat-content"><div class="stat-value" id="stat-company">{{ $stats['company'] ?? 0 }}</div><div class="stat-label">Company</div></div>
        </div>
    </div>

    <!-- Table Card -->
    <div class="table-card">
        <div class="table-card-header">
            <div class="table-card-title">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                Sponsors List
            </div>
            
            <!-- Filters -->
            <div class="filter-section">
                <select id="typeFilter" class="filter-select" data-dt-filter="sponsor_type" data-dt-table="sponsorsTable">
                    <option value="">All Types</option>
                    <option value="individual">Individual</option>
                    <option value="company">Company</option>
                </select>
                <select id="statusFilter" class="filter-select" data-dt-filter="active" data-dt-table="sponsorsTable">
                    <option value="">All Status</option>
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
                @if(isset($countries) && count($countries) > 0)
                <select id="countryFilter" class="filter-select" data-dt-filter="country_id" data-dt-table="sponsorsTable">
                    <option value="">All Countries</option>
                    @foreach($countries as $country)
                        <option value="{{ $country->country_id }}">{{ $country->short_name }}</option>
                    @endforeach
                </select>
                @endif
            </div>
        </div>
        <div class="table-card-body">
            <!-- DataTable with all features: search, export (CSV, XLSX, PDF), import, per page, checkbox -->
            <table id="sponsorsTable" 
                   class="dt-table dt-search dt-export dt-import dt-perpage dt-checkbox" 
                   data-route="{{ route('admin.studentsponsorship.sponsors.data') }}">
                <thead>
                    <tr>
                        <th data-col="_row_num" style="width:50px;">#</th>
                        <th class="dt-sort dt-clickable" data-col="name" data-render="sponsor_cell">Sponsor Name</th>
                        <th data-col="sponsor_type" data-render="type_badge">Type</th>
                        <th data-col="sponsored_students_names" data-render="sponsored_students">Sponsored Students</th>
                        <th data-col="contact" data-render="contact_info">Contact</th>
                        <th data-col="city">City</th>
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
    // Custom renders
    window.dtRenders = window.dtRenders || {};
    
    // Sponsor cell with avatar, name, ID, portal status
    window.dtRenders.sponsor_cell = function(value, row) {
        var initial = value ? value.charAt(0).toUpperCase() : '?';
        var typeClass = row.sponsor_type === 'company' ? 'company' : 'individual';
        var portalBadge = row.has_portal ? '<span style="background:#dbeafe;color:#1d4ed8;padding:2px 6px;border-radius:4px;font-size:9px;font-weight:600;margin-left:6px;">PORTAL</span>' : '';
        
        var nameHtml = row._show_url 
            ? '<a href="' + row._show_url + '" class="dt-clickable">' + (value || '-') + '</a>' 
            : (value || '-');
        
        var html = '<div class="sponsor-cell">' +
            '<div class="sponsor-avatar ' + typeClass + '">' + initial + '</div>' +
            '<div class="sponsor-info">' +
                '<div class="sponsor-name">' + nameHtml + portalBadge + '</div>' +
                '<div class="sponsor-id">ID: ' + (row.sponsor_internal_id || '-') + '</div>';
        if (row.sponsor_occupation) {
            html += '<div class="sponsor-occupation">' + row.sponsor_occupation + '</div>';
        }
        html += '</div></div>';
        return html;
    };
    
    // Type badge
    window.dtRenders.type_badge = function(value, row) {
        if (value === 'company') {
            return '<span class="type-badge type-company">Company</span>';
        }
        return '<span class="type-badge type-individual">Individual</span>';
    };
    
    // Status badge
    window.dtRenders.status_badge = function(value, row) {
        if (value == 1 || value === true) {
            return '<span class="status-active">✓ Active</span>';
        }
        return '<span class="status-inactive">⊘ Inactive</span>';
    };

    // Sponsored Students
    window.dtRenders.sponsored_students = function(value, row) {
        var students = row.sponsored_students || [];
        if (!students.length) {
            return '<span style="color:var(--text-muted);">-</span>';
        }
        
        var html = '<div style="display:flex;flex-wrap:wrap;gap:4px;">';
        students.slice(0, 3).forEach(function(s) {
            var typeColor = s.type === 'School' ? '#3b82f6' : '#8b5cf6';
            html += '<span style="background:' + typeColor + '15;color:' + typeColor + ';padding:2px 8px;border-radius:12px;font-size:11px;font-weight:500;">' + 
                s.name + '</span>';
        });
        if (students.length > 3) {
            html += '<span style="background:#e5e7eb;color:#6b7280;padding:2px 8px;border-radius:12px;font-size:11px;">+' + (students.length - 3) + ' more</span>';
        }
        html += '</div>';
        return html;
    };
    
    // Contact info (email + phone)
    window.dtRenders.contact_info = function(value, row) {
        var html = '';
        if (row.email) {
            html += '<a href="mailto:' + row.email + '" class="contact-email">' + row.email + '</a>';
        }
        if (row.contact_no) {
            html += '<div class="contact-phone"><svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>' + row.contact_no + '</div>';
        }
        return html || '-';
    };
    
    // Actions render
    window.dtRenders.actions = function(value, row) {
        var html = '<div style="display:flex;gap:8px;align-items:center;">';
        
        // View button
        if (row._show_url) {
            html += '<a href="' + row._show_url + '" style="color:#3b82f6;padding:6px;border-radius:6px;display:inline-flex;" title="View">' +
                '<svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">' +
                '<path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>' +
                '<path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>' +
                '</svg></a>';
        }
        
        // Edit button
        if (row._edit_url) {
            html += '<a href="' + row._edit_url + '" style="color:#f59e0b;padding:6px;border-radius:6px;display:inline-flex;" title="Edit">' +
                '<svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">' +
                '<path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>' +
                '</svg></a>';
        }
        
        // Delete button
        if (row._delete_url) {
            html += '<button onclick="deleteSponsor(' + row.id + ', \'' + row._delete_url + '\')" style="color:#ef4444;padding:6px;border-radius:6px;display:inline-flex;background:none;border:none;cursor:pointer;" title="Delete">' +
                '<svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">' +
                '<path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>' +
                '</svg></button>';
        }
        
        html += '</div>';
        return html;
    };
    
    // Delete sponsor function
    function deleteSponsor(id, url) {
        if (!confirm('Are you sure you want to delete this sponsor?')) return;
        
        fetch(url, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Reload the datatable
                var table = document.getElementById('sponsorsTable');
                if (table && table.dtInstance) {
                    table.dtInstance.reload();
                } else {
                    location.reload();
                }
            } else {
                alert(data.message || 'Failed to delete sponsor');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while deleting');
        });
    }
    
    // Filter by status (from stat cards)
    function filterByStatus(status) {
        document.getElementById('statusFilter').value = status;
        document.getElementById('typeFilter').value = '';
        
        var event = new Event('change', { bubbles: true });
        document.getElementById('statusFilter').dispatchEvent(event);
        
        updateActiveCards(status, 'status');
    }
    
    // Filter by type (from stat cards)
    function filterByType(type) {
        document.getElementById('typeFilter').value = type;
        document.getElementById('statusFilter').value = '';
        
        var event = new Event('change', { bubbles: true });
        document.getElementById('typeFilter').dispatchEvent(event);
        
        updateActiveCards(type, 'type');
    }
    
    // Update active card highlighting
    function updateActiveCards(value, filterType) {
        document.querySelectorAll('.stat-card').forEach(function(card) {
            card.classList.remove('active');
            var cardFilter = card.dataset.filter;
            if (value === '' && cardFilter === 'all') {
                card.classList.add('active');
            } else if (cardFilter === value) {
                card.classList.add('active');
            }
        });
    }
    
    // Initialize
    document.addEventListener('DOMContentLoaded', function() {
        var allCard = document.querySelector('.stat-card[data-filter="all"]');
        if (allCard) allCard.classList.add('active');
    });
    
    // Hook into DataTable to get stats
    window.dtBeforeLoad = window.dtBeforeLoad || [];
    window.dtBeforeLoad.push(function(tableId, params) {
        if (tableId === 'sponsorsTable') {
            params.with_stats = 1;
        }
        return params;
    });
    
    // Update stats after load
    window.dtAfterLoad = window.dtAfterLoad || [];
    window.dtAfterLoad.push(function(tableId, response) {
        if (tableId === 'sponsorsTable' && response.stats) {
            document.getElementById('stat-total').textContent = response.stats.total || 0;
            document.getElementById('stat-active').textContent = response.stats.active || 0;
            document.getElementById('stat-individual').textContent = response.stats.individual || 0;
            document.getElementById('stat-company').textContent = response.stats.company || 0;
        }
    });
</script>
