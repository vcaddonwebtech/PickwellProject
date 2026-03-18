<?php

namespace App\Exports;

use App\Models\Complaint;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ComplaintExport implements FromCollection, WithHeadings
{
    private $requestData;

    public function __construct($requestData)
    {
        $this->requestData = $requestData;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $query = Complaint::where('status_id', '3')
            ->orderBy('id', 'desc')
            ->with(['party' => function ($query) {
                $query->select('id', 'code', 'name', 'phone_no');
            }])
            ->with('complaintType', 'engineer', 'status', 'party.area');

        if (isset($this->requestData['complaint_pending_from'])) {
            $query->where('engineer_out_date', '>=', $this->requestData['complaint_pending_from']);
        }

        if (isset($this->requestData['complaint_pending_to'])) {
            $query->where('engineer_out_date', '<=', $this->requestData['complaint_pending_to']);
        }

        if (isset($this->requestData['party_id'])) {
            $query->where('party_id', $this->requestData['party_id']);
        }

        if (isset($this->requestData['engineer_id'])) {
            $query->where('engineer_id', $this->requestData['engineer_id']);
        }

        if (isset($this->requestData['status_id'])) {
            $query->where('status_id', $this->requestData['status_id']);
        }


        $complaints = $query->get()->map(function ($complaint) {
            return [
                'Complain Date' => isset($complaint->date) ? date('d-m-Y', strtotime($complaint->date)) : '',
                'Complaint No' => $complaint->complaint_no ?? '',
                'Party Code' => $complaint->party->code ?? '',
                'Party Name' => $complaint->party->name ?? '',
                'Party Phone' => $complaint->party->phone_no ?? '',
                'Engineer' => $complaint->engineer->name ?? '',
                'Area' => $complaint->party->area->name ?? '',
                'Complaint Type' => $complaint->complaintType->name ?? '',
                'Status' => $complaint->status->name ?? '',
            ];
        });

        return $complaints;
    }

    public function headings(): array
    {
        return [
            'Complain Date',
            'Complaint No',
            'Party Code',
            'Party Name',
            'Party Phone',
            'Engineer',
            'Area',
            'Complaint Type',
            'Status',
        ];
    }
}
