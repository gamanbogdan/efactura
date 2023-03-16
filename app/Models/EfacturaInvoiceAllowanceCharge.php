<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
//////////// BG-21 TAXA SUPLIMENTAREA /////////////
//////////// BG-20 DEDUCERE //////////////////////
class EfacturaInvoiceAllowanceCharge extends Model
{
    use HasFactory;

    protected $table='efactura_invoice_allowance_charge';

    protected $fillable = [
        'invoice_id',
        'Indicator',
        'Codul_motivului',
        'Motivul',
        'Procent',                
        'Valoare',
        'Codul_monedei_RON',
        'Valoarea_de_baza',
        'Codul_categoriei_de_TVA',
        'Cota_de_TVA',
        'Identificatorul_schemei_VAT',
    ];

    public function EfacturaInvoice()
    {
        return $this->belongsTo(EfacturaInvoice::class, 'invoice_id', 'id');
    }
    
}






