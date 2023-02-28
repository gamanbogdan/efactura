<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EfacturaInvoicePath extends Model
{
    use HasFactory;


    protected $table='efactura_invoice_path';

    protected $fillable = [
        'file_upload_id',
        'zip_name',
        'xml_name',
        'xml_path'
    ];


    public function EfacturaZipPath()
    {
        return $this->belongsTo(EfacturaZipPath::class, 'file_upload_id', 'id');
    }


}
