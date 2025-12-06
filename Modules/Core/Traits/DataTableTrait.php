<?php

namespace Modules\Core\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

trait DataTableTrait
{
    /**
     * Handle DataTable AJAX request
     */
    public function dataTable(Request $request)
    {
        $query = $this->model::query();

        // Eager load
        if (property_exists($this, 'with') && !empty($this->with)) {
            $query->with($this->with);
        }

        // Export selected IDs only
        if ($request->has('ids') && $request->has('export')) {
            $ids = array_filter(explode(',', $request->input('ids')));
            if (!empty($ids)) {
                $query->whereIn('id', $ids);
            }
            return $this->dtExport($query, $request->input('export'));
        }

        // Search
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                foreach ($this->searchable ?? [] as $col) {
                    $q->orWhere($col, 'LIKE', "%{$search}%");
                }
            });
        }

        // Filters
        if ($filters = $request->input('filters')) {
            $decoded = is_array($filters) ? $filters : json_decode($filters, true);
            foreach ($decoded ?? [] as $key => $value) {
                if ($value !== '' && $value !== null) {
                    $query->where($key, $value);
                }
            }
        }

        // Sort
        $sortCol = $request->input('sort', 'id');
        $sortDir = $request->input('dir', 'desc');
        $query->orderBy($sortCol, $sortDir);

        // Export all
        if ($request->has('export')) {
            return $this->dtExport($query, $request->input('export'));
        }

        // Paginate
        $perPage = $request->input('per_page', 10);
        $data = $query->paginate($perPage);

        // Add URLs
        $items = collect($data->items())->map(function ($item) {
            $prefix = $this->routePrefix ?? 'admin';
            try {
                $item->_edit_url = route("{$prefix}.edit", $item->id);
                $item->_show_url = route("{$prefix}.show", $item->id);
            } catch (\Exception $e) {
                $item->_edit_url = '#';
                $item->_show_url = '#';
            }
            return $item;
        });

        return response()->json([
            'data' => $items,
            'total' => $data->total(),
            'current_page' => $data->currentPage(),
            'last_page' => $data->lastPage(),
        ]);
    }

    /**
     * Bulk delete selected items
     */
    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);
        
        if (empty($ids)) {
            return response()->json(['success' => false, 'message' => 'No items selected'], 400);
        }

        try {
            $this->model::whereIn('id', $ids)->delete();
            return response()->json(['success' => true, 'message' => count($ids) . ' items deleted']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Delete failed'], 500);
        }
    }

    /**
     * Export data to CSV
     */
    protected function dtExport($query, $type)
    {
        $columns = $this->exportable ?? ['*'];
        $data = $query->get($columns);
        $filename = strtolower(class_basename($this->model)) . '_' . date('Y-m-d');

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}.csv",
        ];

        $callback = function () use ($data) {
            $file = fopen('php://output', 'w');
            if ($data->count()) {
                fputcsv($file, array_keys($data->first()->toArray()));
            }
            foreach ($data as $row) {
                fputcsv($file, $row->toArray());
            }
            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }
}