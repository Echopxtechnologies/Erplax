<?php

namespace Modules\Inventory\Http\Controllers;

use App\Http\Controllers\Admin\AdminController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Modules\Inventory\Models\StockLevel;
use Modules\Core\Traits\DataTableTrait;

class BaseController extends AdminController
{
    use DataTableTrait;  // ← Fixed: was "DataTable", should be "DataTableTrait"

    /**
     * Get product stock quantity
     */
    protected function getProductStock($productId, $warehouseId = null, $rackId = null, $lotId = null)
    {
        $query = StockLevel::where('product_id', $productId);
        
        if ($warehouseId) {
            $query->where('warehouse_id', $warehouseId);
        }
        
        if ($rackId) {
            $query->where('rack_id', $rackId);
        }
        
        if ($lotId) {
            $query->where('lot_id', $lotId);
        }
        
        return $query->sum('qty');
    }

    /**
     * Build hint from validation rules for import templates
     */
    protected function buildImportHint($rules)
    {
        $req = str_contains($rules, 'required') ? 'Required' : 'Optional';
        
        if (str_contains($rules, 'email')) return "{$req}, Email";
        if (str_contains($rules, 'integer')) return "{$req}, Integer";
        if (str_contains($rules, 'numeric')) return "{$req}, Number";
        if (str_contains($rules, 'date')) return "{$req}, Date (YYYY-MM-DD)";
        if (preg_match('/in:([^|]+)/', $rules, $m)) return "{$req}, Options: {$m[1]}";
        if (preg_match('/exists:([^,]+),(\w+)/', $rules, $m)) return "{$req}, ID from {$m[1]}";
        
        return "{$req}, Text";
    }

    /**
     * Parse CSV file for imports
     */
    protected function parseCsv($file)
    {
        $rows = [];
        $headers = [];
        
        if (($handle = fopen($file->getPathname(), 'r')) !== false) {
            $line = 0;
            while (($data = fgetcsv($handle)) !== false) {
                $line++;
                if ($line === 1) {
                    $headers = array_map('trim', $data);
                    continue;
                }
                $row = [];
                foreach ($headers as $i => $h) {
                    $row[$h] = trim($data[$i] ?? '');
                }
                $rows[] = $row;
            }
            fclose($handle);
        }
        
        return $rows;
    }
}