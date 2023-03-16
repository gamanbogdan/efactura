<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// livrare
class EfacturaInvoiceDelivery extends Model
{
    use HasFactory;

    protected $table='efactura_invoice_delivery';

    protected $fillable = [
        'invoice_id',
        'Data_reala_a_livrarii',           
        'Numele_partii_catre_care_se_face_livrarea',                                            
        'Locatie_Identificatorul_locului',        
        'Locatie_Identificatorul_schemei',                     
        'Locatie_Adresa_Strada',        
        'Locatie_Adresa_Informatii_suplimentare_strada',        
        'Locatie_Adresa_Informatii_suplimentare_adresa',        
        'Locatie_Adresa_Oras',        
        'Locatie_Adresa_Cod_Postal',        
        'Locatie_Adresa_Subdiviziunea_tarii',        
        'Locatie_Adresa_Tara',
    ];

    public function EfacturaInvoice()
    {
        return $this->belongsTo(EfacturaInvoice::class, 'invoice_id', 'id');
    }
}
