<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EfacturaInvoice extends Model
{
    use HasFactory;

    protected $table='efactura_invoice';

    protected $fillable = [
        'invoice_path_id',
        'created_anaf',
        'Informatii_factura_Nr_factura',  
        'Informatii_factura_Data_emitere_factura', 
        'Informatii_factura_Data_scadenta_factura',
        'Informatii_factura_Codul_monedei_facturii', 
        'Informatii_factura_Codul_monedei_de_contabilizare_a_TVA',
        'Informatii_factura_Data_de_exigibilitate_a_TVA',
        'Informatii_factura_Data_de_inceput_a_perioadei_de_facturare',
        'Informatii_factura_Data_de_sfarsit_a_perioadei_de_facturare',
        'Informatii_factura_Referinta_cumparatorului',
        'Informatii_factura_Referinta_comenzii',
        'Informatii_factura_Referinta_dispozitiei_de_vanzare',
        'Informatii_factura_Referinta_la_o_factura_anterioara',
		'Informatii_factura_Data_de_emitere_a_facturii_anterioare',
        'Informatii_factura_Referinta_avizului_de_expeditie',
        'Informatii_factura_Referinta_avizului_de_receptie',
        'Informatii_factura_Referinta_cererii_de_oferta_sau_a_lotului',
        'Informatii_factura_Referinta_contractului', 
        'Informatii_factura_Referinta_proiectului',

        'Vanzator_Adresa_electronica',
        'Vanzator_Adresa_electronica_Identificatorul_schemei',
        'Vanzator_Persoana_de_contact',
        'Vanzator_Telefon_persoana_de_contact',        
        'Vanzator_E-mail_persoana_de_contact',
        'Vanzator_Identificator',
        'Vanzator_Identificator_Identificatorul_schemei',
        'Vanzator_Denumire_comerciala',
        'Vanzator_Adresa_Strada',
        'Vanzator_Adresa_Informatii_suplimentare_strada',
        'Vanzator_Adresa_Oras',
        'Vanzator_Adresa_Cod_Postal',
        'Vanzator_Adresa_Subdiviziunea',
        'Vanzator_Adresa_Tara',
        'Vanzator_Adresa_Informatii_suplimentare_adresa',
        'Vanzator_Identificatorul_de_TVA',
        'Vanzator_Nume',
        'Vanzator_Identificatorul_de_inregistrare_legala',
        'Vanzator_Identificatorul_de_inregistrare_legala_Identificatorul_schemei',        
        'Vanzator_Informatii_juridice_suplimentare',

        'Cumparator_Adresa_electronica',
        'Cumparator_Adresa_electronica_Identificatorul_schemei',
        'Cumparator_Persoana_de_contact',
        'Cumparator_Telefon_persoana_de_contact',
        'Cumparator_E_mail_persoana_de_contact',
        'Cumparator_Identificator',
        'Cumparator_Identificator_Identificatorul_schemei',
        'Cumparator_Denumire_comerciala',
        'Cumparator_Adresa_Strada',
        'Cumparator_Adresa_Informatii_suplimentare_strada',
        'Cumparator_Adresa_Oras',
        'Cumparator_Adresa_Cod_Postal',
        'Cumparator_Adresa_Subdiviziunea_tarii',
        'Cumparator_Adresa_Tara',
        'Cumparator_Adresa_Informatii_suplimentare_adresa',
        'Cumparator_Identificatorul_de_TVA',
        'Cumparator_Nume',
        'Cumparator_Identificatorul_de_inregistrare_legala',
        'Cumparator_Identificatorul_de_inregistrare_legala_Identificatorul_schemei',

        'Termeni_de_plata_Nota',

        'Totalurile_documentului_Suma_valorilor_nete_ale_liniilor_facturii',
        'Totalurile_documentului_Suma_valorilor_nete_ale_liniilor_facturii_Codul_monedei',
        'Totalurile_documentului_Valoarea_totala_a_facturii_fara_TVA',
        'Totalurile_documentului_Valoarea_totala_a_facturii_fara_TVA_Codul_monedei',
        'Totalurile_documentului_Valoarea_totala_a_facturii_cu_TVA',
        'Totalurile_documentului_Valoarea_totala_a_facturii_cu_TVA_Codul_monedei',
        'Totalurile_documentului_Suma_deducerilor_la_nivelul_documentului',
        'Totalurile_documentului_Suma_deducerilor_la_nivelul_documentului_Codul_monedei',
        'Totalurile_documentului_Suma_taxelor_suplimentare_la_nivelul_documentului',
        'Totalurile_documentului_Suma_taxelor_suplimentare_la_nivelul_documentului_Codul_monedei',
        'Totalurile_documentului_Suma_platita',
        'Totalurile_documentului_Suma_platita_Codul_monedei',
        'Totalurile_documentului_Valoare_de_rotunjire',
        'Totalurile_documentului_Valoare_de_rotunjire_Codul_monedei',
        'Totalurile_documentului_Suma_de_plata', 
        'Totalurile_documentului_Suma_de_plata_Codul_monedei',

        'Totaluri_tva_Valoarea_totala_a_TVA_a_facturii',
        'Totaluri_tva_Codul_monedei',

        'is_fcn',
        'comment_fcn'

    ];


    public function EfacturaPathInvoice()
    {
        return $this->belongsTo(EfacturaPathInvoice::class, 'invoice_path_id', 'id');
    }

    // note factura
    public function EfacturaInvoiceComments() {
        return $this->hasMany(EfacturaInvoiceComments::class, 'invoice_id', 'id');
    }
    // livrare
    public function EfacturaInvoiceDelivery() {
        return $this->hasOne(EfacturaInvoiceDelivery::class, 'invoice_id', 'id');
    }

    // instructiuni de plata
    public function EfacturaInvoicePaymentMeans() {
        return $this->hasOne(EfacturaInvoicePaymentMeans::class, 'invoice_id', 'id');
    }

    // beneficiar
    public function EfacturaInvoicePayeeParty() {
        return $this->hasOne(EfacturaInvoicePayeeParty::class, 'invoice_id', 'id');
    }

    // reprezentantul fiscal al vanzatorului
    public function EfacturaInvoiceTaxRepresentativeParty() {
        return $this->hasOne(EfacturaInvoiceTaxRepresentativeParty::class, 'invoice_id', 'id');
    }

    // reprezentantul fiscal al vanzatorului
    public function EfacturaInvoiceAllowanceCharge() {
        return $this->hasMany(EfacturaInvoiceAllowanceCharge::class, 'invoice_id', 'id');
    }

    public function EfacturaInvoiceTaxDetails() {
        return $this->hasMany(EfacturaInvoiceTaxDetails::class, 'invoice_id', 'id');
    }
    
    public function EfacturaInvoiceLine() {
        return $this->hasMany(EfacturaInvoiceLine::class, 'invoice_id', 'id');
    }


    
}

