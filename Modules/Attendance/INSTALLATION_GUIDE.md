# Attendance Module - Installation Guide

## Overview

The Attendance Module combines the robust structure of the Book Module with the modern styling of the Calendar Module to provide a complete employee attendance management solution.

## Prerequisites

- Laravel 10+ or Nwidart Module Package
- PHP 8.1+
- Database with users table
- Admin panel with routing support

## Installation Steps

### Step 1: Extract the Module

1. Copy the `AttendanceModule` folder to your `modules` directory (typically `app/Modules` or `Modules`)
2. Ensure the directory structure is preserved:
   ```
   AttendanceModule/
   ├── Config/
   ├── Database/
   ├── Http/
   ├── Models/
   ├── Providers/
   ├── Resources/
   ├── Routes/
   └── module.json
   ```

### Step 2: Register the Module

If using Nwidart Module:
1. Run discovery: `php artisan package:discover`
2. The module should auto-register via `module.json`

If using custom module loading:
1. Add the service provider to `config/modules.php` or your service provider registration mechanism
2. Add: `Modules\Attendance\Providers\AttendanceServiceProvider::class`

### Step 3: Run Database Migrations

```bash
php artisan migrate
```

This will create the `attendances` table with the following structure:
- id (Primary Key)
- user_id (Foreign Key)
- attendance_date (Date)
- check_in_time (DateTime)
- check_out_time (DateTime)
- status (Enum)
- notes (Text)
- timestamps (created_at, updated_at)

### Step 4: Update Admin Menu (Optional)

To add the Attendance menu item to your admin navigation:

1. In your main navigation/menu file, include:
```blade
@include('attendance::menu')
```

Or manually add:
```blade
<li class="nav-item">
    <a href="{{ route('admin.attendance.index') }}" class="nav-link">
        <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <span>Attendance</span>
    </a>
</li>
```

### Step 5: Clear Cache

```bash
php artisan cache:clear
php artisan config:cache
```

### Step 6: Verify Installation

1. Visit: `http://yourapp.local/admin/attendance`
2. You should see the Attendance management dashboard
3. Click "Add Attendance" to create your first record

## Configuration

### Admin Controller Base Class

The AttendanceController extends `AdminController` which should provide:
- `authorizeAdmin()` - Check admin authorization
- `validateRequest()` - Validate incoming requests
- `logAction()` - Log user actions
- `logError()` - Log errors
- `redirectWithSuccess()` - Redirect with success message
- `redirectWithError()` - Redirect with error message

Make sure your `App\Http\Controllers\Admin\AdminController` includes these methods.

### Database Configuration

No additional configuration needed. The module uses the default database connection specified in your `.env` file.

## File Structure Explanation

```
AttendanceModule/
├── Config/                          # Configuration files
│   └── config.php                  # Module configuration
├── Database/
│   └── Migrations/                 # Database migrations
│       └── 2024_12_04_000001...    # Create attendances table
├── Http/
│   └── Controllers/
│       └── AttendanceController.php # Main CRUD controller
├── Models/
│   └── Attendance.php              # Eloquent model
├── Providers/
│   └── AttendanceServiceProvider.php # Service provider
├── Resources/
│   └── views/
│       ├── index.blade.php         # List all records
│       ├── create.blade.php        # Create form
│       ├── edit.blade.php          # Edit form
│       ├── show.blade.php          # View single record
│       └── menu.blade.php          # Navigation menu
├── Routes/
│   ├── web.php                     # Web routes
│   └── api.php                     # API routes
├── composer.json                   # Package metadata
├── module.json                     # Module metadata
└── README.md                       # Module documentation
```

## Routes Reference

All routes are automatically registered and prefixed with `/admin/attendance`:

| Method | Route | Name | Action |
|--------|-------|------|--------|
| GET | / | index | List all attendance |
| GET | /create | create | Show create form |
| POST | / | store | Save new record |
| GET | /{id} | show | View record |
| GET | /{id}/edit | edit | Show edit form |
| PUT | /{id} | update | Update record |
| DELETE | /{id} | destroy | Delete record |

## Usage Examples

### List Attendance Records
```blade
<a href="{{ route('admin.attendance.index') }}">View Attendance</a>
```

### Create New Record
```blade
<a href="{{ route('admin.attendance.create') }}">Add Attendance</a>
```

### Edit Record
```blade
<a href="{{ route('admin.attendance.edit', $attendance->id) }}">Edit</a>
```

### Delete Record
```blade
<form action="{{ route('admin.attendance.destroy', $attendance->id) }}" method="POST">
    @method('DELETE')
    @csrf
    <button type="submit">Delete</button>
</form>
```

## Styling Features

The module uses inline CSS with custom properties for theming:

- **Colors**: Primary, secondary, danger, success colors
- **Spacing**: Consistent spacing using CSS variables
- **Responsive**: Grid-based layout that works on all devices
- **Status Badges**: Color-coded status indicators
- **Form Controls**: Consistent form styling
- **Cards**: Modern card-based design

All styling is responsive and will adapt to mobile, tablet, and desktop screens.

## Troubleshooting

### Routes Not Working
1. Ensure the module is registered in your service provider
2. Run `php artisan route:clear` and `php artisan route:cache`
3. Check that middleware routes are configured correctly

### Migration Errors
1. Ensure users table exists in your database
2. Check database credentials in `.env`
3. Run: `php artisan migrate:status` to see migration status

### Authorization Issues
1. Ensure user has admin role/permission
2. Check `authorizeAdmin()` method implementation
3. Verify middleware is applied correctly

### Styling Issues
1. Ensure CSS variables are defined in your main layout
2. Check that views are being loaded correctly
3. Clear browser cache and refresh

## Support

For detailed theme customization, see the THEME_GUIDE.md file included in the module.

## License

MIT License - See LICENSE file for details
