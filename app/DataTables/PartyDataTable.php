<?php

namespace App\DataTables;

use App\Models\Party;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class PartyDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->addColumn("city_name", function (Party $party) {
                if (isset($party->city)) {
                    return $party->city->name;
                } else {
                    return "-";
                }
                // return  $party->city->name;
            })
            ->addColumn("contct_person", function (Party $party) {
                return $party->contactPerson->name . " (" . $party->contactPerson->phone_no . ")";
            })
            ->addColumn("owner_name", function (Party $party) {
                return $party->owner->name . " (" . $party->owner->phone_no . ")";
            })
            ->addColumn('action', function (Party $party) {
                return  "<div class='btn-group m-1'><a class='btn btn-sm btn-primary' href='" . route('parties.edit', $party) . "'><i class='fa fa-edit'></i></a> <a class='btn btn-sm btn-danger' href='javascript:void(0)' onclick='window.deleteParty(" . $party->id . ")'><i class='fa fa-trash'></i></a></div>";
            })
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Party $model): QueryBuilder
    {
        return $model->newQuery()->with('city', 'state', 'area', 'owner', 'contactPerson')->orderBy('created_at', 'desc');
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('party-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            //->dom('Bfrtip')
            // ->orderBy(1)
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
    public function getColumns(): array
    {
        return [
            Column::computed('DT_RowIndex')
                ->title('No.')
                ->searchable(false)
                ->orderable(false),
            Column::make('name')->title('Party Name')->width(300),
            Column::make('address'),
            Column::make('phone_no')
                ->title('Mobile No.'),
            Column::make('city_name'),
            Column::make('area.name'),
            Column::make('contct_person'),
            Column::make('owner_name'),
            Column::make('id')->title('ID'),
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
        return 'Party_' . date('YmdHis');
    }
}
