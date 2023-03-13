<?php

namespace App\DataTables;

use App\Models\EfacturaInvoice;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class EfacturaInvoiceDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))


        ->addColumn('Nr_factura',function($row){
                
            return '<a href="'.route('efactura.show', $row->id).'"> '.$row->Informatii_factura_Nr_factura .' </a>';
            
        })

        ->addColumn('Is_fcn', function($row){ 
            $is_fcn = "";
            if($row->is_fcn) {
                $is_fcn = '<p class="text-success"> FCN </p>';
            }
            return $is_fcn;
        })



        ->rawColumns(['Nr_factura', 'Is_fcn'])
        ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(EfacturaInvoice $model): QueryBuilder
    {






        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('efacturainvoice-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    //->dom('Bfrtip')
                    ->orderBy(1)
                    ->parameters([

                        'dom'          => 'Bfrtip',

                        'buttons'      => ['excel', 'csv'],

                    ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [

            Column::make('id'),
            Column::make('Nr_factura'),
            Column::make('Vanzator_Nume'),
            Column::make('Cumparator_Nume'),
            Column::make('Is_fcn')
            ->filterColumn('fullname', function($query, $keyword) {
                $sql = "CONCAT(EfacturaInvoice.Vanzator_Nume,'-',EfacturaInvoice.Vanzator_Nume)  like ?";
                $query->whereRaw($sql, ["%{$keyword}%"]);
            })
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
        return 'EfacturaInvoice_' . date('YmdHis');
    }
}
