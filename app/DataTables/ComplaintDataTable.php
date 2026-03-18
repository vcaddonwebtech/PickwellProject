<?php

namespace App\DataTables;

use App\Models\Complaint;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Http\Request;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class ComplaintDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        // $request = new Request;
        // // dump($request->all());
        // if ($request->has('date')) {
        //     $query->whereDate('date', $request->get('date'));
        // }

        // if ($request->has('engineer_name')) {
        //     $query->whereHas('engineer', function ($q) use ($request) {
        //         $q->where('name', 'like', '%' . $request->get('engineer_name') . '%');
        //     });
        // }
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->addColumn('action', function (Complaint $complaint) {
                return "
                        <a class='btn btn-sm btn-primary' href='" . route('complaints.edit', ['complaint' => $complaint]) . "'><i class='fa fa-edit'></i></a>
                        <a class='btn btn-sm btn-danger' href='javascript:void(0)' onclick='window.deleteParty(" . $complaint->id . ")'><i class='fa fa-trash'></i></a>
                        ";
                // <a class='btn btn-sm btn-info' href='javascript:void(0)' onclick='window.addItemPart(" . $complaint->id . ")'><i class='fa fa-product-hunt'></i></a>
            })
            ->addColumn("engineer_name", function (Complaint $complaint) {
                return $complaint->engineer->name ?? ' N/A';
            })
            ->addColumn("service_type_name", function (Complaint $complaint) {
                return $complaint->serviceType->name ?? ' N/A';
            })
            ->addColumn("status_name", function (Complaint $complaint) {
                return $complaint->status->name ?? ' N/A';
            })
            ->addColumn("complaint_type_name", function (Complaint $complaint) {
                return $complaint->complaintType->name ?? ' N/A';
            })
            ->addColumn("product_name", function (Complaint $complaint) {
                return $complaint->product->name ?? ' N/A';
            })
            ->addColumn("sales_entry_mc_no", function (Complaint $complaint) {
                return $complaint->salesEntry->mc_no ?? ' N/A';
            })
            ->addColumn("sales_entry_party_name", function (Complaint $complaint) {
                return $complaint->salesEntry->party->name ?? ' N/A';
            })
            ->addColumn("sales_entry_party_phone_no", function (Complaint $complaint) {
                return $complaint->salesEntry->party->phone_no ?? ' N/A';
            })
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Complaint $model): QueryBuilder
    {
        return $model->newQuery()->with('year', 'complaintType', 'salesEntry', 'product', 'engineer', 'serviceType', 'status');
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('complaint-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            //->dom('Bfrtip')
            ->orderBy(1)
            ->selectStyleSingle()
            ->buttons([
                Button::make('excel'),
                Button::make('csv'),
                Button::make('pdf'),
                Button::make('print'),
                Button::make('reset'),
                Button::make('reload')
            ]);
    }

    /**
     * Get the dataTable columns definition.
     */ //sr_no, date, time, complaint_no, product, sr_no, machine_no, party_name, mobile_number, camplaint_type, service_type, status, engineer_name
    public function getColumns(): array
    {
        return [
            Column::computed('DT_RowIndex')
                ->title('No.')
                ->searchable(false)
                ->orderable(false),
            Column::make('date'),
            Column::make('time'),
            Column::make('id'),
            Column::make('product_name'),
            Column::make('sales_entry_mc_no')->title('Machine No'),
            Column::make('sales_entry_party_name')->title('Party Name'),
            Column::make('sales_entry_party_phone_no')->title('Party Mobile Number'),
            Column::make('complaint_type_name'),
            Column::make('service_type_name'),
            Column::make('status_name'),
            Column::make('engineer_name'),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(60)
                ->addClass('text-center'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Complaint_' . date('YmdHis');
    }
}
