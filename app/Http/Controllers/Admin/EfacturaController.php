<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\EfacturaPathZip;
use App\Models\EfacturaPathInvoice;
use App\Models\EfacturaInvoice;
use App\Models\EfacturaInvoiceComments;
use App\Models\EfacturaInvoiceDelivery;
use App\Models\EfacturaInvoicePaymentMeans;
use App\Models\EfacturaInvoicePayeeParty;
use App\Models\EfacturaInvoiceTaxRepresentativeParty;
use App\Models\EfacturaInvoiceAllowanceCharge;

use App\Models\EfacturaInvoiceTaxDetails;
use App\Models\EfacturaInvoiceLine;
use App\Models\EfacturaInvoiceLineCommodityClassification;



use Datatables;

use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Facades\DB;





use ZipArchive;


class EfacturaController extends Controller
{


    private $storageDestinationPathUnzip ;
    private $path_time;

    public function __construct()
    {


        $this->storageDestinationPathUnzip = storage_path("app/efactura/unzip/");

        if (!\File::exists( $this->storageDestinationPathUnzip)) {
            \File::makeDirectory($this->storageDestinationPathUnzip, 0755, true);
        }

        $this->path_time = time();

    }
    ////////////////////////////////////////////////
    // AFISARE SI LISTARE //////////////////////////
    ////////////////////////////////////////////////
    public function index(Request $request) {

        if(request()->ajax())
        {


            if(!empty($request->from_date)) {

                $data = EfacturaInvoice::whereBetween('created_anaf', [date('Y-m-d', strtotime($request->from_date)), date('Y-m-d', strtotime($request->to_date))])->get();
            }

            else {
                $data = EfacturaInvoice::all();
            }

            return datatables()->of($data)



            ->addColumn('Nr_factura', function($row){

                return '<a href="'.route('efactura.show', $row->id).'"> '.$row->Informatii_factura_Nr_factura .' </a>';

            })

            ->addColumn('Referinta', function($row){
                $referinta = '';

                if (!empty($row->Informatii_factura_Referinta_comenzii)) {
                    $referinta.=$row->Informatii_factura_Referinta_comenzii.'<br>';
                }
                if (!empty($row->Informatii_factura_Referinta_contractului)) {
                    $referinta.=$row->Informatii_factura_Referinta_contractului.'<br>';
                }
                if (!empty($row->Informatii_factura_Referinta_proiectului)) {
                    $referinta.='<br>'.$row->Informatii_factura_Referinta_proiectului.'<br>';
                }
                return  $referinta;

            })

            ->addColumn('Livrare', function($row){

                if($row->EfacturaInvoiceDelivery) {
                    return  $row->EfacturaInvoiceDelivery->Locatie_Adresa_Oras.' '.$row->EfacturaInvoiceDelivery->Locatie_Adresa_Strada;
                }
                else {
                    return  '';
                }

            })

            ->addColumn('Date_created_anaf', function($row){

                return date('d-m-Y', strtotime($row->EfacturaPathInvoice->date_created_anaf)) ;

            })

            ->addColumn('Informatii_factura_Data_emitere_factura', function($row){

                return date('d-m-Y', strtotime($row->Informatii_factura_Data_emitere_factura)) ;

            })


            ->addColumn('Produse', function($row){
                $return = "";
                foreach ($row->EfacturaInvoiceLine as $key=>$value) {
                    $return .= $value->Nume_articol."<br>";
                }
                return $return;

            })




            ->addColumn('Sucursala', function($row){
                $is_fcn = "";
                if($row->is_fcn) {
                    $is_fcn = "<i class='fas fa-check-double text-success' style='font-size:20px'>  FCN </i>";
                }
                elseif($row->is_fcn===null) {
                    $is_fcn = "<span style='visibility: hidden;'>-</span>";
                }
                else {
                    $is_fcn = "<i class='fas fa-check text-secondary'><span style='visibility: hidden;'>.</span></i>";
                }

                return $is_fcn;
            })
            ->rawColumns([ 'Nr_factura', 'Date_created_anaf', 'Informatii_factura_Data_emitere_factura', 'Locatie', 'Referinta' ,'Produse', 'Sucursala'])

            ->addIndexColumn()
            ->make(true);
        }

        return view('admin.efactura.index');
    }




    ///////////////////////////////////////
    // AFISAM  FACTURA ////////////////////
    ///////////////////////////////////////

    public function show(EfacturaInvoice $factura)
    {
        return view('admin.efactura.show', compact('factura'));
    }



    ///////////////////////////////////////
    // UPDATE /////////////////////////////
    ///////////////////////////////////////
    public function update(Request $request, EfacturaInvoice $factura)
        {
      //dd($request->is_fcn);
        $factura->comment_fcn = $request->comment_fcn;
        $factura->is_fcn = $request->is_fcn;
        $factura->save();
        return redirect()->route('efactura.show', $factura->id)->with('status','Editare reusita');
    }




    public function upload(Request $request) {

        $request->validate([
            'zip' => 'required|mimes:zip|max:4048'
            ]);

            $zip = new ZipArchive();
            $status = $zip->open($request->file('zip')->getRealPath());
            $numar_facturi = 0;
            $numar_facturi_duplicat = 0;
            $lista_data_incarcare_anaf = [];
            $lista_data_incarcare_anaf_duplicat = [];

            if ($status !== true) {
             throw new \Exception($status);
            }


            else{

                DB::beginTransaction();

                try {

                    // salvez fisierul zip pe disc si in db

                    $zipPathModel = new EfacturaPathZip;
                    $zipName = $this->path_time.'_'.$request->zip->getClientOriginalName();
                    $filePath = $request->file('zip')->storeAs('efactura/upload', $zipName);
                    $zipPathModel->user_id = auth()->user()->id;
                    $zipPathModel->file_name = $zipName;
                    $zipPathModel->file_path = '/storage/' . $filePath;
                    $zipPathModel->save();
                    $id_zip_upload = $zipPathModel->id;

                    //dezarhivam in folderul zip
                    $zip->extractTo($this->storageDestinationPathUnzip.'/'.$this->path_time);
                    $zip->close();


                    $path_unzip = $this->storageDestinationPathUnzip.'/'.$this->path_time;
                    $dir_path_unzip = opendir($path_unzip);

                    // scanez tot directorul unzip
                    while (false !== ($fileNameDataAnaf = readdir($dir_path_unzip))) {
                        if ($fileNameDataAnaf == "." || $fileNameDataAnaf == "..") continue;

                        $dir_data_anaf  = opendir($this->storageDestinationPathUnzip.'/'.$this->path_time.'/'.$fileNameDataAnaf);
                        // scanez folderul cu nume data unde sunt xml-urile
                        while (false !== ($fileNameXML = readdir($dir_data_anaf))) {

                            $prefix_xml = substr($fileNameXML, 0, 10);
                            $ext = substr($fileNameXML, strrpos($fileNameXML, '.') + 1);

                            // doar fisierele xml
                            if(in_array($ext, array("xml")) && $prefix_xml!="semnatura_") {




                                if ( EfacturaPathInvoice::where('xml_name', $prefix_xml)->count() ) {
                                    $numar_facturi_duplicat++;
                                    $lista_data_incarcare_anaf_duplicat[] = $fileNameDataAnaf;
                                    continue;
                                }


                                $numar_facturi++;
                                $lista_data_incarcare_anaf[] = $fileNameDataAnaf;


                                $invoicePathModel = new EfacturaPathInvoice;
                                $invoicePathModel->file_upload_id = $id_zip_upload;
                                $invoicePathModel->xml_name = (int)$prefix_xml; // asta trebuie sa fie  unic
                                $invoicePathModel->xml_path = $this->path_time.'/'.$fileNameDataAnaf. '/'.$fileNameXML;
                                $invoicePathModel->time = $this->path_time;
                                $invoicePathModel->created_at_anaf = $fileNameDataAnaf;
                                $invoicePathModel->date_created_anaf = date('Y-m-d', strtotime($fileNameDataAnaf));
                                $invoicePathModel->save();


                                $id_invoicePathModel = $invoicePathModel->id;

                                // Procesare fisier xml
                                $invoice_details_xml = $this->get_details_from_xml( $this->path_time.'/'.$fileNameDataAnaf. '/'.$fileNameXML );

                                $invoiceModel = new EfacturaInvoice;

                                $invoiceModel->invoice_path_id = $id_invoicePathModel;
                                $invoiceModel->created_anaf  = date('Y-m-d', strtotime($fileNameDataAnaf));
                                // INFORMATII FACTURA
                                $invoiceModel->Informatii_factura_Nr_factura = $invoice_details_xml['Informatii_factura']['Nr_factura'];
                                $invoiceModel->Informatii_factura_Data_emitere_factura = $invoice_details_xml['Informatii_factura']['Data_emitere_factura'];
                                $invoiceModel->Informatii_factura_Data_scadenta_factura = $invoice_details_xml['Informatii_factura']['Data_scadenta_factura'];
                                $invoiceModel->Informatii_factura_Codul_monedei_facturii = $invoice_details_xml['Informatii_factura']['Codul_monedei_facturii'];
                                $invoiceModel->Informatii_factura_Codul_monedei_de_contabilizare_a_TVA = $invoice_details_xml['Informatii_factura']['Codul_monedei_de_contabilizare_a_TVA'];
                                $invoiceModel->Informatii_factura_Data_de_exigibilitate_a_TVA = $invoice_details_xml['Informatii_factura']['Data_de_exigibilitate_a_TVA'];
                                $invoiceModel->Informatii_factura_Data_de_inceput_a_perioadei_de_facturare = $invoice_details_xml['Informatii_factura']['Data_de_inceput_a_perioadei_de_facturare'];
                                $invoiceModel->Informatii_factura_Data_de_sfarsit_a_perioadei_de_facturare = $invoice_details_xml['Informatii_factura']['Data_de_sfarsit_a_perioadei_de_facturare'];
                                $invoiceModel->Informatii_factura_Referinta_cumparatorului = $invoice_details_xml['Informatii_factura']['Referinta_cumparatorului'];
                                $invoiceModel->Informatii_factura_Referinta_comenzii = $invoice_details_xml['Informatii_factura']['Referinta_comenzii'];
                                $invoiceModel->Informatii_factura_Referinta_dispozitiei_de_vanzare = $invoice_details_xml['Informatii_factura']['Referinta_dispozitiei_de_vanzare'];
                                $invoiceModel->Informatii_factura_Referinta_la_o_factura_anterioara = $invoice_details_xml['Informatii_factura']['Referinta_la_o_factura_anterioara'];
                                $invoiceModel->Informatii_factura_Data_de_emitere_a_facturii_anterioare = $invoice_details_xml['Informatii_factura']['Data_de_emitere_a_facturii_anterioare'];
                                $invoiceModel->Informatii_factura_Referinta_avizului_de_expeditie = $invoice_details_xml['Informatii_factura']['Referinta_avizului_de_expeditie'];
                                $invoiceModel->Informatii_factura_Referinta_avizului_de_receptie = $invoice_details_xml['Informatii_factura']['Referinta_avizului_de_receptie'];
                                $invoiceModel->Informatii_factura_Referinta_cererii_de_oferta_sau_a_lotului = $invoice_details_xml['Informatii_factura']['Referinta_cererii_de_oferta_sau_a_lotului'];
                                $invoiceModel->Informatii_factura_Referinta_contractului = $invoice_details_xml['Informatii_factura']['Referinta_contractului'];
                                $invoiceModel->Informatii_factura_Referinta_proiectului = $invoice_details_xml['Informatii_factura']['Referinta_proiectului'];

                                // VANZATOR
                                $invoiceModel->Vanzator_Adresa_electronica = $invoice_details_xml['Vanzator']['Adresa_electronica'];
                                $invoiceModel->Vanzator_Adresa_electronica_Identificatorul_schemei = $invoice_details_xml['Vanzator']['Adresa_electronica_Identificatorul_schemei'];
                                $invoiceModel->Vanzator_Persoana_de_contact = $invoice_details_xml['Vanzator']['Persoana_de_contact'];
                                $invoiceModel->Vanzator_Telefon_persoana_de_contact = $invoice_details_xml['Vanzator']['Telefon_persoana_de_contact'];
                                $invoiceModel->Vanzator_E_mail_persoana_de_contact = $invoice_details_xml['Vanzator']['E_mail_persoana_de_contact'];
                                $invoiceModel->Vanzator_Identificator = $invoice_details_xml['Vanzator']['Identificator'];
                                $invoiceModel->Vanzator_Identificator_Identificatorul_schemei = $invoice_details_xml['Vanzator']['Identificator_Identificatorul_schemei'];
                                $invoiceModel->Vanzator_Denumire_comerciala = $invoice_details_xml['Vanzator']['Denumire_comerciala'];
                                $invoiceModel->Vanzator_Adresa_Strada = $invoice_details_xml['Vanzator']['Adresa']['Strada'];
                                $invoiceModel->Vanzator_Adresa_Informatii_suplimentare_strada = $invoice_details_xml['Vanzator']['Adresa']['Informatii_suplimentare_strada'];
                                $invoiceModel->Vanzator_Adresa_Oras = $invoice_details_xml['Vanzator']['Adresa']['Oras'];
                                $invoiceModel->Vanzator_Adresa_Cod_Postal = $invoice_details_xml['Vanzator']['Adresa']['Cod_Postal'];
                                $invoiceModel->Vanzator_Adresa_Subdiviziunea_tarii = $invoice_details_xml['Vanzator']['Adresa']['Subdiviziunea_tarii'];
                                $invoiceModel->Vanzator_Adresa_Tara = $invoice_details_xml['Vanzator']['Adresa']['Tara'];
                                $invoiceModel->Vanzator_Adresa_Informatii_suplimentare_adresa = $invoice_details_xml['Vanzator']['Adresa']['Informatii_suplimentare_adresa'];
                                $invoiceModel->Vanzator_Identificatorul_de_TVA = $invoice_details_xml['Vanzator']['Identificatorul_de_TVA'];
                                $invoiceModel->Vanzator_Nume = $invoice_details_xml['Vanzator']['Nume'];
                                $invoiceModel->Vanzator_Identificatorul_de_inregistrare_legala = $invoice_details_xml['Vanzator']['Identificatorul_de_inregistrare_legala'];
                                $invoiceModel->Vanzator_Identificatorul_de_inregistrare_legala_Identificatorul_schemei = $invoice_details_xml['Vanzator']['Identificatorul_de_inregistrare_legala_Identificatorul_schemei'];
                                $invoiceModel->Vanzator_Informatii_juridice_suplimentare = $invoice_details_xml['Vanzator']['Informatii_juridice_suplimentare'];

                                // CUMPARATOR
                                $invoiceModel->Cumparator_Adresa_electronica = $invoice_details_xml['Cumparator']['Adresa_electronica'];
                                $invoiceModel->Cumparator_Adresa_electronica_Identificatorul_schemei = $invoice_details_xml['Cumparator']['Adresa_electronica_Identificatorul_schemei'];
                                $invoiceModel->Cumparator_Persoana_de_contact = $invoice_details_xml['Cumparator']['Persoana_de_contact'];
                                $invoiceModel->Cumparator_Telefon_persoana_de_contact = $invoice_details_xml['Cumparator']['Telefon_persoana_de_contact'];
                                $invoiceModel->Cumparator_E_mail_persoana_de_contact = $invoice_details_xml['Cumparator']['E_mail_persoana_de_contact'];
                                $invoiceModel->Cumparator_Identificator = $invoice_details_xml['Cumparator']['Identificator'];
                                $invoiceModel->Cumparator_Identificator_Identificatorul_schemei = $invoice_details_xml['Cumparator']['Identificator_Identificatorul_schemei'];
                                $invoiceModel->Cumparator_Denumire_comerciala = $invoice_details_xml['Cumparator']['Denumire_comerciala'];
                                $invoiceModel->Cumparator_Adresa_Strada = $invoice_details_xml['Cumparator']['Adresa']['Strada'];
                                $invoiceModel->Cumparator_Adresa_Informatii_suplimentare_strada = $invoice_details_xml['Cumparator']['Adresa']['Informatii_suplimentare_strada'];
                                $invoiceModel->Cumparator_Adresa_Oras = $invoice_details_xml['Cumparator']['Adresa']['Oras'];
                                $invoiceModel->Cumparator_Adresa_Cod_Postal = $invoice_details_xml['Cumparator']['Adresa']['Cod_Postal'];
                                $invoiceModel->Cumparator_Adresa_Subdiviziunea_tarii = $invoice_details_xml['Cumparator']['Adresa']['Subdiviziunea_tarii'];
                                $invoiceModel->Cumparator_Adresa_Tara = $invoice_details_xml['Cumparator']['Adresa']['Tara'];
                                $invoiceModel->Cumparator_Adresa_Informatii_suplimentare_adresa = $invoice_details_xml['Cumparator']['Adresa']['Informatii_suplimentare_adresa'];
                                $invoiceModel->Cumparator_Identificatorul_de_TVA = $invoice_details_xml['Cumparator']['Identificatorul_de_TVA'];
                                $invoiceModel->Cumparator_Nume = $invoice_details_xml['Cumparator']['Nume'];
                                $invoiceModel->Cumparator_Identificatorul_de_inregistrare_legala = $invoice_details_xml['Cumparator']['Identificatorul_de_inregistrare_legala'];
                                $invoiceModel->Cumparator_Identificatorul_de_inregistrare_legala_Identificatorul_schemei = $invoice_details_xml['Cumparator']['Identificatorul_de_inregistrare_legala_Identificatorul_schemei'];

                                // TERMENI DE PLATA
                                if (isset($invoice_details_xml['Termeni_de_plata']['Nota'])){
                                    $invoiceModel->Termeni_de_plata_Nota = $invoice_details_xml['Termeni_de_plata']['Nota'];
                                }

                                // TOTALURI TVA
                                $invoiceModel->Totaluri_tva_Valoarea_totala_a_TVA_a_facturii = $invoice_details_xml['Totaluri_tva']['Valoarea_totala_a_TVA_a_facturii'];
                                $invoiceModel->Totaluri_tva_Codul_monedei = $invoice_details_xml['Totaluri_tva']['Codul_monedei'];

                                // BG-22 TOTALURILE DOCUMENTULUI
                                $invoiceModel->Totalurile_documentului_Suma_valorilor_nete_ale_liniilor_facturii = $invoice_details_xml['Totalurile_documentului']['Suma_valorilor_nete_ale_liniilor_facturii'];
                                $invoiceModel->Totalurile_documentului_Suma_valorilor_nete_ale_liniilor_facturii_Codul_monedei = $invoice_details_xml['Totalurile_documentului']['Suma_valorilor_nete_ale_liniilor_facturii_Codul_monedei'];
                                $invoiceModel->Totalurile_documentului_Valoarea_totala_a_facturii_fara_TVA = $invoice_details_xml['Totalurile_documentului']['Valoarea_totala_a_facturii_fara_TVA'];
                                $invoiceModel->Totalurile_documentului_Valoarea_totala_a_facturii_fara_TVA_Codul_monedei = $invoice_details_xml['Totalurile_documentului']['Valoarea_totala_a_facturii_fara_TVA_Codul_monedei'];
                                $invoiceModel->Totalurile_documentului_Valoarea_totala_a_facturii_cu_TVA = $invoice_details_xml['Totalurile_documentului']['Valoarea_totala_a_facturii_cu_TVA'];
                                $invoiceModel->Totalurile_documentului_Valoarea_totala_a_facturii_cu_TVA_Codul_monedei = $invoice_details_xml['Totalurile_documentului']['Valoarea_totala_a_facturii_cu_TVA_Codul_monedei'];
                                $invoiceModel->Totalurile_documentului_Suma_deducerilor_la_nivelul_documentului = $invoice_details_xml['Totalurile_documentului']['Suma_deducerilor_la_nivelul_documentului'];
                                $invoiceModel->Totalurile_documentului_Suma_deducerilor_la_nivelul_documentului_Codul_monedei = $invoice_details_xml['Totalurile_documentului']['Suma_deducerilor_la_nivelul_documentului_Codul_monedei'];
                                $invoiceModel->Totalurile_documentului_Suma_taxelor_suplimentare_la_nivelul_documentului = $invoice_details_xml['Totalurile_documentului']['Suma_taxelor_suplimentare_la_nivelul_documentului'];
                                $invoiceModel->Totalurile_documentului_Suma_taxelor_suplimentare_la_nivelul_documentului_Codul_monedei = $invoice_details_xml['Totalurile_documentului']['Suma_taxelor_suplimentare_la_nivelul_documentului_Codul_monedei'];
                                $invoiceModel->Totalurile_documentului_Suma_platita = $invoice_details_xml['Totalurile_documentului']['Suma_platita'];
                                $invoiceModel->Totalurile_documentului_Suma_platita_Codul_monedei = $invoice_details_xml['Totalurile_documentului']['Suma_platita_Codul_monedei'];
                                $invoiceModel->Totalurile_documentului_Valoare_de_rotunjire = $invoice_details_xml['Totalurile_documentului']['Valoare_de_rotunjire'];
                                $invoiceModel->Totalurile_documentului_Valoare_de_rotunjire_Codul_monedei = $invoice_details_xml['Totalurile_documentului']['Valoare_de_rotunjire_Codul_monedei'];
                                $invoiceModel->Totalurile_documentului_Suma_de_plata = $invoice_details_xml['Totalurile_documentului']['Suma_de_plata'];
                                $invoiceModel->Totalurile_documentului_Suma_de_plata_Codul_monedei = $invoice_details_xml['Totalurile_documentului']['Suma_de_plata_Codul_monedei'];

                                // salvam  factura
                                $invoiceModel->save();
                                $id_invoiceModel = $invoiceModel->id;


                                // INFORMATII FACTURA
                                // BT-22 Comentariu in factura
                                if (isset($invoice_details_xml['Informatii_factura']['Comentariu_in_factura'])) {
                                    foreach($invoice_details_xml['Informatii_factura']['Comentariu_in_factura'] as $comment) {
                                        $nota = new EfacturaInvoiceComments();
                                        $nota->invoice_id = $id_invoiceModel;
                                        $nota->Nota = $comment;
                                        $nota->save();
                                    }
                                }


                                // BG-21 TAXA SUPLIMENTAREA
                                // BG-20 DEDUCERE
                                if (isset($invoice_details_xml['Taxarea_suplimentara_Deducere'])) {



                                    foreach($invoice_details_xml['Taxarea_suplimentara_Deducere'] as $taxarea_suplimentara_deducere) {

                                        $allowanceChargeModel = new EfacturaInvoiceAllowanceCharge();
                                        $Indicator = $taxarea_suplimentara_deducere['Indicator'] === 'true'? true: false;
                                        $allowanceChargeModel->invoice_id = $id_invoiceModel;
                                        $allowanceChargeModel->Indicator = $Indicator;
                                        $allowanceChargeModel->Codul_motivului = $taxarea_suplimentara_deducere['Codul_motivului'];
                                        $allowanceChargeModel->Motivul = $taxarea_suplimentara_deducere['Motivul'];
                                        $allowanceChargeModel->Procent = $taxarea_suplimentara_deducere['Procent'];
                                        $allowanceChargeModel->Valoare = $taxarea_suplimentara_deducere['Valoare'];
                                        $allowanceChargeModel->Codul_monedei_RON = $taxarea_suplimentara_deducere['Codul_monedei_RON'];
                                        $allowanceChargeModel->Valoarea_de_baza = $taxarea_suplimentara_deducere['Valoarea_de_baza'];
                                        $allowanceChargeModel->Codul_categoriei_de_TVA = $taxarea_suplimentara_deducere['Codul_categoriei_de_TVA'];
                                        $allowanceChargeModel->Cota_de_TVA = $taxarea_suplimentara_deducere['Cota_de_TVA'];
                                        $allowanceChargeModel->Identificatorul_schemei_VAT = $taxarea_suplimentara_deducere['Identificatorul_schemei_VAT'];
                                        $allowanceChargeModel->save();
                                    }
                                }



                                // INFORMATII REFERITOARE LA LIVRARE
                                if (isset($invoice_details_xml['Informatii_referitoare_la_livrare'])) {

                                    $deliveryModel = new EfacturaInvoiceDelivery();
                                    $deliveryModel->invoice_id = $id_invoiceModel;
                                    $deliveryModel->Data_reala_a_livrarii = $invoice_details_xml['Informatii_referitoare_la_livrare']['Data_reala_a_livrarii'];
                                    $deliveryModel->Numele_partii_catre_care_se_face_livrarea = $invoice_details_xml['Informatii_referitoare_la_livrare']['Numele_partii_catre_care_se_face_livrarea'];
                                    $deliveryModel->Locatie_Identificatorul_locului = $invoice_details_xml['Informatii_referitoare_la_livrare']['Locatie']['Identificatorul_locului'];
                                    $deliveryModel->Locatie_Identificatorul_schemei = $invoice_details_xml['Informatii_referitoare_la_livrare']['Locatie']['Identificatorul_schemei'];
                                    if (isset($invoice_details_xml['Informatii_referitoare_la_livrare']['Locatie']['Adresa'])){
                                        $deliveryModel->Locatie_Adresa_Strada = $invoice_details_xml['Informatii_referitoare_la_livrare']['Locatie']['Adresa']['Strada'];
                                        $deliveryModel->Locatie_Adresa_Informatii_suplimentare_strada = $invoice_details_xml['Informatii_referitoare_la_livrare']['Locatie']['Adresa']['Informatii_suplimentare_strada'];
                                        $deliveryModel->Locatie_Adresa_Informatii_suplimentare_adresa = $invoice_details_xml['Informatii_referitoare_la_livrare']['Locatie']['Adresa']['Informatii_suplimentare_adresa'];
                                        $deliveryModel->Locatie_Adresa_Oras = $invoice_details_xml['Informatii_referitoare_la_livrare']['Locatie']['Adresa']['Oras'];
                                        $deliveryModel->Locatie_Adresa_Cod_Postal = $invoice_details_xml['Informatii_referitoare_la_livrare']['Locatie']['Adresa']['Cod_Postal'];
                                        $deliveryModel->Locatie_Adresa_Subdiviziunea_tarii = $invoice_details_xml['Informatii_referitoare_la_livrare']['Locatie']['Adresa']['Subdiviziunea_tarii'];
                                        $deliveryModel->Locatie_Adresa_Tara = $invoice_details_xml['Informatii_referitoare_la_livrare']['Locatie']['Adresa']['Tara'];
                                    }

                                    $deliveryModel->save();
                                }


                                // INSTRUCTIUNI DE PLATA
                                if (isset($invoice_details_xml['Instructiuni_de_plata'])) {

                                    foreach($invoice_details_xml['Instructiuni_de_plata'] as $instructiune_plata) {

                                        $paymentMeansModel = new EfacturaInvoicePaymentMeans();
                                        $paymentMeansModel->invoice_id = $id_invoiceModel;
                                        $paymentMeansModel->Codul_tipului_instrumentului_de_plata = $instructiune_plata['Codul_tipului_instrumentului_de_plata'];
                                        $paymentMeansModel->Nota_privind_instrumentul_de_plata = $instructiune_plata['Nota_privind_instrumentul_de_plata'];
                                        $paymentMeansModel->Aviz_de_plata = $instructiune_plata['Aviz_de_plata'];
                                        $paymentMeansModel->Numarul_contului_principal_al_cardului_de_plata = $instructiune_plata['Numarul_contului_principal_al_cardului_de_plata'];
                                        $paymentMeansModel->Numele_detinatorului_cardului_de_plata = $instructiune_plata['Numele_detinatorului_cardului_de_plata'];
                                        $paymentMeansModel->Identificatorul_contului_de_plata = $instructiune_plata['Identificatorul_contului_de_plata'];
                                        $paymentMeansModel->Numele_contului_de_plata = $instructiune_plata['Numele_contului_de_plata'];
                                        $paymentMeansModel->Identificatorul_furnizorului_de_servicii_de_plata = $instructiune_plata['Identificatorul_furnizorului_de_servicii_de_plata'];
                                        $paymentMeansModel->Debitare_directa_Identificatorul_referintei_mandatului = $instructiune_plata['Debitare_directa']['Identificatorul_referintei_mandatului'];
                                        $paymentMeansModel->Debitare_directa_Identificatorul_contului_debitat = $instructiune_plata['Debitare_directa']['Identificatorul_contului_debitat'];
                                        $paymentMeansModel->save();
                                    }
                                }



                                // BENEFICIAR
                                if (isset($invoice_details_xml['Beneficiar'])) {

                                    $payeePartyModel = new EfacturaInvoicePayeeParty();
                                    $payeePartyModel->invoice_id = $id_invoiceModel;
                                    $payeePartyModel->Identificator = $invoice_details_xml['Beneficiar']['Identificator'];
                                    $payeePartyModel->Identificator_Identificatorul_schemei = $invoice_details_xml['Beneficiar']['Identificator_Identificatorul_schemei'];
                                    $payeePartyModel->Nume_beneficiar = $invoice_details_xml['Beneficiar']['Nume_beneficiar'];
                                    $payeePartyModel->Identificatorul_de_inregistrare_legala = $invoice_details_xml['Beneficiar']['Identificatorul_de_inregistrare_legala'];
                                    $payeePartyModel->Identificatorul_de_inregistrare_legala_Identificatorul_schemei = $invoice_details_xml['Beneficiar']['Identificatorul_de_inregistrare_legala_Identificatorul_schemei'];
                                    $payeePartyModel->save();
                                }



                                // REPREZENTANTUL FISCAL AL VANZATORULUI
                                if (isset($invoice_details_xml['Reprezentantul_fiscal_al_vanzatorului'])) {
                                    $taxRepresentativePartyModel = new EfacturaInvoiceTaxRepresentativeParty();
                                    $taxRepresentativePartyModel->invoice_id = $id_invoiceModel;
                                    $taxRepresentativePartyModel->Nume = $invoice_details_xml['Reprezentantul_fiscal_al_vanzatorului']['Nume'];
                                    $taxRepresentativePartyModel->Adresa_Strada = $invoice_details_xml['Reprezentantul_fiscal_al_vanzatorului']['Adresa']['Strada'];
                                    $taxRepresentativePartyModel->Adresa_Informatii_suplimentare_strada = $invoice_details_xml['Reprezentantul_fiscal_al_vanzatorului']['Adresa']['Informatii_suplimentare_strada'];
                                    $taxRepresentativePartyModel->Adresa_Oras = $invoice_details_xml['Reprezentantul_fiscal_al_vanzatorului']['Adresa']['Oras'];
                                    $taxRepresentativePartyModel->Adresa_Cod_Postal = $invoice_details_xml['Reprezentantul_fiscal_al_vanzatorului']['Adresa']['Cod_Postal'];
                                    $taxRepresentativePartyModel->Adresa_Subdiviziunea_tarii = $invoice_details_xml['Reprezentantul_fiscal_al_vanzatorului']['Adresa']['Subdiviziunea_tarii'];
                                    $taxRepresentativePartyModel->Adresa_Tara = $invoice_details_xml['Reprezentantul_fiscal_al_vanzatorului']['Adresa']['Tara'];
                                    $taxRepresentativePartyModel->Adresa_Informatii_suplimentare_adresa = $invoice_details_xml['Reprezentantul_fiscal_al_vanzatorului']['Adresa']['Informatii_suplimentare_adresa'];
                                    $taxRepresentativePartyModel->Identificatorul_de_TVA = $invoice_details_xml['Reprezentantul_fiscal_al_vanzatorului']['Identificatorul_de_TVA'];
                                    $taxRepresentativePartyModel->save();
                                }




                                // TOTALURI TVA
                                // BG-23 DETALIERE TVA
                                if (isset($invoice_details_xml['Totaluri_tva']['Detaliere_tva'])) {
                                    foreach($invoice_details_xml['Totaluri_tva']['Detaliere_tva'] as $tax_detail) {
                                        $detaliereTvaModel = new EfacturaInvoiceTaxDetails();
                                        $detaliereTvaModel->invoice_id = $id_invoiceModel;
                                        $detaliereTvaModel->Baza_de_calcul = $tax_detail['Baza_de_calcul'];
                                        $detaliereTvaModel->Baza_de_calcul_Codul_monedei = $tax_detail['Baza_de_calcul_Codul_monedei'];
                                        $detaliereTvaModel->Valoare_TVA = $tax_detail['Valoare_TVA'];
                                        $detaliereTvaModel->Valoare_TVA_Codul_monedei = $tax_detail['Valoare_TVA_Codul_monedei'];
                                        $detaliereTvaModel->Codul_categoriei_de_TVA = $tax_detail['Codul_categoriei_de_TVA'];
                                        $detaliereTvaModel->Cota_categoriei_de_TVA = $tax_detail['Cota_categoriei_de_TVA'];
                                        $detaliereTvaModel->Codul_motivului_scutirii = $tax_detail['Codul_motivului_scutirii'];
                                        $detaliereTvaModel->Motivul_scutirii = $tax_detail['Motivul_scutirii'];
                                        $detaliereTvaModel->save();
                                    }
                                }


                                // LINIA FACTURII //////////////////////
                                foreach($invoice_details_xml['Invoice_Line'] as $line) {

                                    $invoiceLineModel = new EfacturaInvoiceLine();
                                    $invoiceLineModel->invoice_id    = $id_invoiceModel;
                                    $invoiceLineModel->Nume_articol  = $line['Nume_articol'];
                                    $invoiceLineModel->Pretul_net_al_articolului = $line['Pretul_net_al_articolului'];
                                    $invoiceLineModel->Pretul_net_al_articolului_Codul_monedei = $line['Pretul_net_al_articolului_Codul_monedei'];
                                    $invoiceLineModel->Cantitatea_de_baza_a_pretului_articolului = $line['Cantitatea_de_baza_a_pretului_articolului'];
                                    $invoiceLineModel->Cantitate_facturata = $line['Cantitate_facturata'];
                                    $invoiceLineModel->UM = $line['UM'];
                                    $invoiceLineModel->Codul_categoriei_de_TVA = $line['Codul_categoriei_de_TVA'];
                                    $invoiceLineModel->Cota_de_TVA = $line['Cota_de_TVA'];
                                    $invoiceLineModel->Valoarea_neta_a_liniei = $line['Valoarea_neta_a_liniei'];


                                    if (isset($line['Informatii_suplimentare'])) {
                                        $invoiceLineModel->Informatii_suplimentare_Descriere_articol = $line['Informatii_suplimentare']['Descriere_articol'];
                                        $invoiceLineModel->Informatii_suplimentare_Tara_de_origine_a_articolului = $line['Informatii_suplimentare']['Tara_de_origine_a_articolului'];
                                        $invoiceLineModel->Informatii_suplimentare_Nota_liniei_facturii = $line['Informatii_suplimentare']['Nota_liniei_facturii'];
                                        $invoiceLineModel->Informatii_suplimentare_Referinta_contabila_a_cumparatorului_din_linia_facturii = $line['Informatii_suplimentare']['Referinta_contabila_a_cumparatorului_din_linia_facturii'];
                                        $invoiceLineModel->Informatii_suplimentare_Data_de_inceput_a_perioadei_de_facturare_a_liniei_facturii = $line['Informatii_suplimentare']['Data_de_inceput_a_perioadei_de_facturare_a_liniei_facturii'];
                                        $invoiceLineModel->Informatii_suplimentare_Data_de_sfarsit_a_perioadei_de_facturare_a_liniei_facturii = $line['Informatii_suplimentare']['Data_de_sfarsit_a_perioadei_de_facturare_a_liniei_facturii'];
                                        $invoiceLineModel->Informatii_suplimentare_Referinta_liniei_comenzii = $line['Informatii_suplimentare']['Referinta_liniei_comenzii'];
                                        $invoiceLineModel->Informatii_suplimentare_Identificatorul_obiectului_liniei_facturii = $line['Informatii_suplimentare']['Identificatorul_obiectului_liniei_facturii'];
                                        $invoiceLineModel->Informatii_suplimentare_Identificatorul_obiectului_liniei_facturii_Identificatorul_schemei = $line['Informatii_suplimentare']['Identificatorul_obiectului_liniei_facturii_Identificatorul_schemei'];
                                        $invoiceLineModel->Informatii_suplimentare_Identificatorul_vanzatorului_articolului = $line['Informatii_suplimentare']['Identificatorul_vanzatorului_articolului'];
                                        $invoiceLineModel->Informatii_suplimentare_Identificatorul_cumparatorului_articolului = $line['Informatii_suplimentare']['Identificatorul_cumparatorului_articolului'];
                                        $invoiceLineModel->Informatii_suplimentare_Identificatorul_standard_al_articolului = $line['Informatii_suplimentare']['Identificatorul_standard_al_articolului'];
                                        $invoiceLineModel->Informatii_suplimentare_Identificatorul_standard_al_articolului_Identificatorul_schemei = $line['Informatii_suplimentare']['Identificatorul_standard_al_articolului_Identificatorul_schemei'];
                                    }

                                    if (isset($line['Atributul_articolului'])) {
                                        $invoiceLineModel->Atributul_articolului_Numele_atributului_articolului = $line['Atributul_articolului']['Numele_atributului_articolului'];
                                        $invoiceLineModel->Atributul_articolului_Valoarea_atributului = $line['Atributul_articolului']['Valoarea_atributului'];
                                    }

                                    if (isset($line['Taxa_suplimentara'])) {
                                        $invoiceLineModel->Taxa_suplimentara_Codul_motivului_taxei_suplimentare = $line['Taxa_suplimentara']['Codul_motivului_taxei_suplimentare'];
                                        $invoiceLineModel->Taxa_suplimentara_Motiv_taxa_suplimentara = $line['Taxa_suplimentara']['Motiv_taxa_suplimentara'];
                                        $invoiceLineModel->Taxa_suplimentara_Procentajul_taxei_suplimentare = $line['Taxa_suplimentara']['Procentajul_taxei_suplimentare'];
                                        $invoiceLineModel->Taxa_suplimentara_Valoarea_taxei_suplimentare = $line['Taxa_suplimentara']['Valoarea_taxei_suplimentare'];
                                        $invoiceLineModel->Taxa_suplimentara_Valoarea_de_baza_a_taxei_suplimentare = $line['Taxa_suplimentara']['Valoarea_de_baza_a_taxei_suplimentare'];
                                    }

                                    if (isset($line['Deducere'])) {
                                        $invoiceLineModel->Deducere_Codul_motivului_deducerii = $line['Deducere']['Codul_motivului_deducerii'];
                                        $invoiceLineModel->Deducere_Motiv_deducere = $line['Deducere']['Motiv_deducere'];
                                        $invoiceLineModel->Deducere_Procentajul_deducerii = $line['Deducere']['Procentajul_deducerii'];
                                        $invoiceLineModel->Deducere_Valoarea_deducerii = $line['Deducere']['Valoarea_deducerii'];
                                        $invoiceLineModel->Deducere_Valoarea_de_baza_a_deducerii = $line['Deducere']['Valoarea_de_baza_a_deducerii'];
                                    }

                                    if (isset($line['Deduceri'])) {
                                        $invoiceLineModel->Deduceri_Reducere_taxa_suplimentara_la_pretul_articolului = $line['Deduceri']['Reducere_taxa_suplimentara_la_pretul_articolului'];
                                        $invoiceLineModel->Deduceri_Pretul_brut_al_articolului = $line['Deduceri']['Pretul_brut_al_articolului'];
                                    }

                                    // salvam linia facturii
                                    $invoiceLineModel->save();
                                    $id_invoiceLine = $invoiceLineModel->id;

                                    //BT-158 Identificatorul clasificarii articolului
                                    if (isset($line['Informatii_clasificare']) ) {

                                        foreach ($line['Informatii_clasificare'] as $commodity_classification) {


                                            $invoiceLineCommodityClassificationModel = new EfacturaInvoiceLineCommodityClassification();

                                            $invoiceLineCommodityClassificationModel->line_id = $id_invoiceLine;
                                            $invoiceLineCommodityClassificationModel->Identificatorul_clasificarii_articolului = $commodity_classification['Identificatorul_clasificarii_articolului'];
                                            $invoiceLineCommodityClassificationModel->Identificatorul_schemei = $commodity_classification['Identificatorul_schemei'];
                                            $invoiceLineCommodityClassificationModel->Identificatorul_versiunii_schemei = $commodity_classification['Identificatorul_versiunii_schemei'];

                                            $invoiceLineCommodityClassificationModel->save();
                                        }
                                    }

                                } // sfarsit linii factura

                            }// doar fisierele xml

                        } // sfarsit scanez folderul cu nume data unde sunt xml-urile
                        closedir($dir_data_anaf);

                    }// sfarsit scanez tot directorul unzip

                    closedir($dir_path_unzip);


                    $fileModel = EfacturaPathZip::find($id_zip_upload);
                    $fileModel->number_invoices = $numar_facturi;
                    $fileModel->number_invoices_duplicate = $numar_facturi_duplicat;
                    $fileModel->save();


                    DB::commit();
                }

                catch (\Exception $e) {
                    DB::rollBack();

                    return redirect()->back()->withErrors( $e->getMessage());
                }




            }

        $lista_data_incarcare  = array_count_values($lista_data_incarcare_anaf);
        $lista_data_incarcare_duplicat  = array_count_values($lista_data_incarcare_anaf_duplicat);

        $lista_data_incarcare_anaf_string = implode(',  ', array_map(
            function ($v, $k) {
                return '( '. $k.' = '.$v. ' facturi )';
            },
            $lista_data_incarcare,
            array_keys($lista_data_incarcare)
        ));

        $lista_data_incarcare_anaf_string_duplicat = implode(',  ', array_map(
            function ($v, $k) {
                return '( '. $k.' = '.$v. ' facturi )';
            },
            $lista_data_incarcare_duplicat,
            array_keys($lista_data_incarcare_duplicat)
        ));

        $message_facturi = 'Au fost inserate '.$numar_facturi.' facturi.';
        if($numar_facturi_duplicat) {
            $message_facturi = 'Au fost inserate '.$numar_facturi.' facturi din '. ($numar_facturi+$numar_facturi_duplicat).'.';
        }

        return back()->with('success','Fisierul '. $request->zip->getClientOriginalName().' a fost incarcat cu succes!')
        ->with('facturi', $message_facturi)
        ->with('duplicate', $numar_facturi_duplicat)
        ->with('lista_data_incarcare', $lista_data_incarcare_anaf_string)
        ->with('lista_data_incarcare_duplicat', $lista_data_incarcare_anaf_string_duplicat)
        ->with('file', $request->zip->getClientOriginalName());

    }





    //////////////////////////////////////////////////
    //////////// VIZUALIZARE PDF DE LA ANAF //////////
    //////////////////////////////////////////////////
    public function pdf_anaf($id) {


        $invoice_obj = EfacturaPathInvoice::find($id);
        $path_pdf = $this->storageDestinationPathUnzip.$invoice_obj->time.'/'.$invoice_obj->created_at_anaf;




        $dh  = opendir($path_pdf);
        while (false !== ($fileName = readdir($dh))) {

            $prefix = substr($fileName, 0, 10);

            $ext = substr($fileName, strrpos($fileName, '.') + 1);
            if(in_array($ext, array("pdf"))) {




                $a[] = $prefix;
                if ($prefix == $invoice_obj->xml_name ) {

                    $files1 = $fileName;
                }
            }

        }
        closedir($dh);
        return response()->file($this->storageDestinationPathUnzip.$invoice_obj->time.'/'.$invoice_obj->created_at_anaf.'/'. $files1);
    }


    //////////////////////////////////////////////////
    //////////// VIZUALIZARE PDF DE LA ANAF //////////
    //////////////////////////////////////////////////
    public function semnatura_anaf($id) {


        $invoice_obj = EfacturaPathInvoice::find($id);
        $path_semnatura = $this->storageDestinationPathUnzip.$invoice_obj->time.'/'.$invoice_obj->created_at_anaf.'/'.$invoice_obj->zip_name.'/semnatura_'.$invoice_obj->xml_name.'.xml';

        return response()->download($path_semnatura);
    }



    //////////////////////////////////////////////////
    //////////// CITIM XML SI RETURNAM UN ARRAY //////
    //////////////////////////////////////////////////
    private function get_details_from_xml($xml_path) {


        $xmlfile = file_get_contents($this->storageDestinationPathUnzip. $xml_path);

        @$sxe = simplexml_load_string($xmlfile);

        @$namespaces = $sxe->getDocNamespaces();
/*
        if (isset($namespaces['cbc']) and isset($namespaces['cac'])) {

            $sxe->registerXPathNamespace('cbc', $namespaces['cbc']);
            $cbc = $sxe->children($namespaces['cbc']);

            $sxe->registerXPathNamespace('cac', $namespaces['cac']);
            $cac = $sxe->children($namespaces['cac']);
        }

        else {
            */
            $sxe->registerXPathNamespace('cbc', "urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2");
            $cbc = $sxe->children("urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2");

            $cbc_namespace = "urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2";

            $sxe->registerXPathNamespace('cac', "urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2");
            $cac = $sxe->children("urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2");

            $cac_namespace = "urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2";

        //}


        //////////////////////////////////////////////////
        //////////// INFORMATII FACTURA //////////////////
        //////////////////////////////////////////////////

        //BT-1 Nr. factura
        $invoice['Informatii_factura']['Nr_factura'] =  (string) @$cbc->ID ;

        //BT-2 Data emitere factura
        $invoice['Informatii_factura']['Data_emitere_factura'] = (string) @$cbc->IssueDate;

        //BT-9 Data scadenta factura
        $invoice['Informatii_factura']['Data_scadenta_factura'] = (string) @$cbc->DueDate;

        //BT-3 Codul tipului facturii TODO: unde avem codificarea?
        $invoice['Informatii_factura']['Codul_tipului_facturii'] = (string) @$cbc->InvoiceTypeCode.( ((string) @$cbc->InvoiceTypeCode == '380' ) ? ' - FACTURA' : '') ;

        //BT-5 Codul monedei facturii
        $invoice['Informatii_factura']['Codul_monedei_facturii'] = (string) @$cbc->DocumentCurrencyCode;

        //BT-6 Codul monedei de contabilizare a TVA
        $invoice['Informatii_factura']['Codul_monedei_de_contabilizare_a_TVA'] = (string) @$cbc->TaxCurrencyCode;

        //BT-7 Data de exigibilitate a TVA
        $invoice['Informatii_factura']['Data_de_exigibilitate_a_TVA'] = (string) @$cbc->TaxPointDate ;

        // BT-73 Data de început a perioadei de facturare
        $invoice['Informatii_factura']['Data_de_inceput_a_perioadei_de_facturare'] = (string) @$cac->InvoicePeriod->children($cbc_namespace)->StartDate ;

        // BT-74 Data de sfarsit a perioadei de facturare
        $invoice['Informatii_factura']['Data_de_sfarsit_a_perioadei_de_facturare'] = (string) @$cac->InvoicePeriod->children($cbc_namespace)->EndDate;

        // BT-19 Referinta cumparatorului
        $invoice['Informatii_factura']['Referinta_cumparatorului'] = (string) @$cbc->AccountingCost;

        //BT-13 Referinta comenzii
        $invoice['Informatii_factura']['Referinta_comenzii'] = null;
        if(@$cac->OrderReference->children($cbc_namespace)->ID) {
            $invoice['Informatii_factura']['Referinta_comenzii'] =  (string) @$cac->OrderReference->children($cbc_namespace)->ID ;
        }

        //BT-14 Referinta dispozitiei de vanzare
        $invoice['Informatii_factura']['Referinta_dispozitiei_de_vanzare'] = null;
        if(@$cac->OrderReference->children($cbc_namespace)->SalesOrderID) {
            $invoice['Informatii_factura']['Referinta_dispozitiei_de_vanzare'] = (string) @$cac->OrderReference->children($cbc_namespace)->SalesOrderID ;
        }

        //BT-25 Referinta la o factura anterioara:
        $invoice['Informatii_factura']['Referinta_la_o_factura_anterioara'] = null;
        //BT-26 Data de emitere a facturii anterioare:
        $invoice['Informatii_factura']['Data_de_emitere_a_facturii_anterioare'] = null;
        if ( @$cac->BillingReference->InvoiceDocumentReference) {
            $invoice['Informatii_factura']['Referinta_la_o_factura_anterioara'] =  (string) @$cac->BillingReference->InvoiceDocumentReference->children($cbc_namespace)->ID;
            $invoice['Informatii_factura']['Data_de_emitere_a_facturii_anterioare'] = (string) @$cac->BillingReference->InvoiceDocumentReference->children($cbc_namespace)->IssueDate;
        }

        // BT-22 Comentariu in factura
        if( @$cbc->Note) {
            foreach ($cbc->Note as $note) {
                //BT-22 Comentariu in factura
                $invoice['Informatii_factura']['Comentariu_in_factura'][] = (string) $note;
            }
        }

        //BT-16 Referinta avizului de expeditie
        $invoice['Informatii_factura']['Referinta_avizului_de_expeditie'] = null;
        if( @$cac->DespatchDocumentReference->children($cbc_namespace)->ID) {
            $invoice['Informatii_factura']['Referinta_avizului_de_expeditie'] = (string) @$cac->DespatchDocumentReference->children($cbc_namespace)->ID ;
        }

        //BT-15 Referinta avizului de receptie
        $invoice['Informatii_factura']['Referinta_avizului_de_receptie'] = null;
        if(@$cac->ReceiptDocumentReference->children($cbc_namespace)->ID) {
            $invoice['Informatii_factura']['Referinta_avizului_de_receptie'] = (string) @$cac->ReceiptDocumentReference->children($cbc_namespace)->ID ;
        }

        //BT-17 Referinta cererii de oferta sau a lotului
        $invoice['Informatii_factura']['Referinta_cererii_de_oferta_sau_a_lotului'] = null;
        if(@$cac->OriginatorDocumentReference->children($cbc_namespace)->ID) {
            $invoice['Informatii_factura']['Referinta_cererii_de_oferta_sau_a_lotului'] =  (string) @$cac->OriginatorDocumentReference->children($cbc_namespace)->ID ;
        }

        //BT-12 Referinta contractului
        $invoice['Informatii_factura']['Referinta_contractului'] = null;
        if(@$cac->ContractDocumentReference->children($cbc_namespace)->ID) {
            $invoice['Informatii_factura']['Referinta_contractului'] = (string) @$cac->ContractDocumentReference->children($cbc_namespace)->ID;
        }

        //BT-11 Referinta proiectului
        $invoice['Informatii_factura']['Referinta_proiectului'] = null;
        if( @$cac->ReceiptDocumentReference->children($cbc_namespace)->ID) {
            $invoice['Informatii_factura']['Referinta_proiectului'] = (string) @$cac->ProjectReference->children($cbc_namespace)->ID;
        }


        //////////////////////////////////////////////////
        //////////// VANZATOR ////////////////////////////
        //////////////////////////////////////////////////

        //BT-34 Adresa electronica
        $invoice['Vanzator']['Adresa_electronica'] = null;
        if( @$cac->AccountingSupplierParty->Party->children($cbc_namespace)->EndpointID ) {
            $invoice['Vanzator']['Adresa_electronica'] = (string) @$cac->AccountingSupplierParty->Party->children($cbc_namespace)->EndpointID ;
        }
        //BT-34-1 Identificatorul schemei
        $invoice['Vanzator']['Adresa_electronica_Identificatorul_schemei'] = null;
        if( @$cac->AccountingSupplierParty->Party->children($cbc_namespace)->EndpointID->attributes()->schemeID ) {
            $invoice['Vanzator']['Adresa_electronica_Identificatorul_schemei'] = (string) @$cac->AccountingSupplierParty->Party->children($cbc_namespace)->EndpointID->attributes()->schemeID ;
        }

        //BT-41 Persoana de contact
        $invoice['Vanzator']['Persoana_de_contact'] = null;
        if( @$cac->AccountingSupplierParty->Party->Contact->children($cbc_namespace)->Name) {
            $invoice['Vanzator']['Persoana_de_contact'] = (string) @$cac->AccountingSupplierParty->Party->Contact->children($cbc_namespace)->Name ;
        }

        //BT-42 Telefon persoana de contact
        $invoice['Vanzator']['Telefon_persoana_de_contact'] = null;
        if( @$cac->AccountingSupplierParty->Party->Contact->children($cbc_namespace)->Telephone ) {
            $invoice['Vanzator']['Telefon_persoana_de_contact'] = (string) @$cac->AccountingSupplierParty->Party->Contact->children($cbc_namespace)->Telephone ;
        }

        //BT-43 E-mail persoana de contact
        $invoice['Vanzator']['E_mail_persoana_de_contact'] = null;
        if( @$cac->AccountingSupplierParty->Party->Contact->children($cbc_namespace)->ElectronicMail ) {
            $invoice['Vanzator']['E_mail_persoana_de_contact'] = (string) @$cac->AccountingSupplierParty->Party->Contact->children($cbc_namespace)->ElectronicMail ;
        }

        //BT-29 Identificator
        $invoice['Vanzator']['Identificator'] = null;
        if ( @$cac->AccountingSupplierParty->Party->PartyIdentification->children($cbc_namespace)->ID) {
            $invoice['Vanzator']['Identificator'] = (string) @$cac->AccountingSupplierParty->Party->PartyIdentification->children($cbc_namespace)->ID ;
        }

        //BT-29-1 Identificatorul schemei
        $invoice['Vanzator']['Identificator_Identificatorul_schemei'] = null;
        if ( @$cac->AccountingSupplierParty->Party->PartyIdentification->children($cbc_namespace)->ID) {
            $invoice['Vanzator']['Identificator_Identificatorul_schemei'] = (string) @$cac->AccountingSupplierParty->Party->PartyIdentification->children($cbc_namespace)->ID->attributes()->schemeID ;
        }

        //BT-28 Denumire comerciala
        $invoice['Vanzator']['Denumire_comerciala'] = null;
        if(@$cac->AccountingSupplierParty->Party->PartyName) {
            $invoice['Vanzator']['Denumire_comerciala'] = (string) @$cac->AccountingSupplierParty->Party->PartyName->children($cbc_namespace) ;
        }

        // ADRESA //////////////////////////

        //BT-35 Strada
        $invoice['Vanzator']['Adresa']['Strada'] =  (string) @$cac->AccountingSupplierParty->Party->PostalAddress->children($cbc_namespace)->StreetName ;

        //BT-36 Informatii suplimentare strada
        $invoice['Vanzator']['Adresa']['Informatii_suplimentare_strada'] =  (string) @$cac->AccountingSupplierParty->Party->PostalAddress->children($cbc_namespace)->AdditionalStreetName ;

        //BT-37 Oras
        $invoice['Vanzator']['Adresa']['Oras'] = (string) @$cac->AccountingSupplierParty->Party->PostalAddress->children($cbc_namespace)->CityName ;

        //BT-38 Cod Postal
        $invoice['Vanzator']['Adresa']['Cod_Postal'] = (string) @$cac->AccountingSupplierParty->Party->PostalAddress->children($cbc_namespace)->PostalZone ;

        //BT-39 Subdiviziunea
        $invoice['Vanzator']['Adresa']['Subdiviziunea_tarii'] = (string) @$cac->AccountingSupplierParty->Party->PostalAddress->children($cbc_namespace)->CountrySubentity ;

        //BT-40 Tara
        $invoice['Vanzator']['Adresa']['Tara'] = (string) @$cac->AccountingSupplierParty->Party->PostalAddress->Country->children($cbc_namespace)->IdentificationCode;

        //BT-162 Informatii suplimentare adresa
        $invoice['Vanzator']['Adresa']['Informatii_suplimentare_adresa'] = (string) @$cac->AccountingSupplierParty->Party->PostalAddress->AddressLine->children($cbc_namespace)->Line ;

        //BT-31 Identificatorul de TVA
        $invoice['Vanzator']['Identificatorul_de_TVA'] = (string) @$cac->AccountingSupplierParty->Party->PartyTaxScheme->children($cbc_namespace)->CompanyID ;

        //BT-27 Nume
        $invoice['Vanzator']['Nume'] =  (string) @$cac->AccountingSupplierParty->Party->PartyLegalEntity->children($cbc_namespace)->RegistrationName;

        //BT-30 Identificatorul de inregistrare legala
        $invoice['Vanzator']['Identificatorul_de_inregistrare_legala'] = (string) @$cac->AccountingSupplierParty->Party->PartyLegalEntity->children($cbc_namespace)->CompanyID ;

        //BT-30-1 Identificatorul schemei
        $invoice['Vanzator']['Identificatorul_de_inregistrare_legala_Identificatorul_schemei'] = null;
        if( @$cac->AccountingSupplierParty->Party->PartyLegalEntity->children($cbc_namespace)->CompanyID->attributes()->schemeID ) {
            $invoice['Vanzator']['Identificatorul_de_inregistrare_legala_Identificatorul_schemei'] = (string) @$cac->AccountingSupplierParty->Party->PartyLegalEntity->children($cbc_namespace)->CompanyID->attributes()->schemeID ;
        }

        //BT-33 Informatii juridice suplimentare
        $invoice['Vanzator']['Informatii_juridice_suplimentare'] = (string) @$cac->AccountingSupplierParty->Party->PartyLegalEntity->children($cbc_namespace)->CompanyLegalForm;


        //////////////////////////////////////////////////
        //////////// CUMPARATOR //////////////////////////
        //////////////////////////////////////////////////

        //BT-49 Adresa electronica
        $invoice['Cumparator']['Adresa_electronica'] = null;
        if(@$cac->AccountingCustomerParty->Party->children($cbc_namespace)->EndpointID){
            $invoice['Cumparator']['Adresa_electronica'] = (string) @$cac->AccountingCustomerParty->Party->children($cbc_namespace)->EndpointID ;
        }

        //BT-49-1 Identificatorul schemei
        $invoice['Cumparator']['Adresa_electronica_Identificatorul_schemei'] = null;
        if(@$cac->AccountingCustomerParty->Party->children($cbc_namespace)->EndpointID->attributes()->schemeID) {
            $invoice['Cumparator']['Adresa_electronica_Identificatorul_schemei'] = (string) @$cac->AccountingCustomerParty->Party->children($cbc_namespace)->EndpointID->attributes()->schemeID ;
        }

        //BT-56 Persoana de contact
        $invoice['Cumparator']['Persoana_de_contact'] = null;
        if( @$cac->AccountingCustomerParty->Party->Contact->children($cbc_namespace)->Name ) {
            $invoice['Cumparator']['Persoana_de_contact'] =  (string) @$cac->AccountingCustomerParty->Party->Contact->children($cbc_namespace)->Name;
        }

        //BT-57 Telefon persoana de contact
        $invoice['Cumparator']['Telefon_persoana_de_contact'] = null;
        if(  @$cac->AccountingCustomerParty->Party->Contact->children($cbc_namespace)->Telephone ) {
            $invoice['Cumparator']['Telefon_persoana_de_contact'] = (string) @$cac->AccountingCustomerParty->Party->Contact->children($cbc_namespace)->Telephone;
        }

        //BT-58 E-mail persoana de contact
        $invoice['Cumparator']['E_mail_persoana_de_contact'] = null;
        if(  @$cac->AccountingCustomerParty->Party->Contact->children($cbc_namespace)->ElectronicMail) {
            $invoice['Cumparator']['E_mail_persoana_de_contact'] = (string) @$cac->AccountingCustomerParty->Party->Contact->children($cbc_namespace)->ElectronicMail;
        }


        //BT-46 Identificator
        $invoice['Cumparator']['Identificator'] = null;
        if (@$cac->AccountingCustomerParty->Party->PartyIdentification->children($cbc_namespace)->ID) {
            $invoice['Cumparator']['Identificator'] = (string) @$cac->AccountingCustomerParty->Party->PartyIdentification->children($cbc_namespace)->ID;
        }

        //BT-46-1 Identificatorul schemei
        $invoice['Cumparator']['Identificator_Identificatorul_schemei'] = null;
        if (@$cac->AccountingCustomerParty->Party->PartyIdentification->children($cbc_namespace)->ID) {
            $invoice['Cumparator']['Identificator_Identificatorul_schemei'] = (string) @$cac->AccountingCustomerParty->Party->PartyIdentification->children($cbc_namespace)->ID->attributes()->schemeID ;
        }

        //BT-45 Denumire comerciala
        $invoice['Cumparator']['Denumire_comerciala'] = (string) @$cac->AccountingCustomerParty->Party->PartyName->children($cbc_namespace)->Name ;



        // ADRESA /////

        //BT-50 Strada
        $invoice['Cumparator']['Adresa']['Strada'] = (string) @$cac->AccountingCustomerParty->Party->PostalAddress->children($cbc_namespace)->StreetName;

        //BT-51 Informatii suplimentare strada
        $invoice['Cumparator']['Adresa']['Informatii_suplimentare_strada'] = (string) @$cac->AccountingCustomerParty->Party->PostalAddress->children($cbc_namespace)->AdditionalStreetName;

        //BT-52 Oras
        $invoice['Cumparator']['Adresa']['Oras'] = (string) @$cac->AccountingCustomerParty->Party->PostalAddress->children($cbc_namespace)->CityName;

        //BT-53 Cod Postal
        $invoice['Cumparator']['Adresa']['Cod_Postal'] = (string) @$cac->AccountingCustomerParty->Party->PostalAddress->children($cbc_namespace)->PostalZone;

        //BT-54 Subdiviziunea tarii
        $invoice['Cumparator']['Adresa']['Subdiviziunea_tarii'] = (string) @$cac->AccountingCustomerParty->Party->PostalAddress->children($cbc_namespace)->CountrySubentity;

        //BT-55 Tara
        $invoice['Cumparator']['Adresa']['Tara'] = (string) @$cac->AccountingCustomerParty->Party->PostalAddress->Country->children($cbc_namespace)->IdentificationCode;

        //BT-163 Informatii suplimentare adresa
        $invoice['Cumparator']['Adresa']['Informatii_suplimentare_adresa'] = (string) @$cac->AccountingCustomerParty->Party->PostalAddress->AddressLine->children($cbc_namespace)->Line ;

        //BT-48 Identificatorul de TVA
        $invoice['Cumparator']['Identificatorul_de_TVA'] = (string) @$cac->AccountingCustomerParty->Party->PartyTaxScheme->children($cbc_namespace)->CompanyID ;

        //BT-44 Nume
        $invoice['Cumparator']['Nume'] = (string) @$cac->AccountingCustomerParty->Party->PartyLegalEntity->children($cbc_namespace)->RegistrationName;

        //BT-47 Identificatorul de inregistrare legala
        $invoice['Cumparator']['Identificatorul_de_inregistrare_legala'] = (string) @$cac->AccountingCustomerParty->Party->PartyLegalEntity->children($cbc_namespace)->CompanyID;

        //BT-47-1 Identificatorul schemei
        $invoice['Cumparator']['Identificatorul_de_inregistrare_legala_Identificatorul_schemei'] = (string) @$cac->AccountingCustomerParty->Party->PartyLegalEntity->children($cbc_namespace)->CompanyID->attributes()->schemeID ;


        //////////////////////////////////////////////////
        //////////// BENEFICIAR //////////////////////////
        //////////////////////////////////////////////////

        if (@$cac->PayeeParty->PartyIdentification) {


            //BT-60 Identificator
            $invoice['Beneficiar']['Identificator'] =  (string) @$cac->PayeeParty->PartyIdentification->children($cbc_namespace)->ID ;

            //BT-60-1 Identificatorul schemei
            $invoice['Beneficiar']['Identificator_Identificatorul_schemei'] = (string) @$cac->PayeeParty->PartyIdentification->children($cbc_namespace)->ID->attributes()->schemeID;

            //BT-59 Nume beneficiar
            $invoice['Beneficiar']['Nume_beneficiar'] = (string) @$cac->PayeeParty->PartyName->children($cbc_namespace)->Name;

            //BT-61 Identificatorul de inregistrare legala
            $invoice['Beneficiar']['Identificatorul_de_inregistrare_legala'] = (string) @$cac->PayeeParty->PartyLegalEntity->children($cbc_namespace)->CompanyID;

            //BT-61-1 Identificatorul schemei
            $invoice['Beneficiar']['Identificatorul_de_inregistrare_legala_Identificatorul_schemei'] =  (string) @$cac->PayeeParty->PartyLegalEntity->children($cbc_namespace)->CompanyID->attributes()->schemeID ;

        }


        //////////////////////////////////////////////////////////
        //////////// REPREZENTANTUL FISCAL AL VANZATORULUI////////
        //////////////////////////////////////////////////////////

        if (@$cac->TaxRepresentativeParty->PartyName) {


            //BT-62 Nume
            $invoice['Reprezentantul_fiscal_al_vanzatorului']['Nume'] = (string) @$cac->TaxRepresentativeParty->PartyName->children($cbc_namespace)->Name ;

            // ADRESA /////

            //BT-64 Strada
            $invoice['Reprezentantul_fiscal_al_vanzatorului']['Adresa']['Strada'] = (string) @$cac->TaxRepresentativeParty->PostalAddress->children($cbc_namespace)->StreetName ;

            //BT-65 Informatii suplimentare strada
            $invoice['Reprezentantul_fiscal_al_vanzatorului']['Adresa']['Informatii_suplimentare_strada'] = (string) @$cac->TaxRepresentativeParty->PostalAddress->children($cbc_namespace)->AdditionalStreetName ;

            //BT-66 Oras
            $invoice['Reprezentantul_fiscal_al_vanzatorului']['Adresa']['Oras'] = (string) @$cac->TaxRepresentativeParty->PostalAddress->children($cbc_namespace)->CityName ;

            //BT-67 Cod Postal
            $invoice['Reprezentantul_fiscal_al_vanzatorului']['Adresa']['Cod_Postal'] = (string) @$cac->TaxRepresentativeParty->PostalAddress->children($cbc_namespace)->PostalZone ;

            //BT-68 Subdiviziunea tarii
            $invoice['Reprezentantul_fiscal_al_vanzatorului']['Adresa']['Subdiviziunea_tarii'] = (string) @$cac->TaxRepresentativeParty->PostalAddress->children($cbc_namespace)->CountrySubentity ;

            //BT-69 Tara

            $invoice['Reprezentantul_fiscal_al_vanzatorului']['Adresa']['Tara'] = (string) @$cac->TaxRepresentativeParty->PostalAddress->Country->children($cbc_namespace)->IdentificationCode ;

            //BT-164 Informatii suplimentare adresa
            $invoice['Reprezentantul_fiscal_al_vanzatorului']['Adresa']['Informatii_suplimentare_adresa'] = (string) @$cac->TaxRepresentativeParty->PostalAddress->AddressLine->children($cbc_namespace)->Line;

            //BT-63 Identificatorul de TVA
            $invoice['Reprezentantul_fiscal_al_vanzatorului']['Identificatorul_de_TVA'] = (string) @$cac->TaxRepresentativeParty->PartyTaxScheme->children($cbc_namespace)->CompanyID;

        }


        //////////////////////////////////////////////////
        //////////// INFORMATII REFERITOARE LA LIVRARE ///
        //////////////////////////////////////////////////

        if ($cac->Delivery) {


            //BT-72 Data reala a livrarii
            $invoice['Informatii_referitoare_la_livrare']['Data_reala_a_livrarii'] = (string) @$cac->Delivery->children($cbc_namespace)->ActualDeliveryDate ;

            //BT-70 Numele partii catre care se face livrarea
            $invoice['Informatii_referitoare_la_livrare']['Numele_partii_catre_care_se_face_livrarea'] = null;
            if(@$cac->Delivery->DeliveryParty->PartyName) {
                $invoice['Informatii_referitoare_la_livrare']['Numele_partii_catre_care_se_face_livrarea'] =  (string) @$cac->Delivery->DeliveryParty->PartyName->children($cbc_namespace)->Name ;
            }


            // ADRESA /////

            if(@$cac->Delivery->DeliveryLocation) {

                //BT-71 Identificatorul locului
                $invoice['Informatii_referitoare_la_livrare']['Locatie']['Identificatorul_locului'] = null;
                if(@$cac->Delivery->DeliveryLocation->children($cbc_namespace)->ID) {
                    $invoice['Informatii_referitoare_la_livrare']['Locatie']['Identificatorul_locului'] =  (string) @$cac->Delivery->DeliveryLocation->children($cbc_namespace)->ID ;
                }

                //BT-71-1 Identificatorul schemei
                $invoice['Informatii_referitoare_la_livrare']['Locatie']['Identificatorul_schemei'] = null;
                if(@ $cac->Delivery->DeliveryLocation->children($cbc_namespace)->ID) {
                    $invoice['Informatii_referitoare_la_livrare']['Locatie']['Identificatorul_schemei'] =  (string) @$cac->Delivery->DeliveryLocation->children($cbc_namespace)->ID->attributes()->schemeID ;
                }

                if( @$cac->Delivery->DeliveryLocation->Address) {
                    //BT-75 Strada
                    $invoice['Informatii_referitoare_la_livrare']['Locatie']['Adresa']['Strada'] = (string) @$cac->Delivery->DeliveryLocation->Address->children($cbc_namespace)->StreetName ;

                    //BT-76 Informatii suplimentare strada
                    $invoice['Informatii_referitoare_la_livrare']['Locatie']['Adresa']['Informatii_suplimentare_strada'] = (string) @$cac->Delivery->DeliveryLocation->Address->children($cbc_namespace)->AdditionalStreetName ;

                    //BT-165 Informatii suplimentare adresa
                    $invoice['Informatii_referitoare_la_livrare']['Locatie']['Adresa']['Informatii_suplimentare_adresa'] =  (string) @$cac->Delivery->DeliveryLocation->Address->AddressLine->children($cbc_namespace)->Line  ;

                    //BT-77 Oras
                    $invoice['Informatii_referitoare_la_livrare']['Locatie']['Adresa']['Oras'] = (string) @$cac->Delivery->DeliveryLocation->Address->children($cbc_namespace)->CityName ;

                    //BT-78 Cod Postal
                    $invoice['Informatii_referitoare_la_livrare']['Locatie']['Adresa']['Cod_Postal'] = (string) @$cac->Delivery->DeliveryLocation->Address->children($cbc_namespace)->PostalZone ;

                    //BT-79 Subdiviziunea tarii
                    $invoice['Informatii_referitoare_la_livrare']['Locatie']['Adresa']['Subdiviziunea_tarii'] = (string) @$cac->Delivery->DeliveryLocation->Address->children($cbc_namespace)->CountrySubentity ;

                    //BT-80 Tara
                    $invoice['Informatii_referitoare_la_livrare']['Locatie']['Adresa']['Tara'] = (string) @$cac->Delivery->DeliveryLocation->Address->Country->children($cbc_namespace)->IdentificationCode ;

                }
            }
        }


        //////////////////////////////////////////////////
        //////////// INSTRUCTIUNI DE PLATA ///////////////
        //////////////////////////////////////////////////

        if ($cac->PaymentMeans) {

            for($pas_pm=0; $pas_pm < @$cac->PaymentMeans->count(); $pas_pm++) {
                //BT-81 Codul tipului instrumentului de plata
                $invoice['Instructiuni_de_plata'][$pas_pm]['Codul_tipului_instrumentului_de_plata'] = (string) @$cac->PaymentMeans[$pas_pm]->children($cbc_namespace)->PaymentMeansCode ;

                //BT-82 Nota privind instrumentul de plata
                $invoice['Instructiuni_de_plata'][$pas_pm]['Nota_privind_instrumentul_de_plata'] = null;
                if(@$cac->PaymentMeans[$pas_pm]->children($cbc_namespace)->InstructionNote) {
                    $invoice['Instructiuni_de_plata'][$pas_pm]['Nota_privind_instrumentul_de_plata'] = (string) @$cac->PaymentMeans[$pas_pm]->children($cbc_namespace)->InstructionNote ;
                }

                //BT-83 Aviz de plata
                $invoice['Instructiuni_de_plata'][$pas_pm]['Aviz_de_plata'] = null;
                if(@$cac->PaymentMeans[$pas_pm]->children($cbc_namespace)->PaymentID) {
                    $invoice['Instructiuni_de_plata'][$pas_pm]['Aviz_de_plata'] = (string) @$cac->PaymentMeans[$pas_pm]->children($cbc_namespace)->PaymentID ;
                }

                //BT-87 Numarul contului principal al cardului de plata
                $invoice['Instructiuni_de_plata'][$pas_pm]['Numarul_contului_principal_al_cardului_de_plata'] = null;
                if(@$cac->PaymentMeans[$pas_pm]->CardAccount->children($cbc_namespace)->PrimaryAccountNumberID) {
                    $invoice['Instructiuni_de_plata'][$pas_pm]['Numarul_contului_principal_al_cardului_de_plata'] =  (string) @$cac->PaymentMeans[$pas_pm]->CardAccount->children($cbc_namespace)->PrimaryAccountNumberID ;
                }

                //BT-88 Numele detinatorului cardului de plata
                $invoice['Instructiuni_de_plata'][$pas_pm]['Numele_detinatorului_cardului_de_plata'] = null;
                if(@$cac->PaymentMeans[$pas_pm]->CardAccount->children($cbc_namespace)->HolderName) {
                    $invoice['Instructiuni_de_plata'][$pas_pm]['Numele_detinatorului_cardului_de_plata'] = (string) @$cac->PaymentMeans[$pas_pm]->CardAccount->children($cbc_namespace)->HolderName ;
                }

                //BT-84 Identificatorul contului de plata
                $invoice['Instructiuni_de_plata'][$pas_pm]['Identificatorul_contului_de_plata'] = (string) @$cac->PaymentMeans[$pas_pm]->PayeeFinancialAccount->children($cbc_namespace)->ID;

                //BT-85 Numele contului de plata
                $invoice['Instructiuni_de_plata'][$pas_pm]['Numele_contului_de_plata'] = (string) @$cac->PaymentMeans[$pas_pm]->PayeeFinancialAccount->children($cbc_namespace)->Name;

                //BT-86 Identificatorul furnizorului de servicii de plata
                $invoice['Instructiuni_de_plata'][$pas_pm]['Identificatorul_furnizorului_de_servicii_de_plata'] = null;
                if(@$cac->PaymentMeans[$pas_pm]->PayeeFinancialAccount->FinancialInstitutionBranch) {

                    $invoice['Instructiuni_de_plata'][$pas_pm]['Identificatorul_furnizorului_de_servicii_de_plata'] =  (string) @$cac->PaymentMeans[$pas_pm]->PayeeFinancialAccount->FinancialInstitutionBranch->children($cbc_namespace)->ID ;
                }

                // DEBITARE directa/////
                //BT-89 Identificatorul referintei mandatului
                $invoice['Instructiuni_de_plata'][$pas_pm]['Debitare_directa']['Identificatorul_referintei_mandatului'] = null;
                if(@$cac->PaymentMeans[$pas_pm]->PaymentMandate->children($cbc_namespace)->ID) {
                    $invoice['Instructiuni_de_plata'][$pas_pm]['Debitare_directa']['Identificatorul_referintei_mandatului'] = (string) @$cac->PaymentMeans[$pas_pm]->PaymentMandate->children($cbc_namespace)->ID ;
                }

                //BT-91 Identificatorul contului debitat
                $invoice['Instructiuni_de_plata'][$pas_pm]['Debitare_directa']['Identificatorul_contului_debitat'] = null;
                if(@$cac->PaymentMeans[$pas_pm]->PaymentMandate->PayerFinancialAccount) {
                    $invoice['Instructiuni_de_plata'][$pas_pm]['Debitare_directa']['Identificatorul_contului_debitat'] = (string) @$cac->PaymentMeans[$pas_pm]->PaymentMandate->PayerFinancialAccount->children($cbc_namespace)->ID ;
                }
            }

        }


        //////////////////////////////////////////////////
        //////////// TERMENI DE PLATA ////////////////////
        //////////////////////////////////////////////////

        $invoice['Termeni_de_plata']['Nota'] = null;
        if (@$cac->PaymentTerms) {
            //BT-20 Nota
            $invoice['Termeni_de_plata']['Nota'] = (string) @$cac->PaymentTerms->children($cbc_namespace)->Note;
        }


        //////////////////////////////////////////////////
        //////////// BG-21 TAXA SUPLIMENTAREA ////////////
        //////////// BG-20 DEDUCERE //////////////////////
        //////////////////////////////////////////////////

        if(@$cac->AllowanceCharge) {

            for($pas_ac=0; $pas_ac < @$cac->AllowanceCharge->count(); $pas_ac++) {

                $indicator =   (string) @$cac->AllowanceCharge[$pas_ac]->children($cbc_namespace)->ChargeIndicator;


                // //TAXA SUPLIMENTARA = true // DEDUCERE = false
                $invoice['Taxarea_suplimentara_Deducere'][$pas_ac]['Indicator'] = $indicator;

                //BT-98/BT-105 Codul motivului
                $invoice['Taxarea_suplimentara_Deducere'][$pas_ac]['Codul_motivului'] = (string) @$cac->AllowanceCharge[$pas_ac]->children($cbc_namespace)->AllowanceChargeReasonCode;

                //BT-97/BT-104 Motivul
                $invoice['Taxarea_suplimentara_Deducere'][$pas_ac]['Motivul'] = (string) @$cac->AllowanceCharge[$pas_ac]->children($cbc_namespace)->AllowanceChargeReason;

                //BT-94/BT-101 Procent
                $invoice['Taxarea_suplimentara_Deducere'][$pas_ac]['Procent'] = (string) @$cac->AllowanceCharge[$pas_ac]->children($cbc_namespace)->MultiplierFactorNumeric;

                //BT-92/BT-99 Valoare
                $invoice['Taxarea_suplimentara_Deducere'][$pas_ac]['Valoare'] = (string) $cac->AllowanceCharge[$pas_ac]->children($cbc_namespace)->Amount;

                //Codul monedei RON
                $invoice['Taxarea_suplimentara_Deducere'][$pas_ac]['Codul_monedei_RON'] = (string) @$cac->AllowanceCharge[$pas_ac]->children($cbc_namespace)->Amount->attributes()->currencyID;

                //BT-93/BT-100 Valoarea de baza
                $invoice['Taxarea_suplimentara_Deducere'][$pas_ac]['Valoarea_de_baza'] = (string) @$cac->AllowanceCharge[$pas_ac]->children($cbc_namespace)->BaseAmount;

                //BT-95/BT-102 Codul categoriei de TVA
                $invoice['Taxarea_suplimentara_Deducere'][$pas_ac]['Codul_categoriei_de_TVA'] =  (string) @$cac->AllowanceCharge[$pas_ac]->TaxCategory->children($cbc_namespace)->ID ;

                //BT-96/BT-103 Cota de TVA
                $invoice['Taxarea_suplimentara_Deducere'][$pas_ac]['Cota_de_TVA'] =  (string) @$cac->AllowanceCharge[$pas_ac]->TaxCategory->children($cbc_namespace)->Percent;

                //Identificatorul schemei VAT
                $invoice['Taxarea_suplimentara_Deducere'][$pas_ac]['Identificatorul_schemei_VAT'] = null;
                if(@$cac->AllowanceCharge[$pas_ac]->TaxCategory->TaxScheme) {
                    $invoice['Taxarea_suplimentara_Deducere'][$pas_ac]['Identificatorul_schemei_VAT'] =  (string) @$cac->AllowanceCharge[$pas_ac]->TaxCategory->TaxScheme->children($cbc_namespace)->ID ;

                }
            }
        }




        //////////////////////////////////////////////////
        //////////// TOTALURI TVA ////////////////////////
        //////////////////////////////////////////////////

        $invoice['Totaluri_tva'] = [];

        if($cac->TaxTotal) {

            //BT-110 Valoarea totala a TVA a facturii
            $invoice['Totaluri_tva']['Valoarea_totala_a_TVA_a_facturii'] = (string) @$cac->TaxTotal->children($cbc_namespace)->TaxAmount;

            // BT-111
            $invoice['Totaluri_tva']['Codul_monedei'] = (string) @$cac->TaxTotal->children($cbc_namespace)->TaxAmount->attributes()->currencyID;

            $nr_detaliere= $cac->TaxTotal->children($cac_namespace)->TaxSubtotal->count();

            // BG-23 DETALIERE TVA
            if ($nr_detaliere) {

                for($i=0; $i<$nr_detaliere; $i++) {

                    //BT-116 Baza de calcul 187.5
                    $invoice['Totaluri_tva']['Detaliere_tva'][$i]['Baza_de_calcul'] = (string) @$cac->TaxTotal->children($cac_namespace)->TaxSubtotal[$i]->children($cbc_namespace)->TaxableAmount;

                    //Codul monedei RON
                    $invoice['Totaluri_tva']['Detaliere_tva'][$i]['Baza_de_calcul_Codul_monedei'] =  (string) @$cac->TaxTotal->children($cac_namespace)->TaxSubtotal[$i]->children($cbc_namespace)->TaxableAmount->attributes()->currencyID ;

                    //BT-117 Valoare TVA
                    $invoice['Totaluri_tva']['Detaliere_tva'][$i]['Valoare_TVA'] = (string) @$cac->TaxTotal->children($cac_namespace)->TaxSubtotal[$i]->children($cbc_namespace)->TaxAmount ;

                    //Codul monedei
                    $invoice['Totaluri_tva']['Detaliere_tva'][$i]['Valoare_TVA_Codul_monedei'] =  (string) @$cac->TaxTotal->children($cac_namespace)->TaxSubtotal[$i]->children($cbc_namespace)->TaxAmount->attributes()->currencyID;

                    //BT-118 Codul categoriei de TVA
                    $invoice['Totaluri_tva']['Detaliere_tva'][$i]['Codul_categoriei_de_TVA'] = (string) @$cac->TaxTotal->children($cac_namespace)->TaxSubtotal[$i]->TaxCategory->children($cbc_namespace)->ID;

                    //BT-119 Cota categoriei de TVA
                    $invoice['Totaluri_tva']['Detaliere_tva'][$i]['Cota_categoriei_de_TVA'] =  (string) @$cac->TaxTotal->children($cac_namespace)->TaxSubtotal[$i]->TaxCategory->children($cbc_namespace)->Percent;


                    //BT-121 Codul motivului scutirii
                    $invoice['Totaluri_tva']['Detaliere_tva'][$i]['Codul_motivului_scutirii'] = null;
                    if(@$cac->TaxTotal->children($cac_namespace)->TaxSubtotal[$i]->TaxCategory->children($cbc_namespace)->TaxExemptionReasonCode) {
                        $invoice['Totaluri_tva']['Detaliere_tva'][$i]['Codul_motivului_scutirii'] = (string) @$cac->TaxTotal->children($cac_namespace)->TaxSubtotal[$i]->TaxCategory->children($cbc_namespace)->TaxExemptionReasonCode;

                    }

                    //BT-120 Motivul scutirii
                    $invoice['Totaluri_tva']['Detaliere_tva'][$i]['Motivul_scutirii'] = null;
                    if(@$cac->TaxTotal->children($cac_namespace)->TaxSubtotal[$i]->TaxCategory->children($cbc_namespace)->TaxExemptionReason) {
                        $invoice['Totaluri_tva']['Detaliere_tva'][$i]['Motivul_scutirii'] = (string) @$cac->TaxTotal->children($cac_namespace)->TaxSubtotal[$i]->TaxCategory->children($cbc_namespace)->TaxExemptionReason;

                    }

                }
            }
        }


        //////////////////////////////////////////////////
        //////////// BG-22 TOTALURILE DOCUMENTULUI ///////
        //////////////////////////////////////////////////

        $invoice['Totalurile_documentului'] = [];
        if($cac->LegalMonetaryTotal) {


            //BT-106 Suma valorilor nete ale liniilor facturii
            $invoice['Totalurile_documentului']['Suma_valorilor_nete_ale_liniilor_facturii'] = (string) @$cac->LegalMonetaryTotal->children($cbc_namespace)->LineExtensionAmount;

            // Codul monedei Codul monedei
            $invoice['Totalurile_documentului']['Suma_valorilor_nete_ale_liniilor_facturii_Codul_monedei'] = (string) @$cac->LegalMonetaryTotal->children($cbc_namespace)->LineExtensionAmount->attributes()->currencyID;

            //BT-109 Valoarea totala a facturii fara TVA
            $invoice['Totalurile_documentului']['Valoarea_totala_a_facturii_fara_TVA'] = (string) @$cac->LegalMonetaryTotal->children($cbc_namespace)->TaxExclusiveAmount;

            //Codul monedei
            $invoice['Totalurile_documentului']['Valoarea_totala_a_facturii_fara_TVA_Codul_monedei'] = (string) @$cac->LegalMonetaryTotal->children($cbc_namespace)->TaxExclusiveAmount->attributes()->currencyID;

            //BT-112 Valoarea totala a facturii cu TVA
            $invoice['Totalurile_documentului']['Valoarea_totala_a_facturii_cu_TVA'] = (string) @$cac->LegalMonetaryTotal->children($cbc_namespace)->TaxInclusiveAmount;

            //Codul monedei
            $invoice['Totalurile_documentului']['Valoarea_totala_a_facturii_cu_TVA_Codul_monedei'] = (string) @$cac->LegalMonetaryTotal->children($cbc_namespace)->TaxInclusiveAmount->attributes()->currencyID;

            //BT-107 Suma deducerilor la nivelul documentului
            $invoice['Totalurile_documentului']['Suma_deducerilor_la_nivelul_documentului'] = null;
            if( @$cac->LegalMonetaryTotal->children($cbc_namespace)->AllowanceTotalAmount) {
                $invoice['Totalurile_documentului']['Suma_deducerilor_la_nivelul_documentului'] = (string) @$cac->LegalMonetaryTotal->children($cbc_namespace)->AllowanceTotalAmount;
            }

            //Codul monedei
            $invoice['Totalurile_documentului']['Suma_deducerilor_la_nivelul_documentului_Codul_monedei'] = null;
            if( @$cac->LegalMonetaryTotal->children($cbc_namespace)->AllowanceTotalAmount->attributes()->currencyID) {
                $invoice['Totalurile_documentului']['Suma_deducerilor_la_nivelul_documentului_Codul_monedei'] =  (string) @$cac->LegalMonetaryTotal->children($cbc_namespace)->AllowanceTotalAmount->attributes()->currencyID;
            }

            //BT-108 Suma taxelor suplimentare la nivelul documentului
            $invoice['Totalurile_documentului']['Suma_taxelor_suplimentare_la_nivelul_documentului'] = null;
            if(@$cac->LegalMonetaryTotal->children($cbc_namespace)->ChargeTotalAmount) {
                $invoice['Totalurile_documentului']['Suma_taxelor_suplimentare_la_nivelul_documentului'] = (string) @$cac->LegalMonetaryTotal->children($cbc_namespace)->ChargeTotalAmount;
            }

            //Codul monedei
            $invoice['Totalurile_documentului']['Suma_taxelor_suplimentare_la_nivelul_documentului_Codul_monedei'] = null;
            if(@$cac->LegalMonetaryTotal->children($cbc_namespace)->ChargeTotalAmount->attributes()->currencyID) {
                $invoice['Totalurile_documentului']['Suma_taxelor_suplimentare_la_nivelul_documentului_Codul_monedei'] = (string) @$cac->LegalMonetaryTotal->children($cbc_namespace)->ChargeTotalAmount->attributes()->currencyID;
            }

            //BT-113 Suma platita
            $invoice['Totalurile_documentului']['Suma_platita'] = null;
            if(@$cac->LegalMonetaryTotal->children($cbc_namespace)->PrepaidAmount) {
                $invoice['Totalurile_documentului']['Suma_platita'] = (string) @$cac->LegalMonetaryTotal->children($cbc_namespace)->PrepaidAmount;
            }

            //Codul monedei
            $invoice['Totalurile_documentului']['Suma_platita_Codul_monedei'] = null;
            if(@$cac->LegalMonetaryTotal->children($cbc_namespace)->PrepaidAmount->attributes()->currencyID) {
                $invoice['Totalurile_documentului']['Suma_platita_Codul_monedei'] = (string) @$cac->LegalMonetaryTotal->children($cbc_namespace)->PrepaidAmount->attributes()->currencyID;
            }

            //BT-114 Valoare de rotunjire
            $invoice['Totalurile_documentului']['Valoare_de_rotunjire'] = null;
            if(@$cac->LegalMonetaryTotal->children($cbc_namespace)->PayableRoundingAmount) {
                $invoice['Totalurile_documentului']['Valoare_de_rotunjire'] = (string) @$cac->LegalMonetaryTotal->children($cbc_namespace)->PayableRoundingAmount;
            }

            //Codul monedei
            $invoice['Totalurile_documentului']['Valoare_de_rotunjire_Codul_monedei'] = null;
            if(@$cac->LegalMonetaryTotal->children($cbc_namespace)->PayableRoundingAmount->attributes()->currencyID) {
                $invoice['Totalurile_documentului']['Valoare_de_rotunjire_Codul_monedei'] = (string) @$cac->LegalMonetaryTotal->children($cbc_namespace)->PayableRoundingAmount->attributes()->currencyID;
            }

            //BT-115 Suma de plata
            $invoice['Totalurile_documentului']['Suma_de_plata'] = (string) @$cac->LegalMonetaryTotal->children($cbc_namespace)->PayableAmount;

            //Codul monedei
            $invoice['Totalurile_documentului']['Suma_de_plata_Codul_monedei'] = (string) @$cac->LegalMonetaryTotal->children($cbc_namespace)->PayableAmount->attributes()->currencyID;

        }


        //////////////////////////////////////////////////
        //////////// LINIA FACTURII //////////////////////
        //////////////////////////////////////////////////

        $invoice['Invoice_Line'] = [];

        $nr_linii= $cac->InvoiceLine->count();

        if($nr_linii) {



            for($i=0;$i<$nr_linii;$i++) {

                //BT-153 Nume articol
                $invoice['Invoice_Line'][$i]['Nume_articol'] = (string) @$cac->InvoiceLine[$i]->children($cac_namespace)->Item->children($cbc_namespace)->Name ;

                //BT-146 Pretul net al articolului
                $invoice['Invoice_Line'][$i]['Pretul_net_al_articolului'] = (string) @$cac->InvoiceLine[$i]->children($cac_namespace)->Price->children($cbc_namespace)->PriceAmount;

                //Codul monedei
                $invoice['Invoice_Line'][$i]['Pretul_net_al_articolului_Codul_monedei'] = (string) @$cac->InvoiceLine[$i]->children($cac_namespace)->Price->children($cbc_namespace)->PriceAmount->attributes()->currencyID;

                //BT-149 Cantitatea de baza a pretului articolului
                $invoice['Invoice_Line'][$i]['Cantitatea_de_baza_a_pretului_articolului'] = (string) @$cac->InvoiceLine[$i]->children($cac_namespace)->Price->children($cbc_namespace)->BaseQuantity;

                //BT-129 Cantitate facturata
                $invoice['Invoice_Line'][$i]['Cantitate_facturata'] =  (string) @$cac->InvoiceLine[$i]->children($cbc_namespace)->InvoicedQuantity;

                //BT-130 UM
                $invoice['Invoice_Line'][$i]['UM'] =  (string) @$cac->InvoiceLine[$i]->children($cbc_namespace)->InvoicedQuantity->attributes()->unitCode;

                //BT-151 Codul categoriei de TVA
                $invoice['Invoice_Line'][$i]['Codul_categoriei_de_TVA'] = (string) @$cac->InvoiceLine[$i]->children($cac_namespace)->Item->children($cac_namespace)->ClassifiedTaxCategory->children($cbc_namespace)->ID;

                //BT-152 Cota de TVA
                $invoice['Invoice_Line'][$i]['Cota_de_TVA'] = (string) @$cac->InvoiceLine[$i]->children($cac_namespace)->Item->children($cac_namespace)->ClassifiedTaxCategory->children($cbc_namespace)->Percent;

                //BT-131 Valoarea neta a liniei
                $invoice['Invoice_Line'][$i]['Valoarea_neta_a_liniei'] = (string) @$cac->InvoiceLine[$i]->children($cbc_namespace)->LineExtensionAmount;

                //Informatii suplimentare ///////////////////

                //BT-154 Descriere articol
                $invoice['Invoice_Line'][$i]['Informatii_suplimentare']['Descriere_articol'] = NULL;
                if(@$cac->InvoiceLine[$i]->children($cac_namespace)->Item->children($cbc_namespace)->Description) {
                    $invoice['Invoice_Line'][$i]['Informatii_suplimentare']['Descriere_articol'] = (string) @$cac->InvoiceLine[$i]->children($cac_namespace)->Item->children($cbc_namespace)->Description;
                }

                //BT-159 Tara de origine a articolului
                $invoice['Invoice_Line'][$i]['Informatii_suplimentare']['Tara_de_origine_a_articolului'] = NULL;
                if(@$cac->InvoiceLine[$i]->children($cac_namespace)->Item->children($cac_namespace)->OriginCountry->children($cbc_namespace)->IdentificationCode){
                    $invoice['Invoice_Line'][$i]['Informatii_suplimentare']['Tara_de_origine_a_articolului'] = (string) @$cac->InvoiceLine[$i]->children($cac_namespace)->Item->children($cac_namespace)->OriginCountry->children($cbc_namespace)->IdentificationCode ;
                }
                //BT-127 Nota liniei facturii
                $invoice['Invoice_Line'][$i]['Informatii_suplimentare']['Nota_liniei_facturii'] = NULL;
                if(@$cac->InvoiceLine[$i]->children($cbc_namespace)->Note) {
                    $invoice['Invoice_Line'][$i]['Informatii_suplimentare']['Nota_liniei_facturii'] = (string) @$cac->InvoiceLine[$i]->children($cbc_namespace)->Note ;
                }

                //BT-133 Referinta contabila a cumparatorului din linia facturii
                $invoice['Invoice_Line'][$i]['Informatii_suplimentare']['Referinta_contabila_a_cumparatorului_din_linia_facturii'] = NULL;
                if(@$cac->InvoiceLine[$i]->children($cbc_namespace)->AccountingCost) {
                    $invoice['Invoice_Line'][$i]['Informatii_suplimentare']['Referinta_contabila_a_cumparatorului_din_linia_facturii'] = (string) @$cac->InvoiceLine[$i]->children($cbc_namespace)->AccountingCost ;
                }

                //BT-134 Data de inceput a perioadei de facturare a liniei facturii
                $invoice['Invoice_Line'][$i]['Informatii_suplimentare']['Data_de_inceput_a_perioadei_de_facturare_a_liniei_facturii'] = NULL;
                if(@$cac->InvoiceLine[$i]->children($cac_namespace)->InvoicePeriod->children($cbc_namespace)->StartDate) {
                    $invoice['Invoice_Line'][$i]['Informatii_suplimentare']['Data_de_inceput_a_perioadei_de_facturare_a_liniei_facturii'] = (string) @$cac->InvoiceLine[$i]->children($cac_namespace)->InvoicePeriod->children($cbc_namespace)->StartDate ;
                }

                //BT-135 Data de sfarsit a perioadei de facturare a liniei facturii
                $invoice['Invoice_Line'][$i]['Informatii_suplimentare']['Data_de_sfarsit_a_perioadei_de_facturare_a_liniei_facturii'] = NULL;
                if(@$cac->InvoiceLine[$i]->children($cac_namespace)->InvoicePeriod->children($cbc_namespace)->EndDate) {
                    $invoice['Invoice_Line'][$i]['Informatii_suplimentare']['Data_de_sfarsit_a_perioadei_de_facturare_a_liniei_facturii'] = (string) @$cac->InvoiceLine[$i]->children($cac_namespace)->InvoicePeriod->children($cbc_namespace)->EndDate;
                }

                //BT-132 Referinta liniei comenzii
                $invoice['Invoice_Line'][$i]['Informatii_suplimentare']['Referinta_liniei_comenzii'] = NULL;
                if(  @$cac->InvoiceLine[$i]->children($cac_namespace)->OrderLineReference->children($cbc_namespace)->LineID) {
                    $invoice['Invoice_Line'][$i]['Informatii_suplimentare']['Referinta_liniei_comenzii'] = (string) @$cac->InvoiceLine[$i]->children($cac_namespace)->OrderLineReference->children($cbc_namespace)->LineID ;
                }

                //BT-128 Identificatorul obiectului liniei facturii
                $invoice['Invoice_Line'][$i]['Informatii_suplimentare']['Identificatorul_obiectului_liniei_facturii'] = NULL;
                if(@$cac->InvoiceLine[$i]->children($cac_namespace)->DocumentReference->children($cbc_namespace)->ID) {
                    $invoice['Invoice_Line'][$i]['Informatii_suplimentare']['Identificatorul_obiectului_liniei_facturii'] = (string) @$cac->InvoiceLine[$i]->children($cac_namespace)->DocumentReference->children($cbc_namespace)->ID;
                }

                //BT-128-1 Identificatorul schemei
                $invoice['Invoice_Line'][$i]['Informatii_suplimentare']['Identificatorul_obiectului_liniei_facturii_Identificatorul_schemei'] = NULL;
                if(@$cac->InvoiceLine[$i]->children($cac_namespace)->DocumentReference->children($cbc_namespace)->ID   ) {
                    $invoice['Invoice_Line'][$i]['Informatii_suplimentare']['Identificatorul_obiectului_liniei_facturii_Identificatorul_schemei'] = (string) @$cac->InvoiceLine[$i]->children($cac_namespace)->DocumentReference->children($cbc_namespace)->ID->attributes()->schemeID;
                }
                //BT-155 Identificatorul vanzatorului articolului
                $invoice['Invoice_Line'][$i]['Informatii_suplimentare']['Identificatorul_vanzatorului_articolului'] = NULL;
                if(@$cac->InvoiceLine[$i]->children($cac_namespace)->Item->children($cac_namespace)->SellersItemIdentification->children($cbc_namespace)->ID) {
                    $invoice['Invoice_Line'][$i]['Informatii_suplimentare']['Identificatorul_vanzatorului_articolului'] =  (string) @$cac->InvoiceLine[$i]->children($cac_namespace)->Item->children($cac_namespace)->SellersItemIdentification->children($cbc_namespace)->ID ;
                }

                //BT-156 Identificatorul cumparatorului articolului
                $invoice['Invoice_Line'][$i]['Informatii_suplimentare']['Identificatorul_cumparatorului_articolului'] = NULL;
                if(@$cac->InvoiceLine[$i]->children($cac_namespace)->Item->children($cac_namespace)->BuyersItemIdentification->children($cbc_namespace)->ID ) {
                    $invoice['Invoice_Line'][$i]['Informatii_suplimentare']['Identificatorul_cumparatorului_articolului'] =  (string) @$cac->InvoiceLine[$i]->children($cac_namespace)->Item->children($cac_namespace)->BuyersItemIdentification->children($cbc_namespace)->ID ;
                }

                //BT-157 Identificatorul standard al articolului
                $invoice['Invoice_Line'][$i]['Informatii_suplimentare']['Identificatorul_standard_al_articolului'] = NULL;
                if(@$cac->InvoiceLine[$i]->children($cac_namespace)->Item->children($cac_namespace)->StandardItemIdentification->children($cbc_namespace)->ID) {
                    $invoice['Invoice_Line'][$i]['Informatii_suplimentare']['Identificatorul_standard_al_articolului'] = (string) @$cac->InvoiceLine[$i]->children($cac_namespace)->Item->children($cac_namespace)->StandardItemIdentification->children($cbc_namespace)->ID;

                }

                //BT-157-1 Identificatorul schemei
                $invoice['Invoice_Line'][$i]['Informatii_suplimentare']['Identificatorul_standard_al_articolului_Identificatorul_schemei'] = NULL;
                if(@$cac->InvoiceLine[$i]->children($cac_namespace)->Item->children($cac_namespace)->StandardItemIdentification) {
                    $invoice['Invoice_Line'][$i]['Informatii_suplimentare']['Identificatorul_standard_al_articolului_Identificatorul_schemei'] = (string) @$cac->InvoiceLine[$i]->children($cac_namespace)->Item->children($cac_namespace)->StandardItemIdentification->children($cbc_namespace)->ID->attributes()->schemeID;
                }


                //TODO de facut modelul
                //BT-158 Identificatorul clasificarii articolului
                if (@$cac->InvoiceLine[$i]->children($cac_namespace)->Item->children($cac_namespace)->CommodityClassification) {

                    $nr_linii_CommodityClassification= $cac->InvoiceLine[$i]->children($cac_namespace)->Item->children($cac_namespace)->CommodityClassification->count();

                    for($cc=0;$cc<$nr_linii_CommodityClassification;$cc++) {
                        //BT-158 Identificatorul clasificarii articolului
                        $invoice['Invoice_Line'][$i]['Informatii_clasificare'][$cc]['Identificatorul_clasificarii_articolului'] = (string)  @$cac->InvoiceLine[$i]->children($cac_namespace)->Item->children($cac_namespace)->CommodityClassification[$cc]->children($cbc_namespace)->ItemClassificationCode;

                        /// CRED CA SE REPETA
                        //BT-158-1 Identificatorul schemei
                        $invoice['Invoice_Line'][$i]['Informatii_clasificare'][$cc]['Identificatorul_schemei'] = (string)  @$cac->InvoiceLine[$i]->children($cac_namespace)->Item->children($cac_namespace)->CommodityClassification[$cc]->children($cbc_namespace)->ItemClassificationCode->attributes()->listID ;

                        //BT-158-2 Identificatorul versiunii schemei
                        $invoice['Invoice_Line'][$i]['Informatii_clasificare'][$cc]['Identificatorul_versiunii_schemei'] = (string)  @$cac->InvoiceLine[$i]->children($cac_namespace)->Item->children($cac_namespace)->CommodityClassification[$cc]->children($cbc_namespace)->ItemClassificationCode->attributes()->listVersionID;

                    }


                }

                //BT-160 Numele atributului articolului
                $invoice['Invoice_Line'][$i]['Atributul_articolului']['Numele_atributului_articolului'] = NULL;
                if(  @$cac->InvoiceLine[$i]->children($cac_namespace)->Item->children($cac_namespace)->AdditionalItemProperty->children($cbc_namespace)->Name) {
                    $invoice['Invoice_Line'][$i]['Atributul_articolului']['Numele_atributului_articolului'] = (string) @$cac->InvoiceLine[$i]->children($cac_namespace)->Item->children($cac_namespace)->AdditionalItemProperty->children($cbc_namespace)->Name ;
                }

                //BT-161 Valoarea atributului
                $invoice['Invoice_Line'][$i]['Atributul_articolului']['Valoarea_atributului'] = NULL;
                if(@$cac->InvoiceLine[$i]->children($cac_namespace)->Item->children($cac_namespace)->AdditionalItemProperty->children($cbc_namespace)->Value) {
                    $invoice['Invoice_Line'][$i]['Atributul_articolului']['Valoarea_atributului'] = (string) @$cac->InvoiceLine[$i]->children($cac_namespace)->Item->children($cac_namespace)->AdditionalItemProperty->children($cbc_namespace)->Value;
                }



                if (@$cac->InvoiceLine[$i]->children($cac_namespace)->AllowanceCharge) {

                    $nr_linii_AllowanceCharge= @$cac->InvoiceLine[$i]->children($cac_namespace)->AllowanceCharge->count();

                    for($ac=0; $ac<$nr_linii_AllowanceCharge; $ac++) {

                        $indicator =   (string) @$cac->InvoiceLine[$i]->children($cac_namespace)->AllowanceCharge[$ac]->children($cbc_namespace)->ChargeIndicator;

                        //TAXA SUPLIMENTARA
                        if( $indicator =='true' ) {

                            //BT-140/BT-145 Codul motivului deducerii/taxei suplimentare
                            $invoice['Invoice_Line'][$i]['Taxa_suplimentara']['Codul_motivului_taxei_suplimentare'] = (string) @$cac->InvoiceLine[$i]->children($cac_namespace)->AllowanceCharge[$ac]->children($cbc_namespace)->AllowanceChargeReasonCode ;

                            //BT-139/BT-144 Motiv deducere/taxa suplimentara
                            $invoice['Invoice_Line'][$i]['Taxa_suplimentara']['Motiv_taxa_suplimentara'] = (string) @$cac->InvoiceLine[$i]->children($cac_namespace)->AllowanceCharge[$ac]->children($cbc_namespace)->AllowanceChargeReason;

                            //BT-138/BT-143 Procentajul deducerii/taxei suplimentare
                            $invoice['Invoice_Line'][$i]['Taxa_suplimentara']['Procentajul_taxei_suplimentare'] = (string) @$cac->InvoiceLine[$i]->children($cac_namespace)->AllowanceCharge[$ac]->children($cbc_namespace)->MultiplierFactorNumeric;

                            //BT-136/BT-141 Valoarea deducerii/taxei suplimentare
                            $invoice['Invoice_Line'][$i]['Taxa_suplimentara']['Valoarea_taxei_suplimentare'] = (string) @$cac->InvoiceLine[$i]->children($cac_namespace)->AllowanceCharge[$ac]->children($cbc_namespace)->Amount;

                            //BT-137/BT-142 Valoarea de baza a deducerii/taxei suplimentare
                            $invoice['Invoice_Line'][$i]['Taxa_suplimentara']['Valoarea_de_baza_a_taxei_suplimentare']  = (string) @$cac->InvoiceLine[$i]->children($cac_namespace)->AllowanceCharge[$ac]->children($cbc_namespace)->BaseAmount ;

                        }

                        //DEDUCERE
                        else {

                            //BT-140/BT-145 Codul motivului deducerii/taxei suplimentare
                            $invoice['Invoice_Line'][$i]['Deducere']['Codul_motivului_deducerii'] = (string) @$cac->InvoiceLine[$i]->children($cac_namespace)->AllowanceCharge[$ac]->children($cbc_namespace)->AllowanceChargeReasonCode ;

                            //BT-139/BT-144 Motiv deducere/taxa suplimentara
                            $invoice['Invoice_Line'][$i]['Deducere']['Motiv_deducere'] = (string) @$cac->InvoiceLine[$i]->children($cac_namespace)->AllowanceCharge[$ac]->children($cbc_namespace)->AllowanceChargeReason;

                            //BT-138/BT-143 Procentajul deducerii/taxei suplimentare
                            $invoice['Invoice_Line'][$i]['Deducere']['Procentajul_deducerii'] = (string) @$cac->InvoiceLine[$i]->children($cac_namespace)->AllowanceCharge[$ac]->children($cbc_namespace)->MultiplierFactorNumeric;

                            //BT-136/BT-141 Valoarea deducerii/taxei suplimentare
                            $invoice['Invoice_Line'][$i]['Deducere']['Valoarea_deducerii'] = (string) @$cac->InvoiceLine[$i]->children($cac_namespace)->AllowanceCharge[$ac]->children($cbc_namespace)->Amount;

                            //BT-137/BT-142 Valoarea de baza a deducerii/taxei suplimentare
                            $invoice['Invoice_Line'][$i]['Deducere']['Valoarea_de_baza_a_deducerii']  = (string) @$cac->InvoiceLine[$i]->children($cac_namespace)->AllowanceCharge[$ac]->children($cbc_namespace)->BaseAmount ;

                        }
                    }
                }

                //BT-147 Reducere/taxa suplimentara la pretul articolului
                if(@$cac->InvoiceLine[$i]->children($cac_namespace)->Price->children($cac_namespace)->AllowanceCharge) {

                    $nr_linii_AllowanceCharge= @$cac->InvoiceLine[$i]->children($cac_namespace)->Price->children($cac_namespace)->AllowanceCharge->count();

                    for($ac=0; $ac<$nr_linii_AllowanceCharge; $ac++) {

                        $indicator =   (string) @$cac->InvoiceLine[$i]->children($cac_namespace)->Price->children($cac_namespace)->AllowanceCharge[$ac]->children($cbc_namespace)->ChargeIndicator;

                        //BT-147 Reducere/taxa suplimentara la pretul articolului
                        $invoice['Invoice_Line'][$i]['Deduceri']['Reducere_taxa_suplimentara_la_pretul_articolului'] = (string) @$cac->InvoiceLine[$i]->children($cac_namespace)->Price->children($cac_namespace)->AllowanceCharge[$ac]->children($cbc_namespace)->Amount ;

                        //BT-148 Pretul brut al articolului
                        $invoice['Invoice_Line'][$i]['Deduceri']['Pretul_brut_al_articolului'] = (string) @$cac->InvoiceLine[$i]->children($cac_namespace)->Price->children($cac_namespace)->AllowanceCharge[$ac]->children($cbc_namespace)->BaseAmount ;
                    }
                }

            }

        }

        return $invoice;
    }

}

