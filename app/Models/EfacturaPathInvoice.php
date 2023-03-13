<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EfacturaPathInvoice extends Model
{
    use HasFactory;


    protected $table='efactura_path_invoice';


    protected $dates = [
        'date_created_anaf',
    ];

    protected $fillable = [
        'file_upload_id',
        'xml_name',
        'xml_path',
        'time',
        'date_created_anaf',
        'created_at_anaf',
    ];


    public function EfacturaPathZip()
    {
        return $this->belongsTo(EfacturaPathZip::class, 'file_upload_id', 'id');
    }


}
