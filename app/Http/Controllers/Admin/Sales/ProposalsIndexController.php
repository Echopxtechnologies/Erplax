<?php

namespace App\Http\Controllers\Admin\Sales;

use App\Http\Controllers\Controller;
use App\Models\Proposal;
use App\Traits\DataTable;
use Illuminate\Http\Request;

class ProposalsIndexController extends Controller
{
    use DataTable;

    protected $model = Proposal::class;
    protected $with = ['customer'];
    protected $searchable = ['proposal_number', 'subject', 'customer.name'];
    protected $sortable = ['id', 'proposal_number', 'subject', 'total', 'date', 'open_till', 'status'];
    protected $filterable = ['status'];
    protected $uniqueField = 'proposal_number';
    protected $exportTitle = 'Proposals Export';

    protected $importable = [
        'proposal_number' => 'nullable|string|max:50',
        'subject'         => 'required|string|max:191',
        'customer_id'     => 'nullable|exists:customers,id',
        'date'            => 'nullable|date',
        'open_till'       => 'nullable|date',
        'total'           => 'nullable|numeric|min:0',
        'status'          => 'nullable|in:draft,sent,open,accepted,declined,revised',
    ];

    public function index()
    {
        $stats = [
            'total' => Proposal::count(),
            'draft' => Proposal::where('status', 'draft')->count(),
            'sent' => Proposal::where('status', 'sent')->count(),
            'open' => Proposal::where('status', 'open')->count(),
            'accepted' => Proposal::where('status', 'accepted')->count(),
            'declined' => Proposal::where('status', 'declined')->count(),
        ];
        
        return view('admin.sales.proposals.index', compact('stats'));
    }

    protected function mapRow($item)
    {
        return [
            'id' => $item->id,
            'proposal_number' => $item->proposal_number,
            'subject' => $item->subject,
            'customer_name' => $item->customer?->name ?? '-',
            'date' => $item->date ? date('d M Y', strtotime($item->date)) : '-',
            'open_till' => $item->open_till ? date('d M Y', strtotime($item->open_till)) : '-',
            'total' => number_format($item->total, 2),
            'status' => $item->status,
            'assigned_to' => $item->assigned_to ?? '-',
            '_edit_url' => route('admin.sales.proposals.edit', $item->id),
            '_show_url' => route('admin.sales.proposals.show', $item->id),
            '_delete_url' => route('admin.sales.proposals.destroy', $item->id),
        ];
    }

    protected function mapExportRow($item)
    {
        return [
            'ID' => $item->id,
            'Proposal #' => $item->proposal_number,
            'Subject' => $item->subject,
            'Customer' => $item->customer?->name ?? '',
            'Date' => $item->date ? date('d M Y', strtotime($item->date)) : '',
            'Open Till' => $item->open_till ? date('d M Y', strtotime($item->open_till)) : '',
            'Total' => $item->total,
            'Status' => ucfirst($item->status),
            'Assigned To' => $item->assigned_to ?? '',
        ];
    }

    protected function importRow($data, $row)
    {
        if (empty($data['proposal_number'])) {
            $data['proposal_number'] = Proposal::generateProposalNumber();
        }
        
        $data['status'] = $data['status'] ?? 'draft';
        $data['date'] = $data['date'] ?? now();
        $data['open_till'] = $data['open_till'] ?? now()->addDays(30);
        
        $existing = Proposal::where('proposal_number', $data['proposal_number'])->first();
        
        if ($existing) {
            $existing->update($data);
            return $existing;
        }
        
        return Proposal::create($data);
    }

    public function data(Request $request)
    {
        return $this->handleData($request);
    }
}