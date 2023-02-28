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



  <div class="card">
    <div class="card-header"><h3 class="card-title">Lista facturi</h3></div>
      <!-- /.card-header -->
    <div class="card-body">


          @if(count($invoices) > 0 )
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>

                    
                    <tr>
                      <th>Id</th>   
                      <th>Nr. factura</th>                                        
                      <th>Vanzator</th>
                      <th>Cumparator</th>
                      <th>Suma</th>
                      <th>Actiuni</th>

                    </tr>
                    </thead>

                    @foreach ($invoices as $invoice)
                    <tbody>
                    <tr>
                      <td>{{ $invoice->id }}</td>
                      <td>{{ $invoice->Informatii_factura_Nr_factura }}</td>
                      <td>{{ $invoice->Vanzator_Nume }}</td>
                      <td>{{ $invoice->Cumparator_Nume }}</td>
                      <td>{{ $invoice->Totalurile_documentului_Suma_de_plata }}</td>
                      <td>
                        <a href="{{ route('efactura.info', $invoice->id ) }}" class="btn btn-primary" > Vezi factura </a>
                        
                      </td>
                      
                    </tr>
                    @endforeach

                    </tbody>
                    <tfoot>
                    <tr>
                    <th>Id</th>   
                      <th>Nr. factura</th>                                        
                      <th>Vanzator</th>
                      <th>Cumparator</th>
                      <th>Suma</th>
                      <th>Actiuni</th>

                    </tr>
                    </tfoot>
                  </table>
                  @else
                  <p>No file found</p>
                  @endif


    </div>
  
  </div>

</div>

  <!-- /.row (main row) -->
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







