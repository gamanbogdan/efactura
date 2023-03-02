@extends('admin.main-layout')




        
@section('content-header')
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Dashboard</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
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
        <div class="callout callout-info">
        <h5><i class="fas fa-info"></i> Note:</h5>
        This page has been enhanced for printing. Click the print button at the bottom of the invoice to test.
        </div>


        <!-- Main content -->
        <div class="invoice p-3 mb-3">
        <!-- title row -->
        <div class="row">
            <div class="col-12">
            <h4>
                <i class="fas fa-globe"></i> AdminLTE, Inc.
                <small class="float-right">Date: 2/10/2014</small>
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
                <b>Data emitere:</b> {{ $factura->Informatii_factura_Data_scadenta_factura }}  <br>
            @endif

            @if($factura->Informatii_factura_Referinta_contractului)
                <b>Referinta contractului:</b> {{ $factura->Informatii_factura_Referinta_contractului }}  <br>
            @endif

            @if($factura->Informatii_factura_Referinta_proiectului)
                <b>Referinta proiectului:</b> {{ $factura->Informatii_factura_Referinta_proiectului }}  <br>
            @endif



            <b>Account:</b> 968-34567
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
            <table class="table table-striped">
                            
                        
                <thead>
                    <tr>
                        <th>Nr.</th>
                        <th>Product</th>                       
                        <th>UM</th>
                        <th>Cantitate</th>
                        <th>Pret unitar</th>
                        <th>Valoare</th>
                        <th>Cota de TVA</th>

                    </tr>
                </thead>
                <tbody>

                    @foreach( $factura->EfacturaInvoiceLine  as $indexKey => $line )

                        
                        <tr>
                            <td>{{ ++$indexKey }}</td>
                            <td>{{ $line->Nume_articol  }}</td>
                            
                            <td>{{ $line->UM  }}</td>
                            <td>{{ $line->Cantitate_facturata  }}</td>
                            <td>{{ $line->Pretul_net_al_articolului  }}</td>
                            <td>{{ $line->Valoarea_neta_a_liniei  }}</td>
                            <td>{{ $line->Cota_de_TVA ?: '0' }}%</td>
                        </tr>

                    @endforeach

                </tbody>




            </table>
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->

        <div class="row">
            <!-- accepted payments column -->
            <div class="col-6">
            <p class="lead">Payment Methods:</p>
            <img src="../../dist/img/credit/visa.png" alt="Visa">
            <img src="../../dist/img/credit/mastercard.png" alt="Mastercard">
            <img src="../../dist/img/credit/american-express.png" alt="American Express">
            <img src="../../dist/img/credit/paypal2.png" alt="Paypal">

            <p class="text-muted well well-sm shadow-none" style="margin-top: 10px;">
                Etsy doostang zoodles disqus groupon greplin oooj voxy zoodles, weebly ning heekya handango imeem
                plugg
                dopplr jibjab, movity jajah plickers sifteo edmodo ifttt zimbra.
            </p>
            </div>
            <!-- /.col -->
            <div class="col-6">
            <p class="lead">Amount Due 2/22/2014</p>

            <div class="table-responsive">
                <table class="table">
                <tr>
                    <th style="width:50%">Totalurile_documentului_Suma_valorilor_nete_ale_liniilor_facturii:</th>
                    <td>{{ $factura->Totalurile_documentului_Suma_valorilor_nete_ale_liniilor_facturii }}</td>
                </tr>

                
                <tr>
                    <th style="width:50%">Totalurile_documentului_Suma_de_plata:</th>
                    <td>{{ $factura->Totalurile_documentului_Suma_de_plata }}</td>
                </tr>

                <tr>
                    <th style="width:50%">Totalurile_documentului_Valoarea_totala_a_facturii_fara_TVA:</th>
                    <td>{{ $factura->Totalurile_documentului_Valoarea_totala_a_facturii_fara_TVA }}</td>
                </tr>
                
                <tr>
                    <th>Totaluri_tva_Valoarea_totala_a_TVA_a_facturii</th>
                    <td>{{ $factura->Totaluri_tva_Valoarea_totala_a_TVA_a_facturii }}</td>
                </tr>
                <tr>
                    <th>Totalurile_documentului_Valoarea_totala_a_facturii_cu_TVA:</th>
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
            <a href="invoice-print.html" rel="noopener" target="_blank" class="btn btn-default"><i class="fas fa-print"></i> Print</a>
            <button type="button" class="btn btn-success float-right"><i class="far fa-credit-card"></i> Submit
                Payment
            </button>
            <button type="button" class="btn btn-primary float-right" style="margin-right: 5px;">
                <i class="fas fa-download"></i> Generate PDF
            </button>
            @php $onclick = "window.open('".route('efactura.pdf_anaf', $factura->id)."', '_blank', 'location=yes,height=670,width=720,scrollbars=yes,status=yes');"; @endphp
            <button type="button" class="btn btn-primary float-right" style="margin-right: 5px;" onclick="{{ $onclick }}">
                <i class="fas fa-download"></i> PDF ANAF  
            </button>
            </div>
        </div>
        </div>
        <!-- /.invoice -->
    </div><!-- /.col -->
</div><!-- /.row -->

@endsection

        