@include('purchase::partials.styles')

<div style="padding: 20px;">
    <div class="page-header">
        <h1>
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" width="28" height="28"><path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
            Add Vendor
        </h1>
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

    <form action="{{ route('admin.purchase.vendors.store') }}" method="POST" id="vendorForm">
        @csrf

        <div class="card">
            <div class="card-header"><h5>Basic Information</h5></div>
            <div class="card-body">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Vendor Code <span class="required">*</span></label>
                        <input type="text" name="vendor_code" class="form-control" value="{{ $vendorCode }}" readonly>
                    </div>
                    <div class="form-group col-2">
                        <label class="form-label">Vendor Name <span class="required">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required minlength="2" maxlength="191">
                        @error('name')<div class="invalid-feedback" style="display:block;color:var(--danger)">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Display Name</label>
                        <input type="text" name="display_name" class="form-control" value="{{ old('display_name') }}" maxlength="191">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Contact Person</label>
                        <input type="text" name="contact_person" class="form-control" value="{{ old('contact_person') }}" maxlength="191">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" maxlength="191">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Phone</label>
                        <input type="tel" name="phone" class="form-control" value="{{ old('phone') }}" maxlength="20">
                        <div class="form-text">Format: +91 9876543210</div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Mobile</label>
                        <input type="tel" name="mobile" class="form-control" value="{{ old('mobile') }}" maxlength="20">
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
                            <option value="REGISTERED" {{ old('gst_type', 'REGISTERED') == 'REGISTERED' ? 'selected' : '' }}>Registered</option>
                            <option value="UNREGISTERED" {{ old('gst_type') == 'UNREGISTERED' ? 'selected' : '' }}>Unregistered</option>
                            <option value="COMPOSITION" {{ old('gst_type') == 'COMPOSITION' ? 'selected' : '' }}>Composition</option>
                            <option value="SEZ" {{ old('gst_type') == 'SEZ' ? 'selected' : '' }}>SEZ</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">GST Number <span class="required gst-required">*</span></label>
                        <input type="text" name="gst_number" id="gstNumber" class="form-control" value="{{ old('gst_number') }}" maxlength="15" placeholder="22AAAAA0000A1Z5" style="text-transform: uppercase;">
                        <div class="form-text">Format: 22AAAAA0000A1Z5</div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">PAN Number</label>
                        <input type="text" name="pan_number" id="panNumber" class="form-control" value="{{ old('pan_number') }}" maxlength="10" placeholder="AAAAA0000A" style="text-transform: uppercase;">
                        <div class="form-text">Format: AAAAA0000A</div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Status <span class="required">*</span></label>
                        <select name="status" class="form-control" required>
                            <option value="ACTIVE" {{ old('status', 'ACTIVE') == 'ACTIVE' ? 'selected' : '' }}>Active</option>
                            <option value="INACTIVE" {{ old('status') == 'INACTIVE' ? 'selected' : '' }}>Inactive</option>
                            <option value="BLOCKED" {{ old('status') == 'BLOCKED' ? 'selected' : '' }}>Blocked</option>
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
                        <textarea name="billing_address" class="form-control" rows="2">{{ old('billing_address') }}</textarea>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">City</label>
                        <input type="text" name="billing_city" class="form-control" value="{{ old('billing_city') }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">State</label>
                        <input type="text" name="billing_state" class="form-control" value="{{ old('billing_state') }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">PIN Code</label>
                        <input type="text" name="billing_pincode" class="form-control" value="{{ old('billing_pincode') }}" maxlength="10">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Country</label>
                        <input type="text" name="billing_country" class="form-control" value="{{ old('billing_country', 'India') }}">
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header"><h5>Payment Terms</h5></div>
            <div class="card-body">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Payment Terms</label>
                        <select name="payment_terms" class="form-control">
                            <option value="Immediate" {{ old('payment_terms') == 'Immediate' ? 'selected' : '' }}>Immediate</option>
                            <option value="Net 15" {{ old('payment_terms') == 'Net 15' ? 'selected' : '' }}>Net 15</option>
                            <option value="Net 30" {{ old('payment_terms', 'Net 30') == 'Net 30' ? 'selected' : '' }}>Net 30</option>
                            <option value="Net 45" {{ old('payment_terms') == 'Net 45' ? 'selected' : '' }}>Net 45</option>
                            <option value="Net 60" {{ old('payment_terms') == 'Net 60' ? 'selected' : '' }}>Net 60</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Credit Days</label>
                        <input type="number" name="credit_days" class="form-control" value="{{ old('credit_days', 30) }}" min="0" max="365">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Credit Limit (₹)</label>
                        <input type="number" name="credit_limit" class="form-control" value="{{ old('credit_limit', 0) }}" step="0.01" min="0">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Opening Balance (₹)</label>
                        <input type="number" name="opening_balance" class="form-control" value="{{ old('opening_balance', 0) }}" step="0.01">
                        <div class="form-text">Positive = payable, Negative = advance</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header"><h5>🏦 Bank Details</h5></div>
            <div class="card-body">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Account Holder Name</label>
                        <input type="text" name="bank_account_holder" class="form-control" value="{{ old('bank_account_holder') }}" placeholder="Account holder name">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Bank Name</label>
                        <input type="text" name="bank_name" class="form-control" value="{{ old('bank_name') }}" placeholder="e.g., State Bank of India">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Account Number</label>
                        <input type="text" name="bank_account_number" class="form-control" value="{{ old('bank_account_number') }}" placeholder="Account number">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Account Type</label>
                        <select name="bank_account_type" class="form-control">
                            <option value="CURRENT" {{ old('bank_account_type') == 'CURRENT' ? 'selected' : '' }}>Current</option>
                            <option value="SAVINGS" {{ old('bank_account_type') == 'SAVINGS' ? 'selected' : '' }}>Savings</option>
                            <option value="OTHER" {{ old('bank_account_type') == 'OTHER' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">IFSC Code</label>
                        <input type="text" name="bank_ifsc" class="form-control" value="{{ old('bank_ifsc') }}" placeholder="e.g., SBIN0001234" style="text-transform: uppercase;">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Branch Name</label>
                        <input type="text" name="bank_branch" class="form-control" value="{{ old('bank_branch') }}" placeholder="Branch name">
                    </div>
                    <div class="form-group col-2">
                        <label class="form-label">UPI ID</label>
                        <input type="text" name="bank_upi_id" class="form-control" value="{{ old('bank_upi_id') }}" placeholder="e.g., vendor@upi">
                    </div>
                </div>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                Save Vendor
            </button>
            <a href="{{ route('admin.purchase.vendors.index') }}" class="btn btn-outline">Cancel</a>
        </div>
    </form>
</div>

<script>
function toggleGstFields() {
    const gstType = document.getElementById('gstType').value;
    const gstNumber = document.getElementById('gstNumber');
    const gstRequired = document.querySelector('.gst-required');
    
    if (gstType === 'UNREGISTERED') {
        gstNumber.disabled = true;
        gstNumber.value = '';
        gstNumber.removeAttribute('required');
        if(gstRequired) gstRequired.style.display = 'none';
    } else {
        gstNumber.disabled = false;
        gstNumber.setAttribute('required', 'required');
        if(gstRequired) gstRequired.style.display = 'inline';
    }
}

document.getElementById('gstNumber')?.addEventListener('input', function() { this.value = this.value.toUpperCase(); });
document.getElementById('panNumber')?.addEventListener('input', function() { this.value = this.value.toUpperCase(); });
document.addEventListener('DOMContentLoaded', toggleGstFields);
</script>
