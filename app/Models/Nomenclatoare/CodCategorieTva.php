<?php

namespace App\Models\Nomenclatoare;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CodCategorieTva extends Model
{
    use HasFactory;

    protected $table='efactura_nomenclator_cod_categorie_tva';
    protected $primaryKey = 'cod';
    
    protected $fillable = [
        'cod',
        'denumire',        
    ];

    public function EfacturaInvoiceLine()
    {
        return $this->belongsTo(EfacturaInvoiceLine::class, 'Codul_categoriei_de_TVA', 'cod');
    }


}
