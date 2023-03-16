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
        Schema::create('efactura_invoice_tax_representative_party', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('invoice_id')->unsigned(); 
            //Reprezentantul_fiscal_al_vanzatorului
            //BT-62 Nume
            $table->string('Nume')->nullable();
                
            // ADRESA /////

            //BT-64 Strada           
            $table->string('Adresa_Strada')->nullable();

            //BT-65 Informatii suplimentare strada         
            $table->string('Adresa_Informatii_suplimentare_strada')->nullable();

            //BT-66 Oras
            $table->string('Adresa_Oras')->nullable();

            //BT-67 Cod Postal           
            $table->string('Adresa_Cod_Postal')->nullable();
                    
            //BT-68 Subdiviziunea tarii          
            $table->string('Adresa_Subdiviziunea_tarii')->nullable();

            //BT-69 Tara
            $table->string('Adresa_Tara')->nullable();

            //BT-164 Informatii suplimentare adresa
            $table->string('Adresa_Informatii_suplimentare_adresa')->nullable();

            //BT-63 Identificatorul de TVA
            $table->string('Identificatorul_de_TVA')->nullable();


            $table->foreign('invoice_id')->references('id')->on('efactura_invoice')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('efactura_invoice_tax_representative_party');
    }
};




