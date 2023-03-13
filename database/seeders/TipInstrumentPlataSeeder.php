<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Nomenclatoare\TipInstrumentPlata;

class TipInstrumentPlataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //

        $lista_tip_plata = [   "1" => "Instrument nedefinit", 
            "2" => "Credit prin casa automată de compensare (ACH)", 
            "3" => "Debit prin casa automată de compensare (ACH)", 
            "4" => "Cerere pentru inversarea debitului transmis la casa automată de compensare (ACH)", 
            "5" => "Cerere pentru inversarea creditului transmis la casa automată de compensare (ACH)", 
            "6" => "Cerere de credit prin casa automată de compensare (ACH)", 
            "7" => "Cerere de debit prin casa automată de compensare (ACH)", 
            "8" => "Sumă reținută (Reținere)", 
            "9" => "Compensare națională sau regională", 
            "10" => "În numerar", 
            "11" => "Inversarea creditului de economii transmis la ACH", 
            "12" => "Inversarea debitului de economii transmis la ACH", 
            "13" => "Credit de economii transmis către ACH", 
            "14" => "Debit de economii transmis către ACH", 
            "15" => "Înregistrare de credit", 
            "16" => "Înregistrare de debit", 
            "17" => "Creditul de concentrare/despăgubire (CCD) la cererea ACH", 
            "18" => "Concentrarea/decontarea cererii de numerar ACH (CCD) debit", 
            "19" => "ACH solicită credite pentru plata comerțului corporativ (CTP)", 
            "20" => "Cec", 
            "21" => "Bilet la ordin", 
            "22" => "Bilet la ordin avalizat", 
            "23" => "Cec bancar (emis de o bancă sau de o unitate similară)", 
            "24" => "Scrisoare de schimb în așteptarea acceptării", 
            "25" => "Cec certificat", 
            "26" => "Cec local", 
            "27" => "Solicitare la ACH pentru plata comerțului corporativ (CTP) debit", 
            "28" => "Solicitare la ACH pentru credite de schimb comercial corporativ (CTX)", 
            "29" => "Solicitare la ACH pentru debitarea schimburilor comerciale corporative (CTX)", 
            "30" => "Transfer de credit", 
            "31" => "Transfer de debit", 
            "32" => "Concentrarea/plata cererii de numerar ACH plus (CCD+)", 
            "33" => "Concentrarea/plata cererii de numerar ACH plus (CCD+)", 
            "34" => "Plăți și depozit prestabilite ACH (PPD)", 
            "35" => "Creditul de concentrare/despăgubire a economiilor ACH (CCD)", 
            "36" => "Concentrarea/decontarea (CCD) a economiilor ACH", 
            "37" => "Credit ACH pentru plata comerțului cu societăți de economii (CTP)", 
            "38" => "Debit ACH pentru economii plăți comerciale corporative (CTP)", 
            "39" => "Credit ACH de schimb comercial cu societăți de economii (CTX)", 
            "40" => "Debit ACH de schimb cu societăți de economii (CTX)", 
            "41" => "Concentrarea/plata în numerar a sumelor din economii prin ACH plus (CCD+)", 
            "42" => "Plata în contul bancar", 
            "43" => "Concentrarea/plata în numerar a economiilor înregistrate în ACH plus (CCD+)", 
            "44" => "Scrisoare de schimb (Cambie) acceptată", 
            "45" => "Transfer de credit prin facilitatea de Home Banking", 
            "46" => "Transfer interbancar de debit ", 
            "47" => "Transfer de debit prin facilitatea de Home Banking", 
            "48" => "Card bancar", 
            "49" => "Debitare directă", 
            "50" => "Plata prin Postgiro", 
            "51" => "Plată prin compensare la distanță conform norma 6 97 CFONB (Organizația franceză pentru standarde bancare)", 
            "52" => "Plată comercială rapidă", 
            "53" => "Plată rapidă din Trezorerie", 
            "54" => "Card de credit", 
            "55" => "Card de debit", 
            "56" => "BankGiro", 
            "57" => "Acord permanent", 
            "58" => "Transferul de credit SEPA", 
            "59" => "Debitare directă SEPA", 
            "60" => "Bilet la ordin", 
            "61" => "Bilet la ordin semnat de debitor", 
            "62" => "Bilet la ordin semnat de debitor și aprobat de o bancă", 
            "63" => "Bilet la ordin semnat de debitor și aprobat de o terță parte", 
            "64" => "Bilet la ordin semnat de o bancă", 
            "65" => "Bilet la ordin semnat de o bancă și aprobat de o altă bancă", 
            "66" => "Bilet la ordin semnat de un terț", 
            "67" => "Bilet la ordin semnat de un terț și aprobat de o bancă", 
            "68" => "Serviciul de plată online", 
            "69" => "Consiliere de transfer", 
            "70" => "Scrisoare de schimb întocmită de creditor cu privire la debitor", 
            "74" => "Scrisoare de schimb întocmită de creditor pentru o bancă", 
            "75" => "Scrisoare de schimb elaborată de creditor, aprobată de o altă bancă", 
            "76" => "Scrisoare de schimb elaborată de creditor pentru o bancă și aprobat de o terță parte", 
            "77" => "Scrisoare de schimb întocmită de creditor pentru o terță parte", 
            "78" => "Scrisoare de schimb întocmită de creditor pentru o terță parte, acceptat și aprobată de o bancă", 
            "91" => "Bilet la ordin netransferabil", 
            "92" => "Cec local netransferabil", 
            "93" => "Referința Giro", 
            "94" => "Giro rapid", 
            "95" => "Giro format liber - care nu este prestabilit", 
            "96" => "Metoda solicitată de plată nu a fost utilizată", 
            "97" => "Compensarea între parteneri", 
            "ZZZ" => "Definit de comun acord"
        ];

        foreach($lista_tip_plata as $cod => $denumire) {
            TipInstrumentPlata::create(['cod' => $cod, 'denumire' => $denumire]);
        }


    }
}
