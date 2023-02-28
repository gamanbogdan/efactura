<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EfacturaZipPath extends Model
{
    use HasFactory;

    protected $table='efactura_zip_path';



    
    protected $fillable = [
        'user_id',
        'file_name',
        'file_path'
        
    ];


    public function EfacturaInvoicePath()
    {
        return $this->hasMany(EfacturaInvoicePath::class,'file_upload_id', 'id');
    }
}
