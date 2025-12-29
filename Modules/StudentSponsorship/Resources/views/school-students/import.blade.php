@extends('layouts.admin')

@section('title', 'Import School Students')

@section('content')
<style>
    :root {
        --primary: #4f46e5;
        --primary-dark: #4338ca;
        --primary-light: #eef2ff;
        --success: #10b981;
        --success-light: #d1fae5;
        --danger: #ef4444;
        --danger-light: #fee2e2;
        --warning: #f59e0b;
        --warning-light: #fef3c7;
        --text-dark: #1f2937;
        --text-muted: #6b7280;
        --border-color: #e5e7eb;
        --card-bg: #ffffff;
        --body-bg: #f9fafb;
    }
    
    .import-container { max-width: 1400px; margin: 0 auto; padding: 24px; }
    .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 16px; }
    .page-title { font-size: 24px; font-weight: 700; color: var(--text-dark); display: flex; align-items: center; gap: 12px; }
    .page-title svg { width: 28px; height: 28px; color: var(--primary); }
    
    .btn { display: inline-flex; align-items: center; gap: 8px; padding: 10px 20px; border-radius: 8px; font-weight: 600; font-size: 14px; cursor: pointer; border: none; transition: all 0.2s; text-decoration: none; }
    .btn-primary { background: var(--primary); color: #fff; }
    .btn-primary:hover { background: var(--primary-dark); }
    .btn-secondary { background: var(--body-bg); color: var(--text-dark); border: 1px solid var(--border-color); }
    .btn-secondary:hover { background: var(--border-color); }
    .btn-success { background: var(--success); color: #fff; }
    .btn-success:hover { background: #059669; }
    .btn-success:disabled { background: #9ca3af; cursor: not-allowed; }
    
    .card { background: var(--card-bg); border-radius: 12px; border: 1px solid var(--border-color); margin-bottom: 24px; }
    .card-header { padding: 20px 24px; border-bottom: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: center; }
    .card-title { font-size: 16px; font-weight: 600; color: var(--text-dark); display: flex; align-items: center; gap: 10px; }
    .card-title svg { width: 20px; height: 20px; color: var(--primary); }
    .card-body { padding: 24px; }
    
    .upload-zone { border: 2px dashed var(--border-color); border-radius: 12px; padding: 48px; text-align: center; transition: all 0.2s; cursor: pointer; background: var(--body-bg); }
    .upload-zone:hover, .upload-zone.dragover { border-color: var(--primary); background: var(--primary-light); }
    .upload-zone svg { width: 48px; height: 48px; color: var(--text-muted); margin-bottom: 16px; }
    .upload-zone h3 { font-size: 16px; font-weight: 600; color: var(--text-dark); margin-bottom: 8px; }
    .upload-zone p { color: var(--text-muted); font-size: 14px; margin-bottom: 16px; }
    .upload-zone input[type="file"] { display: none; }
    .file-info { margin-top: 16px; padding: 12px 16px; background: var(--success-light); border-radius: 8px; color: var(--success); font-weight: 500; display: none; }
    .file-info.show { display: block; }
    
    .instructions { background: var(--primary-light); border-radius: 8px; padding: 16px 20px; margin-bottom: 20px; }
    .instructions h4 { font-size: 14px; font-weight: 600; color: var(--primary-dark); margin-bottom: 8px; }
    .instructions ul { margin: 0; padding-left: 20px; color: var(--text-dark); font-size: 13px; }
    .instructions li { margin-bottom: 4px; }
    
    .template-download { display: flex; align-items: center; gap: 16px; padding: 16px 20px; background: var(--body-bg); border-radius: 8px; margin-top: 20px; }
    .template-download svg { width: 40px; height: 40px; color: var(--success); }
    .template-info h4 { font-size: 14px; font-weight: 600; color: var(--text-dark); margin-bottom: 4px; }
    .template-info p { font-size: 13px; color: var(--text-muted); margin: 0; }
    
    /* DataTable Styles */
    .dt-container { margin-top: 24px; display: none; }
    .dt-container.show { display: block; }
    .dt-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; flex-wrap: wrap; gap: 12px; }
    .dt-title { font-size: 16px; font-weight: 600; color: var(--text-dark); }
    .dt-stats { display: flex; gap: 16px; }
    .dt-stat { padding: 8px 16px; border-radius: 8px; font-size: 13px; font-weight: 600; }
    .dt-stat.total { background: var(--primary-light); color: var(--primary); }
    .dt-stat.valid { background: var(--success-light); color: var(--success); }
    .dt-stat.invalid { background: var(--danger-light); color: var(--danger); }
    
    .dt-controls { display: flex; gap: 12px; align-items: center; }
    .dt-search { padding: 8px 12px; border: 1px solid var(--border-color); border-radius: 6px; font-size: 14px; width: 200px; }
    .dt-search:focus { outline: none; border-color: var(--primary); }
    
    .dt-table-wrapper { overflow-x: auto; border: 1px solid var(--border-color); border-radius: 8px; }
    .dt-table { width: 100%; border-collapse: collapse; font-size: 13px; min-width: 1200px; }
    .dt-table th { background: var(--primary); color: #fff; padding: 12px 10px; text-align: left; font-weight: 600; white-space: nowrap; position: sticky; top: 0; }
    .dt-table th.sortable { cursor: pointer; user-select: none; }
    .dt-table th.sortable:hover { background: var(--primary-dark); }
    .dt-table td { padding: 10px; border-bottom: 1px solid var(--border-color); vertical-align: top; }
    .dt-table tbody tr:hover { background: var(--primary-light); }
    .dt-table tbody tr.invalid { background: var(--danger-light); }
    .dt-table tbody tr.invalid:hover { background: #fecaca; }
    
    .row-num { width: 40px; text-align: center; color: var(--text-muted); font-weight: 500; }
    .row-status { width: 30px; text-align: center; }
    .status-icon { width: 20px; height: 20px; }
    .status-icon.valid { color: var(--success); }
    .status-icon.invalid { color: var(--danger); }
    
    .cell-error { color: var(--danger); font-size: 11px; margin-top: 2px; }
    .cell-value { max-width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .cell-value.full { white-space: normal; word-break: break-word; }
    
    .dt-footer { display: flex; justify-content: space-between; align-items: center; margin-top: 16px; flex-wrap: wrap; gap: 12px; }
    .dt-pagination { display: flex; gap: 4px; }
    .dt-pagination button { padding: 6px 12px; border: 1px solid var(--border-color); background: var(--card-bg); border-radius: 4px; cursor: pointer; font-size: 13px; }
    .dt-pagination button:hover:not(:disabled) { background: var(--primary-light); border-color: var(--primary); }
    .dt-pagination button.active { background: var(--primary); color: #fff; border-color: var(--primary); }
    .dt-pagination button:disabled { opacity: 0.5; cursor: not-allowed; }
    
    .dt-info { font-size: 13px; color: var(--text-muted); }
    
    .import-actions { display: flex; gap: 12px; justify-content: flex-end; margin-top: 24px; padding-top: 24px; border-top: 1px solid var(--border-color); }
    
    .loading-overlay { position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); display: none; justify-content: center; align-items: center; z-index: 9999; }
    .loading-overlay.show { display: flex; }
    .loading-spinner { background: var(--card-bg); padding: 32px 48px; border-radius: 12px; text-align: center; }
    .loading-spinner svg { width: 48px; height: 48px; color: var(--primary); animation: spin 1s linear infinite; }
    .loading-spinner p { margin-top: 16px; font-weight: 600; color: var(--text-dark); }
    @keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
    
    .alert { padding: 16px 20px; border-radius: 8px; margin-bottom: 20px; display: flex; align-items: flex-start; gap: 12px; }
    .alert svg { width: 20px; height: 20px; flex-shrink: 0; margin-top: 2px; }
    .alert-success { background: var(--success-light); color: #065f46; }
    .alert-danger { background: var(--danger-light); color: #991b1b; }
    .alert-warning { background: var(--warning-light); color: #92400e; }
    
    @media (max-width: 768px) {
        .import-container { padding: 16px; }
        .page-header { flex-direction: column; align-items: flex-start; }
        .dt-header { flex-direction: column; align-items: flex-start; }
        .dt-controls { width: 100%; }
        .dt-search { width: 100%; }
    }
</style>

<div class="import-container">
    <div class="page-header">
        <h1 class="page-title">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
            Import School Students
        </h1>
        <a href="{{ route('admin.studentsponsorship.school-students.index') }}" class="btn btn-secondary">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width:18px;height:18px;"><path d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Back to List
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <div>{{ session('success') }}</div>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <div>{{ session('error') }}</div>
        </div>
    @endif

    <!-- Upload Card -->
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Upload Excel File
            </div>
        </div>
        <div class="card-body">
            <div class="instructions">
                <h4>Instructions:</h4>
                <ul>
                    <li>Download the template file and fill in student data</li>
                    <li>Supported formats: <strong>.xlsx, .xls, .csv</strong></li>
                    <li>First row must be header row (will be skipped)</li>
                    <li>Required fields: Full Name, Student ID, School Student ID, Grade, Current State</li>
                    <li>For School and Bank, use existing names from your database</li>
                    <li>Date format: YYYY-MM-DD (e.g., 2020-05-15)</li>
                </ul>
            </div>
            
            <div class="upload-zone" id="uploadZone" onclick="document.getElementById('fileInput').click()">
                <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"></path></svg>
                <h3>Drop your Excel file here</h3>
                <p>or click to browse</p>
                <button type="button" class="btn btn-primary">Select File</button>
                <input type="file" id="fileInput" accept=".xlsx,.xls,.csv" onchange="handleFileSelect(this)">
                <div class="file-info" id="fileInfo"></div>
            </div>
            
            <div class="template-download">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                <div class="template-info">
                    <h4>Download Import Template</h4>
                    <p>Get the Excel template with all columns and sample data</p>
                </div>
                <a href="{{ route('admin.studentsponsorship.school-students.import-template') }}" class="btn btn-success">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width:18px;height:18px;"><path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    Download Template
                </a>
            </div>
        </div>
    </div>

    <!-- Preview DataTable -->
    <div class="card dt-container" id="previewContainer">
        <div class="card-header">
            <div class="card-title">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                Preview Import Data
            </div>
            <div class="dt-stats">
                <span class="dt-stat total">Total: <span id="totalCount">0</span></span>
                <span class="dt-stat valid">Valid: <span id="validCount">0</span></span>
                <span class="dt-stat invalid">Invalid: <span id="invalidCount">0</span></span>
            </div>
        </div>
        <div class="card-body">
            <div class="dt-header">
                <div class="dt-controls">
                    <input type="text" class="dt-search" id="dtSearch" placeholder="Search...">
                    <select class="dt-search" id="dtFilter" style="width:150px;">
                        <option value="all">All Rows</option>
                        <option value="valid">Valid Only</option>
                        <option value="invalid">Invalid Only</option>
                    </select>
                </div>
            </div>
            
            <div class="dt-table-wrapper" style="max-height: 500px; overflow-y: auto;">
                <table class="dt-table" id="previewTable">
                    <thead>
                        <tr>
                            <th class="row-num">#</th>
                            <th class="row-status">Status</th>
                            <th class="sortable" data-col="full_name">Full Name</th>
                            <th class="sortable" data-col="school_internal_id">Student ID</th>
                            <th class="sortable" data-col="school_student_id">School Student ID</th>
                            <th class="sortable" data-col="school_name">School</th>
                            <th class="sortable" data-col="grade">Grade</th>
                            <th class="sortable" data-col="current_state">State</th>
                            <th class="sortable" data-col="dob">DOB</th>
                            <th class="sortable" data-col="phone">Phone</th>
                            <th class="sortable" data-col="city">City</th>
                            <th>Errors</th>
                        </tr>
                    </thead>
                    <tbody id="previewBody"></tbody>
                </table>
            </div>
            
            <div class="dt-footer">
                <div class="dt-info">
                    Showing <span id="showingStart">0</span> to <span id="showingEnd">0</span> of <span id="showingTotal">0</span> entries
                </div>
                <div class="dt-pagination" id="pagination"></div>
            </div>
            
            <div class="import-actions">
                <button type="button" class="btn btn-secondary" onclick="clearPreview()">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width:18px;height:18px;"><path d="M6 18L18 6M6 6l12 12"></path></svg>
                    Clear
                </button>
                <button type="button" class="btn btn-success" id="importBtn" onclick="submitImport()" disabled>
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width:18px;height:18px;"><path d="M5 13l4 4L19 7"></path></svg>
                    Import <span id="importCountText">0</span> Valid Records
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Loading Overlay -->
<div class="loading-overlay" id="loadingOverlay">
    <div class="loading-spinner">
        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
        <p>Processing...</p>
    </div>
</div>

<!-- Hidden form for final import -->
<form id="importForm" method="POST" action="{{ route('admin.studentsponsorship.school-students.import-save') }}" style="display:none;">
    @csrf
    <input type="hidden" name="import_data" id="importDataInput">
</form>

<script>
// Global data
let allData = [];
let filteredData = [];
let currentPage = 1;
const perPage = 50;

// Schools and Banks for validation
const schools = @json($schools ?? []);
const banks = @json($banks ?? []);
const grades = @json($grades ?? []);

// Drag and drop
const uploadZone = document.getElementById('uploadZone');

uploadZone.addEventListener('dragover', (e) => {
    e.preventDefault();
    uploadZone.classList.add('dragover');
});

uploadZone.addEventListener('dragleave', () => {
    uploadZone.classList.remove('dragover');
});

uploadZone.addEventListener('drop', (e) => {
    e.preventDefault();
    uploadZone.classList.remove('dragover');
    const files = e.dataTransfer.files;
    if (files.length > 0) {
        document.getElementById('fileInput').files = files;
        handleFileSelect(document.getElementById('fileInput'));
    }
});

function handleFileSelect(input) {
    const file = input.files[0];
    if (!file) return;
    
    const validTypes = [
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'application/vnd.ms-excel',
        'text/csv'
    ];
    
    const ext = file.name.split('.').pop().toLowerCase();
    if (!['xlsx', 'xls', 'csv'].includes(ext)) {
        alert('Please select a valid Excel or CSV file');
        input.value = '';
        return;
    }
    
    document.getElementById('fileInfo').textContent = `Selected: ${file.name} (${formatFileSize(file.size)})`;
    document.getElementById('fileInfo').classList.add('show');
    
    // Upload and parse
    parseFile(file);
}

function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

function parseFile(file) {
    showLoading(true);
    
    const formData = new FormData();
    formData.append('file', file);
    
    fetch('{{ route("admin.studentsponsorship.school-students.import-parse") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: formData
    })
    .then(r => r.json())
    .then(data => {
        showLoading(false);
        if (data.success) {
            allData = data.rows;
            validateData();
            renderTable();
            document.getElementById('previewContainer').classList.add('show');
        } else {
            alert(data.message || 'Failed to parse file');
        }
    })
    .catch(err => {
        showLoading(false);
        alert('Error parsing file: ' + err.message);
    });
}

function validateData() {
    const schoolNames = schools.map(s => s.name.toLowerCase());
    const bankNames = banks.map(b => b.name.toLowerCase());
    const gradeKeys = Object.keys(grades);
    
    allData.forEach((row, index) => {
        row._rowNum = index + 1;
        row._errors = [];
        
        // Required fields
        if (!row.full_name || !row.full_name.trim()) {
            row._errors.push('Full Name is required');
        }
        if (!row.school_internal_id || !row.school_internal_id.toString().trim()) {
            row._errors.push('Student ID is required');
        }
        if (!row.school_student_id || !row.school_student_id.toString().trim()) {
            row._errors.push('School Student ID is required');
        }
        if (!row.grade) {
            row._errors.push('Grade is required');
        } else if (!gradeKeys.includes(row.grade.toString())) {
            row._errors.push('Invalid Grade (use 1-14)');
        }
        if (!row.current_state) {
            row._errors.push('Current State is required');
        } else if (!['Active', 'Inactive', 'Graduated', 'Dropped'].includes(row.current_state)) {
            row._errors.push('Invalid State');
        }
        
        // Validate school if provided
        if (row.school_name && !schoolNames.includes(row.school_name.toLowerCase())) {
            row._errors.push('School not found');
        }
        
        // Validate bank if provided
        if (row.bank_name && !bankNames.includes(row.bank_name.toLowerCase())) {
            row._errors.push('Bank not found');
        }
        
        // Validate dates
        if (row.dob && !isValidDate(row.dob)) {
            row._errors.push('Invalid DOB format');
        }
        if (row.sponsorship_start_date && !isValidDate(row.sponsorship_start_date)) {
            row._errors.push('Invalid start date');
        }
        if (row.sponsorship_end_date && !isValidDate(row.sponsorship_end_date)) {
            row._errors.push('Invalid end date');
        }
        
        row._valid = row._errors.length === 0;
    });
    
    updateStats();
}

function isValidDate(dateStr) {
    if (!dateStr) return true;
    const d = new Date(dateStr);
    return d instanceof Date && !isNaN(d);
}

function updateStats() {
    const total = allData.length;
    const valid = allData.filter(r => r._valid).length;
    const invalid = total - valid;
    
    document.getElementById('totalCount').textContent = total;
    document.getElementById('validCount').textContent = valid;
    document.getElementById('invalidCount').textContent = invalid;
    document.getElementById('importCountText').textContent = valid;
    
    const importBtn = document.getElementById('importBtn');
    importBtn.disabled = valid === 0;
}

function renderTable() {
    const search = document.getElementById('dtSearch').value.toLowerCase();
    const filter = document.getElementById('dtFilter').value;
    
    filteredData = allData.filter(row => {
        // Filter
        if (filter === 'valid' && !row._valid) return false;
        if (filter === 'invalid' && row._valid) return false;
        
        // Search
        if (search) {
            const searchStr = [
                row.full_name,
                row.school_internal_id,
                row.school_student_id,
                row.school_name,
                row.city,
                row.phone
            ].filter(Boolean).join(' ').toLowerCase();
            if (!searchStr.includes(search)) return false;
        }
        
        return true;
    });
    
    const totalPages = Math.ceil(filteredData.length / perPage);
    if (currentPage > totalPages) currentPage = totalPages || 1;
    
    const start = (currentPage - 1) * perPage;
    const end = start + perPage;
    const pageData = filteredData.slice(start, end);
    
    const tbody = document.getElementById('previewBody');
    tbody.innerHTML = '';
    
    pageData.forEach(row => {
        const tr = document.createElement('tr');
        tr.className = row._valid ? '' : 'invalid';
        
        tr.innerHTML = `
            <td class="row-num">${row._rowNum}</td>
            <td class="row-status">
                ${row._valid 
                    ? '<svg class="status-icon valid" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>'
                    : '<svg class="status-icon invalid" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>'
                }
            </td>
            <td><div class="cell-value">${escapeHtml(row.full_name || '')}</div></td>
            <td><div class="cell-value">${escapeHtml(row.school_internal_id || '')}</div></td>
            <td><div class="cell-value">${escapeHtml(row.school_student_id || '')}</div></td>
            <td><div class="cell-value">${escapeHtml(row.school_name || '')}</div></td>
            <td>${row.grade || ''}</td>
            <td>${escapeHtml(row.current_state || '')}</td>
            <td>${escapeHtml(row.dob || '')}</td>
            <td><div class="cell-value">${escapeHtml(row.phone || '')}</div></td>
            <td><div class="cell-value">${escapeHtml(row.city || '')}</div></td>
            <td>${row._errors.length ? '<div class="cell-error">' + row._errors.join(', ') + '</div>' : ''}</td>
        `;
        
        tbody.appendChild(tr);
    });
    
    // Update info
    document.getElementById('showingStart').textContent = filteredData.length ? start + 1 : 0;
    document.getElementById('showingEnd').textContent = Math.min(end, filteredData.length);
    document.getElementById('showingTotal').textContent = filteredData.length;
    
    // Render pagination
    renderPagination(totalPages);
}

function renderPagination(totalPages) {
    const pagination = document.getElementById('pagination');
    pagination.innerHTML = '';
    
    if (totalPages <= 1) return;
    
    // Prev button
    const prevBtn = document.createElement('button');
    prevBtn.textContent = '‹';
    prevBtn.disabled = currentPage === 1;
    prevBtn.onclick = () => { currentPage--; renderTable(); };
    pagination.appendChild(prevBtn);
    
    // Page numbers
    for (let i = 1; i <= totalPages; i++) {
        if (totalPages > 7 && i > 2 && i < totalPages - 1 && Math.abs(i - currentPage) > 1) {
            if (i === 3 || i === totalPages - 2) {
                const dots = document.createElement('span');
                dots.textContent = '...';
                dots.style.padding = '6px 8px';
                pagination.appendChild(dots);
            }
            continue;
        }
        
        const btn = document.createElement('button');
        btn.textContent = i;
        btn.className = i === currentPage ? 'active' : '';
        btn.onclick = () => { currentPage = i; renderTable(); };
        pagination.appendChild(btn);
    }
    
    // Next button
    const nextBtn = document.createElement('button');
    nextBtn.textContent = '›';
    nextBtn.disabled = currentPage === totalPages;
    nextBtn.onclick = () => { currentPage++; renderTable(); };
    pagination.appendChild(nextBtn);
}

function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function clearPreview() {
    allData = [];
    filteredData = [];
    currentPage = 1;
    document.getElementById('previewContainer').classList.remove('show');
    document.getElementById('fileInput').value = '';
    document.getElementById('fileInfo').classList.remove('show');
}

function submitImport() {
    const validData = allData.filter(r => r._valid).map(r => {
        const cleaned = {...r};
        delete cleaned._rowNum;
        delete cleaned._errors;
        delete cleaned._valid;
        return cleaned;
    });
    
    if (validData.length === 0) {
        alert('No valid records to import');
        return;
    }
    
    if (!confirm(`Import ${validData.length} students?`)) return;
    
    document.getElementById('importDataInput').value = JSON.stringify(validData);
    document.getElementById('importForm').submit();
    showLoading(true);
}

function showLoading(show) {
    document.getElementById('loadingOverlay').classList.toggle('show', show);
}

// Event listeners
document.getElementById('dtSearch').addEventListener('input', () => {
    currentPage = 1;
    renderTable();
});

document.getElementById('dtFilter').addEventListener('change', () => {
    currentPage = 1;
    renderTable();
});

// Sortable columns
document.querySelectorAll('.dt-table th.sortable').forEach(th => {
    th.addEventListener('click', () => {
        const col = th.dataset.col;
        const isAsc = th.classList.contains('sort-asc');
        
        document.querySelectorAll('.dt-table th.sortable').forEach(h => h.classList.remove('sort-asc', 'sort-desc'));
        th.classList.add(isAsc ? 'sort-desc' : 'sort-asc');
        
        allData.sort((a, b) => {
            const aVal = (a[col] || '').toString().toLowerCase();
            const bVal = (b[col] || '').toString().toLowerCase();
            return isAsc ? bVal.localeCompare(aVal) : aVal.localeCompare(bVal);
        });
        
        renderTable();
    });
});
</script>
@endsection
