{{-- <x-layouts.app> --}}
    <div style="padding: 20px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h1>Leads</h1>
        </div>

        <!-- Stats Bar -->
        <div style="display: flex; gap: 15px; margin-bottom: 20px;">
            <div style="background: white; padding: 10px 20px; border-radius: 5px; border: 1px solid #E3E6F0;">
                <span style="color: #666;">{{ $stats['followup'] }}</span>
                <span style="color: #2888DA; font-weight: 500;"> Followup</span>
            </div>
            <div style="background: white; padding: 10px 20px; border-radius: 5px; border: 1px solid #E3E6F0;">
                <span style="color: #666;">{{ $stats['lead'] }}</span>
                <span style="color: #2888DA; font-weight: 500;"> Lead</span>
            </div>
            <div style="background: white; padding: 10px 20px; border-radius: 5px; border: 1px solid #E3E6F0;">
                <span style="color: #666;">{{ $stats['customer'] }}</span>
                <span style="color: #7cb342; font-weight: 500;"> Customer</span>
            </div>
            <div style="background: white; padding: 10px 20px; border-radius: 5px; border: 1px solid #E3E6F0;">
                <span style="color: #E74C3C; font-weight: 500;">{{ $stats['lost'] }} Lost Leads - {{ $stats['lost_percentage'] }}%</span>
            </div>
        </div>

        @if(session('success'))
            <div style="background: #D4EDDA; border: 1px solid #C3E6CB; color: #155724; padding: 12px; border-radius: 5px; margin-bottom: 20px;">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div style="background: #F8D7DA; border: 1px solid #F5C6CB; color: #721C24; padding: 12px; border-radius: 5px; margin-bottom: 20px;">
                {{ session('error') }}
            </div>
        @endif

        <!-- Action Buttons -->
        <div style="display: flex; gap: 10px; margin-bottom: 20px;">
            {{-- Temporarily removed permission check --}}
<a href="{{ route('admin.leads.create') }}" style="background: #1e293b; color: white; padding: 8px 16px; border-radius: 6px; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; font-weight: 500; font-size: 14px;">
    <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
        <path d="M12 4v16m8-8H4"/>
    </svg>
    New Lead
</a>



           
            
        </div>

        <!-- Table Controls -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
            <div style="display: flex; gap: 10px; align-items: center;">
                <select style="padding: 8px; border: 1px solid #DEE2E6; border-radius: 5px;">
                    <option>25</option>
                    <option>50</option>
                    <option>100</option>
                    <option>All</option>
                </select>
                
                <button onclick="openManipulationModal()" style="background: white; color: #333; padding: 8px 15px; border: 1px solid #DEE2E6; border-radius: 5px; cursor: pointer;">
                    Manipulation
                            </button>
                <button onclick="reloadTable()" style="background: white; color: #333; padding: 8px 15px; border: 1px solid #DEE2E6; border-radius: 5px; cursor: pointer;" title="Reload">
                  ↻
                </button>
            </div>
            
        </div>
<div style="background: white; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); overflow: hidden;">
    <table style="width: 100%; border-collapse: collapse;" 
           class="dt-table dt-search dt-export dt-import dt-perpage dt-checkbox"
           id="leadsTable"
           data-route="{{ route('admin.leads.data') }}">
        <thead>
            <tr style="background: #F8F9FA; border-bottom: 2px solid #DEE2E6;">
                
                <th style="padding: 15px; text-align: left; font-weight: 600;" class="dt-sort" data-col="id">#</th>
                {{-- <th style="padding: 15px; text-align: left; font-weight: 600;" class="dt-sort" data-col="name">Name</th> --}}
                <th style="padding: 15px; text-align: left; font-weight: 600;" class="dt-sort dt-clickable" data-col="name" data-render="leadName">Name</th>
                <th style="padding: 15px; text-align: left; font-weight: 600;" data-col="company">Company</th>
                <th style="padding: 15px; text-align: left; font-weight: 600;" data-col="email">Email</th>
                <th style="padding: 15px; text-align: left; font-weight: 600;" data-col="phone">Phone</th>
                <th style="padding: 15px; text-align: left; font-weight: 600;" class="dt-sort" data-col="lead_value">Value</th>
                <th style="padding: 15px; text-align: left; font-weight: 600;" data-col="tags">Tags</th>
                <th style="padding: 15px; text-align: left; font-weight: 600;" data-col="assigned">Assigned</th>
                <th style="padding: 15px; text-align: left; font-weight: 600;" data-col="status">Status</th>
                <th style="padding: 15px; text-align: left; font-weight: 600;" data-col="source">Source</th>
                <th style="padding: 15px; text-align: left; font-weight: 600;" data-render="actions">Actions</th>
            </tr>
        </thead>
        <tbody>
            <!-- DataTable will populate this via AJAX -->
        </tbody>
    </table>
</div>

@include('components.datatable')


<script>
(function() {
    const observer = new MutationObserver(function(mutations, obs) {
        const exportBtn = document.querySelector('.dt-export-btn');
        
        if (exportBtn && !exportBtn.dataset.hijacked) {
            exportBtn.dataset.hijacked = 'true';
            
            const table = document.getElementById('leadsTable');
            const baseRoute = table ? table.dataset.route : '';
            
            // Remove existing onclick
            exportBtn.onclick = null;
            
            // Add new click handler
            exportBtn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                let dropdown = document.getElementById('dtExportDropdown');
                
                if (!dropdown) {
                    dropdown = document.createElement('div');
                    dropdown.id = 'dtExportDropdown';
                    dropdown.style.cssText = 'position: absolute; display: none; background: white; border: 1px solid #DEE2E6; border-radius: 5px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); min-width: 180px; z-index: 10000; top: 100%; right: 0; margin-top: 8px;';
                    
                    dropdown.innerHTML = `
                        <a href="${baseRoute}?export=csv" style="display: block; padding: 14px 18px; text-decoration: none; color: #333; border-bottom: 1px solid #f0f0f0; font-size: 14px; font-weight: 500;">
                             Export as CSV
                        </a>
                        <a href="${baseRoute}?export=excel" style="display: block; padding: 14px 18px; text-decoration: none; color: #333; border-bottom: 1px solid #f0f0f0; font-size: 14px; font-weight: 500;">
                             Export as Excel
                        </a>
                        <a href="${baseRoute}?export=pdf" style="display: block; padding: 14px 18px; text-decoration: none; color: #333; font-size: 14px; font-weight: 500;">
                             Export as PDF
                        </a>
                    `;
                    
                    // Make parent relative
                    exportBtn.parentElement.style.position = 'relative';
                    exportBtn.parentElement.appendChild(dropdown);
                    
                    dropdown.querySelectorAll('a').forEach(link => {
                        link.onmouseenter = function() { this.style.background = '#f8f9fa'; };
                        link.onmouseleave = function() { this.style.background = 'white'; };
                        link.onclick = function(e) { 
                            dropdown.style.display = 'none'; 
                        };
                    });
                }
                
                dropdown.style.display = dropdown.style.display === 'none' ? 'block' : 'none';
            });
            
            document.addEventListener('click', function(e) {
                const dropdown = document.getElementById('dtExportDropdown');
                if (dropdown && !exportBtn.contains(e.target) && !dropdown.contains(e.target)) {
                    dropdown.style.display = 'none';
                }
            });
            
            console.log('✅ Export button hijacked!');
            obs.disconnect();
        }
    });
    
    observer.observe(document.body, {
        childList: true,
        subtree: true
    });
    
    setTimeout(() => observer.disconnect(), 5000);
})();
</script>

{{-- Export Dropdown Hijack --}}
<script>
(function() {
    const observer = new MutationObserver(function(mutations, obs) {
        const exportBtn = document.querySelector('.dt-export-btn');
        
        if (exportBtn && !exportBtn.dataset.hijacked) {
            exportBtn.dataset.hijacked = 'true';
            
            const table = document.getElementById('leadsTable');
            const baseRoute = table ? table.dataset.route : '';
            
            exportBtn.onclick = null;
            
            exportBtn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                let dropdown = document.getElementById('dtExportDropdown');
                
                if (!dropdown) {
                    dropdown = document.createElement('div');
                    dropdown.id = 'dtExportDropdown';
                    dropdown.style.cssText = 'position: absolute; display: none; background: white; border: 1px solid #DEE2E6; border-radius: 5px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); min-width: 180px; z-index: 10000; top: 100%; right: 0; margin-top: 8px;';
                    
                    dropdown.innerHTML = `
                        <a href="${baseRoute}?export=csv" style="display: block; padding: 14px 18px; text-decoration: none; color: #333; border-bottom: 1px solid #f0f0f0; font-size: 14px; font-weight: 500;">📄 Export as CSV</a>
                        <a href="${baseRoute}?export=excel" style="display: block; padding: 14px 18px; text-decoration: none; color: #333; border-bottom: 1px solid #f0f0f0; font-size: 14px; font-weight: 500;">📊 Export as Excel</a>
                        <a href="${baseRoute}?export=pdf" style="display: block; padding: 14px 18px; text-decoration: none; color: #333; font-size: 14px; font-weight: 500;">📑 Export as PDF</a>
                    `;
                    
                    exportBtn.parentElement.style.position = 'relative';
                    exportBtn.parentElement.appendChild(dropdown);
                    
                    dropdown.querySelectorAll('a').forEach(link => {
                        link.onmouseenter = function() { this.style.background = '#f8f9fa'; };
                        link.onmouseleave = function() { this.style.background = 'white'; };
                        link.onclick = function(e) { dropdown.style.display = 'none'; };
                    });
                }
                
                dropdown.style.display = dropdown.style.display === 'none' ? 'block' : 'none';
            });
            
            document.addEventListener('click', function(e) {
                const dropdown = document.getElementById('dtExportDropdown');
                if (dropdown && !exportBtn.contains(e.target) && !dropdown.contains(e.target)) {
                    dropdown.style.display = 'none';
                }
            });
            
            console.log('✅ Export dropdown ready');
            obs.disconnect();
        }
    });
    
    observer.observe(document.body, { childList: true, subtree: true });
    setTimeout(() => observer.disconnect(), 5000);
})();
</script>











{{-- Status Dropdown Replacement --}}
<script>
// Get statuses from PHP
const STATUSES = @json($statuses->map(fn($s) => ['id' => $s->id, 'name' => $s->name, 'color' => $s->color]));

// Function to replace status text with dropdown
function replaceStatusWithDropdown() {
    const table = document.getElementById('leadsTable');
    if (!table) return;
    
    // Status is the 9th column (index 8 with checkbox, index 7 without)
    const hasCheckbox = table.classList.contains('dt-checkbox');
    const statusColumnIndex = hasCheckbox ? 9 : 8;
    
    const rows = table.querySelectorAll('tbody tr');
    
    rows.forEach(function(row) {
        const cells = row.querySelectorAll('td');
        if (cells.length === 0) return;
        
        const statusCell = cells[statusColumnIndex];
        if (!statusCell) return;
        
        // Skip if already converted
        if (statusCell.querySelector('.status-dropdown')) return;
        
        const statusText = statusCell.textContent.trim();
        const idCell = hasCheckbox ? cells[1] : cells[0]; // Skip checkbox column if present
        const leadId = idCell.textContent.trim();
        
        // Find matching status
        const currentStatus = STATUSES.find(s => s.name === statusText);
        if (!currentStatus) return;
        
        // Create dropdown
        const dropdown = document.createElement('select');
        dropdown.className = 'status-dropdown';
        dropdown.dataset.leadId = leadId;
        dropdown.dataset.originalValue = currentStatus.id;
        dropdown.style.cssText = `background: ${currentStatus.color}; color: white; padding: 5px 12px; border-radius: 15px; font-size: 12px; border: none; cursor: pointer; font-weight: 500;`;
        
        // Add options
        STATUSES.forEach(status => {
            const option = document.createElement('option');
            option.value = status.id;
            option.textContent = status.name;
            option.selected = status.id === currentStatus.id;
            option.style.cssText = 'background: white; color: #333;';
            dropdown.appendChild(option);
        });
        
        // Replace cell content
        statusCell.innerHTML = '';
        statusCell.appendChild(dropdown);
    });
}

// Run after DataTable loads
document.addEventListener('DOMContentLoaded', function() {
    // Initial load
    setTimeout(replaceStatusWithDropdown, 1000);
    
    // Watch for table updates
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.target.tagName === 'TBODY' || mutation.target.closest('tbody')) {
                setTimeout(replaceStatusWithDropdown, 100);
            }
        });
    });
    
    const table = document.getElementById('leadsTable');
    if (table) {
        const tbody = table.querySelector('tbody');
        if (tbody) {
            observer.observe(tbody, {
                childList: true,
                subtree: true
            });
        }
    }
    
    console.log('✅ Status dropdown watcher started');
});

// Handle status change
document.addEventListener('change', function(e) {
    if (e.target.classList.contains('status-dropdown')) {
        const leadId = e.target.dataset.leadId;
        const newStatusId = e.target.value;
        const selectElement = e.target;
        const originalValue = selectElement.dataset.originalValue;
        
        const newStatus = STATUSES.find(s => s.id == newStatusId);
        if (!newStatus) return;
        
        selectElement.disabled = true;
        selectElement.style.opacity = '0.6';
        
        fetch(`/admin/leads/${leadId}/update-status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ status: newStatusId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                selectElement.style.background = data.status.color;
                selectElement.dataset.originalValue = newStatusId;
                selectElement.disabled = false;
                selectElement.style.opacity = '1';
                
                console.log('✅ Status updated to:', data.status.name);
                setTimeout(() => window.location.reload(), 500);
            } else {
                alert('Failed to update status');
                selectElement.value = originalValue;
                selectElement.disabled = false;
                selectElement.style.opacity = '1';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to update status');
            selectElement.value = originalValue;
            selectElement.disabled = false;
            selectElement.style.opacity = '1';
        });
    }
});
</script>
    


<script>
function reloadTable() {
    const table = document.getElementById('leadsTable');
    if (table && table.dtReload) {
        table.dtReload();
        console.log('✅ Table reloaded');
    } else {
        console.log('⚠️ dtReload not found, doing full page reload');
        window.location.reload();
    }
}
</script>


<script>
window.dtRenders = window.dtRenders || {};

window.dtRenders.leadName = function(data, row) {
    return '<span class="lead-name">' + row.name + '</span>';
};
</script>














    
{{-- </x-layouts.app> --}}