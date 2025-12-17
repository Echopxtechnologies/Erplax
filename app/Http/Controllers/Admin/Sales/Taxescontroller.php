<?php

namespace App\Http\Controllers\Admin\Sales;

use App\Http\Controllers\Controller;
use App\Models\Tax;
use Illuminate\Http\Request;

class TaxesController extends Controller
{
    /**
     * Get all active taxes (API endpoint for invoice form)
     */
    public function index()
    {
        // Check if it's an AJAX/JSON request
        if (request()->ajax() || request()->wantsJson() || request()->is('api/*')) {
            $taxes = Tax::where('active', 1)
                ->orderBy('name')
                ->get(['id', 'name', 'rate']);
            
            return response()->json($taxes);
        }
        
        // For regular page view
        $taxes = Tax::orderBy('name')->paginate(20);
        return view('admin.sales.taxes.index', compact('taxes'));
    }

    /**
     * Store a new tax
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'rate' => 'required|numeric|min:0|max:100',
            'active' => 'boolean',
        ]);

        $validated['active'] = $request->has('active') ? 1 : 1; // Default active

        Tax::create($validated);

        return redirect()->route('admin.sales.taxes.index')
            ->with('success', 'Tax created successfully.');
    }

    /**
     * Update existing tax
     */
    public function update(Request $request, Tax $tax)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'rate' => 'required|numeric|min:0|max:100',
            'active' => 'boolean',
        ]);

        $validated['active'] = $request->has('active') ? 1 : 0;

        $tax->update($validated);

        return redirect()->route('admin.sales.taxes.index')
            ->with('success', 'Tax updated successfully.');
    }

    /**
     * Delete tax
     */
    public function destroy(Tax $tax)
    {
        $tax->delete();

        return redirect()->route('admin.sales.taxes.index')
            ->with('success', 'Tax deleted successfully.');
    }
}