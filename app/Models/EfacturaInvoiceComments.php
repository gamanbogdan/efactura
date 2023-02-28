<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EfacturaInvoiceComments extends Model
{
    use HasFactory;

    protected $table='efactura_invoice_comments';

    protected $fillable = [
        'invoice_id',
        'Nota',
    ];

    public function EfacturaInvoice()
    {
        return $this->belongsTo(EfacturaInvoice::class, 'invoice_id', 'id');
    }
    
}
