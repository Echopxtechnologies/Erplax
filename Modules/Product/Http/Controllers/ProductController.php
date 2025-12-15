<?php

namespace Modules\Product\Http\Controllers;

use App\Http\Controllers\Admin\AdminController;
use Modules\Product\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;

class ProductController extends AdminController
{
    public function index()
    {
        $stats = [
            'total' => Product::count(),
            'active' => Product::active()->count(),
        ];
        return $this->moduleView('product::index', compact('stats'));
    }

    public function dataTable(Request $request): JsonResponse
    {
        $query = Product::query();

        if ($request->has('ids') && $request->has('export')) {
            $ids = array_filter(explode(',', $request->input('ids')));
            if (!empty($ids)) $query->whereIn('id', $ids);
            return $this->export($query);
        }

        if ($search = $request->input('search')) {
            $query->search($search);
        }

        if ($filters = $request->input('filters')) {
            $decoded = is_array($filters) ? $filters : json_decode($filters, true);
            foreach ($decoded ?? [] as $key => $value) {
                if ($value !== '' && $value !== null) {
                    $query->where($key, $value);
                }
            }
        }

        $query->orderBy($request->input('sort', 'id'), $request->input('dir', 'desc'));

        if ($request->has('export')) return $this->export($query);

        $data = $query->paginate($request->input('per_page', 15));

        $items = collect($data->items())->map(function ($item) {
            $item->_edit_url = route('admin.product.edit', $item->id);
            $item->_show_url = route('admin.product.show', $item->id);
            return $item;
        });

        return response()->json([
            'data' => $items,
            'total' => $data->total(),
            'current_page' => $data->currentPage(),
            'last_page' => $data->lastPage(),
        ]);
    }

    protected function export($query)
    {
        $data = $query->get();
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=products_' . date('Y-m-d') . '.csv',
        ];

        $callback = function () use ($data) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Name', 'SKU', 'Purchase Price', 'Sale Price', 'MRP', 'Status']);
            foreach ($data as $row) {
                fputcsv($file, [$row->id, $row->name, $row->sku, $row->purchase_price, $row->sale_price, $row->mrp, $row->status_label]);
            }
            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }

    public function create()
    {
        return $this->moduleView('product::create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:191',
            'sku' => 'required|string|max:100|unique:products,sku',
            'description' => 'nullable|string',
            'purchase_price' => 'required|numeric|min:0',
            'sale_price' => 'required|numeric|min:0',
            'mrp' => 'nullable|numeric|min:0',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);
        Product::create($validated);

        return redirect()->route('admin.product.index')->with('success', 'Product created!');
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);
        return $this->moduleView('product::show', compact('product'));
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        return $this->moduleView('product::edit', compact('product'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:191',
            'sku' => 'required|string|max:100|unique:products,sku,' . $id,
            'description' => 'nullable|string',
            'purchase_price' => 'required|numeric|min:0',
            'sale_price' => 'required|numeric|min:0',
            'mrp' => 'nullable|numeric|min:0',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);
        $product->update($validated);

        return redirect()->route('admin.product.index')->with('success', 'Product updated!');
    }

    public function destroy($id)
    {
        Product::findOrFail($id)->delete();

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Product deleted!']);
        }
        return redirect()->route('admin.product.index')->with('success', 'Product deleted!');
    }

    public function bulkDelete(Request $request): JsonResponse
    {
        $ids = $request->input('ids', []);
        if (empty($ids)) {
            return response()->json(['success' => false, 'message' => 'No items selected'], 400);
        }
        $deleted = Product::whereIn('id', $ids)->delete();
        return response()->json(['success' => true, 'message' => "{$deleted} product(s) deleted!"]);
    }

    public function toggleStatus($id): JsonResponse
    {
        $product = Product::findOrFail($id);
        $product->is_active = !$product->is_active;
        $product->save();
        return response()->json(['success' => true, 'is_active' => $product->is_active]);
    }

    public function search(Request $request): JsonResponse
    {
        $products = Product::active()
            ->search($request->input('q', ''))
            ->limit(10)
            ->get(['id', 'name', 'sku', 'sale_price', 'purchase_price']);

        return response()->json([
            'results' => $products->map(fn($p) => [
                'id' => $p->id,
                'text' => "{$p->name} ({$p->sku})",
                'name' => $p->name,
                'sku' => $p->sku,
                'sale_price' => $p->sale_price,
                'purchase_price' => $p->purchase_price,
            ])
        ]);
    }
}
