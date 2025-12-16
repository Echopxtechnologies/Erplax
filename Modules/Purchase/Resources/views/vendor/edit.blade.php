@include('purchase::partials.styles')


<div class="page-header">
    <h1>Edit Vendor: {{ $vendor->name }}</h1>
    <a href="{{ route('admin.purchase.vendors.index') }}" class="btn btn-outline">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
        Back
    </a>
</div>

@if($errors->any())
<div class="alert alert-danger">
    <ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
</div>
@endif

<form action="{{ route('admin.purchase.vendors.update', $vendor->id) }}" method="POST" id="vendorForm">
    @csrf
    @method('PUT')

    <div class="card">
        <div class="card-header"><h5>Basic Information</h5></div>
        <div class="card-body">
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Vendor Code</label>
                    <input type="text" class="form-control" value="{{ $vendor->vendor_code }}" readonly>
                </div>
                <div class="form-group col-2">
                    <label class="form-label">Vendor Name <span class="required">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $vendor->name) }}" required minlength="2" maxlength="191">
                    <div class="invalid-feedback">Vendor name is required (2-191 characters)</div>
                </div>
                <div class="form-group">
                    <label class="form-label">Display Name</label>
                    <input type="text" name="display_name" class="form-control" value="{{ old('display_name', $vendor->display_name) }}" maxlength="191">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Contact Person</label>
                    <input type="text" name="contact_person" class="form-control" value="{{ old('contact_person', $vendor->contact_person) }}" maxlength="191">
                </div>
                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $vendor->email) }}" maxlength="191">
                    <div class="invalid-feedback">Please enter a valid email address</div>
                </div>
                <div class="form-group">
                    <label class="form-label">Phone</label>
                    <input type="tel" name="phone" class="form-control" value="{{ old('phone', $vendor->phone) }}" maxlength="20">
                </div>
                <div class="form-group">
                    <label class="form-label">Mobile</label>
                    <input type="tel" name="mobile" class="form-control" value="{{ old('mobile', $vendor->mobile) }}" maxlength="20">
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h5>Tax Information</h5></div>
        <div class="card-body">
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">GST Type <span class="required">*</span></label>
                    <select name="gst_type" id="gstType" class="form-control" required onchange="toggleGstFields()">
                        <option value="REGISTERED" {{ old('gst_type', $vendor->gst_type) == 'REGISTERED' ? 'selected' : '' }}>Registered</option>
                        <option value="UNREGISTERED" {{ old('gst_type', $vendor->gst_type) == 'UNREGISTERED' ? 'selected' : '' }}>Unregistered</option>
                        <option value="COMPOSITION" {{ old('gst_type', $vendor->gst_type) == 'COMPOSITION' ? 'selected' : '' }}>Composition</option>
                        <option value="SEZ" {{ old('gst_type', $vendor->gst_type) == 'SEZ' ? 'selected' : '' }}>SEZ</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">GST Number <span class="required gst-required">*</span></label>
                    <input type="text" name="gst_number" id="gstNumber" class="form-control" value="{{ old('gst_number', $vendor->gst_number) }}" maxlength="15" placeholder="22AAAAA0000A1Z5" style="text-transform: uppercase;">
                    <div class="invalid-feedback">Enter valid 15-character GST number</div>
                </div>
                <div class="form-group">
                    <label class="form-label">PAN Number</label>
                    <input type="text" name="pan_number" id="panNumber" class="form-control" value="{{ old('pan_number', $vendor->pan_number) }}" maxlength="10" placeholder="AAAAA0000A" style="text-transform: uppercase;">
                    <div class="invalid-feedback">Enter valid 10-character PAN number</div>
                </div>
                <div class="form-group">
                    <label class="form-label">Status <span class="required">*</span></label>
                    <select name="status" class="form-control" required>
                        <option value="ACTIVE" {{ old('status', $vendor->status) == 'ACTIVE' ? 'selected' : '' }}>Active</option>
                        <option value="INACTIVE" {{ old('status', $vendor->status) == 'INACTIVE' ? 'selected' : '' }}>Inactive</option>
                        <option value="BLOCKED" {{ old('status', $vendor->status) == 'BLOCKED' ? 'selected' : '' }}>Blocked</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h5>Billing Address</h5></div>
        <div class="card-body">
            <div class="form-row">
                <div class="form-group col-full">
                    <label class="form-label">Address</label>
                    <textarea name="billing_address" class="form-control" rows="2" maxlength="500">{{ old('billing_address', $vendor->billing_address) }}</textarea>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">City</label>
                    <input type="text" name="billing_city" class="form-control" value="{{ old('billing_city', $vendor->billing_city) }}" maxlength="100">
                </div>
                <div class="form-group">
                    <label class="form-label">State</label>
                    <input type="text" name="billing_state" class="form-control" value="{{ old('billing_state', $vendor->billing_state) }}" maxlength="100">
                </div>
                <div class="form-group">
                    <label class="form-label">Pincode</label>
                    <input type="text" name="billing_pincode" class="form-control" value="{{ old('billing_pincode', $vendor->billing_pincode) }}" maxlength="10">
                </div>
                <div class="form-group">
                    <label class="form-label">Country</label>
                    <input type="text" name="billing_country" class="form-control" value="{{ old('billing_country', $vendor->billing_country ?? 'India') }}" maxlength="100">
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h5>Financial Settings</h5></div>
        <div class="card-body">
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Payment Terms</label>
                    <select name="payment_terms" class="form-control">
                        <option value="Immediate" {{ old('payment_terms', $vendor->payment_terms) == 'Immediate' ? 'selected' : '' }}>Immediate</option>
                        <option value="Net 15" {{ old('payment_terms', $vendor->payment_terms) == 'Net 15' ? 'selected' : '' }}>Net 15</option>
                        <option value="Net 30" {{ old('payment_terms', $vendor->payment_terms) == 'Net 30' ? 'selected' : '' }}>Net 30</option>
                        <option value="Net 45" {{ old('payment_terms', $vendor->payment_terms) == 'Net 45' ? 'selected' : '' }}>Net 45</option>
                        <option value="Net 60" {{ old('payment_terms', $vendor->payment_terms) == 'Net 60' ? 'selected' : '' }}>Net 60</option>
                        <option value="Net 90" {{ old('payment_terms', $vendor->payment_terms) == 'Net 90' ? 'selected' : '' }}>Net 90</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Credit Days</label>
                    <input type="number" name="credit_days" class="form-control" value="{{ old('credit_days', $vendor->credit_days) }}" min="0" max="365">
                </div>
                <div class="form-group">
                    <label class="form-label">Credit Limit (₹)</label>
                    <input type="number" name="credit_limit" class="form-control" value="{{ old('credit_limit', $vendor->credit_limit) }}" step="0.01" min="0">
                </div>
                <div class="form-group">
                    <label class="form-label">Current Balance (₹)</label>
                    <input type="text" class="form-control" value="{{ number_format($vendor->current_balance ?? 0, 2) }}" readonly>
                </div>
            </div>
        </div>
    </div>

    <!-- Bank Details -->
    <div class="card">
        <div class="card-header"><h5>🏦 Bank Details</h5></div>
        <div class="card-body">
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Account Holder Name</label>
                    <input type="text" name="bank_account_holder" class="form-control" value="{{ old('bank_account_holder', $bankDetail->account_holder_name ?? '') }}" placeholder="Account holder name">
                </div>
                <div class="form-group">
                    <label class="form-label">Bank Name</label>
                    <input type="text" name="bank_name" class="form-control" value="{{ old('bank_name', $bankDetail->bank_name ?? '') }}" placeholder="e.g., State Bank of India">
                </div>
                <div class="form-group">
                    <label class="form-label">Account Number</label>
                    <input type="text" name="bank_account_number" class="form-control" value="{{ old('bank_account_number', $bankDetail->account_number ?? '') }}" placeholder="Account number">
                </div>
                <div class="form-group">
                    <label class="form-label">Account Type</label>
                    <select name="bank_account_type" class="form-control">
                        <option value="CURRENT" {{ old('bank_account_type', $bankDetail->account_type ?? 'CURRENT') == 'CURRENT' ? 'selected' : '' }}>Current</option>
                        <option value="SAVINGS" {{ old('bank_account_type', $bankDetail->account_type ?? '') == 'SAVINGS' ? 'selected' : '' }}>Savings</option>
                        <option value="OTHER" {{ old('bank_account_type', $bankDetail->account_type ?? '') == 'OTHER' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">IFSC Code</label>
                    <input type="text" name="bank_ifsc" class="form-control" value="{{ old('bank_ifsc', $bankDetail->ifsc_code ?? '') }}" placeholder="e.g., SBIN0001234" style="text-transform: uppercase;">
                </div>
                <div class="form-group">
                    <label class="form-label">Branch Name</label>
                    <input type="text" name="bank_branch" class="form-control" value="{{ old('bank_branch', $bankDetail->branch_name ?? '') }}" placeholder="Branch name">
                </div>
                <div class="form-group col-2">
                    <label class="form-label">UPI ID</label>
                    <input type="text" name="bank_upi_id" class="form-control" value="{{ old('bank_upi_id', $bankDetail->upi_id ?? '') }}" placeholder="e.g., vendor@upi">
                </div>
            </div>
        </div>
    </div>

    <div class="form-actions">
        <div class="form-actions-left">
            <button type="button" class="btn btn-danger" onclick="if(confirm('Are you sure you want to delete this vendor?')) document.getElementById('deleteForm').submit();">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                Delete
            </button>
        </div>
        <div class="form-actions-right">
            <a href="{{ route('admin.purchase.vendors.show', $vendor->id) }}" class="btn btn-outline">Cancel</a>
            <button type="submit" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/></svg>
                Update Vendor
            </button>
        </div>
    </div>
</form>

<form id="deleteForm" action="{{ route('admin.purchase.vendors.destroy', $vendor->id) }}" method="POST" style="display:none;">
    @csrf
    @method('DELETE')
</form>

<script>
function toggleGstFields() {
    const gstType = document.getElementById('gstType').value;
    const gstNumber = document.getElementById('gstNumber');
    const gstRequired = document.querySelector('.gst-required');
    
    if (gstType === 'UNREGISTERED') {
        gstNumber.disabled = true;
        gstNumber.value = '';
        gstNumber.removeAttribute('required');
        if (gstRequired) gstRequired.style.display = 'none';
    } else {
        gstNumber.disabled = false;
        gstNumber.setAttribute('required', 'required');
        if (gstRequired) gstRequired.style.display = 'inline';
    }
}

// Auto uppercase
document.getElementById('gstNumber').addEventListener('input', function() { this.value = this.value.toUpperCase(); });
document.getElementById('panNumber').addEventListener('input', function() { this.value = this.value.toUpperCase(); });

document.addEventListener('DOMContentLoaded', toggleGstFields);
</script>
