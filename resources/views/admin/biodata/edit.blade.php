@extends('layouts.app-admin')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">
         Edit Biodata
     </h1>
     <ol class="breadcrumb">
        <li class="active">
            <i class="fa fa-user"></i> Edit Biodata diri
        </li>
    </ol>
</div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-yellow">
            <div class="panel-heading">
            </div>

            {!! Form::model($user, ['files'=>true, 'url' => ['/update-biodata', $user->id]]) !!}
            <div class="panel-body">
                <div class="table table-responsive">
                    <label class="label label-primary"><i class="fa fa-user"></i> Account</label><br><br>
                    <table class="table">
                                    <input type="hidden" name="name" class="form-control" value="{{Auth::user()->name}}">
                        <tr>
                            <td width="1%">Username</td>
                            <td width="1%">:</td>
                            <td>
                                <div class="col-md-5">
                                    <input type="text" name="username" class="form-control" value="{{Auth::user()->username}}">
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td width="1%">Email</td>
                            <td width="1%">:</td>
                            <td>
                                <div class="col-md-5">
                                    <input type="email" name="email" class="form-control" value="{{Auth::user()->email}}"></div>
                                </td>
                            </tr>
                            <tr>
                                <td width="1%">Password</td>
                                <td width="1%">:</td>
                                <td>
                                    <div class="col-md-5">
                                        <input type="password" name="password" class="form-control">
                                    </div>
                                </td>
                            </tr>
                            <!-- <tr>
                                <td width="1%">Password Presensi</td>
                                <td width="1%">:</td>
                                <td>
                                    <div class="col-md-5">
                                        <input type="text" name="password_presensi" class="form-control" value="{{Auth::user()->password_presensi}}">
                                    </div>
                                </td>
                            </tr> -->
                        </table>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-xs-12">
                            <div class="table table-responsive">
                                <label class="label label-success"><i class="fa fa-book"></i> Biodata Lengkap</label><br><br>
                                <table class="table" >
                                    @foreach($data as $row)
                                    <tr>
                                        <td width="10%">Foto</td>
                                        <td width="1%">:</td>
                                        <td width="25%">
                                            <input type="file" name="foto" class="form-control">
                                        </td>
                                        <td colspan="3"></td>
                                    </tr>
                                    <tr>
                                        <td width="10%">NIG</td>
                                        <td width="1%">:</td>
                                        <td width="25%">
                                            <input type="hidden" name="nik" class="form-control" value="{{$row->nik}}">
                                            <input type="text" name="nig" class="form-control" value="{{$row->nig}}">
                                        </td>
                                        <td width="10%">Telp</td>
                                        <td width="1%">:</td>
                                        <td width="25%">
                                            <input type="number" name="no_telp" class="form-control" value="{{$row->no_telp}}">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Nama Lengkap</td>
                                        <td>:</td>
                                        <td>
                                            <input type="text" name="nm_lengkap" class="form-control" value="{{$row->nm_lengkap}}">
                                        </td>
                                        <td>HP</td>
                                        <td>:</td>
                                        <td>
                                            <input type="number" name="no_hp" class="form-control" value="{{$row->no_hp}}">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Jenis Kelamin</td>
                                        <td>:</td>
                                        <td>
                                            <select class="form-control" name="jk">
                                                <option> {{$row->jk}}</option>
                                                <option>Laki - laki</option>
                                                <option>Perempuan</option>
                                            </select>
                                        </td>
                                        <td>Kode Pos</td>
                                        <td>:</td>
                                        <td>
                                            <input type="number" name="kode_pos" class="form-control" value="{{$row->kode_pos}}">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>TTL</td>
                                        <td>:</td>
                                        <td>
                                        <div class="row">
                                        <div class="col-md-12">
                                        Tempat: 
                                        <input type="text" name="tempat" class="form-control" value="{{$row->tempat}}">
                                        </div>
                                        <div class="col-md-6"> 
                                        <br>Tanggal Lahir:
                                        <input type="date" name="tgl_lahir" class="form-control" value="{{$row->tgl_lahir}}">
                                        </div>
                                        </div></td>
                                        <td>Alamat</td>
                                        <td>:</td>
                                        <td>
                                        <div class="row">
                                            <div class="col-md-4">
                                                JL:
                                                <input type="text" name="jl" class="form-control" value="{{$row->jl}}">
                                            </div>
                                            <div class="col-md-4">
                                                Rt:
                                                <input type="text" name="rt" class="form-control" value="{{$row->rt}}">
                                            </div>
                                            <div class="col-md-4">
                                                Rw:
                                                <input type="text" name="rw" class="form-control" value="{{$row->rw}}">
                                            </div>
                                            <div class="col-md-4">
                                                <br>Dusun:
                                                <input type="text" name="dusun" class="form-control" value="{{$row->dusun}}">
                                            </div>
                                            <div class="col-md-4">
                                                <br>Desa:
                                                <input type="text" name="desa" class="form-control" value="{{$row->desa}}">
                                            </div>
                                            <div class="col-md-4">
                                                <br>Kecamatan:
                                                <input type="text" name="kecamatan" class="form-control" value="{{$row->kecamatan}}">
                                            </div>
                                        </div></td>
                                        </tr>
                                        <tr>
                                            <td>Agama</td>
                                            <td>:</td>
                                            <td>
                                            <input type="text" name="agama" class="form-control" value="{{$row->agama}}">
                                            </td>
                                            <td>Status Kepegawaian</td>
                                            <td>:</td>
                                            <td>
                                            <input type="text" name="status_kepegawaian" class="form-control" value="{{$row->status_kepegawaian}}">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Status</td>
                                            <td>:</td>
                                            <td><input type="text" name="status" class="form-control" value="{{$row->status}}"></td>
                                            <td>NIY / NIGK</td>
                                            <td>:</td>
                                            <td><input type="text" name="niy_nigk" class="form-control" value="{{$row->niy_nigk}}"></td>
                                        </tr>
                                        <tr>
                                            <td>Kewarganegaraan</td>
                                            <td>:</td>
                                            <td>
                                            <input type="text" name="kewarganegaraan" class="form-control" value="{{$row->kewarganegaraan}}"></td>
                                            <td>NUPTK</td>
                                            <td>:</td>
                                            <td><input type="text" name="nuptk" class="form-control" value="{{$row->nuptk}}"></td>
                                        </tr>
                                        <tr>
                                            <td>Nama Ibu</td>
                                            <td>:</td>
                                            <td>
                                            <input type="text" name="nm_ibu" class="form-control" value="{{$row->nm_ibu}}"></td>
                                            <td>SK Pengangkatan</td>
                                            <td>:</td>
                                            <td><input type="text" name="sk_pengangkatan" class="form-control" value="{{$row->sk_pengangkatan}}">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="3"></td>
                                            <td>Masa Kontrak</td>
                                            <td>:</td>
                                            <td><input type="text" name="masa_kontrak" class="form-control" value="{{$row->masa_kontrak}}">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="6" align="right"><br><br>
                                            <a href="{{url('/biodata')}}">
                                                <input type="button" class="btn btn-default" value="Kembali">
                                            </a>
                                                    <button class="btn btn-info"><i class="fa fa-save"></i> Update</button>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </table>
                                    </div>
                                </div>
                            </div>
                         {{Form::close()}}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @endsection