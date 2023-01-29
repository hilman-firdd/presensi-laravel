@extends('layouts.app-admin')

@section('content')

<h2 class="mt-3">Tambah User</h2>
<ol class="breadcrumb mb-3">
    <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{url('/manajemen-user')}}">Master User</a></li>
    <li class="breadcrumb-item active">Tambah User</li>
</ol>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <a href="{{url('/manajemen-user')}}" class="text-dark text-decoration-none">
                    <i class="fas fa-arrow-left"></i>&ensp;Kembali
                </a>
            </div>
            <div class="card-body">
                {!! Form::open(['url' => ['/simpan-user']]) !!}
                <div class="row">
                    <div class="col-md-6">

                        <h5 class="mb-3"><i class="fa fa-key"></i> Data Login</h5>
                        
                        <div class="form-group row mb-3">
                            <label for="email" class="col-md-4 col-form-label">{{ __('E-Mail') }}</label>
                            <div class="col-md-8">
                                <input id="email" type="email"
                                    class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email"
                                    value="{{ old('email') }}" required="required" autofocus>

                                @if ($errors->has('email'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label for="username"
                                class="col-md-4 col-form-label">{{ __('Username *') }}</label>
                            <div class="col-md-8">
                                <input id="username" type="text"
                                    class="form-control{{ $errors->has('username') ? ' is-invalid' : '' }}"
                                    name="username" value="{{ old('username') }}" required>

                                @if ($errors->has('username'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('username') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label for="password"
                                class="col-md-4 col-form-label">{{ __('Password*') }}</label>

                            <div class="col-md-8">
                                <input id="password" type="password"
                                    class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                                    name="password" value="{{ old('password_view')}}" required>

                                @if ($errors->has('password'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label for="level" class="col-md-4 col-form-label">{{ __('Level *') }}</label>
                            <div class="col-md-6">
                                <select id="level" type="text"
                                    class="form-select{{ $errors->has('level') ? ' is-invalid' : '' }}" name="level"
                                    value="{{ old('level')}}" required autofocus>
                                    <option value="">- Pilih -</option>
                                    <option value="0">Admin</option>
                                    <option value="1">Pimpinan Yayasan</option>
                                    <option value="2">Pimpinan Fakultas</option>
                                    <option value="3">Pimpinan Program Studi</option>
                                    <option value="4">Pimpinan Divisi</option>
                                    <option value="5">Non Jabatan</option>
                                </select>

                                @if ($errors->has('level'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('level') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label for="cluster"
                                class="col-md-4 col-form-label">{{ __('Cluster *') }}</label>
                            <div class="col-md-6">
                                <select id="cluster" type="text"
                                    class="form-select{{ $errors->has('cluster') ? ' is-invalid' : '' }}"
                                    name="cluster" value="{{ old('cluster')}}" required>
                                    <option value="">- Pilih -</option>
                                    <option>Tetap</option>
                                    <option>Honorer</option>
                                    <option>Satpam</option>
                                    <option>OB</option>
                                    <option>Lainnya</option>
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

                        <h5 class="mb-3"><i class="fa fa-user"></i> Data Diri</h5>

                        <div class="form-group row mb-3">
                            <label for="nik" class="col-md-4 col-form-label">{{ __('NIK*') }}</label>
                            <div class="col-md-8">
                                <input id="nik" type="number" min="0"
                                    class="form-control{{ $errors->has('nik') ? ' is-invalid' : '' }}" name="nik"
                                    value="{{ old('nik')}}" required>

                                @if ($errors->has('nik'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('nik') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label for="nama"
                                class="col-md-4 col-form-label">{{ __('Nama Lengkap *') }}</label>
                            <div class="col-md-8">
                                <input id="nama" type="text"
                                    class="form-control{{ $errors->has('nama') ? ' is-invalid' : '' }}" name="nama"
                                    value="{{ old('nama') }}" required autofocus>

                                @if ($errors->has('nama'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('nama') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label for="gelar" class="col-md-4 col-form-label">{{ __('Gelar') }}</label>
                            <div class="col-md-4">
                                <input id="gelar" type="text" min="0"
                                    class="form-control{{ $errors->has('gelar') ? ' is-invalid' : '' }}" name="gelar"
                                    value="{{ old('gelar')}}">

                                @if ($errors->has('gelar'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('gelar') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label for="jabatan"
                                class="col-md-4 col-form-label">{{ __('Jabatan') }}</label>
                            <div class="col-md-8">
                                <input id="jabatan" type="text" min="0"
                                    class="form-control{{ $errors->has('jabatan') ? ' is-invalid' : '' }}"
                                    name="jabatan" value="{{ old('jabatan')}}">

                                @if ($errors->has('jabatan'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('jabatan') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label for="alamat_ktp"
                                class="col-md-4 col-form-label">{{ __('Alamat KTP') }}</label>
                            <div class="col-md-8">
                                <input id="alamat_ktp" type="text" min="0"
                                    class="form-control{{ $errors->has('alamat_ktp') ? ' is-invalid' : '' }}"
                                    name="alamat_ktp" value="{{ old('alamat_ktp')}}">

                                @if ($errors->has('alamat_ktp'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('alamat_ktp') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label for="alamat_domisili"
                                class="col-md-4 col-form-label">{{ __('Alamat Domisili') }}</label>
                            <div class="col-md-8">
                                <input id="alamat_domisili" type="text" min="0"
                                    class="form-control{{ $errors->has('alamat_domisili') ? ' is-invalid' : '' }}"
                                    name="alamat_domisili" value="{{ old('alamat_domisili')}}">

                                @if ($errors->has('alamat_domisili'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('alamat_domisili') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label for="no_hp" class="col-md-4 col-form-label">Nomor Handphone</label>
                            <div class="col-md-8">
                                <input id="no_hp" type="number" min="0"
                                    class="form-control{{ $errors->has('no_hp') ? ' is-invalid' : '' }}" name="no_hp"
                                    value="{{ old('no_hp')}}">

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
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
            </div>
            {{Form::close()}}
        </div>
    </div>
</div>

@endsection