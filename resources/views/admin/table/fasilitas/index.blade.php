@extends('admin.layout.layout')

@section('css')
<!-- DataTables -->
<link rel="stylesheet" href="{{ url('admin/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ url('admin/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ url('admin/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
@endsection

@section('content')
@if (session()->has('success'))
<div class="alert alert-primary">
    {{ session()->get('success') }}
</div>
@endif
<div class="content-wrapper">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Data Table Fasilitas</h3>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <a href="{{ route('fasilitas.create') }}">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-plus"></i> Tambah</button>
                </a>
            </div>
            <table id="example1" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Deskripsi</th>
                        <th>Foto</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($fasilitas as $data)
                    <tr>
                        <td>{{ $data->nama }}</td>
                        <td>{{ $data->deskripsi }}</td>
                        <td>
                            <a href="{{ route('fasilitasimage.index', ['fasilitasId' => $data->id]) }}" class="btn btn-info">Tambahkan / Lihat Foto</a>
                        </td>
                        <td>
                            <form onsubmit="return confirm('Apakah Anda Yakin ?');" action="{{ route('fasilitas.destroy', $data->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger"><i class="fa fa-trash"></i></button>
                                <a href="{{ route('fasilitas.edit', $data->id) }}" class="btn btn-outline-warning"><i class="fa fa-edit"></i></a>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

@section('js')
<!-- DataTables & Plugins -->
<script src="{{ url('admin/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ url('admin/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ url('admin/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ url('admin/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
<script src="{{ url('admin/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ url('admin/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ url('admin/plugins/jszip/jszip.min.js') }}"></script>
<script src="{{ url('admin/plugins/pdfmake/pdfmake.min.js') }}"></script>
<script src="{{ url('admin/plugins/pdfmake/vfs_fonts.js') }}"></script>
<script src="{{ url('admin/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
<script src="{{ url('admin/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
<script src="{{ url('admin/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>

<!-- Page specific script -->
<script>
    $(function() {
        $("#example1").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    });
</script>
@endsection