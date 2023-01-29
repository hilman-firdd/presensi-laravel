@extends('layouts.app-admin')

@section('content')
<h2 class="my-3">Edit User</h2>
<ol class="breadcrumb mb-3">
    <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{url('/manajemen-user')}}">Master User</a></li>
    <li class="breadcrumb-item active">Edit User</li>
</ol>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <a href="{{url('manajemen-user')}}" class="text-dark text-decoration-none">
                    <i class="fas fa-arrow-left"></i>&ensp;Kembali</a>
            </div>
            <div class="card-body">
                {!! Form::model($user, ['url' => ['/update-data', $user->id_user]]) !!}
                <div class="row">
                    <div class="col-md-6">
                        <ol class="breadcrumb">
                            <li class="active">
                                <center><i class="fa fa-key"></i> Data Login</center>
                            </li>
                        </ol>
                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail') }}</label>
                            <div class="col-md-8">
                                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ $user->email }}" required="required">

                                @if ($errors->has('email'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="username" class="col-md-4 col-form-label text-md-right">{{ __('Username *') }}</label>
                            <div class="col-md-8">
                                <input id="username" type="text" class="form-control{{ $errors->has('username') ? ' is-invalid' : '' }}" name="username" value="{{ $user->username }}" required autofocus>

                                @if ($errors->has('username'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('username') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password*') }}</label>

                            <div class="col-md-8">
                                <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" value="{{ $user->password_view}}" required>

                                @if ($errors->has('password'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="level" class="col-md-4 col-form-label text-md-right">{{ __('Level *') }}</label>
                            <div class="col-md-6">
                            <select id="level" type="text" class="form-select{{ $errors->has('level') ? ' is-invalid' : '' }}" name="level" required autofocus>
                                    <option value="">- Pilih -</option>
                                    <option value="0" <?php if ($user->level == "0") {
                                                            echo 'selected';
                                                        } ?>>Admin</option>
                                    <option value="1" <?php if ($user->level == "1") {
                                                            echo 'selected';
                                                        } ?>>Pimpinan Yayasan</option>
                                    <option value="2" <?php if ($user->level == "2") {
                                                            echo 'selected';
                                                        } ?>>Pimpinan Fakultas</option>
                                    <option value="3" <?php if ($user->level == "3") {
                                                            echo 'selected';
                                                        } ?>>Pimpinan Program Studi</option>
                                    <option value="4" <?php if ($user->level == "4") {
                                                            echo 'selected';
                                                        } ?>>Pimpinan Divisi</option>
                                    <option value="5" <?php if ($user->level == "5") {
                                                            echo 'selected';
                                                        } ?>>Non Jabatan</option>
                                </select>

                                @if ($errors->has('level'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('level') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label for="cluster" class="col-md-4 col-form-label">{{ __('Cluster *') }}</label>
                            <div class="col-md-6">
                                <select id="cluster" type="text" class="form-select{{ $errors->has('cluster') ? ' is-invalid' : '' }}" name="cluster" value="{{ old('cluster')}}" required>
                                    <option value="">- Pilih -</option>
                                    <option value="Tetap" <?php if ($user->cluster == "Tetap") {
                                                                echo 'selected';
                                                            } ?>>Tetap</option>
                                    <option value="Honorer" <?php if ($user->cluster == "Honorer") {
                                                                echo 'selected';
                                                            } ?>>Honorer</option>
                                    <option value="Satpam" <?php if ($user->cluster == "Satpam") {
                                                                echo 'selected';
                                                            } ?>>Satpam</option>
                                    <option value="OB" <?php if ($user->cluster == "OB") {
                                                            echo 'selected';
                                                        } ?>>OB</option>
                                    <option value="Lainnya" <?php if ($user->cluster == "Lainnya") {
                                                                echo 'selected';
                                                            } ?>>Lainnya</option>
                                </select>

                                @if ($errors->has('cluster'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('cluster') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <ol class="breadcrumb">
                            <li class="active">
                                <center><i class="fa fa-user"></i> Data Diri</center>
                            </li>
                        </ol>
                        <div class="form-group row">
                            <label for="nik" class="col-md-4 col-form-label text-md-right">{{ __('NIK*') }}</label>
                            <div class="col-md-8">
                                <input id="nik" type="number" min="0" class="form-control{{ $errors->has('nik') ? ' is-invalid' : '' }}" name="nik" value="{{ $user->nik}}" required autofocus>

                                @if ($errors->has('nik'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('nik') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="nama" class="col-md-4 col-form-label text-md-right">{{ __('Nama Lengkap *') }}</label>
                            <div class="col-md-8">
                                <input id="nama" type="text" class="form-control{{ $errors->has('nama') ? ' is-invalid' : '' }}" name="nama" value="{{ $user->nama }}" required autofocus>

                                @if ($errors->has('nama'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('nama') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="gelar" class="col-md-4 col-form-label text-md-right">{{ __('Gelar') }}</label>
                            <div class="col-md-4">
                                <input id="gelar" type="text" min="0" class="form-control{{ $errors->has('gelar') ? ' is-invalid' : '' }}" name="gelar" value="{{ $user->gelar}}">

                                @if ($errors->has('gelar'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('gelar') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="jabatan" class="col-md-4 col-form-label text-md-right">{{ __('Jabatan') }}</label>
                            <div class="col-md-8">
                                <input id="jabatan" type="text" min="0" class="form-control{{ $errors->has('jabatan') ? ' is-invalid' : '' }}" name="jabatan" value="{{ $user->jabatan}}">

                                @if ($errors->has('jabatan'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('jabatan') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="alamat_ktp" class="col-md-4 col-form-label text-md-right">{{ __('Alamat KTP') }}</label>
                            <div class="col-md-8">
                                <input id="alamat_ktp" type="text" min="0" class="form-control{{ $errors->has('alamat_ktp') ? ' is-invalid' : '' }}" name="alamat_ktp" value="{{ $user->alamat_ktp}}">

                                @if ($errors->has('alamat_ktp'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('alamat_ktp') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="alamat_domisili" class="col-md-4 col-form-label text-md-right">{{ __('Alamat Domisili') }}</label>
                            <div class="col-md-8">
                                <input id="alamat_domisili" type="text" min="0" class="form-control{{ $errors->has('alamat_domisili') ? ' is-invalid' : '' }}" name="alamat_domisili" value="{{ $user->alamat_domisili}}">

                                @if ($errors->has('alamat_domisili'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('alamat_domisili') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="no_hp" class="col-md-4 col-form-label text-md-right">Nomor Handphone</label>
                            <div class="col-md-8">
                                <input id="no_hp" type="number" min="0" class="form-control{{ $errors->has('no_hp') ? ' is-invalid' : '' }}" name="no_hp" value="{{ $user->no_hp}}">

                                @if ($errors->has('no_hp'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('no_hp') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary float-right"><i class="fas fa-save"></i> Simpan</button>
            </div>
            {{Form::close()}}
        </div>
    </div>
</div>

@endsection