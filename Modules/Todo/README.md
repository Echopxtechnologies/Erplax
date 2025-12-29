# Todo Module v2.0

A complete task management module using **DataTable v2.0** with full CRUD, export (CSV/Excel/PDF), import with auto-lookup, and multiple bulk actions.

## Features

- ✅ Task creation, editing, deletion
- ✅ Priority levels (Low, Medium, High)
- ✅ Status tracking (Pending, In Progress, Completed)
- ✅ Due date with overdue detection
- ✅ Task assignment (Admin only)
- ✅ Access control (Admin sees all, users see own + assigned)
- ✅ Search & filtering
- ✅ Export to CSV, Excel, PDF
- ✅ Import from Excel/CSV with auto-lookup
- ✅ Multiple bulk actions (Delete, Mark Completed, Mark Pending, Mark In Progress)
- ✅ Overdue notifications
- ✅ Real-time stats dashboard

---

## Requirements

- Laravel 10+
- Core Module with `DataTableTrait` v2.0
- PhpSpreadsheet (for Excel export/import)
- DomPDF (for PDF export)

---

## Installation

1. Copy the `Todo` folder to `Modules/`
2. Run migrations:
   ```bash
   php artisan migrate
   ```
3. Clear cache:
   ```bash
   php artisan optimize:clear
   ```

---

## File Structure

```
Todo/
├── Config/
│   └── config.php
├── Console/Commands/
│   └── CheckOverdueTasks.php
├── Database/Migrations/
│   └── 2024_12_05_000001_create_todos_table.php
├── Http/Controllers/
│   ├── Client/
│   │   └── ClientTodoController.php
│   └── TodoController.php          ← Uses DataTableTrait v2.0
├── Models/
│   └── Todo.php
├── Providers/
│   ├── RouteServiceProvider.php
│   └── TodoServiceProvider.php
├── Resources/views/
│   ├── client/
│   │   ├── index.blade.php
│   │   └── show.blade.php
│   ├── create.blade.php
│   ├── edit.blade.php
│   ├── index.blade.php             ← DataTable UI
│   ├── menu.blade.php
│   └── show.blade.php
├── Routes/
│   ├── api.php
│   ├── client.php
│   └── web.php
├── composer.json
├── module.json
└── README.md
```

---

## DataTable v2.0 Configuration

The `TodoController` uses `DataTableTrait` with these configurations:

```php
use DataTableTrait;

protected $model = Todo::class;
protected $with = ['user', 'assignee'];
protected $searchable = ['title', 'description'];
protected $sortable = ['id', 'title', 'priority', 'status', 'due_date', 'created_at'];
protected $filterable = ['status', 'priority', 'assigned_to', 'user_id'];
protected $routePrefix = 'admin.todo';

// Import with validation
protected $importable = [
    'title'       => 'required|string|max:255',
    'description' => 'nullable|string',
    'priority'    => 'in:low,medium,high',
    'status'      => 'in:pending,in_progress,completed',
    'due_date'    => 'nullable|date',
];

// Auto-lookup: Excel has "assignee_name" → saves as "assigned_to" ID
protected $importLookups = [
    'assignee_name' => [
        'table'   => 'users',
        'search'  => 'name',
        'return'  => 'id',
        'save_as' => 'assigned_to',
    ],
];

// Default values
protected $importDefaults = [
    'priority' => 'medium',
    'status'   => 'pending',
];

// Bulk actions dropdown
protected $bulkActions = [
    'delete'      => ['label' => 'Delete', 'confirm' => true, 'color' => 'red'],
    'complete'    => ['label' => 'Mark Completed', 'color' => 'green'],
    'pending'     => ['label' => 'Mark Pending', 'color' => 'yellow'],
    'in_progress' => ['label' => 'Mark In Progress', 'color' => 'blue'],
];
```

---

## Routes

| Method | URI | Name | Description |
|--------|-----|------|-------------|
| GET | `/admin/todo` | admin.todo.index | List page |
| GET/POST | `/admin/todo/data` | admin.todo.data | DataTable endpoint |
| POST | `/admin/todo/bulk-action` | admin.todo.bulk-action | Bulk actions |
| GET | `/admin/todo/create` | admin.todo.create | Create form |
| POST | `/admin/todo` | admin.todo.store | Store new task |
| GET | `/admin/todo/{id}` | admin.todo.show | View task |
| GET | `/admin/todo/{id}/edit` | admin.todo.edit | Edit form |
| PUT | `/admin/todo/{id}` | admin.todo.update | Update task |
| DELETE | `/admin/todo/{id}` | admin.todo.destroy | Delete task |
| POST | `/admin/todo/{id}/toggle-status` | admin.todo.toggle-status | Quick status change |

---

## Access Control

- **Admin users** (`is_admin = true`): See all tasks, can assign to any user
- **Regular users**: See only their own tasks + tasks assigned to them

This is implemented by overriding `dtList()` and `getExportData()` methods.

---

## Import Template

Download the import template from the DataTable UI. The template includes:

| Column | Description |
|--------|-------------|
| title | Task title (required) |
| description | Task description |
| priority | low, medium, high |
| status | pending, in_progress, completed |
| due_date | Date (YYYY-MM-DD) |
| assignee_name | User name (auto-converted to ID) |

**Auto-Lookup**: Enter the user's name in `assignee_name` column - it will automatically find the user and save their ID.

---

## Bulk Actions

Select tasks using checkboxes, then choose an action from the dropdown:

- **Delete** - Permanently delete selected tasks (with confirmation)
- **Mark Completed** - Set status to "completed"
- **Mark Pending** - Set status to "pending"  
- **Mark In Progress** - Set status to "in_progress"

---

## Overdue Detection

Tasks are considered overdue when:
- `due_date` is in the past
- `status` is NOT "completed"

The system can send notifications for overdue tasks via:
```bash
php artisan todo:check-overdue
```

Add to scheduler in `app/Console/Kernel.php`:
```php
$schedule->command('todo:check-overdue')->dailyAt('08:00');
```

---

## Changelog

### v2.0
- Upgraded to DataTable v2.0
- Added import lookups (auto convert names to IDs)
- Added multiple bulk actions dropdown
- Added import defaults
- Improved code organization

### v1.0
- Initial release
- Basic CRUD operations
- Export to CSV/Excel/PDF
- Import from Excel
- Single bulk delete action
