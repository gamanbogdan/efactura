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
        Schema::create('efactura_invoice_allowance_charge', function (Blueprint $table) {
            
            //////////////////////////////////////////////////
            //////////// BG-21 TAXA SUPLIMENTAREA /////////////
            //////////// BG-20 DEDUCERE //////////////////////
            //////////////////////////////////////////////////

            $table->id();
            
            $table->unsignedBigInteger('invoice_id')->unsigned(); 

            //indicator
            $table->boolean('Indicator')->nullable();

            //BT-98/BT-105 Codul motivului
            $table->string('Codul_motivului')->nullable();

            //BT-97/BT-104 Motivul
            $table->string('Motivul')->nullable();

            //BT-94/BT-101 Procent
            $table->string('Procent')->nullable();
                        
            //BT-92/BT-99 Valoare
            $table->string('Valoare')->nullable();

            //Codul monedei RON
            $table->string('Codul_monedei_RON')->nullable();

            //BT-93/BT-100 Valoarea de baza
            $table->string('Valoarea_de_baza')->nullable();

            //BT-95/BT-102 Codul categoriei de TVA
            $table->string('Codul_categoriei_de_TVA')->nullable();

            //BT-96/BT-103 Cota de TVA
            $table->string('Cota_de_TVA')->nullable();

            //Identificatorul schemei VAT
            $table->string('Identificatorul_schemei_VAT')->nullable();

            $table->foreign('invoice_id')->references('id')->on('efactura_invoice')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('efactura_invoice_allowance_charge');
    }
};
