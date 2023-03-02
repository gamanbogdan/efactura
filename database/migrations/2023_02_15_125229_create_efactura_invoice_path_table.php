<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('efactura_invoice_path', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('file_upload_id')->unsigned(); 
            
            $table->string('zip_name')->nullable();
            $table->string('xml_name')->nullable(); 
            $table->string('xml_path')->nullable();
            $table->date('date_created_anaf');
            $table->string('created_at_anaf')->nullable();
            $table->timestamps();


            $table->foreign('file_upload_id')->references('id')->on('efactura_zip_path')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('efactura_invoice_path');
    }
};
