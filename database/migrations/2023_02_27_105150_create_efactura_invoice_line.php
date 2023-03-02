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
        Schema::create('efactura_invoice_line', function (Blueprint $table) {
            
            $table->id();
            $table->unsignedBigInteger('invoice_id')->unsigned(); 


            $table->string('Nume_articol')->nullable();
            $table->string('Pretul_net_al_articolului')->nullable();
            $table->string('Pretul_net_al_articolului_Codul_monedei')->nullable();
            $table->string('Cantitatea_de_baza_a_pretului_articolului')->nullable();
            $table->string('Cantitate_facturata')->nullable();
            $table->string('UM')->nullable();
            $table->string('Codul_categoriei_de_TVA')->nullable();
            $table->string('Cota_de_TVA')->nullable(); 
            $table->string('Valoarea_neta_a_liniei')->nullable();

            $table->string('Informatii_suplimentare_Descriere_articol')->nullable();
            $table->string('Informatii_suplimentare_Tara_de_origine_a_articolului')->nullable();
            $table->string('Informatii_suplimentare_Nota_liniei_facturii')->nullable();
            $table->string('Informatii_suplimentare_Referinta_contabila_a_cumparatorului_din_linia_facturii')->nullable();
            $table->string('Informatii_suplimentare_Data_de_inceput_a_perioadei_de_facturare_a_liniei_facturii')->nullable();
            $table->string('Informatii_suplimentare_Data_de_sfarsit_a_perioadei_de_facturare_a_liniei_facturii')->nullable();
            $table->string('Informatii_suplimentare_Referinta_liniei_comenzii')->nullable();
            $table->string('Informatii_suplimentare_Identificatorul_obiectului_liniei_facturii')->nullable();
            $table->string('Informatii_suplimentare_Identificatorul_obiectului_liniei_facturii_Identificatorul_schemei')->nullable();
            $table->string('Informatii_suplimentare_Identificatorul_vanzatorului_articolului')->nullable();
            $table->string('Informatii_suplimentare_Identificatorul_cumparatorului_articolului')->nullable();
            $table->string('Informatii_suplimentare_Identificatorul_standard_al_articolului')->nullable();
            $table->string('Informatii_suplimentare_Identificatorul_standard_al_articolului_Identificatorul_schemei')->nullable();



            $table->string('Atributul_articolului_Numele_atributului_articolului')->nullable();
            $table->string('Atributul_articolului_Valoarea_atributului')->nullable();       
            
            
            $table->string('Taxa_suplimentara_Codul_motivului_taxei_suplimentare')->nullable(); 
            $table->string('Taxa_suplimentara_Motiv_taxa_suplimentara')->nullable(); 
            $table->string('Taxa_suplimentara_Procentajul_taxei_suplimentare')->nullable(); 
            $table->string('Taxa_suplimentara_Valoarea_taxei_suplimentare')->nullable(); 
            $table->string('Taxa_suplimentara_Valoarea_de_baza_a_taxei_suplimentare')->nullable(); 


            $table->string('Deducere_Codul_motivului_deducerii')->nullable(); 
            $table->string('Deducere_Motiv_deducere')->nullable(); 
            $table->string('Deducere_Procentajul_deducerii')->nullable(); 
            $table->string('Deducere_Valoarea_deducerii')->nullable(); 
            $table->string('Deducere_Valoarea_de_baza_a_deducerii')->nullable(); 


            $table->string('Deduceri_Reducere_taxa_suplimentara_la_pretul_articolului')->nullable(); 
            $table->string('Deduceri_Pretul_brut_al_articolului')->nullable(); 


            $table->timestamps();

            $table->foreign('invoice_id')->references('id')->on('efactura_invoice')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('efactura_invoice_line');
    }
};
