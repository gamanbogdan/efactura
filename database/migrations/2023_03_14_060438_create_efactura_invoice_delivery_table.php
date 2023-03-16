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
        Schema::create('efactura_invoice_delivery', function (Blueprint $table) {
            
            $table->id();

            $table->unsignedBigInteger('invoice_id')->unsigned(); 
 
            //BT-72 Data reala a livrarii
            $table->string('Data_reala_a_livrarii')->nullable();

            //BT-70 Numele partii catre care se face livrarea
            $table->string('Numele_partii_catre_care_se_face_livrarea')->nullable();
                            
            // ADRESA /////        
            //BT-71 Identificatorul locului
            $table->string('Locatie_Identificatorul_locului')->nullable();

            //BT-71-1 Identificatorul schemei
            $table->string('Locatie_Identificatorul_schemei')->nullable();             

            //BT-75 Strada
            $table->string('Locatie_Adresa_Strada')->nullable();

            //BT-76 Informatii suplimentare strada
            $table->string('Locatie_Adresa_Informatii_suplimentare_strada')->nullable();

            //BT-165 Informatii suplimentare adresa
            $table->string('Locatie_Adresa_Informatii_suplimentare_adresa')->nullable();

            //BT-77 Oras
            $table->string('Locatie_Adresa_Oras')->nullable();

            //BT-78 Cod Postal
            $table->string('Locatie_Adresa_Cod_Postal')->nullable();

            //BT-79 Subdiviziunea tarii
            $table->string('Locatie_Adresa_Subdiviziunea_tarii')->nullable();

            //BT-80 Tara
            $table->string('Locatie_Adresa_Tara')->nullable();

            $table->timestamps();

            $table->foreign('invoice_id')->references('id')->on('efactura_invoice')->onDelete('cascade');
        
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('efactura_invoice_delivery');
    }
};
