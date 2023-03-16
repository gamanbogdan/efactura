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
        Schema::create('efactura_path_invoice', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('file_upload_id')->unsigned(); 
            
            
            $table->unsignedBigInteger('xml_name')->unsigned(); 
            $table->string('xml_path')->nullable();
            $table->integer('time')->unsigined();
            $table->date('date_created_anaf');
            $table->string('created_at_anaf')->nullable();
            $table->timestamps();

            $table->index(['xml_name']);
            $table->foreign('file_upload_id')->references('id')->on('efactura_path_zip')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('efactura_path_invoice');
    }
};
