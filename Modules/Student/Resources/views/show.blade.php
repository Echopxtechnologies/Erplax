<x-layouts.app>
    <div style="padding: 20px;">
        <div style="margin-bottom: 20px;">
            <a href="{{ route('admin.student.index') }}" style="color: #3498DB; text-decoration: none;">← Back to Students</a>
        </div>

        <div style="background: white; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); padding: 20px; max-width: 600px;">
            <h1 style="margin-bottom: 20px;">{{ $student->name }}</h1>

            <div style="margin-bottom: 20px;">
                <h3 style="color: #95A5A6; font-size: 14px; margin-bottom: 5px;">Email</h3>
                <p style="font-size: 16px; margin: 0;">{{ $student->email }}</p>
            </div>

            @if($student->phone)
                <div style="margin-bottom: 20px;">
                    <h3 style="color: #95A5A6; font-size: 14px; margin-bottom: 5px;">Phone</h3>
                    <p style="font-size: 16px; margin: 0;">{{ $student->phone }}</p>
                </div>
            @endif

            @if($student->course)
                <div style="margin-bottom: 20px;">
                    <h3 style="color: #95A5A6; font-size: 14px; margin-bottom: 5px;">Course</h3>
                    <p style="font-size: 16px; margin: 0;">{{ $student->course }}</p>
                </div>
            @endif

            <div style="margin-bottom: 20px;">
                <h3 style="color: #95A5A6; font-size: 14px; margin-bottom: 5px;">Status</h3>
                <p style="font-size: 16px; margin: 0;">
                    <span style="background: {{ $student->status == 'active' ? '#27AE60' : ($student->status == 'inactive' ? '#95A5A6' : '#3498DB') }}; color: white; padding: 4px 12px; border-radius: 20px; font-size: 12px;">
                        {{ ucfirst($student->status) }}
                    </span>
                </p>
            </div>

            @if($student->admission_date)
                <div style="margin-bottom: 20px;">
                    <h3 style="color: #95A5A6; font-size: 14px; margin-bottom: 5px;">Admission Date</h3>
                    <p style="font-size: 16px; margin: 0;">{{ $student->admission_date->format('d M Y') }}</p>
                </div>
            @endif

            <div style="display: flex; gap: 10px;">
                <a href="{{ route('admin.student.edit', $student->id) }}" style="background: #F39C12; color: white; padding: 10px 20px; border-radius: 5px; text-decoration: none; display: inline-block; font-weight: 600;">
                    Edit
                </a>
                <form method="POST" action="{{ route('admin.student.destroy', $student->id) }}" style="display: inline-block;" onsubmit="return confirm('Are you sure?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" style="background: #E74C3C; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-weight: 600;">
                        Delete
                    </button>
                </form>
                <a href="{{ route('admin.student.index') }}" style="background: #95A5A6; color: white; padding: 10px 20px; border-radius: 5px; text-decoration: none; display: inline-block; font-weight: 600;">
                    Back
                </a>
            </div>
        </div>
    </div>
</x-layouts.app>
