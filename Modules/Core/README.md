# Core - DataTable

Simple folder with shared DataTable files.

## Structure

```
Modules/
├── Core/
│   ├── Traits/
│   │   └── DataTableTrait.php
│   └── Views/
│       └── datatable.blade.php
```

## Usage

### Controller

```php
use Modules\Core\Traits\DataTableTrait;

class YourController extends Controller
{
    // use DataTableTrait;

    protected $model = YourModel::class;
    protected $searchable = ['name', 'email'];
    protected $routePrefix = 'admin.yourmodule';
}
```

### Routes

```php
Route::get('/data', [YourController::class, 'dataTable'])->name('data');

// If using checkbox bulk delete:
Route::post('/bulk-delete', [YourController::class, 'bulkDelete'])->name('bulk-delete');
```

### View

```blade
<table class="dt-table dt-search dt-export dt-perpage dt-checkbox" 
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

## Table Classes

| Class | Feature |
|-------|---------|
| `dt-table` | Required |
| `dt-search` | Search box |
| `dt-export` | Export All CSV |
| `dt-perpage` | 10/25/50/100 dropdown |
| `dt-checkbox` | Checkbox selection + bulk actions |

## Column Options

| Attribute | Purpose |
|-----------|---------|
| `class="dt-sort"` | Sortable |
| `data-col="name"` | DB column |
| `data-render="badge"` | Colored badge |
| `data-render="date"` | Format date |
| `data-render="actions"` | View/Edit/Delete |
