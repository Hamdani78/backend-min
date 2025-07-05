@extends('admin.layout.layout')

@section('css')
<!-- Ekko Lightbox -->
<link rel="stylesheet" href="{{ url('admin/plugins/ekko-lightbox/ekko-lightbox.css') }}">
@endsection

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Foto Kegiatan Madrasah</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">kegiatan</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="mb-3">
                        <a href="{{route('kegiatan.index')}}" class="float-end">
                            <button type="button" class="btn btn-primary">Back</button>
                        </a>
                    </div>
                    <div class="card card-primary">
                        <div class="card-header ">
                            <div>
                                <h4 class="card-title">ID : {{ $kegiatan->id }}</h4><br>
                                <h4 class="card-title">Kegiatan : {{ $kegiatan->nama }}</h4>
                            </div>
                        </div>
                        <div class="card-body">
                            @if (session('status'))
                            <div class="alert alert-success">
                                {{ (session('status')) }}
                            </div>
                            @endif

                            @if ($errors->any())
                            <ul class="alert alert-warning">
                                @foreach ($errors->all() as $error)
                                <li>{{$error}}</li>
                                @endforeach
                            </ul>
                            @endif
                            <div class="mb-3">
                                <form action="{{ url('/admin/kegiatan/kegiatan-images/'.$kegiatan->id.'/upload') }}" method="post" enctype="multipart/form-data">
                                    @csrf
                                    <label for="foto" class="form-label">Upload Foto (maks: 20 foto saja)</label>
                                    <input type="file" id="foto" name="foto[]" multiple class="form-control">
                                    <div class="mt-3">
                                        <button type="submit" class="btn btn-primary">Tambahkan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            @foreach($images as $kegiatanImg)
                            <div class="img-thumbnail">
                                <a href="{{ asset($kegiatanImg->foto) }}" data-toggle="lightbox" data-gallery="gallery">
                                    <img src="{{ asset($kegiatanImg->foto) }}" class="img-fluid mb-2" alt="" />
                                </a>

                                <form action="{{ route('kegiatanimage.destroy', $kegiatanImg->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus gambar ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                </form>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
@endsection

@section('js')
<script src="{{ url('admin/plugins/ekko-lightbox/ekko-lightbox.min.js') }}"></script>
<script src="{{ url('admin/plugins/filterizr/jquery.filterizr.min.js') }}"></script>

<script>
    $(document).on('click', '[data-toggle="lightbox"]', function(event) {
        event.preventDefault();
        $(this).ekkoLightbox({
            alwaysShowClose: true
        });
    });
</script>
@endsection