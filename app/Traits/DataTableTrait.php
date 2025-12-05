<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

trait DataTableTrait
{
    /**
     * Handle DataTable AJAX request
     * 
     * Required in controller:
     *   protected $model = YourModel::class;
     *   protected $searchable = ['column1', 'column2'];
     * 
     * Optional:
     *   protected $exportable = ['col1', 'col2'];
     *   protected $with = ['relationship'];
     *   protected $routePrefix = 'admin.students';
     */
    public function dataTable(Request $request)
    {
        $query = $this->model::query();

        // Eager load
        if (property_exists($this, 'with') && !empty($this->with)) {
            $query->with($this->with);
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
            foreach (json_decode($filters, true) ?? [] as $key => $value) {
                if ($value !== '' && $value !== null) {
                    $query->where($key, $value);
                }
            }
        }

        // Sort
        $query->orderBy(
            $request->input('sort', 'id'),
            $request->input('dir', 'desc')
        );

        // Export
        if ($request->has('export')) {
            return $this->dtExport($query, $request->input('export'));
        }

        // Paginate
        $data = $query->paginate($request->input('per_page', 10));

        // Add URLs
        $items = collect($data->items())->map(function ($item) {
            $prefix = $this->routePrefix ?? 'admin';
            $item->_edit_url = route("{$prefix}.edit", $item->id);
            $item->_show_url = route("{$prefix}.show", $item->id);
            return $item;
        });

        return response()->json([
            'data' => $items,
            'total' => $data->total(),
            'current_page' => $data->currentPage(),
            'last_page' => $data->lastPage(),
        ]);
    }

    protected function dtExport($query, $type)
    {
        $data = $query->get($this->exportable ?? ['*']);
        $name = strtolower(class_basename($this->model)) . '_' . date('Y-m-d');

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$name}.csv",
        ];

        $callback = function () use ($data) {
            $f = fopen('php://output', 'w');
            if ($data->count()) fputcsv($f, array_keys($data->first()->toArray()));
            foreach ($data as $row) fputcsv($f, $row->toArray());
            fclose($f);
        };

        return Response::stream($callback, 200, $headers);
    }
}
