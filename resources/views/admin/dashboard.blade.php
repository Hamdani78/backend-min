@extends('admin.layout.layout')

@section('css')
<!-- Ekko Lightbox -->
<link rel="stylesheet" href="{{ url('admin/plugins/ekko-lightbox/ekko-lightbox.css') }}">
@endsection

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ $totalPegawai }}</h3>
                            <p>Total Pegawai</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{ $totalSiswa }}</h3>
                            <p>Total Kelas</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{ $totalKegiatan }}</h3>
                            <p>Total Kegiatan</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3>{{ $totalFasilitas }}</h3>
                            <p>Total Fasilitas</p>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <section class="content">
        <div class="card">
            <div class="card-header text-center bg-primary">
                <h1><strong>Fasilitas Madrasah</strong></h1>
            </div>
            <div class="card-body">
                <!-- Fasilitas -->
                <div class="row">
                    @foreach($fasilitas as $fasilitasItem)
                    <div class="col-lg-12 col-md-10 col-sm-10">
                        <div class="card">
                            <div class="card-header">
                                <h2>{{ $fasilitasItem->nama }}</h2>
                            </div>
                            <div class="card-body">
                                <div class="ekko-lightbox-container">
                                    @if($fasilitasImages->where('fasilitas_id', $fasilitasItem->id)->isNotEmpty())
                                    @foreach($fasilitasImages->where('fasilitas_id', $fasilitasItem->id) as $fasilitasImage)
                                    <a href="{{ asset($fasilitasImage->foto) }}" data-toggle="lightbox" data-gallery="gallery{{ $fasilitasItem->id }}">
                                        <img src="{{ asset($fasilitasImage->foto) }}" class="img-fluid mb-2" alt="" />
                                    </a>
                                    @endforeach
                                    @else
                                    <p>Tidak ada foto tersedia untuk fasilitas ini.</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header text-center bg-primary">
                <h1><strong>Kegiatan Madrasah</strong></h1>
            </div>
            <div class="card-body">
                <!-- Kegiatan -->
                <div class="row">
                    @foreach($kegiatan as $kegiatanItem)
                    <div class="col-lg-12 col-md-10 col-sm-10">
                        <div class="card">
                            <div class="card-header">
                                <h2>{{ $kegiatanItem->nama }}</h2>
                            </div>
                            <div class="card-body">
                                <div class="ekko-lightbox-container">
                                    @if($kegiatanImages->where('kegiatans_id', $kegiatanItem->id)->isNotEmpty())
                                    @foreach($kegiatanImages->where('kegiatans_id', $kegiatanItem->id) as $kegiatanImage)
                                    <a href="{{ asset($kegiatanImage->foto) }}" data-toggle="lightbox" data-gallery="gallery{{ $kegiatanItem->id }}">
                                        <img src="{{ asset($kegiatanImage->foto) }}" class="img-fluid mb-2" alt="" />
                                    </a>
                                    @endforeach
                                    @else
                                    <p>Tidak ada foto tersedia untuk kegiatan ini.</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

</div>
<!-- /.content-wrapper -->
@endsection

@section('js')
<script src="{{url('admin/plugins/jquery/jquery.min.js')}}"></script>
<script src="{{url('admin/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{url('admin/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js')}}"></script>
<script src="{{url('admin/js/adminlte.js')}}"></script>

<!-- Ekko Lightbox -->
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