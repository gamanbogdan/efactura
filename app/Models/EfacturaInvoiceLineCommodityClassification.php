<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EfacturaInvoiceLineCommodityClassification extends Model
{
    use HasFactory;



    protected $table='efactura_invoice_line_commodity_classification';

    protected $fillable = [
        'line_id',
        'Identificatorul_clasificarii_articolului',
        'Identificatorul_schemei',
        'Identificatorul_versiunii_schemei',


    ];

    public function EfacturaInvoiceLine()
    {
        return $this->belongsTo(EfacturaInvoiceLine::class, 'line_id', 'id');
    }




}
