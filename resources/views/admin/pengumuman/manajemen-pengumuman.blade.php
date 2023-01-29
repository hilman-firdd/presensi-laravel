@extends('layouts.app-admin')

@section('content')
<h2 class="mt-3">Pengumuman</h2>
<ol class="breadcrumb mb-3">
    <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">Home</a></li>
    <li class="breadcrumb-item active">Pengumuman</li>
</ol>
<div class="row">
    <div class="col-12">
        <div class="">
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a role="tab" class="nav-link active" id="tab-1" data-bs-toggle="tab" href="#tab-content-1">
                        <span>Daftar Pengumuman</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a role="tab" class="nav-link" id="tab-0" data-bs-toggle="tab" href="#tab-content-0">
                        <span>Tambah</span>
                    </a>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane tabs-animation fade" id="tab-content-0" role="tabpanel">
                    <div class="main-card mb-3 card">
                        <div class="card-body">
                            <div class="col-md-12">
                                {!! Form::open(['files'=>true, 'url' => ['/simpan-manajemen-pengumuman']]) !!}

                                <div class="form-group row mb-3">
                                    <label for="judul" class="col-md-2 col-form-label">{{ __('Judul *') }}</label>
                                    <div class="col-md-10">
                                        <input id="judul" type="text" class="form-control{{ $errors->has('judul') ? ' is-invalid' : '' }}" name="judul" value="{{ old('judul')}}" autofocus>

                                        @if ($errors->has('judul'))
                                        <span class="invalid-feedback">
                                            {{ $errors->first('judul') }}
                                        </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group row mb-3">
                                    <label class="col-md-2 col-form-label">{{ __('Gambar *') }}</label>
                                    <div class="col-md-5">
                                        <input onchange="readGambar(event)" name="gambar" id="gambar" type="file" class="form-control{{ $errors->has('gambar') ? ' is-invalid' : '' }}" gambar="gambar" value="{{ old('gambar') }}" autofocus>
                                        <small class="text-muted">*Format gambar JPG/ PNG), dengan ukuran 1280 x 300</small>
                                        @if ($errors->has('gambar'))
                                        <span class="invalid-feedback">
                                            {{ $errors->first('gambar') }}
                                        </span>
                                        @endif
                                    </div>
                                    <div class="col-md-5">
                                        <img id='output' style="width: 200px;">
                                    </div>
                                </div>
                                <div class="form-group row mb-3">
                                    <label class="col-md-2 col-form-label">{{ __('Isi *') }}</label>
                                    <div class="col-md-10">
                                        <textarea name="isi" id="summernote"></textarea>
                                    </div>
                                </div>
                                <div class="form-group row mb-3">
                                    <label class="col-md-2 col-form-label"></label>
                                    <div class="col-md-5">
                                        <button type="submit" class="btn btn-primary btn-lg"><i class="fas fa-save"></i> Simpan</button>
                                    </div>
                                </div>
                                {!! Form::close() !!}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane tabs-animation fade show active" id="tab-content-1" role="tabpanel">
                    <div class="main-card mb-3 card">
                        <div class="card-body">
                            <h4 class="card-title mb-3">Daftar Pengumuman</h4>
                            <div class="table-responsive">
                                <table id="dataTable" class="mb-0 table table-bordered table-hover table-striped">
                                    <thead>
                                        <tr>
                                            <th class="text-center">No</th>
                                            <th class="text-center">Gambar</th>
                                            <th class="text-center">Judul</th>
                                            <th class="text-center">Isi</th>
                                            <th class="text-center">Penulis</th>
                                            <th class="text-center">Tanggal</th>
                                            <th class="text-center">Viewer</th>
                                            <th class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($data as $key => $value)
                                        <tr>
                                            <td align="center">{{$key+1}}</td>
                                            <td align="center" width="20%">
                                                <img src="{{asset('/img/pengumuman/')}}/{{$value->gambar}}" width="100%">
                                            </td>
                                            <td>{{$value->judul}}</td>
                                            <td>{!! $value->isi !!}</td>
                                            <td>{{$value->nama}}</td>
                                            <td align="center">{{date_format(date_create($value->tanggal),"d/m/Y")}}</td>
                                            <td align="center">{{$value->view}}</td>
                                            <td align="center">
                                                <a title="Hapus" href="#" type="button" class="btn btn-danger btn-sm tooltipku" data-bs-toggle="modal" data-bs-target="#myModal1{{$value->id}}"><i class="fa fa-trash"></i></a>
                                            </td>
                                            @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                @foreach($data as $row)
                <div class="modal fade" id="myModal1{{$row->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                    <<div class="modal-dialog modal-sm" role="document">
                        <div class="modal-content">
                            <div class="modal-body">
                                Hapus Data ?
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <a title="Hapus" href="{!! url('/'.$row->id.'/delete-manajemen-pengumuman') !!}" class="btn btn-danger"><i class="fa fa-trash"></i> Ok</a>
                            </div>
                        </div>
                </div>
            </div>
            @endforeach

            <script type="text/javascript">
                var readGambar = function(event) {
                    var input = event.target;

                    var reader = new FileReader();
                    reader.onload = function() {
                        var dataURL = reader.result;
                        var output = document.getElementById('output');
                        output.src = dataURL;
                    };
                    reader.readAsDataURL(input.files[0]);
                };
            </script>
            @endsection