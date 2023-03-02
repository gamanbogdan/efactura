@extends('admin.main-layout')




@section('css')

<!-- <meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" >
-->

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" >
<link  href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" rel="stylesheet">

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


        <!-- ./col -->
        <div class="col-lg-6 col-12">

            @if ($message = Session::get('success'))
                <div class="text-success">
                    <strong>{{ $message }}</strong>
                </div>
            @endif


            @if (count($errors) > 0)
                <div class="text-danger">

                        @foreach ($errors->all() as $error)
                        {{ $error }}
                        @endforeach

                </div>
            @endif
        </div>


        <!-- ./col -->
        <div class="col-lg-6 col-12 ">
            
                <form action="{{route('efactura.upload')}}" method="post" enctype="multipart/form-data" >
                @csrf

                    <div class="row mb-2" >
                        
                        <div class="col">
                        <label class="custom-file-label" for="chooseFile">Selecteaza fisier...</label>
                        <input type="file" name="zip" class="custom-file-input" id="chooseFile" >
                        </div>  

                        <div class="col">
                        <button type="submit" name="submit" class="btn btn-primary btn-block">
                            Incarca zip
                        </button>
                        </div>
                            
                    </div>
            </form>

        </div>  
    </div>   

  <!-- /.row (main row) -->





    <!-- /.row -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                <h3 class="card-title">Fixed Header Table</h3>


                </div>
                <!-- /.card-header -->
                <div class="card-body table-responsive p-3" >
                    <table  id="efactura-datatable" class="table table-head-fixed ">
                        <thead>
                            <tr>
                                <th>Crt.</th>
                                <th>Nr. factura</th>
                                <th>Vanzator</th>
                                <th>Cumparator</th>
                                <th>Suma <br> plata</th>
                                <th>Incarcare <br> anaf</th>
                                
                                <th width="8%">Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
    </div>
    <!-- /.row -->

@endsection




@section('js')

<!-- <meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" >
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<link  href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" rel="stylesheet">
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script> -->






    <script src="{{ asset('/admin-assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('/admin-assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('/admin-assets/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('/admin-assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>








<script src="{{ asset('/admin-assets/js/efactura.js') }}"></script>

<script>

$(document).ready( function () {
    $.ajaxSetup({
        headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('#efactura-datatable').DataTable({
        processing: true,
        serverSide: true,
        stateSave: true,
        ajax: "{{ url('/admin/efactura-index') }}",
        columns: [
            { data: 'id', name: 'id' },
            { data: 'Nr_factura', name: 'Nr_factura' },
            { data: 'Vanzator_Nume', name: 'Vanzator_Nume' },
            { data: 'Cumparator_Nume', name: 'Cumparator_Nume' },
            { data: 'Totalurile_documentului_Suma_de_plata', name: 'Suma' },
            { data: 'Date_created_anaf', name: 'Date created anaf' },
            
            { data: 'action', name: 'action', orderable: false },
        ],
        order: [[0, 'desc']]
    });

});
</script>



@endsection