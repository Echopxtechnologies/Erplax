# 📚 DataTable Trait v2.1 - Complete Documentation

> **One Trait, All Features!** - Search, Sort, Filter, Export, Import, Bulk Actions - All in One!

---

## 📖 Table of Contents

1. [What is DataTable Trait?](#1-what-is-datatable-trait)
2. [Installation & Setup](#2-installation--setup)
3. [Basic Usage](#3-basic-usage)
4. [Controller Properties](#4-controller-properties)
5. [Listing Data](#5-listing-data)
6. [Searching](#6-searching)
7. [Filtering](#7-filtering)
8. [Sorting](#8-sorting)
9. [Pagination](#9-pagination)
10. [Export (CSV, Excel, PDF)](#10-export-csv-excel-pdf)
11. [Import from Excel/CSV](#11-import-from-excelcsv)
12. [Import Lookups (Auto Convert Names to IDs)](#12-import-lookups)
13. [Bulk Actions](#13-bulk-actions)
14. [Custom Row Mapping](#14-custom-row-mapping)
15. [Blade View Setup](#15-blade-view-setup)
16. [Serial Number Column](#16-serial-number-column)
17. [JavaScript API](#17-javascript-api)
18. [Complete Examples](#18-complete-examples)

---

## 1. What is DataTable Trait?

### 🤔 Simple Explanation

Imagine you have a **Products** table in your database. You want to:
- Show all products in a nice table ✅
- Let users search products ✅
- Let users sort by name, price, date ✅
- Filter by category, status ✅
- Export to Excel/PDF ✅
- Import from Excel ✅
- Delete multiple products at once ✅

**Without DataTable Trait:** You write 500+ lines of code in EVERY controller!

**With DataTable Trait:** Just add 10-20 lines of configuration! 🎉

### 🎯 Features at a Glance

| Feature | What it does |
|---------|-------------|
| **List** | Shows data in table with pagination |
| **Search** | Type and find - searches multiple columns |
| **Filter** | Filter by dropdown (category, status, etc.) |
| **Sort** | Click column header to sort asc/desc |
| **Export** | Download as CSV, Excel (XLSX), or PDF |
| **Import** | Upload Excel/CSV to add/update records |
| **Auto-Create** | Auto-create lookup records during import |
| **Template** | Download import template with hints |
| **Bulk Actions** | Select multiple → Delete/Activate/etc. |
| **Row Number** | Serial number column (1, 2, 3...) |

---

## 2. Installation & Setup

### Step 1: Copy the Trait File

Put `DataTableTrait.php` in your project:
```
Modules/Core/Traits/DataTableTrait.php
```

### Step 2: Copy the Blade File

Put `datatable.blade.php` in your views:
```
resources/views/components/datatable.blade.php
```
OR
```
Modules/Core/Resources/views/datatable.blade.php
```

### Step 3: Required Packages

```bash
# For Excel export/import
composer require phpoffice/phpspreadsheet

# For PDF export
composer require barryvdh/laravel-dompdf
```

### Step 4: Add Routes

In your module's `routes/web.php`:
```php
Route::prefix('products')->name('admin.products.')->group(function () {
    // Regular CRUD routes
    Route::get('/', [ProductController::class, 'index'])->name('index');
    Route::get('/create', [ProductController::class, 'create'])->name('create');
    Route::post('/', [ProductController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [ProductController::class, 'edit'])->name('edit');
    Route::put('/{id}', [ProductController::class, 'update'])->name('update');
    Route::delete('/{id}', [ProductController::class, 'destroy'])->name('destroy');
    
    // ⭐ DataTable routes - ADD THESE!
    Route::match(['get', 'post'], '/data', [ProductController::class, 'handleData'])->name('data');
    Route::post('/bulk-action', [ProductController::class, 'handleBulkAction'])->name('bulk-action');
});
```

---

## 3. Basic Usage

### Minimum Setup (Just 3 lines!)

```php
<?php

namespace Modules\Product\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Core\Traits\DataTableTrait;  // 1️⃣ Import the trait
use Modules\Product\Models\Product;

class ProductController extends Controller
{
    use DataTableTrait;  // 2️⃣ Use the trait
    
    protected $model = Product::class;  // 3️⃣ Set the model
    
    public function index()
    {
        return view('product::index');
    }
}
```

**That's it!** Now your controller has:
- `handleData()` - For listing, export, import, template
- `handleBulkAction()` - For bulk delete, activate, etc.

---

## 4. Controller Properties

### All Available Properties

```php
class ProductController extends Controller
{
    use DataTableTrait;
    
    // ═══════════════════════════════════════════
    // REQUIRED
    // ═══════════════════════════════════════════
    
    protected $model = Product::class;
    
    // ═══════════════════════════════════════════
    // OPTIONAL - Listing & Relations
    // ═══════════════════════════════════════════
    
    // Eager load relations (prevents N+1 queries)
    protected $with = ['category', 'brand', 'supplier'];
    
    // Route prefix for auto-generating edit/delete URLs
    protected $routePrefix = 'admin.products';
    
    // ═══════════════════════════════════════════
    // OPTIONAL - Search
    // ═══════════════════════════════════════════
    
    // Columns to search in (default: ['name'])
    protected $searchable = ['name', 'sku', 'barcode', 'description'];
    
    // Search in related table columns
    protected $searchable = ['name', 'sku', 'category.name', 'brand.name'];
    
    // ═══════════════════════════════════════════
    // OPTIONAL - Sorting
    // ═══════════════════════════════════════════
    
    // Columns allowed for sorting (default: ['id', 'created_at'])
    protected $sortable = ['id', 'name', 'price', 'stock', 'created_at'];
    
    // ═══════════════════════════════════════════
    // OPTIONAL - Filtering
    // ═══════════════════════════════════════════
    
    // Columns allowed for filtering
    protected $filterable = ['category_id', 'brand_id', 'status', 'is_active'];
    
    // ═══════════════════════════════════════════
    // OPTIONAL - Export
    // ═══════════════════════════════════════════
    
    // Columns to include in export (default: all columns)
    protected $exportable = ['id', 'sku', 'name', 'category.name', 'price', 'stock'];
    
    // Title shown in export file
    protected $exportTitle = 'Products Report';
    
    // ═══════════════════════════════════════════
    // OPTIONAL - Import
    // ═══════════════════════════════════════════
    
    // Validation rules for import
    protected $importable = [
        'name'        => 'required|string|max:191',
        'sku'         => 'required|string|max:50|unique:products,sku',
        'category_id' => 'required|exists:categories,id',
        'price'       => 'required|numeric|min:0',
        'stock'       => 'nullable|integer|min:0',
        'status'      => 'nullable|in:active,inactive',
    ];
    
    // Unique field for update-or-create during import
    protected $uniqueField = 'sku';  // Default: 'sku'
    
    // Default values when column is empty in Excel
    protected $importDefaults = [
        'status' => 'active',
        'stock'  => 0,
    ];
    
    // Lookups - convert names to IDs (see section 12)
    protected $importLookups = [...];
    
    // ═══════════════════════════════════════════
    // OPTIONAL - Bulk Actions
    // ═══════════════════════════════════════════
    
    protected $bulkActions = [
        'delete'     => ['label' => 'Delete Selected',     'confirm' => true,  'color' => 'red'],
        'activate'   => ['label' => 'Activate Selected',   'confirm' => false, 'color' => 'green'],
        'deactivate' => ['label' => 'Deactivate Selected', 'confirm' => false, 'color' => 'yellow'],
    ];
}
```

### Property Reference Table

| Property | Type | Default | Description |
|----------|------|---------|-------------|
| `$model` | string | **required** | Model class name |
| `$with` | array | `[]` | Relations to eager load |
| `$routePrefix` | string | `null` | For auto edit/delete URLs |
| `$searchable` | array | `['name']` | Columns to search |
| `$sortable` | array | `['id', 'created_at']` | Columns allowed for sorting |
| `$filterable` | array | `[]` | Columns allowed for filtering |
| `$exportable` | array | all columns | Columns to export |
| `$exportTitle` | string | Model name | Title in export file |
| `$importable` | array | `[]` | Validation rules for import |
| `$uniqueField` | string | `'sku'` | Unique field for upsert |
| `$importDefaults` | array | `[]` | Default values for empty cells |
| `$importLookups` | array | `[]` | Name→ID conversions |
| `$bulkActions` | array | `['delete']` | Available bulk actions |

---

## 5. Listing Data

### How it Works

When you call `handleData()` with GET request, it returns JSON:

```json
{
    "data": [
        {"id": 1, "name": "iPhone 15", "price": 999, "_edit_url": "/products/1/edit"},
        {"id": 2, "name": "Samsung S24", "price": 899, "_edit_url": "/products/2/edit"}
    ],
    "total": 150,
    "current_page": 1,
    "last_page": 15,
    "per_page": 10
}
```

### API Endpoint

```
GET /products/data
GET /products/data?page=2
GET /products/data?per_page=25
GET /products/data?search=iphone
GET /products/data?sort=price&dir=asc
GET /products/data?category_id=5&status=active
```

### Available Query Parameters

| Parameter | Example | Description |
|-----------|---------|-------------|
| `page` | `?page=2` | Page number |
| `per_page` | `?per_page=25` | Items per page (max 100) |
| `search` | `?search=iphone` | Search term |
| `sort` | `?sort=price` | Column to sort by |
| `dir` | `?dir=asc` | Sort direction (asc/desc) |
| Any filter | `?status=active` | Filter by column value |

---

## 6. Searching

### Basic Search

```php
// Search in one column
protected $searchable = ['name'];

// Search in multiple columns
protected $searchable = ['name', 'sku', 'barcode', 'description'];
```

**How it works:** When user types "iphone", it searches:
```sql
WHERE name LIKE '%iphone%' 
   OR sku LIKE '%iphone%' 
   OR barcode LIKE '%iphone%'
   OR description LIKE '%iphone%'
```

### Search in Related Tables

```php
protected $with = ['category', 'brand'];

protected $searchable = [
    'name',
    'sku',
    'category.name',  // 👈 Searches in categories table
    'brand.name',     // 👈 Searches in brands table
];
```

---

## 7. Filtering

### Setup Filters

```php
protected $filterable = ['category_id', 'brand_id', 'status', 'is_active'];
```

### Auto-Detected Filters

Even without `$filterable`, these are automatically filtered:
- Any column ending with `_id` (e.g., `category_id`, `user_id`)
- `status`
- `type`
- `is_active`

### Special Date Filters

```
GET /products/data?from_date=2024-01-01&to_date=2024-12-31
```

### Frontend Filter Example

```html
<select id="categoryFilter">
    <option value="">All Categories</option>
    <option value="1">Electronics</option>
    <option value="2">Clothing</option>
</select>

<script>
document.getElementById('categoryFilter').onchange = function() {
    dtInstance['productsTable'].setFilter('category_id', this.value);
};
</script>
```

---

## 8. Sorting

### Setup Sortable Columns

```php
protected $sortable = ['id', 'name', 'price', 'stock', 'created_at'];
```

### How Sorting Works

1. **Default:** Sorted by `id DESC` (newest first)
2. **Click column header:** Toggles between ASC and DESC
3. **API:** `?sort=price&dir=asc`

### Frontend

In your blade table, add `dt-sort` class and `data-col`:

```html
<th class="dt-sort" data-col="name">Name</th>
<th class="dt-sort" data-col="price">Price</th>
<th>Description</th>  <!-- Not sortable -->
```

---

## 9. Pagination

### How it Works

- Default: 10 items per page
- User can select: 10, 25, 50, 100
- Maximum: 100 items per page (security limit)

### Response

```json
{
    "data": [...],
    "total": 150,         // Total records
    "current_page": 1,    // Current page
    "last_page": 15,      // Total pages
    "per_page": 10        // Items per page
}
```

---

## 10. Export (CSV, Excel, PDF)

### Enable Export

Just add `dt-export` class to your table (see Blade section).

### API Endpoints

```
GET /products/data?export=csv      → Downloads products_2024-01-15_143022.csv
GET /products/data?export=xlsx     → Downloads products_2024-01-15_143022.xlsx
GET /products/data?export=pdf      → Downloads products_2024-01-15_143022.pdf
```

### Export with Filters

Exports respect current filters:
```
GET /products/data?export=xlsx&category_id=5&status=active
```

### Export Selected Items Only

```
GET /products/data?export=xlsx&ids=1,5,10,15
```

### Customize Export Columns

```php
// Only these columns will be exported
protected $exportable = ['id', 'sku', 'name', 'price', 'stock'];

// Include related data
protected $exportable = ['id', 'sku', 'name', 'category.name', 'brand.name', 'price'];
```

### Custom Export Title

```php
protected $exportTitle = 'Products Inventory Report';
```

### Custom Export Mapping

```php
protected function mapExportRow($item)
{
    return [
        'Product ID'    => $item->id,
        'SKU Code'      => $item->sku,
        'Product Name'  => $item->name,
        'Category'      => $item->category?->name ?? 'N/A',
        'Unit Price'    => '₹' . number_format($item->price, 2),
        'In Stock'      => $item->stock . ' units',
        'Status'        => ucfirst($item->status),
    ];
}
```

---

## 11. Import from Excel/CSV

### Enable Import

1. Add `$importable` property with validation rules
2. Add `dt-import` class to your table (see Blade section)

### Basic Setup

```php
protected $importable = [
    'name'        => 'required|string|max:191',
    'sku'         => 'required|string|max:50|unique:products,sku',
    'category_id' => 'required|exists:categories,id',
    'price'       => 'required|numeric|min:0',
    'stock'       => 'nullable|integer|min:0',
    'status'      => 'nullable|in:active,inactive',
];
```

### Validation Rules Explained

| Rule | Meaning |
|------|---------|
| `required` | Column must have value |
| `nullable` | Column can be empty |
| `string` | Must be text |
| `max:191` | Maximum 191 characters |
| `numeric` | Must be a number |
| `integer` | Must be whole number |
| `min:0` | Minimum value 0 |
| `email` | Must be valid email |
| `unique:products,sku` | SKU must be unique in products table |
| `exists:categories,id` | Must exist in categories table |
| `in:active,inactive` | Must be one of these values |

### Default Values

When a cell is empty in Excel, use default:

```php
protected $importDefaults = [
    'status' => 'active',
    'stock'  => 0,
    'created_by' => 'import',
];
```

### Unique Field (Update or Create)

```php
protected $uniqueField = 'sku';  // Default is 'sku'
```

**How it works:**
- If SKU exists → **Update** the record
- If SKU doesn't exist → **Create** new record

### Download Template

```
GET /products/data?template=1
```

Downloads Excel file with:
- **Sheet 1:** Column headers + hints (Required/Optional, data type)
- **Sheet 2:** Reference data (categories, brands, etc.)

### Import Response

```json
{
    "success": true,
    "message": "8 of 10 imported (2 lookup records auto-created)",
    "results": {
        "total": 10,
        "success": 8,
        "failed": 2,
        "errors": [
            "Row 5: The sku has already been taken.",
            "Row 8: The price must be a number."
        ],
        "created": [
            "Row 3: Created 'New Category' in categories (ID: 15)",
            "Row 7: Created 'New Brand' in brands (ID: 8)"
        ]
    }
}
```

---

## 12. Import Lookups

### 🤔 The Problem

Your Excel has:
| name | category_name | price |
|------|---------------|-------|
| iPhone | Electronics | 999 |

But your database needs `category_id`, not `category_name`!

### ✅ The Solution: Import Lookups

```php
protected $importLookups = [
    'category_name' => [
        'table'   => 'categories',    // Table to search in
        'search'  => 'name',          // Column to search
        'return'  => 'id',            // Column to return (default: 'id')
        'save_as' => 'category_id',   // Save result as this column
    ],
];
```

**What happens:**
1. Excel has `category_name = "Electronics"`
2. Trait searches: `SELECT id FROM categories WHERE name = 'Electronics'`
3. Found ID = 5
4. Sets `category_id = 5` in the import data
5. Removes `category_name` column

### 🚀 Auto-Create if Not Found

```php
protected $importLookups = [
    'category_name' => [
        'table'       => 'categories',
        'search'      => 'name',
        'save_as'     => 'category_id',
        'create'      => true,  // 👈 Auto-create if not found!
        'create_data' => [      // 👈 Extra fields when creating
            'status' => 1,
            'created_by' => 'import',
        ],
    ],
];
```

**What happens if "New Category" doesn't exist:**
1. Excel has `category_name = "New Category"`
2. Trait searches → Not found!
3. `create = true` → Creates new category:
   ```sql
   INSERT INTO categories (name, status, created_by, created_at, updated_at) 
   VALUES ('New Category', 1, 'import', NOW(), NOW())
   ```
4. Gets new ID = 15
5. Sets `category_id = 15`

### Multiple Lookups Example

```php
protected $importLookups = [
    // Category lookup - auto-create enabled
    'category_name' => [
        'table'       => 'categories',
        'search'      => 'name',
        'save_as'     => 'category_id',
        'create'      => true,
        'create_data' => ['status' => 1],
    ],
    
    // Brand lookup - auto-create enabled
    'brand_name' => [
        'table'       => 'brands',
        'search'      => 'name',
        'save_as'     => 'brand_id',
        'create'      => true,
    ],
    
    // Supplier lookup - NO auto-create (error if not found)
    'supplier_code' => [
        'table'   => 'suppliers',
        'search'  => 'code',
        'return'  => 'id',
        'save_as' => 'supplier_id',
        // create not set = false = will error if not found
    ],
];
```

### Lookup Configuration Options

| Option | Required | Default | Description |
|--------|----------|---------|-------------|
| `table` | ✅ Yes | - | Database table to search |
| `search` | ✅ Yes | - | Column to search in |
| `return` | No | `'id'` | Column value to return |
| `save_as` | ✅ Yes | - | Save result as this column |
| `create` | No | `false` | Auto-create if not found |
| `create_data` | No | `[]` | Extra fields when creating |

---

## 13. Bulk Actions

### Default Bulk Actions

```php
// If you don't define $bulkActions, you get:
protected $bulkActions = [
    'delete' => ['label' => 'Delete', 'confirm' => true, 'color' => 'red'],
];
```

### Custom Bulk Actions

```php
protected $bulkActions = [
    'delete' => [
        'label'   => 'Delete Selected',
        'confirm' => true,   // Show confirmation dialog
        'color'   => 'red',  // red, green, yellow, blue
    ],
    'activate' => [
        'label'   => 'Activate Selected',
        'confirm' => false,
        'color'   => 'green',
    ],
    'deactivate' => [
        'label'   => 'Deactivate Selected',
        'confirm' => false,
        'color'   => 'yellow',
    ],
];
```

### Built-in Action Handlers

These actions work automatically:
- `delete` → Deletes selected records
- `activate` → Sets `status = 'active'`
- `deactivate` → Sets `status = 'inactive'`

### Custom Action Handler

For custom actions, create a method `bulk{ActionName}`:

```php
protected $bulkActions = [
    'archive' => ['label' => 'Archive Selected', 'confirm' => true, 'color' => 'yellow'],
];

// Custom handler for 'archive' action
public function bulkArchive(Request $request)
{
    $ids = $request->input('ids', []);
    
    $count = Product::whereIn('id', $ids)->update([
        'status' => 'archived',
        'archived_at' => now(),
    ]);
    
    return response()->json([
        'success' => true,
        'message' => "{$count} products archived"
    ]);
}
```

---

## 14. Custom Row Mapping

### Default Behavior

By default, all model columns are returned plus action URLs.

### Custom Row Mapping

Control exactly what data is returned:

```php
protected function mapRow($item)
{
    return [
        'id'         => $item->id,
        'name'       => $item->name,
        'sku'        => $item->sku,
        'price'      => '₹' . number_format($item->price, 2),
        'category'   => $item->category?->name ?? 'N/A',
        'status'     => $item->status,
        'created'    => $item->created_at->format('d M Y'),
        '_edit_url'  => route('admin.products.edit', $item->id),
        '_delete_url'=> route('admin.products.destroy', $item->id),
    ];
}
```

---

## 15. Blade View Setup

### Basic Table Structure

```html
@include('core::datatable')  <!-- Include CSS & JS -->

<table 
    class="dt-table dt-search dt-export dt-import dt-perpage dt-checkbox"
    data-route="{{ route('admin.products.data') }}"
    data-bulk-route="{{ route('admin.products.bulk-action') }}"
    id="productsTable"
>
    <thead>
        <tr>
            <th data-col="_row_num" style="width:50px;">#</th>
            <th class="dt-sort" data-col="name">Name</th>
            <th class="dt-sort" data-col="price">Price</th>
            <th data-col="category">Category</th>
            <th data-col="status" data-render="status">Status</th>
            <th data-render="actions">Actions</th>
        </tr>
    </thead>
    <tbody></tbody>
</table>
```

### Table Classes Explained

| Class | What it enables |
|-------|-----------------|
| `dt-table` | **Required** - Marks this as a DataTable |
| `dt-search` | Shows search input box |
| `dt-export` | Shows export dropdown (CSV, Excel, PDF) |
| `dt-import` | Shows import button |
| `dt-perpage` | Shows "per page" dropdown (10, 25, 50, 100) |
| `dt-checkbox` | Shows checkbox column for bulk selection |

### Data Attributes

| Attribute | Required | Description |
|-----------|----------|-------------|
| `data-route` | ✅ Yes | API endpoint for data |
| `data-bulk-route` | No | Endpoint for bulk actions |
| `id` | No | Table ID (auto-generated if not set) |
| `data-filters` | No | Default filters as JSON |

### Column Attributes

| Attribute | Description |
|-----------|-------------|
| `data-col` | Column name from API response |
| `data-render` | Special rendering: `status`, `badge`, `actions`, `date`, `image` |
| `class="dt-sort"` | Makes column sortable |

### Special Columns

| Column | Usage | Description |
|--------|-------|-------------|
| `_row_num` | `data-col="_row_num"` | Serial number (1, 2, 3...) |
| `_checkbox` | Auto-added with `dt-checkbox` class | Checkbox for selection |
| `actions` | `data-render="actions"` | Edit/Delete buttons |

### Renderers

| Renderer | Description |
|----------|-------------|
| `status` | Colored badge based on value |
| `badge` | Generic colored badge |
| `actions` | Edit/Delete buttons |
| `date` | Format as date |
| `datetime` | Format as datetime |
| `currency` | Format as currency |
| `image` | Show image thumbnail |

---

## 16. Serial Number Column

### 🤔 The Problem

Using database `id` shows gaps after deleting records:
| ID | Name |
|----|------|
| 1 | Apple |
| 5 | Banana |
| 11 | Cherry |

### ✅ The Solution: `_row_num`

Clean serial numbers with no gaps:
| # | Name |
|---|------|
| 1 | Apple |
| 2 | Banana |
| 3 | Cherry |

### Usage

Just add this column in your Blade table:

```html
<th data-col="_row_num" style="width:50px;">#</th>
```

### Full Example

```html
<table class="dt-table dt-search dt-checkbox" data-route="{{ route('admin.products.data') }}">
    <thead>
        <tr>
            <th data-col="_row_num" style="width:50px;">#</th>  <!-- Serial Number -->
            <th class="dt-sort" data-col="name">Name</th>
            <th class="dt-sort" data-col="price">Price</th>
            <th data-render="actions">Actions</th>
        </tr>
    </thead>
    <tbody></tbody>
</table>
```

### How it Works

Row number is calculated based on pagination:

| Page | Per Page | Row Numbers |
|------|----------|-------------|
| 1 | 10 | 1, 2, 3...10 |
| 2 | 10 | 11, 12, 13...20 |
| 3 | 10 | 21, 22, 23...30 |
| 1 | 25 | 1, 2, 3...25 |
| 2 | 25 | 26, 27, 28...50 |

**Formula:** `(page - 1) * perPage + index + 1`

---

## 17. JavaScript API

### Access Table Instance

```javascript
// Get instance by table ID
var table = window.dtInstance['productsTable'];
```

### Available Methods

```javascript
// Reload data
table.reload();

// Set filter
table.setFilter('category_id', 5);
table.setFilter('status', 'active');

// Clear filter
table.setFilter('category_id', '');

// Get selected IDs
var selected = table.getSelected();  // [1, 5, 10]

// Clear selection
table.clearSelection();

// Export
table.exportTo('csv');
table.exportTo('xlsx');
table.exportTo('pdf');

// Export selected only
table.exportSelected('xlsx');
```

### Examples

#### Filter by Dropdown
```html
<select id="categoryFilter">
    <option value="">All Categories</option>
    @foreach($categories as $cat)
        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
    @endforeach
</select>

<script>
document.getElementById('categoryFilter').onchange = function() {
    window.dtInstance['productsTable'].setFilter('category_id', this.value);
};
</script>
```

#### Filter by Date Range
```html
<input type="date" id="fromDate">
<input type="date" id="toDate">
<button onclick="applyDateFilter()">Apply</button>

<script>
function applyDateFilter() {
    var table = window.dtInstance['productsTable'];
    table.setFilter('from_date', document.getElementById('fromDate').value);
    table.setFilter('to_date', document.getElementById('toDate').value);
}
</script>
```

---

## 18. Complete Examples

### Example 1: Simple Product CRUD

```php
<?php

namespace Modules\Product\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Core\Traits\DataTableTrait;
use Modules\Product\Models\Product;

class ProductController extends Controller
{
    use DataTableTrait;
    
    protected $model = Product::class;
    protected $with = ['category'];
    protected $routePrefix = 'admin.products';
    protected $searchable = ['name', 'sku'];
    protected $sortable = ['id', 'name', 'price', 'created_at'];
    protected $filterable = ['category_id', 'status'];
    
    public function index()
    {
        $categories = Category::all();
        return view('product::index', compact('categories'));
    }
}
```

### Example 2: Full-Featured with Import

```php
<?php

namespace Modules\Student\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Core\Traits\DataTableTrait;
use Modules\Student\Models\Student;

class StudentController extends Controller
{
    use DataTableTrait;
    
    protected $model = Student::class;
    protected $with = ['school', 'class'];
    protected $routePrefix = 'admin.students';
    
    protected $searchable = ['name', 'email', 'roll_number', 'school.name'];
    protected $sortable = ['id', 'name', 'roll_number', 'created_at'];
    protected $filterable = ['school_id', 'class_id', 'status'];
    
    protected $exportable = ['id', 'roll_number', 'name', 'email', 'school.name', 'class.name'];
    protected $exportTitle = 'Students List';
    
    protected $importable = [
        'name'       => 'required|string|max:191',
        'email'      => 'nullable|email|unique:students,email',
        'roll_number'=> 'required|string|unique:students,roll_number',
        'school_id'  => 'required|exists:schools,id',
        'class_id'   => 'required|exists:classes,id',
        'status'     => 'nullable|in:active,inactive',
    ];
    
    protected $uniqueField = 'roll_number';
    
    protected $importDefaults = [
        'status' => 'active',
    ];
    
    // Auto-convert names to IDs with auto-create
    protected $importLookups = [
        'school_name' => [
            'table'       => 'schools',
            'search'      => 'name',
            'save_as'     => 'school_id',
            'create'      => true,  // 👈 Auto-create if not found
            'create_data' => ['status' => 1],
        ],
        'class_name' => [
            'table'       => 'classes',
            'search'      => 'name',
            'save_as'     => 'class_id',
            'create'      => true,
        ],
    ];
    
    protected $bulkActions = [
        'delete'     => ['label' => 'Delete Selected',     'confirm' => true,  'color' => 'red'],
        'activate'   => ['label' => 'Activate Selected',   'confirm' => false, 'color' => 'green'],
        'deactivate' => ['label' => 'Deactivate Selected', 'confirm' => false, 'color' => 'yellow'],
    ];
    
    protected function mapRow($item)
    {
        return [
            'id'          => $item->id,
            'roll_number' => $item->roll_number,
            'name'        => $item->name,
            'email'       => $item->email ?? '-',
            'school'      => $item->school?->name ?? '-',
            'class'       => $item->class?->name ?? '-',
            'status'      => $item->status,
            'created'     => $item->created_at->format('d M Y'),
            '_edit_url'   => route('admin.students.edit', $item->id),
            '_delete_url' => route('admin.students.destroy', $item->id),
        ];
    }
}
```

### Example 3: Blade View with Filters

```html
@extends('layouts.admin')

@section('content')
<div class="page-header">
    <h1>Students</h1>
    <a href="{{ route('admin.students.create') }}" class="btn btn-primary">+ Add Student</a>
</div>

<!-- Filters -->
<div class="filters-row">
    <select id="schoolFilter" class="form-select">
        <option value="">All Schools</option>
        @foreach($schools as $school)
            <option value="{{ $school->id }}">{{ $school->name }}</option>
        @endforeach
    </select>
    
    <select id="statusFilter" class="form-select">
        <option value="">All Status</option>
        <option value="active">Active</option>
        <option value="inactive">Inactive</option>
    </select>
</div>

<!-- DataTable -->
@include('core::datatable')

<table 
    class="dt-table dt-search dt-export dt-import dt-perpage dt-checkbox"
    data-route="{{ route('admin.students.data') }}"
    data-bulk-route="{{ route('admin.students.bulk-action') }}"
    id="studentsTable"
>
    <thead>
        <tr>
            <th data-col="_row_num" style="width:50px;">#</th>
            <th class="dt-sort" data-col="roll_number">Roll No</th>
            <th class="dt-sort" data-col="name">Name</th>
            <th data-col="email">Email</th>
            <th data-col="school">School</th>
            <th data-col="status" data-render="status">Status</th>
            <th data-render="actions">Actions</th>
        </tr>
    </thead>
    <tbody></tbody>
</table>

<script>
document.getElementById('schoolFilter').onchange = function() {
    window.dtInstance['studentsTable'].setFilter('school_id', this.value);
};

document.getElementById('statusFilter').onchange = function() {
    window.dtInstance['studentsTable'].setFilter('status', this.value);
};
</script>
@endsection
```

---

## 📝 Quick Reference Card

### Controller Properties
```php
use DataTableTrait;

protected $model = Model::class;              // Required
protected $with = ['relation1', 'relation2']; // Eager load
protected $routePrefix = 'admin.items';       // For URLs
protected $searchable = ['col1', 'col2'];     // Search columns
protected $sortable = ['col1', 'col2'];       // Sort columns
protected $filterable = ['col1', 'col2'];     // Filter columns
protected $exportable = ['col1', 'col2'];     // Export columns
protected $exportTitle = 'Report Title';      // Export title
protected $importable = ['col' => 'rules'];   // Import validation
protected $uniqueField = 'sku';               // Upsert field
protected $importDefaults = ['col' => 'val']; // Default values
protected $importLookups = [...];             // Name→ID lookups
protected $bulkActions = [...];               // Bulk actions
```

### Routes
```php
Route::match(['get', 'post'], '/data', [Controller::class, 'handleData'])->name('data');
Route::post('/bulk-action', [Controller::class, 'handleBulkAction'])->name('bulk-action');
```

### Blade Table
```html
<table 
    class="dt-table dt-search dt-export dt-import dt-perpage dt-checkbox"
    data-route="{{ route('admin.items.data') }}"
    id="myTable"
>
    <thead>
        <tr>
            <th data-col="_row_num" style="width:50px;">#</th>
            <th class="dt-sort" data-col="name">Name</th>
            <th data-col="status" data-render="status">Status</th>
            <th data-render="actions">Actions</th>
        </tr>
    </thead>
    <tbody></tbody>
</table>
```

### Import Lookups with Auto-Create
```php
protected $importLookups = [
    'school_name' => [
        'table'       => 'schools',
        'search'      => 'name',
        'save_as'     => 'school_id',
        'create'      => true,           // Auto-create if not found
        'create_data' => ['status' => 1], // Extra fields
    ],
];
```

### JavaScript API
```javascript
window.dtInstance['myTable'].reload();
window.dtInstance['myTable'].setFilter('status', 'active');
window.dtInstance['myTable'].getSelected();
window.dtInstance['myTable'].exportTo('xlsx');
```

---

## 🔄 Version History

| Version | Changes |
|---------|---------|
| v2.1 | Added `_row_num` serial number column |
| v2.0 | Added import lookups with auto-create (`create => true`) |
| v1.0 | Initial release |

---

**Made with ❤️ for Laravel Developers**

*Version 2.1 - Last Updated: 2024*