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

                <div class="col-1">
                    <a href="{{ route('efactura.index') }}" class="btn btn-primary text-white" style="text-decoration: none"> <i class="fas fa-arrow-left"></i> Inapoi</a>

                </div>

                <div class="col-10  float-center">
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
                <div class="col-1 float-right">
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

                <!-- furnizor -->
                <div class="col-sm-4 invoice-col">
                    Furnizor
                    <address>
                        <strong>{{ $factura->Vanzator_Nume }}</strong><br>
                        Cui: {{ $factura->Vanzator_Identificatorul_de_TVA }} <br>
                        Reg com: {{ $factura->Vanzator_Identificatorul_de_inregistrare_legala }} <br>
                        Adresa: {{ $factura->Vanzator_Adresa_Strada }} {{ $factura->Vanzator_Adresa_Informatii_suplimentare_strada }}
                        {{ $factura->Vanzator_Adresa_Cod_Postal }}
                        <br>
                        {{ $factura->Vanzator_Adresa_Oras }}, {{ $factura->Vanzator_Adresa_Subdiviziunea_tarii }}<br>

                        @if($factura->Vanzator_Informatii_juridice_suplimentare)
                            @php $Vanzator_Informatii_juridice_suplimentare =  explode('#', $factura->Vanzator_Informatii_juridice_suplimentare) @endphp

                            @foreach( $Vanzator_Informatii_juridice_suplimentare  as $Vanzator_Informatie_juridica_suplimentara )
                                {{ $Vanzator_Informatie_juridica_suplimentara }} <br>
                            @endforeach
                        @endif
                        Telefon: {{ $factura->Vanzator_Telefon_persoana_de_contact }}<br>
                        Email: {{ $factura->Vanzator_E_mail_persoana_de_contact }} <br>
                        @if($factura->Vanzator_Persoana_de_contact)
                            Persoana contact: {{ $factura->Vanzator_Persoana_de_contact }}

                        @endif
                    </address>
                </div>

                <!-- informatii factura -->
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

                    @if($factura->Informatii_factura_Referinta_comenzii)
                        <b>Referinta comenzii:</b> {{ $factura->Informatii_factura_Referinta_comenzii }}  <br>
                    @endif

                    @if($factura->Informatii_factura_Referinta_dispozitiei_de_vanzare)
                        <b>Referinta dispozitiei de vanzare:</b> {{ $factura->Informatii_factura_Referinta_dispozitiei_de_vanzare }}  <br>
                    @endif

                    @if($factura->Informatii_factura_Referinta_la_o_factura_anterioara)
                        <b>Referinta la o factura anterioara:</b> {{ $factura->Informatii_factura_Referinta_la_o_factura_anterioara }}  <br>
                    @endif

                    @if($factura->Informatii_factura_Data_de_emitere_a_facturii_anterioare)
                        <b>Data de emitere a facturii anterioare:</b> {{ $factura->Informatii_factura_Data_de_emitere_a_facturii_anterioare }}  <br>
                    @endif

                    @if($factura->Informatii_factura_Referinta_avizului_de_expeditie)
                        <b>Referinta avizului de expeditie:</b> {{ $factura->Informatii_factura_Referinta_avizului_de_expeditie }}  <br>
                    @endif

                    @if($factura->Informatii_factura_Referinta_avizului_de_receptie)
                        <b>Referinta avizului de receptie:</b> {{ $factura->Informatii_factura_Referinta_avizului_de_receptie }}  <br>
                    @endif

                    @if($factura->Informatii_factura_Referinta_cererii_de_oferta_sau_a_lotului)
                        <b>Referinta cererii de oferta sau a lotului:</b> {{ $factura->Informatii_factura_Referinta_cererii_de_oferta_sau_a_lotului }}  <br>
                    @endif


                    @if($factura->Termeni_de_plata_Nota)
                        <b>Termeni de plata:</b> {{ $factura->Termeni_de_plata_Nota }}  <br>
                    @endif

                    @if($factura->Informatii_factura_Data_de_inceput_a_perioadei_de_facturare)
                        <b>Inceput perioada facturare:</b> {{ $factura->Informatii_factura_Data_de_inceput_a_perioadei_de_facturare }}  <br>
                    @endif

                    @if($factura->Informatii_factura_Data_de_sfarsit_a_perioadei_de_facturare)
                        <b>Sfarsit perioada facturare:</b> {{ $factura->Informatii_factura_Data_de_sfarsit_a_perioadei_de_facturare }}  <br>
                    @endif



                </div>
                <!-- /.col -->


                <!-- client -->
                <div class="col-sm-4 invoice-col">
                    Client
                    <address>
                        <strong>{{ $factura->Cumparator_Nume }}</strong><br>
                        Cui: {{ $factura->Cumparator_Identificatorul_de_TVA }} <br>
                        Reg com: {{ $factura->Cumparator_Identificatorul_de_inregistrare_legala }} <br>
                        Adresa: {{ $factura->Cumparator_Adresa_Strada }} {{ $factura->Cumparator_Adresa_Informatii_suplimentare_strada }}
                        {{ $factura->Cumparator_Adresa_Cod_Postal }}
                        <br>
                        {{ $factura->Cumparator_Adresa_Oras }}, {{ $factura->Cumparator_Adresa_Subdiviziunea_tarii }}<br>
                        @if($factura->Cumparator_Informatii_juridice_suplimentare)
                            @php $Cumparator_Informatii_juridice_suplimentare =  explode('#', $factura->Cumparator_Informatii_juridice_suplimentare) @endphp

                            @foreach( $Cumparator_Informatii_juridice_suplimentare  as $Cumparator_Informatie_juridica_suplimentara )
                                {{ $Cumparator_Informatie_juridica_suplimentara }} <br>
                            @endforeach
                        @endif
                        Telefon: {{ $factura->Cumparator_Telefon_persoana_de_contact }}<br>
                        Email: {{ $factura->Cumparator_E_mail_persoana_de_contact }}
                        @if($factura->Cumparator_Persoana_de_contact)
                        Persoana contact: {{ $factura->Cumparator_Persoana_de_contact }}

                         @endif

                        @if($factura->Informatii_factura_Referinta_cumparatorului)
                            Referinta cumparator: {{ $factura->Informatii_factura_Referinta_cumparatorului }}
                        @endif

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

            <!-- nota factura -->
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

                <div class="col-6">

                    <!-- beneficiar -->
                    @if($factura->EfacturaInvoicePayeeParty)

                        <p class="lead my-1">Beneficiar:</p>

                        @if($factura->EfacturaInvoicePayeeParty->Identificator)
                            <b>Identificator:</b> {{ $factura->EfacturaInvoicePayeeParty->Identificator }}  <br>
                        @endif

                        @if($factura->EfacturaInvoicePayeeParty->Identificator_Identificatorul_schemei)
                            <b>Identificatorul schemei:</b> {{ $factura->EfacturaInvoicePayeeParty->Identificator_Identificatorul_schemei }}  <br>
                        @endif

                        @if($factura->EfacturaInvoicePayeeParty->Nume_beneficiar)
                            <b>Nume Beneficiar:</b> {{ $factura->EfacturaInvoicePayeeParty->Nume_beneficiar }}  <br>
                        @endif

                        @if($factura->EfacturaInvoicePayeeParty->Identificatorul_de_inregistrare_legala)
                            <b>Identificatorul de inregistrare legala:</b> {{ $factura->EfacturaInvoicePayeeParty->Identificatorul_de_inregistrare_legala }}  <br>
                        @endif

                        @if($factura->EfacturaInvoicePayeeParty->Identificatorul_de_inregistrare_legala_Identificatorul_schemei)
                            <b>Identificatorul schemei:</b> {{ $factura->EfacturaInvoicePayeeParty->Identificatorul_de_inregistrare_legala_Identificatorul_schemei }}  <br>
                        @endif

                    @endif



                    <!-- reprezentantul fiscal al vanzatorului  -->
                    @if($factura->EfacturaInvoiceTaxRepresentativeParty)

                        <p class="lead my-1">Reprezentantul fiscal al vanzatorului:</p>

                        @if($factura->EfacturaInvoiceTaxRepresentativeParty->Nume)
                            <b>Nume:</b> {{ $factura->EfacturaInvoiceTaxRepresentativeParty->Nume }}  <br>
                        @endif

                        @if($factura->EfacturaInvoiceTaxRepresentativeParty->Adresa_Strada)
                            <b>Strada:</b> {{ $factura->EfacturaInvoiceTaxRepresentativeParty->Adresa_Strada }}  <br>
                        @endif

                        @if($factura->EfacturaInvoiceTaxRepresentativeParty->Adresa_Informatii_suplimentare_strada)
                            <b>Informatii suplimentare strada:</b> {{ $factura->EfacturaInvoiceTaxRepresentativeParty->Adresa_Informatii_suplimentare_strada }}  <br>
                        @endif

                        @if($factura->EfacturaInvoiceTaxRepresentativeParty->Adresa_Oras)
                            <b>Oras:</b> {{ $factura->EfacturaInvoiceTaxRepresentativeParty->Adresa_Oras }}  <br>
                        @endif

                        @if($factura->EfacturaInvoiceTaxRepresentativeParty->Adresa_Cod_Postal)
                            <b>Cod Postal:</b> {{ $factura->EfacturaInvoiceTaxRepresentativeParty->Adresa_Cod_Postal }}  <br>
                        @endif

                        @if($factura->EfacturaInvoiceTaxRepresentativeParty->Adresa_Subdiviziunea_tarii)
                            <b>Subdiviziunea tarii:</b> {{ $factura->EfacturaInvoiceTaxRepresentativeParty->Adresa_Subdiviziunea_tarii }}  <br>
                        @endif

                        @if($factura->EfacturaInvoiceTaxRepresentativeParty->Adresa_Tara)
                            <b>Tara:</b> {{ $factura->EfacturaInvoiceTaxRepresentativeParty->Adresa_Tara }}  <br>
                        @endif

                        @if($factura->EfacturaInvoiceTaxRepresentativeParty->Adresa_Informatii_suplimentare_adresa)
                            <b>Informatii suplimentare adresa:</b> {{ $factura->EfacturaInvoiceTaxRepresentativeParty->Adresa_Informatii_suplimentare_adresa }}  <br>
                        @endif

                        @if($factura->EfacturaInvoiceTaxRepresentativeParty->Identificatorul_de_TVA)
                            <b>Identificatorul de TVA:</b> {{ $factura->EfacturaInvoiceTaxRepresentativeParty->Identificatorul_de_TVA }}  <br>
                        @endif

                    @endif



                    <!-- instructiuni de plata -->
                    @if($factura->EfacturaInvoicePaymentMeans)

                        @foreach($factura->EfacturaInvoicePaymentMeans as $indexKey => $paymentMeans)
                            <p class="lead my-1">Instructiuni de plata @if( count($factura->EfacturaInvoicePaymentMeans)>1 )  {{  $indexKey+1 }}  @endif:</p>

                            @if($paymentMeans->Codul_tipului_instrumentului_de_plata)
                                <b>Tip plata:</b> {{ $paymentMeans->TipInstrumentPlata->denumire }}  <br>
                            @endif

                            @if($paymentMeans->Nota_privind_instrumentul_de_plata)
                                <b>Nota privind instrumentul de plata:</b> {{ $paymentMeans->Nota_privind_instrumentul_de_plata }}  <br>
                            @endif

                            @if($paymentMeans->Aviz_de_plata)
                                <b>Aviz de plata:</b> {{ $paymentMeans->Aviz_de_plata }}  <br>
                            @endif

                            @if($paymentMeans->Numarul_contului_principal_al_cardului_de_plata)
                                <b>Numarul contului principal al cardului de plata:</b> {{ $paymentMeans->Numarul_contului_principal_al_cardului_de_plata }}  <br>
                            @endif

                            @if($paymentMeans->Numele_detinatorului_cardului_de_plata)
                                <b>Numele detinatorului cardului de plata:</b> {{ $paymentMeans->Numele_detinatorului_cardului_de_plata }}  <br>
                            @endif

                            @if($paymentMeans->Identificatorul_contului_de_plata)
                                <b>Cont:</b> {{ $paymentMeans->Identificatorul_contului_de_plata }}  <br>
                            @endif

                            @if($paymentMeans->Numele_contului_de_plata)
                                <b>Numele contului:</b> {{ $paymentMeans->Numele_contului_de_plata }}  <br>
                            @endif

                            @if($paymentMeans->Identificatorul_furnizorului_de_servicii_de_plata)
                                <b>Banca:</b> {{ $paymentMeans->Identificatorul_furnizorului_de_servicii_de_plata }}  <br>
                            @endif

                            @if($paymentMeans->Debitare_directa_Identificatorul_referintei_mandatului)
                                <b>Debitare directa Identificatorul referintei mandatului:</b> {{ $paymentMeans->Debitare_directa_Identificatorul_referintei_mandatului }}  <br>
                            @endif

                            @if($paymentMeans->Debitare_directa_Identificatorul_contului_debitat)
                                <b>Debitare directa Identificatorul contului debitat:</b> {{ $paymentMeans->Debitare_directa_Identificatorul_contului_debitat }}  <br>
                            @endif
                        @endforeach

                    @endif

                    <!-- instructiuni de livrare -->
                    @if($factura->EfacturaInvoiceDelivery)
                        <p class="lead my-1" >Livrare:</p>

                        @if($factura->EfacturaInvoiceDelivery->Data_reala_a_livrarii)
                            <b>Data reala a livrarii:</b> {{ $factura->EfacturaInvoiceDelivery->Data_reala_a_livrarii }}  <br>
                        @endif

                        @if($factura->EfacturaInvoiceDelivery->Numele_partii_catre_care_se_face_livrarea)
                            <b>Numele partii catre care se face livrarea:</b> {{ $factura->EfacturaInvoiceDelivery->Numele_partii_catre_care_se_face_livrarea }}  <br>
                        @endif

                        @if($factura->EfacturaInvoiceDelivery->Locatie_Identificatorul_locului)
                            <b>Locatie Identificatorul locului:</b> {{ $factura->EfacturaInvoiceDelivery->Locatie_Identificatorul_locului }}  <br>
                        @endif

                        @if($factura->EfacturaInvoiceDelivery->Locatie_Identificatorul_schemei)
                            <b>Locatie Identificatorul schemei:</b> {{ $factura->EfacturaInvoiceDelivery->Locatie_Identificatorul_schemei }}  <br>
                        @endif

                        @if($factura->EfacturaInvoiceDelivery->Locatie_Adresa_Strada)
                            <b>Strada:</b> {{ $factura->EfacturaInvoiceDelivery->Locatie_Adresa_Strada }}  <br>
                        @endif

                        @if($factura->EfacturaInvoiceDelivery->Locatie_Adresa_Informatii_suplimentare_strada)
                            <b>Informatii suplimentare strada:</b> {{ $factura->EfacturaInvoiceDelivery->Locatie_Adresa_Informatii_suplimentare_strada }}  <br>
                        @endif

                        @if($factura->EfacturaInvoiceDelivery->Locatie_Adresa_Informatii_suplimentare_adresa)
                            <b>Informatii suplimentare adresa:</b> {{ $factura->EfacturaInvoiceDelivery->Locatie_Adresa_Informatii_suplimentare_adresa }}  <br>
                        @endif

                        @if($factura->EfacturaInvoiceDelivery->Locatie_Adresa_Oras)
                            <b>Oras:</b> {{ $factura->EfacturaInvoiceDelivery->Locatie_Adresa_Oras }}  <br>
                        @endif

                        @if($factura->EfacturaInvoiceDelivery->Locatie_Adresa_Cod_Postal)
                            <b>Cod Postal:</b> {{ $factura->EfacturaInvoiceDelivery->Locatie_Adresa_Cod_Postal }}  <br>
                        @endif

                        @if($factura->EfacturaInvoiceDelivery->Locatie_Adresa_Subdiviziunea_tarii)
                            <b>Subdiviziunea tarii:</b> {{ $factura->EfacturaInvoiceDelivery->Locatie_Adresa_Subdiviziunea_tarii }}  <br>
                        @endif

                        @if($factura->EfacturaInvoiceDelivery->Locatie_Adresa_Tara)
                            <b>Tara:</b> {{ $factura->EfacturaInvoiceDelivery->Locatie_Adresa_Tara }}  <br>
                        @endif

                    @endif




                    <!-- BG-21 TAXA SUPLIMENTAREA  BG-20 DEDUCERE  -->
                    @if($factura->EfacturaInvoiceAllowanceCharge)
                        @foreach( $factura->EfacturaInvoiceAllowanceCharge  as $indexKey => $allowanceCharge )
                            @if($allowanceCharge->Indicator)
                                <p class="lead my-1" >Taxare suplimentara:</p>

                            @else
                                <p class="lead my-1" >Deducere:</p>
                            @endif

                            @if($allowanceCharge->Codul_motivului)
                                <b>Codul motivului:</b> {{ $allowanceCharge->Codul_motivului }}  <br>
                            @endif

                            @if($allowanceCharge->Motivul)
                                <b>Motivul:</b> {{ $allowanceCharge->Motivul }}  <br>
                            @endif

                            @if($allowanceCharge->Procent)
                                <b>Procent:</b> {{ $allowanceCharge->Procent }}  <br>
                            @endif

                            @if($allowanceCharge->Valoare)
                                <b>Valoare:</b> {{ $allowanceCharge->Valoare }}  <br>
                            @endif

                            @if($allowanceCharge->Codul_monedei_RON)
                                <b>Codul monedei :</b> {{ $allowanceCharge->Codul_monedei_RON }}  <br>
                            @endif

                            @if($allowanceCharge->Valoarea_de_baza)
                                <b>Valoarea de baza:</b> {{ $allowanceCharge->Valoarea_de_baza }}  <br>
                            @endif

                            @if($allowanceCharge->Codul_categoriei_de_TVA)
                                <b>Codul categoriei de TVA:</b> {{ $allowanceCharge->Codul_categoriei_de_TVA }}  <br>
                            @endif

                            @if($allowanceCharge->Cota_de_TVA)
                                <b>Cota de TVA:</b> {{ $allowanceCharge->Cota_de_TVA }}  <br>
                            @endif

                            @if($allowanceCharge->Identificatorul_schemei_VAT)
                                <b>Identificatorul schemei VAT:</b> {{ $allowanceCharge->Identificatorul_schemei_VAT }}  <br>
                            @endif

                        @endforeach

                    @endif

                </div>

                <!-- Totaluri document -->
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
