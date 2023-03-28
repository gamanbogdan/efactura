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
        Schema::create('efactura_invoice', function (Blueprint $table) {

            $table->id();

            $table->unsignedBigInteger('invoice_path_id')->unsigned();

            $table->date('created_anaf');
            ///////////////////////////////////////////////
            // INFORMATII FACTURA /////////////////////////
            ///////////////////////////////////////////////

            //BT-1 Nr. factura
            $table->string('Informatii_factura_Nr_factura')->nullable();

            //BT-2 Data emitere factura
            $table->string('Informatii_factura_Data_emitere_factura')->nullable();

            //BT-9 Data scadenta factura
            $table->string('Informatii_factura_Data_scadenta_factura')->nullable();

            //BT-5 Codul monedei facturii
            $table->string('Informatii_factura_Codul_monedei_facturii')->nullable();

            //BT-6 Codul monedei de contabilizare a TVA
            $table->string('Informatii_factura_Codul_monedei_de_contabilizare_a_TVA')->nullable();

            //BT-7 Data de exigibilitate a TVA
            $table->string('Informatii_factura_Data_de_exigibilitate_a_TVA')->nullable();

            // BT-73 Data de început a perioadei de facturare
            $table->string('Informatii_factura_Data_de_inceput_a_perioadei_de_facturare')->nullable();

            // BT-74 Data de sfarsit a perioadei de facturare
            $table->string('Informatii_factura_Data_de_sfarsit_a_perioadei_de_facturare')->nullable();

            // BT-19 Referinta cumparatorului
            $table->string('Informatii_factura_Referinta_cumparatorului')->nullable();

            //BT-13 Referinta comenzii
            $table->string('Informatii_factura_Referinta_comenzii')->nullable();

            //BT-14 Referinta dispozitiei de vanzare
            $table->string('Informatii_factura_Referinta_dispozitiei_de_vanzare')->nullable();

            //BT-25 Referinta la o factura anterioara:
            $table->string('Informatii_factura_Referinta_la_o_factura_anterioara')->nullable();

            //BT-26 Data de emitere a facturii anterioare:
            $table->string('Informatii_factura_Data_de_emitere_a_facturii_anterioare')->nullable();

            //BT-16 Referinta avizului de expeditie
            $table->string('Informatii_factura_Referinta_avizului_de_expeditie')->nullable();

            //BT-15 Referinta avizului de receptie
            $table->string('Informatii_factura_Referinta_avizului_de_receptie')->nullable();

            //BT-17 Referinta cererii de oferta sau a lotului
            $table->string('Informatii_factura_Referinta_cererii_de_oferta_sau_a_lotului')->nullable();

            //BT-12 Referinta contractului
            $table->string('Informatii_factura_Referinta_contractului')->nullable();

            //BT-11 Referinta proiectului
            $table->string('Informatii_factura_Referinta_proiectului')->nullable();


            //////////////////////////////////////////////
            /// VAnZATOR /////////////////////////////////
            //////////////////////////////////////////////

            //BT-34 Adresa electronica
            $table->string('Vanzator_Adresa_electronica')->nullable();

            //BT-34-1 Identificatorul schemei
            $table->string('Vanzator_Adresa_electronica_Identificatorul_schemei')->nullable();


            //BT-41 Persoana de contact
            $table->string('Vanzator_Persoana_de_contact')->nullable();


            //BT-42 Telefon persoana de contact
            $table->string('Vanzator_Telefon_persoana_de_contact')->nullable();


            //BT-43 E-mail persoana de contact
            $table->string('Vanzator_E_mail_persoana_de_contact')->nullable();


            //BT-29 Identificator
            $table->string('Vanzator_Identificator')->nullable();


            //BT-29-1 Identificatorul schemei
            $table->string('Vanzator_Identificator_Identificatorul_schemei')->nullable();


            //BT-28 Denumire comerciala
            $table->string('Vanzator_Denumire_comerciala')->nullable();

            // ADRESA //////////////////////////

            //BT-35 Strada
            $table->string('Vanzator_Adresa_Strada')->nullable();

            //BT-36 Informatii suplimentare strada
            $table->string('Vanzator_Adresa_Informatii_suplimentare_strada')->nullable();

            //BT-37 Oras
            $table->string('Vanzator_Adresa_Oras')->nullable();

            //BT-38 Cod Postal
            $table->string('Vanzator_Adresa_Cod_Postal')->nullable();

            //BT-39 Subdiviziunea
            $table->string('Vanzator_Adresa_Subdiviziunea_tarii')->nullable();

            //BT-40 Tara
            $table->string('Vanzator_Adresa_Tara')->nullable();

            //BT-162 Informatii suplimentare adresa
            $table->string('Vanzator_Adresa_Informatii_suplimentare_adresa')->nullable();

            //BT-31 Identificatorul de TVA
            $table->string('Vanzator_Identificatorul_de_TVA')->nullable();

            //BT-27 Nume
            $table->string('Vanzator_Nume')->nullable();

            //BT-30 Identificatorul de inregistrare legala
            $table->string('Vanzator_Identificatorul_de_inregistrare_legala')->nullable();

            //BT-30-1 Identificatorul schemei
            $table->string('Vanzator_Identificatorul_de_inregistrare_legala_Identificatorul_schemei')->nullable();

            //BT-33 Informatii juridice suplimentare
            $table->string('Vanzator_Informatii_juridice_suplimentare', 500)->nullable();



            //////////////////////////////////////////////////
            //////////// CUMPARATOR //////////////////////////
            //////////////////////////////////////////////////

            //BT-49 Adresa electronica
            $table->string('Cumparator_Adresa_electronica')->nullable();

            //BT-49-1 Identificatorul schemei
            $table->string('Cumparator_Adresa_electronica_Identificatorul_schemei')->nullable();

            //BT-56 Persoana de contact
            $table->string('Cumparator_Persoana_de_contact')->nullable();

            //BT-57 Telefon persoana de contact
            $table->string('Cumparator_Telefon_persoana_de_contact')->nullable();

            //BT-58 E-mail persoana de contact
            $table->string('Cumparator_E_mail_persoana_de_contact')->nullable();

            //BT-46 Identificator
            $table->string('Cumparator_Identificator')->nullable();

            //BT-46-1 Identificatorul schemei
            $table->string('Cumparator_Identificator_Identificatorul_schemei')->nullable();

            //BT-45 Denumire comerciala
            $table->string('Cumparator_Denumire_comerciala')->nullable();

            // ADRESA /////

            //BT-50 Strada
            $table->string('Cumparator_Adresa_Strada')->nullable();

            //BT-51 Informatii suplimentare strada
            $table->string('Cumparator_Adresa_Informatii_suplimentare_strada')->nullable();

            //BT-52 Oras
            $table->string('Cumparator_Adresa_Oras')->nullable();

            //BT-53 Cod Postal
            $table->string('Cumparator_Adresa_Cod_Postal')->nullable();

            //BT-54 Subdiviziunea tarii
            $table->string('Cumparator_Adresa_Subdiviziunea_tarii')->nullable();

            //BT-55 Tara
            $table->string('Cumparator_Adresa_Tara')->nullable();

            //BT-163 Informatii suplimentare adresa
            $table->string('Cumparator_Adresa_Informatii_suplimentare_adresa')->nullable();

            //BT-48 Identificatorul de TVA
            $table->string('Cumparator_Identificatorul_de_TVA')->nullable();

            //BT-44 Nume
            $table->string('Cumparator_Nume')->nullable();

            //BT-47 Identificatorul de inregistrare legala
            $table->string('Cumparator_Identificatorul_de_inregistrare_legala')->nullable();

            //BT-47-1 Identificatorul schemei
            $table->string('Cumparator_Identificatorul_de_inregistrare_legala_Identificatorul_schemei')->nullable();









            //////////////////////////////////////////////////
            //////////// TERMENI DE PLATA ////////////////////
            //////////////////////////////////////////////////
            //BT-20 Nota
            $table->text('Termeni_de_plata_Nota')->nullable();






            ////////////////////////////////////////////////////////
            //////////// BG-22 TOTALURILE DOCUMENTULUI /////////////
            ////////////////////////////////////////////////////////

            //BT-106 Suma valorilor nete ale liniilor facturii
            $table->string('Totalurile_documentului_Suma_valorilor_nete_ale_liniilor_facturii')->nullable();

            //Codul monedei Codul monedei
            $table->string('Totalurile_documentului_Suma_valorilor_nete_ale_liniilor_facturii_Codul_monedei')->nullable();

            //BT-109 Valoarea totala a facturii fara TVA
            $table->string('Totalurile_documentului_Valoarea_totala_a_facturii_fara_TVA')->nullable();

            //Codul monedei
            $table->string('Totalurile_documentului_Valoarea_totala_a_facturii_fara_TVA_Codul_monedei')->nullable();

            //BT-112 Valoarea totala a facturii cu TVA
            $table->string('Totalurile_documentului_Valoarea_totala_a_facturii_cu_TVA')->nullable();

            //Codul monedei
            $table->string('Totalurile_documentului_Valoarea_totala_a_facturii_cu_TVA_Codul_monedei')->nullable();

            //BT-107 Suma deducerilor la nivelul documentului
            $table->string('Totalurile_documentului_Suma_deducerilor_la_nivelul_documentului')->nullable();

            //Codul monedei
            $table->string('Totalurile_documentului_Suma_deducerilor_la_nivelul_documentului_Codul_monedei')->nullable();

            //BT-108 Suma taxelor suplimentare la nivelul documentului
            $table->string('Totalurile_documentului_Suma_taxelor_suplimentare_la_nivelul_documentului')->nullable();

            //Codul monedei
            $table->string('Totalurile_documentului_Suma_taxelor_suplimentare_la_nivelul_documentului_Codul_monedei')->nullable();

            //BT-113 Suma platita
            $table->string('Totalurile_documentului_Suma_platita')->nullable();

            //Codul monedei
            $table->string('Totalurile_documentului_Suma_platita_Codul_monedei')->nullable();

            //BT-114 Valoare de rotunjire
            $table->string('Totalurile_documentului_Valoare_de_rotunjire')->nullable();

            //Codul monedei
            $table->string('Totalurile_documentului_Valoare_de_rotunjire_Codul_monedei')->nullable();

            //BT-115 Suma de plata
            $table->string('Totalurile_documentului_Suma_de_plata')->nullable();

            //Codul monedei
            $table->string('Totalurile_documentului_Suma_de_plata_Codul_monedei')->nullable();



            //////////////////////////////////////////////////
            //////////// TOTALURI TVA ////////////////////////
            //////////////////////////////////////////////////
            //BT-110 Valoarea totala a TVA a facturii
            $table->string('Totaluri_tva_Valoarea_totala_a_TVA_a_facturii')->nullable();

            // BT-111
            $table->string('Totaluri_tva_Codul_monedei')->nullable();


            /////////////////////////////////////////////////
            // FCN REMARKS //////////////////////////////////
            /////////////////////////////////////////////////

            $table->boolean('is_fcn')->nullable()->default(null);
            $table->string('comment_fcn')->nullable();

            $table->timestamps();

            $table->foreign('invoice_path_id')->references('id')->on('efactura_path_invoice')->onDelete('cascade');

            $table->index(['created_anaf']);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('efactura_invoice');
    }
};

