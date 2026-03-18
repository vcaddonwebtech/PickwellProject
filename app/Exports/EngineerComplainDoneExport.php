<?php

namespace App\Exports;

use App\Models\Complaint;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class EngineerComplainDoneExport implements FromCollection, WithHeadings
{
    private $requestData;

    public function __construct($requestData)
    {
        $this->requestData = $requestData;
    }

    public function collection()
    {
        $query = Complaint::where('status_id', '3')
            ->orderBy('id', 'desc')
            ->with(['party' => function ($query) {
                $query->select('id', 'code', 'name', 'phone_no');
            }])
            ->with('complaintType', 'engineer');

        if (isset($this->requestData['engineer_visit_from'])) {
            $query->where('engineer_out_date', '>=', $this->requestData['engineer_visit_from']);
        }

        if (isset($this->requestData['engineer_visit_to'])) {
            $query->where('engineer_out_date', '<=', $this->requestData['engineer_visit_to']);
        }

        if (isset($this->requestData['party_id'])) {
            $query->where('party_id', $this->requestData['party_id']);
        }

        if (isset($this->requestData['engineer_id'])) {
            $query->where('engineer_id', $this->requestData['engineer_id']);
        }

        $complaints = $query->get()->map(function ($complaint) {
            return [
                'Done Date' => isset($complaint->engineer_out_date) ? date('d-m-Y', strtotime($complaint->engineer_out_date)) : '',
                'Complain Date' => isset($complaint->date) ? date('d-m-Y', strtotime($complaint->date)) : '',
                'Complaint No' => $complaint->complaint_no ?? '',
                'Party Code' => $complaint->party->code ?? '',
                'Party Name' => $complaint->party->name ?? '',
                'Party Phone' => $complaint->party->phone_no ?? '',
                'Engineer' => $complaint->engineer->name ?? '',
                'Time Duration' => $complaint->engineer_time_duration ?? '',
                'Complaint Type' => $complaint->complaintType->name ?? '',
            ];
        });

        return $complaints;
    }

    public function headings(): array
    {
        return [
            'Done Date',
            'Complain Date',
            'Complaint No',
            'Party Code',
            'Party Name',
            'Party Phone',
            'Engineer',
            'Time Duration',
            'Complaint Type',
        ];
    }
}
