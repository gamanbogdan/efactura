<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// reprezentantul fiscal al vanzatorului
class EfacturaInvoiceTaxRepresentativeParty extends Model
{

    protected $table='efactura_invoice_tax_representative_party';
    
    protected $fillable = [
        'invoice_id',
        'Nume',
        'Adresa_Strada',
        'Adresa_Informatii_suplimentare_strada',
        'Adresa_Oras',
        'Adresa_Cod_Postal',
        'Adresa_Subdiviziunea_tarii',
        'Adresa_Tara',          
        'Adresa_Informatii_suplimentare_adresa',
        'Identificatorul_de_TVA',
    ];

    public function EfacturaInvoice()
    {
        return $this->belongsTo(EfacturaInvoice::class, 'invoice_id', 'id');
    }
}


