<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EfacturaInvoiceLine extends Model
{
    use HasFactory;

    protected $table='efactura_invoice_line';

    protected $fillable = [

        'invoice_id',

        'Nume_articol',
        'Pretul_net_al_articolului',
        'Pretul_net_al_articolului_Codul_monedei',
        'Cantitatea_de_baza_a_pretului_articolului',
        'Cantitate_facturata',
        'UM',
        'Codul_categoriei_de_TVA',
        'Cota_de_TVA',
        'Valoarea_neta_a_liniei',

        'Informatii_suplimentare_Descriere_articol',
        'Informatii_suplimentare_Tara_de_origine_a_articolului',
        'Informatii_suplimentare_Nota_liniei_facturii',
        'Informatii_suplimentare_Referinta_contabila_a_cumparatorului_din_linia_facturii',
        'Informatii_suplimentare_Data_de_inceput_a_perioadei_de_facturare_a_liniei_facturii',
        'Informatii_suplimentare_Data_de_sfarsit_a_perioadei_de_facturare_a_liniei_facturii',
        'Informatii_suplimentare_Referinta_liniei_comenzii',
        'Informatii_suplimentare_Identificatorul_obiectului_liniei_facturii',
        'Informatii_suplimentare_Identificatorul_obiectului_liniei_facturii_Identificatorul_schemei',
        'Informatii_suplimentare_Identificatorul_vanzatorului_articolului',
        'Informatii_suplimentare_Identificatorul_cumparatorului_articolului',
        'Informatii_suplimentare_Identificatorul_standard_al_articolului',
        'Informatii_suplimentare_Identificatorul_standard_al_articolului_Identificatorul_schemei',

        'Atributul_articolului_Numele_atributului_articolului',
        'Atributul_articolului_Valoarea_atributului',       
                
        'Taxa_suplimentara_Codul_motivului_taxei_suplimentare', 
        'Taxa_suplimentara_Motiv_taxa_suplimentara', 
        'Taxa_suplimentara_Procentajul_taxei_suplimentare', 
        'Taxa_suplimentara_Valoarea_taxei_suplimentare', 
        'Taxa_suplimentara_Valoarea_de_baza_a_taxei_suplimentare', 

        'Deducere_Codul_motivului_deducerii', 
        'Deducere_Motiv_deducere', 
        'Deducere_Procentajul_deducerii', 
        'Deducere_Valoarea_deducerii', 
        'Deducere_Valoarea_de_baza_a_deducerii', 

        'Deduceri_Reducere_taxa_suplimentara_la_pretul_articolului', 
        'Deduceri_Pretul_brut_al_articolului', 

    ];

    public function EfacturaInvoice()
    {
        return $this->belongsTo(EfacturaInvoice::class, 'invoice_id', 'id');
    }

    public function EfacturaInvoiceLineCommodityClassification() {
        return $this->hasMany(EfacturaInvoiceLineCommodityClassification::class, 'line_id', 'id');
    }

    public function CodCategorieTva() {
        return $this->hasOne(Nomenclatoare\CodCategorieTva::class, 'cod', 'Codul_categoriei_de_TVA');
    }

    public function UnitateMasura() {
        return $this->hasOne(Nomenclatoare\UnitateMasura::class, 'cod', 'UM');
    }
}
