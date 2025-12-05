# Student Module - Installation Guide

## Prerequisites

Make sure you have the DataTable system set up (ONE TIME):

1. `app/Traits/DataTableTrait.php` exists
2. `public/js/datatable.js` exists
3. `public/css/datatable.css` exists
4. CSS & JS are included in your layout

## Step 1: Copy Module

Copy the `Student` folder to your `Modules/` directory:

```
Modules/
└── Student/
    ├── Config/
    ├── Database/
    ├── Http/
    ├── Models/
    ├── Providers/
    ├── Resources/
    ├── Routes/
    ├── module.json
    └── composer.json
```

## Step 2: Register Service Provider

Add to `config/app.php` providers array:

```php
'providers' => [
    // ...
    Modules\Student\Providers\StudentServiceProvider::class,
],
```

Or if using laravel-modules package, it will auto-register.

## Step 3: Run Migration

```bash
php artisan migrate
```

## Step 4: Add Permissions (if using permission system)

Add these permissions to your database:

- `student.list.read`
- `student.create.create`
- `student.list.edit`
- `student.list.delete`

## Step 5: Add Menu Item

Include in your sidebar/menu:

```blade
@include('student::menu')
```

## Done!

Access the module at: `http://your-app.com/admin/student`

## DataTable Features

The index page uses DataTable with these features:

| Class | Feature |
|-------|---------|
| `dt-table` | Enables DataTable |
| `dt-search` | Adds search box |
| `dt-export` | Adds export button |
| `dt-perpage` | Adds per-page selector |

To customize, edit `Resources/views/index.blade.php`
