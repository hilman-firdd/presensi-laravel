@extends('layouts.app-admin')

@section('content')

<h2 class="mt-3">Detail User</h2>
<ol class="breadcrumb mb-3">
    <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{url('/manajemen-user')}}">Master User</a></li>
    <li class="breadcrumb-item active">Detail User</li>
</ol>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <a href="{{url('manajemen-user')}}" class="text-dark text-decoration-none">
                    <i class="fas fa-arrow-left"></i>&ensp;Kembali</a>
            </div>
            <div class="card-body">
                @foreach($data as $row)
                <div class="row">
                    <div class="col-md-6">

                        <h5 class="mb-3"><i class="fa fa-key"></i> Data Login</h5>

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label">{{ __('E-Mail') }}</label>
                            <div class="col-md-8">
                                : {{$row->email}}
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="username" class="col-md-4 col-form-label">{{ __('Username') }}</label>
                            <div class="col-md-8">
                                : {{$row->username}}
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label">{{ __('Password') }}</label>

                            <div class="col-md-8">
                                : {{$row->password_view}}
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label for="level" class="col-md-4 col-form-label text-md-right">{{ __('Level') }}</label>
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
                            <label for="cluster" class="col-md-4 col-form-label">{{ __('Cluster') }}</label>
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

                        <h5 class="mb-3"><i class="fa fa-user"></i> Data Diri</h5>

                        <div class="form-group row">
                            <label for="nik" class="col-md-4 col-form-label">{{ __('NIK') }}</label>
                            <div class="col-md-8">
                                : {{$row->nik}}
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="nama" class="col-md-4 col-form-label">{{ __('Nama Lengkap') }}</label>
                            <div class="col-md-8">
                                : {{$row->nama}}
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="gelar" class="col-md-4 col-form-label">{{ __('Gelar') }}</label>
                            <div class="col-md-4">
                                : {{$row->gelar}}
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="jabatan" class="col-md-4 col-form-label">{{ __('Jabatan') }}</label>
                            <div class="col-md-8">
                                : {{$row->jabatan}}
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="alamat_ktp" class="col-md-4 col-form-label">{{ __('Alamat KTP') }}</label>
                            <div class="col-md-8">
                                : {{$row->alamat_ktp}}
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="alamat_domisili" class="col-md-4 col-form-label">{{ __('Alamat Domisili') }}</label>
                            <div class="col-md-8">
                                : {{$row->alamat_domisili}}
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="no_hp" class="col-md-4 col-form-label">Nomor Handphone</label>
                            <div class="col-md-8">
                                : {{$row->no_hp}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @endforeach
        </div>
    </div>
</div>

@endsection