<?php

namespace App\Models\Nomenclatoare;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnitateMasura extends Model
{
    use HasFactory;

    protected $table='efactura_nomenclator_unitate_masura';
    protected $primaryKey = 'cod';
    
    protected $fillable = [
        'cod',
        'denumire',        
    ];

    public function EfacturaInvoiceLine()
    {
        return $this->belongsTo(EfacturaInvoiceLine::class, 'UM', 'cod');
    }


}
