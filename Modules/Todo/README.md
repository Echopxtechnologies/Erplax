# Todo Module

A professional task management module with user-based access control.

## Features

- ✅ Personal task management
- ✅ User-based access (each user sees only their own tasks)
- ✅ Admin view (admin can see all users' tasks)
- ✅ Priority levels (Low, Medium, High)
- ✅ Status tracking (Pending, In Progress, Completed)
- ✅ Due dates with overdue alerts
- ✅ Dashboard stats cards
- ✅ DataTable with search, export, bulk actions
- ✅ Professional UI with dark/light mode

## Installation

### Step 1: Copy Module
Copy the `Todo` folder to your `Modules/` directory:
```
Modules/
└── Todo/
```

### Step 2: Run Migration
```bash
php artisan migrate
```

### Step 3: Register Module (if not auto-discovered)
Add to `config/app.php` providers:
```php
Modules\Todo\Providers\TodoServiceProvider::class,
```

Or add to your modules database table:
```sql
INSERT INTO modules (name, alias, description, is_active, sort_order) 
VALUES ('Todo', 'todo', 'Personal task management', 1, 10);
```

### Step 4: Clear Cache
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

## Access Control

| User Type | Can See |
|-----------|---------|
| Regular User (`is_admin = 0`) | Only their own tasks |
| Admin User (`is_admin = 1`) | All users' tasks |

## Database Schema

### `todos` Table
| Column | Type | Description |
|--------|------|-------------|
| id | bigint | Primary key |
| user_id | bigint | Foreign key to users |
| title | string | Task title |
| description | text | Task description (nullable) |
| priority | enum | low, medium, high |
| status | enum | pending, in_progress, completed |
| due_date | date | Due date (nullable) |
| completed_at | timestamp | When task was completed |
| created_at | timestamp | Created timestamp |
| updated_at | timestamp | Updated timestamp |

## Routes

| Method | URL | Name | Description |
|--------|-----|------|-------------|
| GET | /admin/todo | admin.todo.index | List tasks |
| GET | /admin/todo/data | admin.todo.data | DataTable JSON |
| GET | /admin/todo/create | admin.todo.create | Create form |
| POST | /admin/todo | admin.todo.store | Store task |
| GET | /admin/todo/{id} | admin.todo.show | View task |
| GET | /admin/todo/{id}/edit | admin.todo.edit | Edit form |
| PUT | /admin/todo/{id} | admin.todo.update | Update task |
| DELETE | /admin/todo/{id} | admin.todo.destroy | Delete task |
| POST | /admin/todo/bulk-delete | admin.todo.bulk-delete | Bulk delete |

## Files Structure

```
Todo/
├── Config/
│   └── config.php
├── Database/
│   └── Migrations/
│       └── 2024_12_05_000001_create_todos_table.php
├── Http/
│   └── Controllers/
│       └── TodoController.php
├── Models/
│   └── Todo.php
├── Providers/
│   ├── TodoServiceProvider.php
│   └── RouteServiceProvider.php
├── Resources/
│   └── views/
│       ├── index.blade.php      (list with stats)
│       ├── create.blade.php     (create form)
│       ├── edit.blade.php       (edit form)
│       ├── show.blade.php       (view details)
│       └── menu.blade.php       (sidebar menu)
├── Routes/
│   ├── web.php
│   └── api.php
├── composer.json
├── module.json
└── README.md
```

## UI Features

### Dashboard Stats
- Total Tasks
- Pending Tasks
- In Progress Tasks
- Completed Tasks
- Overdue Tasks

### DataTable Features
- Search by title/description
- Sort by any column
- Export to CSV
- Bulk delete
- Checkbox selection
- Pagination

### Priority Visual
- 🟢 Low - Green
- 🟡 Medium - Yellow/Orange
- 🔴 High - Red

### Status Visual
- ⏳ Pending - Warning color
- 🔄 In Progress - Blue
- ✅ Completed - Green

## Usage

### Create Task
1. Click "Add New Task"
2. Enter title (required)
3. Add description (optional)
4. Select priority
5. Set status
6. Set due date (optional)
7. Click "Create Task"

### Edit Task
1. Click "Edit" button on task
2. Modify fields
3. Click "Update Task"

### Delete Task
- Single: Click "Delete" button
- Bulk: Select checkboxes → Click "Delete Selected"

### Export Tasks
- All: Click "Export All"
- Selected: Select checkboxes → Click "Export Selected"

## Customization

### Add More Statuses
Edit the migration and model:
```php
// Migration
$table->enum('status', ['pending', 'in_progress', 'completed', 'cancelled']);

// Add badge style in datatable.blade.php
.dt-badge-cancelled { background: var(--danger-light); color: var(--danger); }
```

### Add Categories
1. Create `todo_categories` table
2. Add `category_id` to `todos` table
3. Add relationship in model
4. Update controller and views

## Support

For issues or questions, contact your administrator.
