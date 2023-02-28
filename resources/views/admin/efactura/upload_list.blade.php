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

  <div class="card">
              <div class="card-header">
                <h3 class="card-title">DataTable with default features</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">


        @if(count($upload_files) > 0 )
                <table id="example1" class="table table-bordered table-striped">
                  <thead>

                  
                  <tr>
                    <th>id</th>
                    <th>name</th>
                    <th>file_path</th>
                    <th>created_at</th>
                    <th>update_at</th>
                    <th>numar facturi</th>
                  </tr>
                  </thead>

                  @foreach ($upload_files as $file)
                  <tbody>
                  <tr>
                    <td>{{$file->id}}</td>
                    <td>{{$file->file_name}}</td>
                    <td>{{$file->file_path}}</td>
                    <td>{{$file->created_at}}</td>
                    <td>{{$file->updated_at}}</td>
                    <td>{{$file->number_invoices}}</td>
                  </tr>
                  @endforeach

                  </tbody>
                  <tfoot>
                  <tr>
                    <th>id</th>
                    <th>name</th>
                    <th>file_path</th>
                    <th>created_at</th>
                    <th>update_at</th>
                    <th>numar facturi</th>
                  </tr>
                  </tfoot>
                </table>
                @else
                <p>No file found</p>
                @endif


              </div>
             
            </div>

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