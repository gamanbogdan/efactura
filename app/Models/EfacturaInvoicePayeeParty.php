<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// beneficiar
class EfacturaInvoicePayeeParty extends Model
{
    protected $table='efactura_invoice_payee_party';

    protected $fillable = [
        'invoice_id',
        'Identificator',
        'Identificator_Identificatorul_schemei',
        'Nume_beneficiar',
        'Identificatorul_de_inregistrare_legala',
        'Identificatorul_de_inregistrare_legala_Identificatorul_schemei',
    ];

    public function EfacturaInvoice()
    {
        return $this->belongsTo(EfacturaInvoice::class, 'invoice_id', 'id');
    }


}
