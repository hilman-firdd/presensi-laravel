
@extends('layouts.app-admin')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">
            Biodata
        </h1>
        <ol class="breadcrumb">
            <li class="active">
                <i class="fa fa-user"></i> Biodata diri
            </li>
        </ol>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-yellow">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-sign-in fa-fw"></i> Data diri</h3>
            </div>
            <div class="panel-body">
               <div class="row">
                <div class="col-lg-4">
                {!! Form::model($data, ['url' => ['/update-biodata', $data->id]]) !!}
                    <label class="label label-primary">Data pegawai</label><hr>
                    @foreach($biodata as $row)
                    <div class="col-md-5">
                        <strong>Nama lengkap</strong> 
                    </div>
                    <div class="col-md-7">
                    <input type="text" name="nm_lengkap" value="{{$row->nm_lengkap}}" class="form-control">
                    </div><br><br>
                    <div class="col-md-5">
                        <strong>Jenis kelamin</strong> 
                    </div>
                    <div class="col-md-7">
                    <select class="form-control" name="jk">
                        <option>{{$row->jk}}</option>
                        <option>Laki - laki</option>
                        <option>Perempuan</option>
                    </select>
                    </div><br><br>
                    <div class="col-md-5">
                        <strong>NIG</strong> 
                    </div>
                    <div class="col-md-7">
                        <input type="hidden" name="nik" class="form-control" value="{{$row->nik}}">
                        <input type="number" name="nig" class="form-control" value="{{$row->nig}}">
                    </div><br><br>
                    <div class="col-md-5">
                        <strong>Tempat</strong> 
                    </div>
                    <div class="col-md-7">
                        <input type="text" name="tempat" class="form-control" value="{{$row->tempat}}">
                    </div><br><br>
                    <div class="col-md-5">
                        <strong>Tgl lahir</strong> 
                    </div>
                    <div class="col-md-7">
                        <input type="date" name="tgl_lahir" class="form-control" value="{{$row->tgl_lahir}}">
                    </div><br><br>
                    <div class="col-md-5">
                        <strong>Agama</strong> 
                    </div>
                    <div class="col-md-7">
                        <input type="text" name="agama" class="form-control" value="{{$row->agama}}">
                    </div><br><br>
                    <div class="col-md-5">
                        <strong>Status</strong> 
                    </div>
                    <div class="col-md-7">
                        <input type="text" name="status" class="form-control" value="{{$row->status}}">
                    </div><br><br>
                    <div class="col-md-5">
                        <strong>Kewarganegaraan</strong> 
                    </div>
                    <div class="col-md-7">
                        <input type="text" name="kewarganegaraan" class="form-control" value="{{$row->kewarganegaraan}}">
                    </div><br><br>
                    <div class="col-md-5">
                        <strong>Nama Ibu</strong> 
                    </div>
                    <div class="col-md-7">
                        <input type="text" name="nm_ibu" class="form-control" value="{{$row->nm_ibu}}">
                    </div>
                </div>
                <div class="col-lg-4">
                    <label class="label label-danger">Data alamat</label><hr>
                    <div class="col-md-5">
                        <strong>Jalan</strong> 
                    </div>
                    <div class="col-md-7">
                        <input type="text" name="jl" class="form-control" value="{{$row->jl}}">
                    </div><br><br>
                    <div class="col-md-5">
                        <strong>Rt</strong> 
                    </div>
                    <div class="col-md-7">
                        <input type="text" name="rt" class="form-control" value="{{$row->rt}}">
                    </div><br><br>
                    <div class="col-md-5">
                        <strong>Rw</strong> 
                    </div>
                    <div class="col-md-7">
                        <input type="text" name="rw" class="form-control" value="{{$row->rw}}">
                    </div><br><br>
                    <div class="col-md-5">
                        <strong>Dusun</strong> 
                    </div>
                    <div class="col-md-7">
                        <input type="text" name="dusun" class="form-control" value="{{$row->dusun}}">
                    </div><br><br>
                    <div class="col-md-5">
                        <strong>Desa</strong> 
                    </div>
                    <div class="col-md-7">
                        <input type="text" name="desa" class="form-control" value="{{$row->desa}}">
                    </div><br><br>
                    <div class="col-md-5">
                        <strong>Kecamatan</strong> 
                    </div>
                    <div class="col-md-7">
                        <input type="text" name="kecamatan" class="form-control" value="{{$row->kecamatan}}">
                    </div><br><br>
                    <div class="col-md-5">
                        <strong>Kode Pos</strong> 
                    </div>
                    <div class="col-md-7">
                        <input type="number" name="kode_pos" class="form-control" value="{{$row->kode_pos}}">
                    </div><br><br>
                </div>
                <div class="col-lg-4">
                    <label class="label label-success">Data kepegawaian</label><hr>
                    <div class="col-md-5">
                        <strong>Status pegawai</strong> 
                    </div>
                    <div class="col-md-7">
                        <input type="text" name="status_kepegawaian" class="form-control" value="{{$row->status_kepegawaian}}">
                    </div><br><br>
                    <div class="col-md-5">
                        <strong>NIY / NIGK</strong> 
                    </div>
                    <div class="col-md-7">
                        <input type="text" name="niy_nigk" class="form-control" value="{{$row->niy_nigk}}">
                    </div><br><br>
                    <div class="col-md-5">
                        <strong>NUPTK</strong> 
                    </div>
                    <div class="col-md-7">
                        <input type="text" name="nuptk" class="form-control" value="{{$row->nuptk}}">
                    </div><br><br>
                    <div class="col-md-5">
                        <strong>SK</strong> 
                    </div>
                    <div class="col-md-7">
                        <input type="text" name="sk_pengangkatan" class="form-control" value="{{$row->sk_pengangkatan}}"><br>
                        <button class="btn btn-danger btn-block" ><i class="fa fa-save"></i> Simpan</button>
                    </div><br><br><br>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <hr><label class="label label-info">Kontak</label><hr>
                        <div class="col-md-5">
                            <strong>Desa</strong> 
                        </div>
                        <div class="col-md-7">
                            <input type="text" name="desa" class="form-control" value="{{$row->desa}}">
                        </div><br><br>
                        <div class="col-md-5">
                            <strong>Kecamatan</strong> 
                        </div>
                        <div class="col-md-7">
                            <input type="text" name="kecamatan" class="form-control" value="{{$row->kecamatan}}">
                        </div><br><br>
                        <div class="col-md-5">
                            <strong>Kode Pos</strong> 
                        </div>
                        <div class="col-md-7">
                            <input type="text" name="kode_pos" class="form-control" value="{{$row->kode_pos}}">
                        </div><br><br>
                    </div>
                </div>
                @endforeach
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
</div>
@endsection