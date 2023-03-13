<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EfacturaPathZip extends Model
{
    use HasFactory;

    protected $table='efactura_path_zip';



    
    protected $fillable = [
        'user_id',
        'file_name',
        'file_path'
        
    ];


    public function EfacturaPathInvoice()
    {
        return $this->hasMany(EfacturaPathInvoice::class,'file_upload_id', 'id');
    }
}
