<x-layouts.app>
    
    <div style="padding: 20px;">
        <div style="margin-bottom: 20px;">
            <h1>Create New Student</h1>
        </div>

        <div style="background: white; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); padding: 20px; max-width: 600px;">
            <form method="POST" action="{{ route('admin.student.store') }}">
                @csrf

                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600;">Name <span style="color: red;">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required style="width: 100%; padding: 10px; border: 1px solid #DEE2E6; border-radius: 5px; font-size: 14px; box-sizing: border-box;">
                    @error('name')
                        <span style="color: #E74C3C; font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600;">Email <span style="color: red;">*</span></label>
                    <input type="email" name="email" value="{{ old('email') }}" required style="width: 100%; padding: 10px; border: 1px solid #DEE2E6; border-radius: 5px; font-size: 14px; box-sizing: border-box;">
                    @error('email')
                        <span style="color: #E74C3C; font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600;">Phone</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" style="width: 100%; padding: 10px; border: 1px solid #DEE2E6; border-radius: 5px; font-size: 14px; box-sizing: border-box;">
                    @error('phone')
                        <span style="color: #E74C3C; font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600;">Course</label>
                    <input type="text" name="course" value="{{ old('course') }}" style="width: 100%; padding: 10px; border: 1px solid #DEE2E6; border-radius: 5px; font-size: 14px; box-sizing: border-box;">
                    @error('course')
                        <span style="color: #E74C3C; font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600;">Status</label>
                    <select name="status" style="width: 100%; padding: 10px; border: 1px solid #DEE2E6; border-radius: 5px; font-size: 14px; box-sizing: border-box;">
                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="graduated" {{ old('status') == 'graduated' ? 'selected' : '' }}>Graduated</option>
                    </select>
                    @error('status')
                        <span style="color: #E74C3C; font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600;">Admission Date</label>
                    <input type="date" name="admission_date" value="{{ old('admission_date') }}" style="width: 100%; padding: 10px; border: 1px solid #DEE2E6; border-radius: 5px; font-size: 14px; box-sizing: border-box;">
                    @error('admission_date')
                        <span style="color: #E74C3C; font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>

                <div style="display: flex; gap: 10px;">
                    <button type="submit" style="background: #27AE60; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-weight: 600;">
                        Create Student
                    </button>
                    <a href="{{ route('admin.student.index') }}" style="background: #95A5A6; color: white; padding: 10px 20px; border-radius: 5px; text-decoration: none; display: inline-block; font-weight: 600;">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
