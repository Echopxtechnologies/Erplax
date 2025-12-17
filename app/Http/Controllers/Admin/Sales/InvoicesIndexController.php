<?php

namespace App\Http\Controllers\Admin\Sales;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Traits\DataTable;
use Illuminate\Http\Request;

class InvoicesIndexController extends Controller
{
    use DataTable;

    // ==================== DATATABLE CONFIGURATION ====================
    protected $model = Invoice::class;
    
    protected $with = ['customer'];
    
    protected $searchable = ['invoice_number', 'subject', 'customer.name'];
    
    protected $sortable = ['id', 'invoice_number', 'subject', 'total', 'amount_due', 'date', 'due_date', 'status', 'payment_status'];
    
    protected $filterable = ['status', 'payment_status'];
    
    protected $uniqueField = 'invoice_number';
    
    protected $exportTitle = 'Invoices Export';

    // Import validation rules
    protected $importable = [
        'invoice_number' => 'nullable|string|max:50',
        'subject'        => 'required|string|max:191',
        'customer_id'    => 'nullable|exists:customers,id',
        'date'           => 'nullable|date',
        'due_date'       => 'nullable|date',
        'total'          => 'nullable|numeric|min:0',
        'status'         => 'nullable|in:draft,sent,paid,cancelled',
        'payment_status' => 'nullable|in:unpaid,partial,paid,overdue',
    ];

    public function index()
    {
        $stats = [
            'total' => Invoice::count(),
            'draft' => Invoice::where('status', 'draft')->count(),
            'sent' => Invoice::where('status', 'sent')->count(),
            'paid' => Invoice::where('payment_status', 'paid')->count(),
            'overdue' => Invoice::where('due_date', '<', now())->where('payment_status', '!=', 'paid')->count(),
            'total_amount' => Invoice::sum('total'),
            'total_paid' => Invoice::sum('amount_paid'),
            'total_due' => Invoice::sum('amount_due'),
        ];
        
        return view('admin.sales.invoices.index', compact('stats'));
    }

    // ==================== CUSTOM ROW MAPPING FOR LIST ====================
    protected function mapRow($item)
    {
        return [
            'id' => $item->id,
            'invoice_number' => $item->invoice_number,
            'subject' => $item->subject,
            'customer_name' => $item->customer?->name ?? '-',
            'date' => $item->date ? date('d M Y', strtotime($item->date)) : '-',
            'due_date' => $item->due_date ? date('d M Y', strtotime($item->due_date)) : '-',
            'total' => number_format($item->total, 2),
            'amount_paid' => number_format($item->amount_paid, 2),
            'amount_due' => number_format($item->amount_due, 2),
            'status' => $item->status,
            'payment_status' => $item->payment_status,
            'is_overdue' => $item->due_date && $item->due_date < now() && $item->payment_status !== 'paid',
            '_edit_url' => route('admin.sales.invoices.edit', $item->id),
            '_show_url' => route('admin.sales.invoices.show', $item->id),
            '_delete_url' => route('admin.sales.invoices.destroy', $item->id),
        ];
    }

    // ==================== CUSTOM EXPORT ROW MAPPING ====================
    protected function mapExportRow($item)
    {
        return [
            'ID' => $item->id,
            'Invoice #' => $item->invoice_number,
            'Subject' => $item->subject,
            'Customer' => $item->customer?->name ?? '',
            'Date' => $item->date ? date('d M Y', strtotime($item->date)) : '',
            'Due Date' => $item->due_date ? date('d M Y', strtotime($item->due_date)) : '',
            'Total' => $item->total,
            'Paid' => $item->amount_paid,
            'Due' => $item->amount_due,
            'Status' => ucfirst($item->status),
            'Payment Status' => ucfirst($item->payment_status),
        ];
    }

    // ==================== CUSTOM IMPORT ROW HANDLER ====================
    protected function importRow($data, $row)
    {
        if (empty($data['invoice_number'])) {
            $data['invoice_number'] = Invoice::generateInvoiceNumber();
        }
        
        $data['status'] = $data['status'] ?? 'draft';
        $data['payment_status'] = $data['payment_status'] ?? 'unpaid';
        $data['date'] = $data['date'] ?? now();
        $data['due_date'] = $data['due_date'] ?? now()->addDays(30);
        
        $existing = Invoice::where('invoice_number', $data['invoice_number'])->first();
        
        if ($existing) {
            $existing->update($data);
            return $existing;
        }
        
        return Invoice::create($data);
    }

    // ==================== DATA ENDPOINT ====================
    public function data(Request $request)
    {
        return $this->handleData($request);
    }

    // ==================== CUSTOM QUERY FILTER ====================
    protected function customQuery($query, $request)
    {
        // Handle overdue filter
        if ($request->input('payment_status') === 'overdue') {
            $query->where('due_date', '<', now())->where('payment_status', '!=', 'paid');
        }
        
        return $query;
    }
}