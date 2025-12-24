<?php

namespace App\Http\Controllers\Admin\Sales;

use App\Http\Controllers\Admin\AdminController;
use App\Models\Estimation;
use App\Traits\DataTable;
use Illuminate\Http\Request;

class EstimationsIndexController extends AdminController
{
    use DataTable;

    protected $model = Estimation::class;
    protected $with = ['customer'];
    protected $searchable = ['estimation_number', 'subject', 'customer.name'];
    protected $sortable = ['id', 'estimation_number', 'subject', 'total', 'date', 'valid_until', 'status'];
    protected $filterable = ['status'];
    protected $uniqueField = 'estimation_number';
    protected $exportTitle = 'Estimations Export';

    protected $importable = [
        'estimation_number' => 'nullable|string|max:50',
        'subject'           => 'required|string|max:191',
        'customer_id'       => 'nullable|exists:customers,id',
        'date'              => 'nullable|date',
        'valid_until'       => 'nullable|date',
        'total'             => 'nullable|numeric|min:0',
        'status'            => 'nullable|in:draft,sent,accepted,declined,expired',
    ];

    public function index()
    {
        $stats = [
            'total' => Estimation::count(),
            'draft' => Estimation::where('status', 'draft')->count(),
            'sent' => Estimation::where('status', 'sent')->count(),
            'accepted' => Estimation::where('status', 'accepted')->count(),
            'declined' => Estimation::where('status', 'declined')->count(),
            'expired' => Estimation::where('valid_until', '<', now())->whereNotIn('status', ['accepted', 'declined'])->count(),
        ];
        
        return view('admin.sales.estimations.index', compact('stats'));
    }

    protected function mapRow($item)
    {
        return [
            'id' => $item->id,
            'estimation_number' => $item->estimation_number,
            'subject' => $item->subject,
            'customer_name' => $item->customer?->name ?? '-',
            'date' => $item->date ? date('d M Y', strtotime($item->date)) : '-',
            'valid_until' => $item->valid_until ? date('d M Y', strtotime($item->valid_until)) : '-',
            'total' => number_format($item->total, 2),
            'status' => $item->status,
            'is_expired' => $item->valid_until && $item->valid_until < now() && !in_array($item->status, ['accepted', 'declined']),
            '_edit_url' => route('admin.sales.estimations.edit', $item->id),
            '_show_url' => route('admin.sales.estimations.show', $item->id),
            '_delete_url' => route('admin.sales.estimations.destroy', $item->id),
        ];
    }

    protected function mapExportRow($item)
    {
        return [
            'ID' => $item->id,
            'Estimation #' => $item->estimation_number,
            'Subject' => $item->subject,
            'Customer' => $item->customer?->name ?? '',
            'Date' => $item->date ? date('d M Y', strtotime($item->date)) : '',
            'Valid Until' => $item->valid_until ? date('d M Y', strtotime($item->valid_until)) : '',
            'Total' => $item->total,
            'Status' => ucfirst($item->status),
        ];
    }

    protected function importRow($data, $row)
    {
        if (empty($data['estimation_number'])) {
            $data['estimation_number'] = Estimation::generateEstimationNumber();
        }
        
        $data['status'] = $data['status'] ?? 'draft';
        $data['date'] = $data['date'] ?? now();
        $data['valid_until'] = $data['valid_until'] ?? now()->addDays(30);
        
        $existing = Estimation::where('estimation_number', $data['estimation_number'])->first();
        
        if ($existing) {
            $existing->update($data);
            return $existing;
        }
        
        return Estimation::create($data);
    }

    public function data(Request $request)
    {
        return $this->handleData($request);
    }

    protected function customQuery($query, $request)
    {
        if ($request->input('status') === 'expired') {
            $query->where('valid_until', '<', now())->whereNotIn('status', ['accepted', 'declined']);
        }
        return $query;
    }
}