<style>
.pos-header{display:flex;justify-content:space-between;align-items:center;margin-bottom:24px}
.pos-header h1{font-size:24px;font-weight:700;color:var(--text-primary);margin:0;display:flex;align-items:center;gap:10px}
.pos-header h1 svg{width:28px;height:28px;color:var(--primary)}
.settings-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(400px,1fr));gap:24px}
.settings-card{background:var(--card-bg);border:1px solid var(--card-border);border-radius:12px;overflow:hidden}
.settings-card-header{padding:16px 20px;border-bottom:1px solid var(--card-border);font-size:16px;font-weight:600;color:var(--text-primary);display:flex;align-items:center;gap:10px}
.settings-card-body{padding:20px}
.form-group{margin-bottom:20px}
.form-group:last-child{margin-bottom:0}
.form-label{display:block;font-size:13px;font-weight:600;color:var(--text-primary);margin-bottom:8px}
.form-input{width:100%;height:44px;border:1px solid var(--input-border);border-radius:8px;padding:0 14px;font-size:14px;background:var(--input-bg);color:var(--input-text)}
.form-input:focus{outline:none;border-color:var(--primary);box-shadow:0 0 0 3px rgba(59,130,246,0.1)}
.form-row{display:grid;grid-template-columns:1fr 1fr;gap:16px}
.form-check{display:flex;align-items:center;gap:12px;padding:16px;background:var(--body-bg);border-radius:8px;cursor:pointer}
.form-check input{width:20px;height:20px;accent-color:var(--primary)}
.form-check-label .title{font-weight:600;color:var(--text-primary);margin-bottom:4px}
.form-check-label .desc{font-size:13px;color:var(--text-muted)}
.btn-save{display:inline-flex;align-items:center;gap:8px;background:linear-gradient(135deg,var(--primary),var(--primary-hover));color:#fff;padding:12px 24px;border:none;border-radius:8px;font-weight:600;font-size:14px;cursor:pointer;transition:all 0.2s}
.btn-save:hover{transform:translateY(-2px);box-shadow:0 4px 12px rgba(59,130,246,0.3)}
.btn-save svg{width:18px;height:18px}
.staff-table{width:100%;border-collapse:collapse}
.staff-table th{padding:12px 16px;text-align:left;font-size:12px;font-weight:700;color:var(--text-muted);text-transform:uppercase;background:var(--body-bg);border-bottom:2px solid var(--card-border)}
.staff-table td{padding:14px 16px;border-bottom:1px solid var(--card-border)}
.staff-row{display:flex;align-items:center;gap:12px}
.staff-avatar{width:40px;height:40px;background:linear-gradient(135deg,var(--primary),#8b5cf6);color:#fff;border-radius:10px;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:16px}
.staff-name{font-weight:600;color:var(--text-primary)}
.staff-email{font-size:12px;color:var(--text-muted)}
.staff-select{height:38px;border:1px solid var(--input-border);border-radius:6px;padding:0 10px;font-size:13px;background:var(--input-bg);color:var(--input-text);min-width:180px}
.badge{display:inline-flex;align-items:center;padding:4px 10px;border-radius:12px;font-size:12px;font-weight:600}
.badge-primary{background:var(--primary-light);color:var(--primary)}
.badge-warning{background:var(--warning-light);color:var(--warning)}
.alert{padding:12px 16px;border-radius:8px;margin-bottom:20px;font-weight:500}
.alert-success{background:var(--success-light);color:var(--success)}
</style>

<div style="padding:20px">
<div class="pos-header"><h1><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>POS Settings</h1></div>

@if(session('success'))<div class="alert alert-success">✓ {{ session('success') }}</div>@endif

<form action="{{ route('admin.pos.settings.save') }}" method="POST">@csrf
<div class="settings-grid">
<div class="settings-card">
<div class="settings-card-header">🏪 Store Information</div>
<div class="settings-card-body">
<div class="form-group"><label class="form-label">Store Name *</label><input type="text" name="store_name" class="form-input" value="{{ $settings->store_name }}" required></div>
<div class="form-row">
<div class="form-group"><label class="form-label">Phone</label><input type="text" name="store_phone" class="form-input" value="{{ $settings->store_phone }}"></div>
<div class="form-group"><label class="form-label">GSTIN</label><input type="text" name="store_gstin" class="form-input" value="{{ $settings->store_gstin }}"></div>
</div>
<div class="form-group"><label class="form-label">Address</label><input type="text" name="store_address" class="form-input" value="{{ $settings->store_address }}"></div>
</div>
</div>

<div class="settings-card">
<div class="settings-card-header">🧾 Invoice & Tax Settings</div>
<div class="settings-card-body">
<div class="form-row">
<div class="form-group"><label class="form-label">Invoice Prefix *</label><input type="text" name="invoice_prefix" class="form-input" value="{{ $settings->invoice_prefix }}" required></div>
<div class="form-group"><label class="form-label">Tax Rate (%) *</label><input type="number" name="default_tax_rate" class="form-input" value="{{ $settings->default_tax_rate }}" step="0.01" min="0" max="100" required></div>
</div>
<div class="form-group"><label class="form-check"><input type="checkbox" name="tax_inclusive" value="1" {{ $settings->tax_inclusive ? 'checked' : '' }}><div class="form-check-label"><div class="title">Tax Inclusive Pricing (MRP)</div><div class="desc">Prices already include tax</div></div></label></div>
<div class="form-group"><label class="form-label">Default Warehouse</label><select name="default_warehouse_id" class="form-input"><option value="">— Select —</option>@foreach($warehouses as $wh)<option value="{{ $wh->id }}" {{ $settings->default_warehouse_id == $wh->id ? 'selected' : '' }}>{{ $wh->name }}</option>@endforeach</select></div>
<div class="form-group"><label class="form-label">Receipt Footer</label><input type="text" name="receipt_footer" class="form-input" value="{{ $settings->receipt_footer }}" placeholder="Thank you!"></div>
<button type="submit" class="btn-save"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>Save Settings</button>
</div>
</div>
</div>
</form>

<div class="settings-card" style="margin-top:24px">
<div class="settings-card-header">👥 Staff Warehouse Assignment</div>
<div style="padding:0">
@if(count($warehouses) == 0)
<div style="padding:40px;text-align:center;color:var(--text-muted)"><p>Create warehouses in Inventory module first</p></div>
@else
<table class="staff-table">
<thead><tr><th>Staff Member</th><th>Current Warehouse</th><th>Assign Warehouse</th></tr></thead>
<tbody>
@foreach($staff as $member)
<tr>
<td><div class="staff-row"><div class="staff-avatar">{{ strtoupper(substr($member->name, 0, 1)) }}</div><div><div class="staff-name">{{ $member->name }}</div><div class="staff-email">{{ $member->email }}</div></div></div></td>
<td>@php $wh = collect($warehouses)->firstWhere('id', $member->warehouse_id); @endphp @if($wh)<span class="badge badge-primary">{{ $wh->name }}</span>@else<span class="badge badge-warning">Not Assigned</span>@endif</td>
<td><select class="staff-select" onchange="assignWarehouse({{ $member->id }}, this.value)"><option value="">— No Warehouse —</option>@foreach($warehouses as $wh)<option value="{{ $wh->id }}" {{ $member->warehouse_id == $wh->id ? 'selected' : '' }}>{{ $wh->name }}</option>@endforeach</select></td>
</tr>
@endforeach
</tbody>
</table>
@endif
</div>
</div>
</div>

<script>
function assignWarehouse(staffId, warehouseId) {
    fetch('{{ route("admin.pos.settings.assign") }}', {method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content},body:JSON.stringify({staff_id:staffId,warehouse_id:warehouseId})}).then(r=>r.json()).then(d=>{if(d.success)location.reload();});
}
</script>
