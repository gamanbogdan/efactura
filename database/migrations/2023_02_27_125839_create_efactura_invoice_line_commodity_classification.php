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
        Schema::create('efactura_invoice_line_commodity_classification', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('line_id')->unsigned(); 


            $table->string('Identificatorul_clasificarii_articolului')->nullable();                         
            $table->string('Identificatorul_schemei')->nullable(); 
            $table->string('Identificatorul_versiunii_schemei')->nullable(); 


            $table->timestamps();
            $table->foreign('line_id')->references('id')->on('efactura_invoice_line')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('efactura_invoice_line_commodity_classification');
    }
};
