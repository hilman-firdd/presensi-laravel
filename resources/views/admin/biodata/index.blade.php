@extends('layouts.app-admin')
@section('content')
<h2 class="mt-3">Biodata Profil Saya</h2>
<ol class="breadcrumb mb-3">
<li class="breadcrumb-item"><a href="{{url('/dashboard')}}">Home</a></li>
<li class="breadcrumb-item active">Biodata Profil Saya</li>
</ol>

<div class="card">
    <div class="card-body">
        {!! Form::open(['url' => ['/update-biodata']]) !!}
        <div class="row">
        <div class="col-md-6">

                <h5 class="mb3"><i class="fa fa-user"></i> Data Diri</h5>
                 
                <div class="form-group row mb-3">
                    <label for="nik" class="col-md-4 col-form-label text-md-right">{{ __('NIK*') }}</label>
                    <div class="col-md-8">
                        <input id="nik" type="number" min="0" class="form-control{{ $errors->has('nik') ? ' is-invalid' : '' }}" name="nik" value="{{Auth::user()->nik}}" required autofocus>

                        @if ($errors->has('nik'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('nik') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>
                <div class="form-group row mb-3">
                    <label for="nama" class="col-md-4 col-form-label text-md-right">{{ __('Nama Lengkap *') }}</label>
                    <div class="col-md-8">
                        <input id="nama" type="text" class="form-control{{ $errors->has('nama') ? ' is-invalid' : '' }}" name="nama" value="{{ Auth::user()->nama }}" required autofocus>

                        @if ($errors->has('nama'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('nama') }}</strong>
                        </span>
                        @endif
                    </div>
                </div> 
                <div class="form-group row mb-3">
                    <label for="gelar" class="col-md-4 col-form-label text-md-right">{{ __('Gelar') }}</label>
                    <div class="col-md-4">
                        <input id="gelar" type="text" min="0" class="form-control{{ $errors->has('gelar') ? ' is-invalid' : '' }}" name="gelar" value="{{Auth::user()->gelar}}" >

                        @if ($errors->has('gelar'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('gelar') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>
                <div class="form-group row mb-3">
                    <label for="jabatan" class="col-md-4 col-form-label text-md-right">{{ __('Jabatan') }}</label>
                    <div class="col-md-8">
                        <input id="jabatan" type="text" min="0" class="form-control{{ $errors->has('jabatan') ? ' is-invalid' : '' }}" name="jabatan" value="{{Auth::user()->jabatan}}" >

                        @if ($errors->has('jabatan'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('jabatan') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>
                <div class="form-group row mb-3">
                    <label for="alamat_ktp" class="col-md-4 col-form-label text-md-right">{{ __('Alamat KTP') }}</label>
                    <div class="col-md-8">
                        <input id="alamat_ktp" type="text" min="0" class="form-control{{ $errors->has('alamat_ktp') ? ' is-invalid' : '' }}" name="alamat_ktp" value="{{Auth::user()->alamat_ktp}}" >

                        @if ($errors->has('alamat_ktp'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('alamat_ktp') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>
                <div class="form-group row mb-3">
                    <label for="alamat_domisili" class="col-md-4 col-form-label text-md-right">{{ __('Alamat Domisili') }}</label>
                    <div class="col-md-8">
                        <input id="alamat_domisili" type="text" min="0" class="form-control{{ $errors->has('alamat_domisili') ? ' is-invalid' : '' }}" name="alamat_domisili" value="{{Auth::user()->alamat_domisili}}" >

                        @if ($errors->has('alamat_domisili'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('alamat_domisili') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>
                <div class="form-group row mb-3">
                    <label for="no_hp" class="col-md-4 col-form-label text-md-right">Nomor Handphone</label>
                    <div class="col-md-8">
                        <input id="no_hp" type="number" min="0" class="form-control{{ $errors->has('no_hp') ? ' is-invalid' : '' }}" name="no_hp" value="{{Auth::user()->no_hp}}" >

                        @if ($errors->has('no_hp'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('no_hp') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-6">

                <h5 class="mb-3"><i class="fa fa-key"></i> Data Login</h5>
       
                <div class="form-group row mb-3">
                    <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail') }}</label>
                    <div class="col-md-8">
                        <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ Auth::user()->email }}" required="required">

                        @if ($errors->has('email'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>
                <div class="form-group row mb-3">
                    <label for="username" class="col-md-4 col-form-label text-md-right">{{ __('Username *') }}</label>
                    <div class="col-md-8">
                        <input id="username" type="text" class="form-control{{ $errors->has('username') ? ' is-invalid' : '' }}" name="username" value="{{ Auth::user()->username }}" required autofocus>

                        @if ($errors->has('username'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('username') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>
                <div class="form-group row mb-3">
                    <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password*') }}</label>

                    <div class="col-md-8">
                        <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" value="{{Auth::user()->password_view}}" required>

                        @if ($errors->has('password'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>

            </div>
            <div class="form-group mt-3">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
                </div>
        </div>
    </div>
    {{Form::close()}}
</div>

@endsection