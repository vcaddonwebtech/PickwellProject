<?php

namespace App\DataTables;

use App\Models\MachineSalesEntry;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class MachineSalesEntryDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.  sr.no product_sr.no, machine_no , date, product, party, address, mobile_no, contact_person_number, owner_phone_number, order_no, remarks
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->addColumn('action', function (MachineSalesEntry $machineSalesEntry) {
                return  "<div class='btn-group m-1'><a class='btn btn-sm btn-primary' href='" . route('MachineSales.edit', $machineSalesEntry) . "'><i class='fa fa-edit'></i></a> <a class='btn btn-sm btn-danger' href='javascript:void(0)' onclick='window.deleteParty(" . $machineSalesEntry->id . ")'><i class='fa fa-trash'></i></a></div>";
            })
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(MachineSalesEntry $model): QueryBuilder
    {
        return $model->newQuery()->with('party', 'product', 'serviceType')->orderBy('date', 'desc');
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('machinesalesentry-table')
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
     */
    //  sr.no product_sr.no, machine_no , date, product, party, address, mobile_no, contact_person_number, owner_phone_number, order_no, remarks
    public function getColumns(): array
    {
        return [
            Column::computed('DT_RowIndex')
                ->title('No.')
                ->searchable(false)
                ->orderable(false),
            Column::make('serial_no')
                ->title('Product Serial No.'),
            Column::make('mc_no')
                ->title('Machine No.'),
            Column::make('date'),
            Column::make('product.name'),
            Column::make('party.name'),
            Column::make('party.address'),
            Column::make('party.phone_no')->title('Mobile No.'),
            Column::make('party.contact_person.phone_no')->title('Contact Person Mobile Number'),
            Column::make('party.owner.phone_no')->title('Owner Mobile Number'),
            Column::make('order_no'),
            Column::make('remarks'),
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
        return 'MachineSalesEntry_' . date('YmdHis');
    }
}
