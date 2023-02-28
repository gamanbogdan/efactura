@extends('admin.main-layout')


@section('css')
  <!-- DataTables -->
  <link rel="stylesheet" href="{{ asset('/admin-assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('/admin-assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('/admin-assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
@endsection
       
        
        
        
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
              <li class="breadcrumb-item active">Efactura </li>
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

        @foreach ($invoice as $key_invoice_1 => $detalii_invoice_1)

        @if ( in_array( $key_invoice_1 ,['Informatii factura','Vanzator', 'Cumparator', 'Beneficiar', 'Reprezentantul fiscal al vanzatorului', 'Informatii referitoare la livrare',
        'Instructiuni de plata', 'Termeni de plata', 'Taxarea suplimentara',  'Deducere', 'Totaluri tva', 'Totalurile documentului', 'Produse'
        ]))
        <div class="card" >
            <div class="card-body">
                <h5 class="card-title">{{$key_invoice_1}}</h5>
                
                @foreach ($detalii_invoice_1 as $key_invoice_2 => $detalii_invoice_2)

                    @if (is_string($detalii_invoice_2))
                        <p class="card-text"> [ {{$key_invoice_1}} ] [ {{ $key_invoice_2 }} ] =>  {{$detalii_invoice_2}}</p>
                    @endif
                    
                    @if (is_array( $detalii_invoice_2))




                        @foreach ($detalii_invoice_2 as $key_invoice_3 => $detalii_invoice_3)

                            @if (is_string($detalii_invoice_3))
                            <p class="card-text"> [ {{$key_invoice_1}} ] [ {{ $key_invoice_2 }} ] [{{ $key_invoice_3 }} ] =>  {{$detalii_invoice_3}}</p>
                            @endif

                            @if (is_array( $detalii_invoice_3))

                                @foreach ($detalii_invoice_3 as $key_invoice_4 => $detalii_invoice_4)

                                    @if (is_string($detalii_invoice_4))
                                        <p class="card-text"> [ {{$key_invoice_1}} ] [ {{ $key_invoice_2 }} ] [{{ $key_invoice_3 }} ] [{{ $key_invoice_4 }} ] =>  {{$detalii_invoice_4}}</p>
                                    @endif

                                    @if (is_array( $detalii_invoice_4))
                                        @foreach ($detalii_invoice_4 as $key_invoice_5 => $detalii_invoice_5)
                                        
                                            <p class="card-text"> [ {{$key_invoice_1}} ] [ {{ $key_invoice_2 }} ] [{{ $key_invoice_3 }} ] [{{ $key_invoice_4 }} ] [{{ $key_invoice_5 }} ]=>  {{$detalii_invoice_5}}</p>
                                        @endforeach

                                    @endif


                                @endforeach
                            
                            @endif 



                        
                        @endforeach



                    @endif
                
                @endforeach
            </div>
        </div>

        @endif
        @endforeach



  


        

        </div>
        <!-- /.invoice -->




        </div><!-- /.col -->
    </div><!-- /.row -->



@endsection


@section('js')
    <!-- DataTables  & Plugins -->
    <script src="{{ asset('/admin-assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('/admin-assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('/admin-assets/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('/admin-assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('/admin-assets/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('/admin-assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('/admin-assets/plugins/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('/admin-assets/plugins/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ asset('/admin-assets/plugins/pdfmake/vfs_fonts.js') }}"></script>
    <script src="{{ asset('/admin-assets/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('/admin-assets/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('/admin-assets/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>

    <script src="{{ asset('/admin-assets/js/efactura-upload.js') }}"></script>
@endsection        