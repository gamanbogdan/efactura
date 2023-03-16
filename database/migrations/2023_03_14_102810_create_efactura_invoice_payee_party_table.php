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
        Schema::create('efactura_invoice_payee_party', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('invoice_id')->unsigned(); 

            //BT-60 Identificator
            $table->string('Identificator')->nullable();
                    
            //BT-60-1 Identificatorul schemei
            $table->string('Identificator_Identificatorul_schemei')->nullable();

            //BT-59 Nume beneficiar            
            $table->string('Nume_beneficiar')->nullable();

            //BT-61 Identificatorul de inregistrare legala
            $table->string('Identificatorul_de_inregistrare_legala')->nullable();

            //BT-61-1 Identificatorul schemei
            $table->string('Identificatorul_de_inregistrare_legala_Identificatorul_schemei')->nullable();
            
            $table->foreign('invoice_id')->references('id')->on('efactura_invoice')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('efactura_invoice_payee_party');
    }
};
