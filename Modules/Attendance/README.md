# Attendance Module

Employee attendance management module with a modern Calendar-style user interface.

## Features

- **Complete CRUD Operations**: Create, Read, Update, and Delete attendance records
- **Employee Management**: Track attendance by employee with user relationships
- **Time Tracking**: Record check-in and check-out times
- **Status Management**: Multiple attendance statuses (Present, Absent, Late, Half-Day, On Leave)
- **Advanced Filtering**: Filter by employee, status, and date range
- **Responsive Design**: Modern, clean UI using Calendar styling with CSS variables
- **Admin Dashboard Integration**: Seamless integration with your admin panel

## Installation

1. Extract the `AttendanceModule` folder to your `modules` directory
2. Register the module in your application
3. Run migrations: `php artisan migrate`
4. Clear cache: `php artisan cache:clear`
5. Visit `/admin/attendance` to access the module

## Database Structure

The attendance table includes:
- `id` - Primary key
- `user_id` - Foreign key to users table
- `attendance_date` - Date of attendance
- `check_in_time` - Check-in timestamp
- `check_out_time` - Check-out timestamp
- `status` - Attendance status (present, absent, late, half-day, on-leave)
- `notes` - Additional notes or remarks
- `timestamps` - Created and updated timestamps

## Routes

All routes are prefixed with `/admin/attendance` and require admin authentication:

- `GET /` - List all attendance records
- `GET /create` - Show create form
- `POST /` - Store new attendance record
- `GET /{id}` - View specific record
- `GET /{id}/edit` - Show edit form
- `PUT /{id}` - Update record
- `DELETE /{id}` - Delete record

## Styling

The module uses the same modern styling as the Calendar module with CSS custom properties for consistency:
- `--space-lg`, `--space-md`, `--space-sm` - Spacing utilities
- `--text-primary`, `--text-secondary`, `--text-muted` - Text colors
- `--card-border` - Border colors
- `--danger` - Danger color for statuses
- `--form-control` - Form styling

All styling is inline with responsive grid layouts for optimal viewing on all devices.

## Status Types

- **Present**: Employee was present
- **Absent**: Employee was absent
- **Late**: Employee arrived late
- **Half-Day**: Employee worked half the day
- **On Leave**: Employee was on approved leave

## Features Comparison

### Book Module Structure
✓ Complete CRUD operations inherited from Book Module
✓ Admin controller with authorization
✓ Comprehensive validation
✓ Action logging
✓ Error handling

### Calendar Module Styling
✓ Modern card-based layout
✓ Responsive table design
✓ Status badges with color coding
✓ Inline CSS using variables
✓ Modal dialogs
✓ Flexible form layouts

## Support

For issues or questions, please refer to the installation guide or contact your system administrator.
