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
            $table->string('Vanzator_Adresa_Subdiviziunea')->nullable();

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
            $table->string('Vanzator_Informatii_juridice_suplimentare')->nullable();



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
            //////////// BENEFICIAR //////////////////////////
            //////////////////////////////////////////////////

           //BT-60 Identificator
           $table->string('Beneficiar_Identificator')->nullable();
                        
           //BT-60-1 Identificatorul schemei
           $table->string('Beneficiar_Identificator_Identificatorul_schemei')->nullable();
           
           //BT-59 Nume beneficiar            
           $table->string('Beneficiar_Nume_beneficiar')->nullable();
           
           //BT-61 Identificatorul de inregistrare legala
           $table->string('Beneficiar_Identificatorul_de_inregistrare_legala')->nullable();
           
           //BT-61-1 Identificatorul schemei
           $table->string('Beneficiar_Identificatorul_de_inregistrare_legala_Identificatorul_schemei')->nullable();
             

            //////////////////////////////////////////////////////////
            //////////// REPREZENTANTUL FISCAL AL VANZATORULUI////////
            //////////////////////////////////////////////////////////

            //BT-62 Nume
            $table->string('Reprezentantul_fiscal_al_vanzatorului_Nume')->nullable();
                
            // ADRESA /////

            //BT-64 Strada           
            $table->string('Reprezentantul_fiscal_al_vanzatorului_Adresa_Strada')->nullable();

            //BT-65 Informatii suplimentare strada         
            $table->string('Reprezentantul_fiscal_al_vanzatorului_Adresa_Informatii_suplimentare_strada')->nullable();

            //BT-66 Oras
            $table->string('Reprezentantul_fiscal_al_vanzatorului_Adresa_Oras')->nullable();

            //BT-67 Cod Postal           
            $table->string('Reprezentantul_fiscal_al_vanzatorului_Adresa_Cod_Postal')->nullable();
                    
            //BT-68 Subdiviziunea tarii          
            $table->string('Reprezentantul_fiscal_al_vanzatorului_Adresa_Subdiviziunea_tarii')->nullable();

            //BT-69 Tara
            $table->string('Reprezentantul_fiscal_al_vanzatorului_Adresa_Tara')->nullable();

            //BT-164 Informatii suplimentare adresa
            $table->string('Reprezentantul_fiscal_al_vanzatorului_Adresa_Informatii_suplimentare_adresa')->nullable();

            //BT-63 Identificatorul de TVA
            $table->string('Reprezentantul_fiscal_al_vanzatorului_Identificatorul_de_TVA')->nullable();

            
            //////////////////////////////////////////////////
            //////////// INFORMATII REFERITOARE LA LIVRARE ///
            //////////////////////////////////////////////////

            //BT-72 Data reala a livrarii
            $table->string('Informatii_referitoare_la_livrare_Data_reala_a_livrarii')->nullable();

            //BT-70 Numele partii catre care se face livrarea
            $table->string('Informatii_referitoare_la_livrare_Numele_partii_catre_care_se_face_livrarea')->nullable();
                            
            // ADRESA /////        
            //BT-71 Identificatorul locului
            $table->string('Informatii_referitoare_la_livrare_Locatie_Identificatorul_locului')->nullable();

            //BT-71-1 Identificatorul schemei
            $table->string('Informatii_referitoare_la_livrare_Locatie_Identificatorul_schemei')->nullable();             

            //BT-75 Strada
            $table->string('Informatii_referitoare_la_livrare_Locatie_Adresa_Strada')->nullable();

            //BT-76 Informatii suplimentare strada
            $table->string('Informatii_referitoare_la_livrare_Locatie_Adresa_Informatii_suplimentare_strada')->nullable();

            //BT-165 Informatii suplimentare adresa
            $table->string('Informatii_referitoare_la_livrare_Locatie_Adresa_Informatii_suplimentare_adresa')->nullable();

            //BT-77 Oras
            $table->string('Informatii_referitoare_la_livrare_Locatie_Adresa_Oras')->nullable();

            //BT-78 Cod Postal
            $table->string('Informatii_referitoare_la_livrare_Locatie_Adresa_Cod_Postal')->nullable();

            //BT-79 Subdiviziunea tarii
            $table->string('Informatii_referitoare_la_livrare_Locatie_Adresa_Subdiviziunea_tarii')->nullable();

            //BT-80 Tara
            $table->string('Informatii_referitoare_la_livrare_Locatie_Adresa_Tara')->nullable();
                    


            //////////////////////////////////////////////////
            //////////// INSTRUCTIUNI DE PLATA ///////////////
            //////////////////////////////////////////////////

            //BT-81 Codul tipului instrumentului de plata            
            $table->string('Instructiuni_de_plata_Codul_tipului_instrumentului_de_plata')->nullable();

            //BT-82 Nota privind instrumentul de plata
            $table->string('Instructiuni_de_plata_Nota_privind_instrumentul_de_plata')->nullable();

            //BT-83 Aviz de plata
            $table->string('Instructiuni_de_plata_Aviz_de_plata')->nullable();

            //BT-87 Numarul contului principal al cardului de plata
            $table->string('Instructiuni_de_plata_Numarul_contului_principal_al_cardului_de_plata')->nullable();

            //BT-88 Numele detinatorului cardului de plata
            $table->string('Instructiuni_de_plata_Numele_detinatorului_cardului_de_plata')->nullable();

            //BT-84 Identificatorul contului de plata
            $table->string('Instructiuni_de_plata_Identificatorul_contului_de_plata')->nullable();

            //BT-85 Numele contului de plata
            $table->string('Instructiuni_de_plata_Numele_contului_de_plata')->nullable();

            //BT-86 Identificatorul furnizorului de servicii de plata
            $table->string('Instructiuni_de_plata_Identificatorul_furnizorului_de_servicii_de_plata')->nullable();

            // DEBITARE directa///// 
		    //BT-89 Identificatorul referintei mandatului
            $table->string('Instructiuni_de_plata_Debitare_directa_Identificatorul_referintei_mandatului')->nullable();

		    //BT-91 Identificatorul contului debitat
            $table->string('Instructiuni_de_plata_Debitare_directa_Identificatorul_contului_debitat')->nullable();


            //////////////////////////////////////////////////
            //////////// TERMENI DE PLATA ////////////////////
            //////////////////////////////////////////////////
            //BT-20 Nota
            $table->string('Termeni_de_plata_Nota')->nullable();


            //////////////////////////////////////////////////
            //////////// BG-21 TAXA SUPLIMENTAREA /////////////
            //////////////////////////////////////////////////

            //BT-98/BT-105 Codul motivului
            $table->string('Taxarea_suplimentara_Codul_motivului')->nullable();

            //BT-97/BT-104 Motivul
            $table->string('Taxarea_suplimentara_Motivul')->nullable();

            //BT-94/BT-101 Procent
            $table->string('Taxarea_suplimentara_Procent')->nullable();
                        
            //BT-92/BT-99 Valoare
            $table->string('Taxarea_suplimentara_Valoare')->nullable();

            //Codul monedei RON
            $table->string('Taxarea_suplimentara_Codul_monedei_RON')->nullable();

            //BT-93/BT-100 Valoarea de baza
            $table->string('Taxarea_suplimentara_Valoarea_de_baza')->nullable();

            //BT-95/BT-102 Codul categoriei de TVA
            $table->string('Taxarea_suplimentara_Codul_categoriei_de_TVA')->nullable();

            //BT-96/BT-103 Cota de TVA
            $table->string('Taxarea_suplimentara_Cota_de_TVA')->nullable();

            //Identificatorul schemei VAT
            $table->string('Taxarea_suplimentara_Identificatorul_schemei_VAT')->nullable();


        	//////////////////////////////////////////////////
        	//////////// BG-20 DEDUCERE //////////////////////
        	//////////////////////////////////////////////////

            //BT-98/BT-105 Codul motivului
            $table->string('Deducere_Codul_motivului')->nullable();

            //BT-97/BT-104 Motivul
            $table->string('Deducere_Motivul')->nullable();

            //BT-94/BT-101 Procent
            $table->string('Deducere_Procent')->nullable();
                        
            //BT-92/BT-99 Valoare
            $table->string('Deducere_Valoare')->nullable();

            //Codul monedei RON
            $table->string('Deducere_Codul_monedei_RON')->nullable();

            //BT-93/BT-100 Valoarea de baza
            $table->string('Deducere_Valoarea_de_baza')->nullable();

            //BT-95/BT-102 Codul categoriei de TVA
            $table->string('Deducere_Codul_categoriei_de_TVA')->nullable();

            //BT-96/BT-103 Cota de TVA
            $table->string('Deducere_Cota_de_TVA')->nullable();

            //Identificatorul schemei VAT
            $table->string('Deducere_Identificatorul_schemei_VAT')->nullable();



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





            $table->timestamps();

            $table->foreign('invoice_path_id')->references('id')->on('efactura_invoice_path')->onDelete('cascade');
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

