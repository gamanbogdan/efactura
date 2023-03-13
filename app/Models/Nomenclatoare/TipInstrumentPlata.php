<?php

namespace App\Models\Nomenclatoare;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipInstrumentPlata extends Model
{
    use HasFactory;


    protected $table='efactura_nomenclator_tip_instrument_plata';
    protected $primaryKey = 'cod';
    
    protected $fillable = [
        'cod',
        'denumire',        
    ];

    public function EfacturaInvoice()
    {
        return $this->belongsTo(EfacturaInvoice::class, 'Instructiuni_de_plata_Codul_tipului_instrumentului_de_plata', 'cod');
    }
}
