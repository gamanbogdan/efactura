<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EfacturaInvoiceTaxDetails extends Model
{
    use HasFactory;


    protected $table='efactura_invoice_tax_details';

    protected $fillable = [
        'invoice_id',
        'Baza_de_calcul',
        'Baza_de_calcul_Codul_monedei',
        'Valoare_TVA',
        'Valoare_TVA_Codul_monedei',
        'Codul_categoriei_de_TVA',
        'Cota_categoriei_de_TVA',
        'Codul_motivului_scutirii',
        'Motivul_scutirii',

    ];

    public function EfacturaInvoice()
    {
        return $this->belongsTo(EfacturaInvoice::class, 'invoice_id', 'id');
    }

}
