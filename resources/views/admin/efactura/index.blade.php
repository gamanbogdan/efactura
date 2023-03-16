@extends('admin.main-layout')

@section('css')
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" >
    <link  href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.0.3/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css">  
@endsection

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
        <!-- ./col -->
        <div class="col-lg-8">
            @if ($message = Session::get('success'))
                <div class="text-success">
                    <strong>{{ $message }}</strong>
                    {{ Session::get('facturi') }} 
                    
                    @if( Session::get('duplicate') )
                    <span class="text-warning">                 
                        Duplicate: {{ Session::get('duplicate') }}                       
                    </span>
                    @endif                    

                </div>
                @if(Session::get('lista_data_incarcare') )
                    <div class="text-success">
                        Date incarcare: {{ Session::get('lista_data_incarcare') }}
                    </div>
                @endif

                @if(Session::get('lista_data_incarcare_duplicat') )
                    <div class="text-warning">
                        Date duplicate: {{ Session::get('lista_data_incarcare_duplicat') }}
                    </div>
                @endif

                
            @endif
            @if (count($errors) > 0)
                <div class="text-danger">
                        @foreach ($errors->all() as $error)
                        {{ $error }}
                        @endforeach
                </div>
            @endif
        </div>

        <div class="col-lg-4 ">           
            <form action="{{route('efactura.upload')}}" method="post" enctype="multipart/form-data" >
                @csrf
                <div class="row mb-2" >                      
                    <div class="col">
                        <label class="custom-file-label" for="chooseFile">Selecteaza fisier...</label>
                        <input type="file" name="zip" class="custom-file-input" id="chooseFile" >
                    </div>  
                    <div class="col-3">
                        <button type="submit" name="submit" class="btn btn-primary btn-block">
                            Incarca zip
                        </button>
                    </div>                          
                </div>
            </form>
        </div>  
    </div>   

    <!-- /.row -->
    <div class="row">
        <div class="col-12">
            <div class="card">

                <div class="card-header">
                    <div class="row float-left form-inline">                    
                        <div class="form-group mt-2 mx-3">
                            <label>Perioada incarcare SPV: </label>
                        </div>                    
                        <div class="form-group mx-sm-3 mb-0">
                            <input type="text" name="from_date" id="from_date" class="form-control" value="{{ date('d-m-Y', strtotime('-3 month')) }}" readonly />
                        </div>
                        <div class="form-group mx-sm-3 mb-0">
                            <input type="text" name="to_date" id="to_date" class="form-control" value="{{ date('d-m-Y') }}" readonly />
                        </div>



                        <div class="form-group mx-sm-3 mb-0">
                            <button type="button" name="filter" id="filter" class="btn btn-primary">Filtru</button>
                        </div>    
                    </div>


                    <div class="row float-right form-inline">
                        <div class="form-group mt-2 mx-3">
                            <label for="sucursalaFilter" >Sucursala: </label>
                        </div>

                        <div class="form-group mx-sm-3 mb-0">
                            <select id="sucursalaFilter" class="custom-select" >
                                <option value="">Toate sucursalele</option>
                                <option value="FCN">Sucursala FCN</option>
                                <option value=".">Alta sucursala</option>
                                <option value="-">Sucursala nesetata</option>
                            </select>
                        </div>

                    </div>


                </div>
               



                <div class="card-body" >

                    <table  id="efactura-datatable" class="table table-striped " style="width:100%;"  >
                        <thead>
                            <tr>
                                <th>Nr. <br> Crt.</th>
                                <th>Nr. factura</th>
                                <th>Furnizor</th>                                
                                <th>Suma <br> plata</th>
                                <th>Referinta</th>
                                <th>Livrare</th>
                                <th>Produse</th>
                                <th>Data <br> factura</th>
                                <th>Incarcare <br> in SPV</th>
                                <th>Sucursala</th>
                            </tr>
                        </thead>
                    </table>
                </div>  

            </div>            
        </div>
    </div>
@endsection


@section('js')

    <script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <script src="{{ asset('/admin-assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
 



    
    <script src="https://cdn.datatables.net/buttons/1.6.5/js/dataTables.buttons.min.js" type="text/javascript"></script>

    <script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.flash.min.js" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js" type="text/javascript"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.html5.min.js" type="text/javascript"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.print.min.js" type="text/javascript"></script>

    <script src="{{ asset('/admin-assets/js/efactura.js') }}"></script>



<script>

    $(document).ready( function () {


        $.ajaxSetup({
        headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

        var efactura_datatable = $('#efactura-datatable').DataTable({
            processing: true,
            serverSide: false,
            stateSave: true,
            ajax: {
                url: "{{ url('/admin/efactura') }}",
                data: function (data)
                    {
                        data.from_date=$('#from_date').val();
                        data.to_date=$('#to_date').val();
                        data._token =  "{{ csrf_token() }}";
                    },
                cache : true,
                type: "GET",
            },
            autoWidth: true,
            dom: "<'row'<'col-sm-12 my-2'Bftr>> <'row'<'col-sm-4 mt-1'li><'col-sm-8 mt-0'p>>",
            buttons: [
                {
                    extend: 'excel',
                    text: '<span class="fas fa-file-excel"></span> Excel Export',
                    title: 'Facturi incarcate in SPV in perioada: '+$('#from_date').val()+' - '+$('#to_date').val(),
                    exportOptions: {
                        modifier: {
                            search: 'applied',
                            order: 'applied',
                            
                        },
                        

                        columns: [ 0, 2, 1, 7, 8, 9 ],
                    },
                    className: ' mb-2',
                }
            ],
            language: {
                "processing": "Procesează...",
                "lengthMenu": "Afișează _MENU_ înregistrări pe pagină",
                "zeroRecords": "Nu am găsit nimic - ne pare rău",
                "info": "Afișate de la _START_ la _END_ din _TOTAL_ înregistrări",
                "infoEmpty": "Afișate de la 0 la 0 din 0 înregistrări",
                "infoFiltered": "(filtrate dintr-un total de _MAX_ înregistrări)",
                "search": "Caută:",
                "aria": {
                    "sortAscending": "Sortează ascendent",
                    "sortDescending": "Sortează descendent"
                },
                "emptyTable": "Nu există date în tabel",
                "searchPlaceholder": "Caută în tabel",
                "thousands": ".",
                "infoThousands": ".",
                "paginate": {
                    "first": "Prima pagină",
                    "last": "Ultima pagină",
                    "next": "Pagina următoare",
                    "previous": "Pagina precedentă"
                }
            },

            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex' },
                { data: 'Nr_factura', name: 'Nr_factura' },
                { data: 'Vanzator_Nume', name: 'Vanzator_Nume' },
                { data: 'Totalurile_documentului_Suma_de_plata', name: 'Suma' },
                { data: 'Referinta', name: 'Referinta'},
                { data: 'Livrare', name: 'Livrare'},
                { data: 'Produse', name: 'Produse' },
                { data: 'Informatii_factura_Data_emitere_factura', name: 'Informatii_factura_Data_emitere_factura' },                
                { data: 'Date_created_anaf', name: 'Date created anaf' },
                { data: 'Sucursala', name: 'Sucursala' },             
            ],
            order: [[0, 'desc']],

        });
    
        //$("#efactura-datatable_filter.dataTables_filter").append($("#sucursalaFilter"));

        var sucursalaIndex = 0;
        $("#efactura-datatable th").each(function (i) {
            if ($($(this)).html() == "Sucursala") {
                sucursalaIndex = i; 
                return false;
            }
        });

        $.fn.dataTable.ext.search.push(
            function (settings, data, dataIndex) {
            var selectedItem = $('#sucursalaFilter').val()
            var sucursala = data[sucursalaIndex];
            if (selectedItem === "" || sucursala.includes(selectedItem)) {
                return true;
            }
            return false;
        });

        $("#sucursalaFilter").change(function (e) {
            efactura_datatable.draw();
        });

        efactura_datatable.draw();

        $.datepicker.regional['ro'] = {
            monthNames: [ "Ianuarie", "Februarie", "Martie", "Aprilie", "Mai", "Iunie", "Iulie", "August", "Septembrie", "Octombrie", "Noiembrie", "Decembrie"],
            dayNamesMin: ["Lu", "Ma", "Me", "Jo", "Vi", "Sa", "Du"],
        };

        $.datepicker.setDefaults($.datepicker.regional['ro']);
        $("#from_date").datepicker({
            todayBtn:'linked',
            dateFormat:'dd-mm-yy',
            autoclose:true          
        });
                           
        $("#to_date").datepicker({
            todayBtn:'linked',
            dateFormat:'dd-mm-yy',
            autoclose:true           
        });
            

        $('#filter').click(function(){
                efactura_datatable.ajax.reload();
        });

    });
    </script>

@endsection

