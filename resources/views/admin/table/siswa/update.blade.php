@extends('admin.layout.layout')

@section('content')
<div class="content-wrapper">
    <div class="col">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Ubah Data Siswa</h3>
            </div>
            <form action="{{ route('siswa.update', $siswa->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="row">
                        <div class="col col-md-12 form-group">
                            <label>Kelas</label>
                            <input type="text" class="form-control" id="kelas" name="kelas" value="{{$siswa->kelas }}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col col-md-12 form-group">
                            <label>Jumlah Siswa</label>
                            <input type="text" class="form-control" id="jml_siswa" name="jml_siswa" value="{{$siswa->jml_siswa }}">
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
                    <button type="submit" class="btn btn-primary">Update</button>
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