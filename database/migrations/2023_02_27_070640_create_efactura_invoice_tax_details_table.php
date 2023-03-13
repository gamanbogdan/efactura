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
        Schema::create('efactura_invoice_tax_details', function (Blueprint $table) {

            $table->id();
            $table->unsignedBigInteger('invoice_id')->unsigned(); 
            $table->string('Baza_de_calcul')->nullable();
            $table->string('Baza_de_calcul_Codul_monedei')->nullable();
            $table->string('Valoare_TVA')->nullable();
            $table->string('Valoare_TVA_Codul_monedei')->nullable();
            $table->string('Codul_categoriei_de_TVA')->nullable();
            $table->string('Cota_categoriei_de_TVA')->nullable();

            $table->string('Codul_motivului_scutirii')->nullable();
            $table->string('Motivul_scutirii')->nullable();

            $table->timestamps();
            $table->foreign('invoice_id')->references('id')->on('efactura_invoice')->onDelete('cascade');
        });
    }



    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('efactura_invoice_tax_details');
    }
};
