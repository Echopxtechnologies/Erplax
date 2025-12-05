# Core - Shared DataTable

Simple folder with shared files. NOT a module, just files to use.

## Structure

```
Modules/
├── Core/                    ← Just a folder
│   ├── Traits/
│   │   └── DataTableTrait.php
│   └── Views/
│       └── datatable.blade.php
├── Book/
├── Student/
└── Todo/
```

## ONE TIME SETUP

Add this ONE line to `app/Providers/AppServiceProvider.php`:

```php
public function boot()
{
    // Add this line
    $this->loadViewsFrom(base_path('Modules/Core/Views'), 'core');
}
```

That's it!

---

## HOW TO USE IN ANY MODULE

### 1. Controller (add 4 lines)

```php
use Modules\Core\Traits\DataTableTrait;

class YourController extends AdminController
{
    use DataTableTrait;
    
    protected $model = YourModel::class;
    protected $searchable = ['name', 'email'];
    protected $routePrefix = 'admin.yourmodule';
}
```

### 2. Routes (add 1 line)

```php
Route::get('/data', [YourController::class, 'dataTable'])->name('data');
```

### 3. View - Just add classes to table!

```blade
<table class="dt-table dt-search dt-export dt-perpage" 
       data-route="{{ route('admin.yourmodule.data') }}">
    <thead>
        <tr>
            <th class="dt-sort" data-col="id">ID</th>
            <th class="dt-sort" data-col="name">Name</th>
            <th class="dt-sort" data-col="status" data-render="badge">Status</th>
            <th data-render="actions">Actions</th>
        </tr>
    </thead>
    <tbody></tbody>
</table>

@include('core::datatable')
```

---

## TABLE CLASSES

| Class | What Appears |
|-------|--------------|
| `dt-table` | Required - enables styling |
| `dt-search` | Search box (left) |
| `dt-export` | Export CSV button (right) |
| `dt-perpage` | 10/25/50/100 dropdown (right) |

## TH CLASSES & ATTRIBUTES

| On `<th>` | Purpose |
|-----------|---------|
| `class="dt-sort"` | Click to sort |
| `data-col="name"` | Database column |
| `data-render="badge"` | Colored badge |
| `data-render="date"` | Format date |
| `data-render="actions"` | View/Edit/Delete |

## BADGE COLORS (auto)

- `active` → Green
- `inactive` → Gray  
- `pending` → Orange
- `completed` → Green
- `cancelled` → Red
