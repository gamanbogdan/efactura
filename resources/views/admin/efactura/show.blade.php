@extends('admin.main-layout')




        
@section('content-header')
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Efactura</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Vizualizare</a></li>
              <li class="breadcrumb-item active"> Vizualizare factura {{ $factura->Informatii_factura_Nr_factura }}</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
@endsection 




@section('body')    

<div class="row">
    <div class="col-12">
        <div class="callout callout-info  no-print">
            <div class="row"> 
                
                <div class="col-10"> 
                    <form action="{{ route('efactura.update', $factura->id) }}" method="POST" enctype="multipart/form-data" class="form-inline">
                        @csrf
                        @method('PUT')
                        <div class="form-group mr-4">
                                   
                            <input type="text" name="comment_fcn" value="{{ $factura->comment_fcn }}" class="form-control" placeholder="Comentariu ..." id="comment_fcn">
                        </div>



                        <div class="form-group mr-4">
                            <h5 class="mr-4 mb-0"> Apartine FCN: </h5>
                            <div class="form-check form-check-inline">
                                <label for="is_fcn_da" class="form-check-label mr-1"> Da </label>
                                <input type="radio" name="is_fcn" value="1" id="is_fcn_da" class="form-check-input" {{  ($factura->is_fcn === '1' ? ' checked' : '') }} >
                            </div>

                            <div class="form-check form-check-inline">
                                <label for="is_fcn_nu" class="form-check-label mr-1"> Nu </label>
                                <input type="radio" name="is_fcn" value="0" id="is_fcn_nu" class="form-check-input" {{  ($factura->is_fcn === '0' ? ' checked' : '') }} > 
                            </div>
                        
                        </div>



                        <div class="form-group">
                            <button type="submit" class="btn btn-primary ml-3">Editeaza</button>
                        </div>  
                        
                        
                        @if(session('status'))
                            <div class="text-success mb-1 mt-1 ml-2">
                                {{ session('status') }}
                            </div>
                        @endif


                    </form>
                </div>
                <div class="col-2 float-right">
                    @php $onclick = "window.open('".route('efactura.pdf_anaf', $factura->invoice_path_id)."', '_blank', 'location=yes,height=670,width=720,scrollbars=yes,status=yes');"; @endphp
                    <div class="float-left" ><i class="fas fa-file-pdf mr-2"> </i> <a href="#" onclick="{{ $onclick }}" class="text-primary"> Factura Anaf </a> </div> <br>
                    <div class="float-left" ><i class="fas fa-download mr-2"> </i> <a href="{{ route('efactura.semnatura_anaf', $factura->invoice_path_id) }}" class="text-primary">Semnatura </a> </div>
                </div>
            </div>

        </div>


        <!-- Main content -->
        <div class="invoice p-3 mb-3">
            <!-- title row -->
            <div class="row">
                <div class="col-12">
                <h4>
                    
                    <small class="float-right">Data: {{ date('d/m/Y') }} </small>
                </h4>
                </div>
                <!-- /.col -->
            </div>
            <!-- info row -->
            <div class="row invoice-info">
                <div class="col-sm-4 invoice-col">
                    Furnizor
                    <address>
                        <strong>{{ $factura->Vanzator_Nume }}</strong><br>
                        Cui: {{ $factura->Vanzator_Identificatorul_de_TVA }} <br>
                        Reg com: {{ $factura->Vanzator_Identificatorul_de_inregistrare_legala }} <br>
                        Adresa: {{ $factura->Vanzator_Adresa_Strada }} {{ $factura->Vanzator_Adresa_Informatii_suplimentare_strada }} 
                        {{ $factura->Vanzator_Adresa_Cod_Postal }}
                        <br>
                        {{ $factura->Vanzator_Adresa_Oras }} <br>
                        {{ $factura->Vanzator_Adresa_Subdiviziunea }}<br>
                        @if($factura->Vanzator_Informatii_juridice_suplimentare)
                            @php $Vanzator_Informatii_juridice_suplimentare =  explode('#', $factura->Vanzator_Informatii_juridice_suplimentare) @endphp
                            
                            @foreach( $Vanzator_Informatii_juridice_suplimentare  as $Vanzator_Informatie_juridica_suplimentara ) 
                                {{ $Vanzator_Informatie_juridica_suplimentara }} <br>
                            @endforeach
                        @endif
                        Telefon: {{ $factura->Vanzator_Telefon_persoana_de_contact }}<br>
                        Email: {{ $factura->Vanzator_E_mail_persoana_de_contact }}
                    </address>
                </div>

                <!-- /.col -->
                <div class="col-sm-4 invoice-col">
                <b>FACTURA #{{ $factura->Informatii_factura_Nr_factura }}</b><br>
                <br>
                @if($factura->Informatii_factura_Referinta_comenzii)
                    <b>Referinta comanda:</b> {{ $factura->Informatii_factura_Referinta_comenzii }}  <br>
                @endif

                @if($factura->Informatii_factura_Data_emitere_factura)
                    <b>Data emitere:</b> {{ $factura->Informatii_factura_Data_emitere_factura }}  <br>
                @endif

                @if($factura->Informatii_factura_Data_scadenta_factura)
                    <b>Data scadenta:</b> {{ $factura->Informatii_factura_Data_scadenta_factura }}  <br>
                @endif

                @if($factura->Informatii_factura_Referinta_contractului)
                    <b>Referinta contractului:</b> {{ $factura->Informatii_factura_Referinta_contractului }}  <br>
                @endif

                @if($factura->Informatii_factura_Referinta_proiectului)
                    <b>Referinta proiectului:</b> {{ $factura->Informatii_factura_Referinta_proiectului }}  <br>
                @endif



                
                </div>
                <!-- /.col -->


                <!-- /.col -->
                <div class="col-sm-4 invoice-col">
                    Client
                    <address>
                        <strong>{{ $factura->Cumparator_Nume }}</strong><br>
                        Cui: {{ $factura->Cumparator_Identificatorul_de_TVA }} <br>
                        Reg com: {{ $factura->Cumparator_Identificatorul_de_inregistrare_legala }} <br>
                        Adresa: {{ $factura->Cumparator_Adresa_Strada }} {{ $factura->Cumparator_Adresa_Informatii_suplimentare_strada }} 
                        {{ $factura->Cumparator_Adresa_Cod_Postal }}
                        <br>
                        {{ $factura->Cumparator_Adresa_Oras }} <br>
                        {{ $factura->CumparatorVanzator_Adresa_Subdiviziunea }}<br>
                        @if($factura->Cumparator_Informatii_juridice_suplimentare)
                            @php $Cumparator_Informatii_juridice_suplimentare =  explode('#', $factura->Cumparator_Informatii_juridice_suplimentare) @endphp
                            
                            @foreach( $Cumparator_Informatii_juridice_suplimentare  as $Cumparator_Informatie_juridica_suplimentara ) 
                                {{ $Cumparator_Informatie_juridica_suplimentara }} <br>
                            @endforeach
                        @endif
                        Telefon: {{ $factura->Cumparator_Telefon_persoana_de_contact }}<br>
                        Email: {{ $factura->Cumparator_E_mail_persoana_de_contact }}
                    </address>
                </div>

            </div>
            <!-- /.row -->



            <!-- Table row -->
            <div class="row">
                <div class="col-12 table-responsive">
                <table class="table table-striped  ">
                                
                            
                    <thead>
                        <tr>
                            <th>Nr.</th>
                            <th>Product</th>                       
                            <th>UM</th>
                            <th>Cantitate</th>
                            <th>Pret unitar</th>
                            <th>Valoare</th>
                            <th>Cota de TVA</th>
                            <th>TVA</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach( $factura->EfacturaInvoiceLine  as $indexKey => $line )

                            
                            <tr>
                                <td>{{ ++$indexKey }}</td>
                                <td>{{ $line->Nume_articol  }}</td>
                                
                                <td>{{ $line->UnitateMasura->denumire  }}</td>
                                <td>{{ $line->Cantitate_facturata  }}</td>
                                <td>{{ $line->Pretul_net_al_articolului  }}</td>
                                <td>{{ $line->Valoarea_neta_a_liniei  }}</td>
                                <td>{{ $line->Cota_de_TVA}}% </td>
                                <td>{{ ($line->Cota_de_TVA*$line->Valoarea_neta_a_liniei)/100}} </td>
                            </tr>

                            
                            @if($line->Taxa_suplimentara_Codul_motivului_taxei_suplimentare)
                            <tr>
                                <td colspan='8'>
                                {{ $line->Taxa_suplimentara_Codul_motivului_taxei_suplimentare  }}
                                {{ $line->Taxa_suplimentara_Motiv_taxa_suplimentara  }}
                                {{ $line->Taxa_suplimentara_Procentajul_taxei_suplimentare  }}
                                {{ $line->Taxa_suplimentara_Valoarea_taxei_suplimentare  }}
                                {{ $line->Taxa_suplimentara_Valoarea_de_baza_a_taxei_suplimentare  }} 
                                </td>
                            </tr>                               
                            @endif

                            @if($line->Deducere_Codul_motivului_deducerii)
                            <tr>
                                <td colspan='8'>
                                    Deducere:
                                    {{ $line->Deducere_Motiv_deducere  }}
                                    | Procent: {{ $line->Deducere_Procentajul_deducerii  }}
                                    | Valoare: {{ $line->Deducere_Valoarea_deducerii  }}
                                    | Valoare de baza: {{ $line->Deducere_Valoarea_de_baza_a_deducerii  }}  
                                    </td>
                            </tr>                             
                            @endif                           
 

                        @endforeach

                    </tbody>




                </table>
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->

            @if(count($factura->EfacturaInvoiceComments))
            <div class="row invoice-info">
                <div class="col-sm-12 invoice-col">
                <p class="lead my-1">Nota:</p>
                    <ul>
                        @foreach( $factura->EfacturaInvoiceComments  as $indexKey => $note )                                                
                            <li>{{ $note->Nota  }}</li>                                           
                        @endforeach
                    </ul>
                </div>        
            </div>  
            @endif 

            <div class="row">
                <!-- accepted payments column -->
                <div class="col-6">
                    <p class="lead my-1">Instructiuni de plata:</p>

                     @php $inst=false @endphp
                        @if($factura->Instructiuni_de_plata_Codul_tipului_instrumentului_de_plata)
                            <b>Tip plata:</b> {{ $factura->TipInstrumentPlata->denumire }}  <br>
                            @php $inst=true @endphp
                        @endif
                        
                        @if($factura->Instructiuni_de_plata_Nota_privind_instrumentul_de_plata)
                            <b>Nota privind instrumentul de plata:</b> {{ $factura->Instructiuni_de_plata_Nota_privind_instrumentul_de_plata }}  <br>
                            @php $inst=true @endphp
                        @endif

                        @if($factura->Instructiuni_de_plata_Aviz_de_plata)
                            <b>Aviz de plata:</b> {{ $factura->Instructiuni_de_plata_Aviz_de_plata }}  <br>
                            @php $inst=true @endphp
                        @endif

                        @if($factura->Instructiuni_de_plata_Numarul_contului_principal_al_cardului_de_plata)
                            <b>Numarul contului principal al cardului de plata:</b> {{ $factura->Instructiuni_de_plata_Numarul_contului_principal_al_cardului_de_plata }}  <br>
                            @php $inst=true @endphp
                        @endif

                        @if($factura->Instructiuni_de_plata_Numele_detinatorului_cardului_de_plata)
                            <b>Numele detinatorului cardului de plata:</b> {{ $factura->Instructiuni_de_plata_Numele_detinatorului_cardului_de_plata }}  <br>
                            @php $inst=true @endphp
                        @endif

                        @if($factura->Instructiuni_de_plata_Identificatorul_contului_de_plata)
                            <b>Cont:</b> {{ $factura->Instructiuni_de_plata_Identificatorul_contului_de_plata }}  <br>
                            @php $inst=true @endphp
                        @endif

                        @if($factura->Instructiuni_de_plata_Numele_contului_de_plata)
                            <b>Numele contului:</b> {{ $factura->Instructiuni_de_plata_Numele_contului_de_plata }}  <br>
                            @php $inst=true @endphp
                        @endif

                        @if($factura->Instructiuni_de_plata_Identificatorul_furnizorului_de_servicii_de_plata)
                            <b>Banca:</b> {{ $factura->Instructiuni_de_plata_Identificatorul_furnizorului_de_servicii_de_plata }}  <br>
                            @php $inst=true @endphp
                        @endif

                        @if($factura->Instructiuni_de_plata_Debitare_directa_Identificatorul_referintei_mandatului)
                            <b>Debitare directa Identificatorul referintei mandatului:</b> {{ $factura->Instructiuni_de_plata_Debitare_directa_Identificatorul_referintei_mandatului }}  <br>
                            @php $inst=true @endphp
                        @endif

                        @if($factura->Instructiuni_de_plata_Debitare_directa_Identificatorul_contului_debitat)
                            <b>Debitare directa Identificatorul contului debitat:</b> {{ $factura->Instructiuni_de_plata_Debitare_directa_Identificatorul_contului_debitat }}  <br>
                            @php $inst=true @endphp
                        @endif

                        @if($inst)  @endif
                        


                    <p class="lead my-1" >Livrare:</p>

                    @if($factura->Informatii_referitoare_la_livrare_Data_reala_a_livrarii)
                        <b>Data reala a livrarii:</b> {{ $factura->Informatii_referitoare_la_livrare_Data_reala_a_livrarii }}  <br>
                    @endif

                    @if($factura->Informatii_referitoare_la_livrare_Numele_partii_catre_care_se_face_livrarea)
                        <b>Numele partii catre care se face livrarea:</b> {{ $factura->Informatii_referitoare_la_livrare_Numele_partii_catre_care_se_face_livrarea }}  <br>
                    @endif

                    @if($factura->Informatii_referitoare_la_livrare_Locatie_Identificatorul_locului)
                        <b>Locatie Identificatorul locului:</b> {{ $factura->Informatii_referitoare_la_livrare_Locatie_Identificatorul_locului }}  <br>
                    @endif

                    @if($factura->Informatii_referitoare_la_livrare_Locatie_Identificatorul_schemei)
                        <b>Locatie Identificatorul schemei:</b> {{ $factura->Informatii_referitoare_la_livrare_Locatie_Identificatorul_schemei }}  <br>
                    @endif

                    @if($factura->Informatii_referitoare_la_livrare_Locatie_Adresa_Strada)
                        <b>Strada:</b> {{ $factura->Informatii_referitoare_la_livrare_Locatie_Adresa_Strada }}  <br>
                    @endif

                    @if($factura->Informatii_referitoare_la_livrare_Locatie_Adresa_Informatii_suplimentare_strada)
                        <b>Informatii suplimentare strada:</b> {{ $factura->Informatii_referitoare_la_livrare_Locatie_Adresa_Informatii_suplimentare_strada }}  <br>
                    @endif

                    @if($factura->Informatii_referitoare_la_livrare_Locatie_Adresa_Informatii_suplimentare_adresa)
                        <b>Informatii suplimentare adresa:</b> {{ $factura->Informatii_referitoare_la_livrare_Locatie_Adresa_Informatii_suplimentare_adresa }}  <br>
                    @endif
            
                    @if($factura->Informatii_referitoare_la_livrare_Locatie_Adresa_Oras)
                        <b>Oras:</b> {{ $factura->Informatii_referitoare_la_livrare_Locatie_Adresa_Oras }}  <br>
                    @endif

                    @if($factura->Informatii_referitoare_la_livrare_Locatie_Adresa_Cod_Postal)
                        <b>Cod Postal:</b> {{ $factura->Informatii_referitoare_la_livrare_Locatie_Adresa_Cod_Postal }}  <br>
                    @endif

                    @if($factura->Informatii_referitoare_la_livrare_Locatie_Adresa_Subdiviziunea_tarii)
                        <b>Subdiviziunea tarii:</b> {{ $factura->Informatii_referitoare_la_livrare_Locatie_Adresa_Subdiviziunea_tarii }}  <br>
                    @endif

                    @if($factura->Informatii_referitoare_la_livrare_Locatie_Adresa_Tara)
                        <b>Tara:</b> {{ $factura->Informatii_referitoare_la_livrare_Locatie_Adresa_Tara }}  <br>
                    @endif
                    
                </div>
                <!-- /.col -->


                <div class="col-6">
                <p class="lead">Totaluri document</p>

                <div class="table-responsive">
                    <table class="table">
                    <tr>
                        <th >Suma valorilor nete ale liniilor facturii:</th>
                        <td>{{ $factura->Totalurile_documentului_Suma_valorilor_nete_ale_liniilor_facturii }}</td>
                    </tr>

                    
                    <tr>
                        <th >Suma de plata:</th>
                        <td>{{ $factura->Totalurile_documentului_Suma_de_plata }}</td>
                    </tr>

                    <tr>
                        <th>Valoarea totala a facturii fara TVA:</th>
                        <td>{{ $factura->Totalurile_documentului_Valoarea_totala_a_facturii_fara_TVA }}</td>
                    </tr>
                    
                    <tr>
                        <th>Valoarea totala a TVA a facturii</th>
                        <td>{{ $factura->Totaluri_tva_Valoarea_totala_a_TVA_a_facturii }}</td>
                    </tr>
                    <tr>
                        <th>Valoarea totala a facturii cu TVA:</th>
                        <td>{{ $factura->Totalurile_documentului_Valoarea_totala_a_facturii_cu_TVA }} </td>
                    </tr>

                    </table>
                </div>
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->

            <!-- this row will not appear when printing -->
            <div class="row no-print">
                <div class="col-12">
                    <button type="button" rel="noopener"  class="btn btn-primary float-right mr-2" id="print-window"><i class="fas fa-print"></i> Print</button>                    
                </div>
            </div>
        </div>
        <!-- /.invoice -->
    </div><!-- /.col -->
</div><!-- /.row -->

@endsection

@section('js')
<script>
$(document).ready( function () {
    $('#print-window').click(function() {
        window.print();
    });
});
</script>
@endsection