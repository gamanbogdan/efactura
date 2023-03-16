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
        Schema::create('efactura_invoice_payment_means', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('invoice_id')->unsigned(); 

            //BT-81 Codul tipului instrumentului de plata            
            $table->char('Codul_tipului_instrumentului_de_plata')->nullable();

            //BT-82 Nota privind instrumentul de plata
            $table->string('Nota_privind_instrumentul_de_plata')->nullable();

            //BT-83 Aviz de plata
            $table->string('Aviz_de_plata')->nullable();

            //BT-87 Numarul contului principal al cardului de plata
            $table->string('Numarul_contului_principal_al_cardului_de_plata')->nullable();

            //BT-88 Numele detinatorului cardului de plata
            $table->string('Numele_detinatorului_cardului_de_plata')->nullable();

            //BT-84 Identificatorul contului de plata
            $table->string('Identificatorul_contului_de_plata')->nullable();

            //BT-85 Numele contului de plata
            $table->string('Numele_contului_de_plata')->nullable();

            //BT-86 Identificatorul furnizorului de servicii de plata
            $table->string('Identificatorul_furnizorului_de_servicii_de_plata')->nullable();

            // DEBITARE directa///// 
		    //BT-89 Identificatorul referintei mandatului
            $table->string('Debitare_directa_Identificatorul_referintei_mandatului')->nullable();

		    //BT-91 Identificatorul contului debitat
            $table->string('Debitare_directa_Identificatorul_contului_debitat')->nullable();


            $table->timestamps();
            $table->foreign('invoice_id')->references('id')->on('efactura_invoice')->onDelete('cascade');
            $table->foreign('Codul_tipului_instrumentului_de_plata')->references('cod')->on('efactura_nomenclator_tip_instrument_plata');
 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('efactura_invoice_payment_means');
    }
};

