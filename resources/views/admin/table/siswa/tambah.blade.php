@extends('admin.layout.layout')

@section('content')
<div class="content-wrapper">
    <br>
    <div class="col">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Tambah Data Siswa</h3>
            </div>
            <form action="{{ route('siswa.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col col-md-12 form-group">
                            <label>Kelas</label>
                            <input type="text" class="form-control" id="kelas" name="kelas" placeholder="Masukkan Nama">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col col-md-12 form-group">
                            <label>Jumlah Siswa</label>
                            <input type="text" class="form-control" id="jml_siswa" name="jml_siswa" placeholder="Masukkan Jumlah Siswa">
                        </div>
                    </div>
                    <div class="row">
                    <div class="col col-md-12 form-group">
                        <label>Nama Wali Kelas</label>
                        <select class="form-control" id="pegawai" name="pegawai">
                            @foreach($pegawai as $pegawai)
                                <option value="{{ $pegawai->id }}">{{ $pegawai->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Tambah</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('css')
<link rel="stylesheet" href="{{ url('admin/plugins/summernote/summernote-bs4.min.css') }}">
@endsection

@section('js')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ url('admin/plugins/summernote/summernote-bs4.min.js') }}"></script>
<script>
    $(function() {
        $('#deskripsi').summernote();
    });
</script>
@endsection