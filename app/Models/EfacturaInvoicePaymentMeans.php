<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

//////////// INSTRUCTIUNI DE PLATA ///////////////
class EfacturaInvoicePaymentMeans extends Model
{

    protected $table='efactura_invoice_payment_means';

    protected $fillable = [
        'invoice_id',
        'Codul_tipului_instrumentului_de_plata',
        'Nota_privind_instrumentul_de_plata',
        'Aviz_de_plata',
        'Numarul_contului_principal_al_cardului_de_plata',
        'Numele_detinatorului_cardului_de_plata',
        'Identificatorul_contului_de_plata',
        'Numele_contului_de_plata',
        'Identificatorul_furnizorului_de_servicii_de_plata',
        'Debitare_directa_Identificatorul_referintei_mandatului',
        'Debitare_directa_Identificatorul_contului_debitat',
    ];

    public function EfacturaInvoice()
    {
        return $this->belongsTo(EfacturaInvoice::class, 'invoice_id', 'id');
    }

    public function TipInstrumentPlata() {
        return $this->hasOne(Nomenclatoare\TipInstrumentPlata::class, 'cod', 'Codul_tipului_instrumentului_de_plata');
    }
}



