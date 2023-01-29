@extends('layouts.app-admin')
@section('content')
<h2 class="mt-3">Jatah Cuti</h2>
<ol class="breadcrumb mb-3">
    <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">Home</a></li>
    <li class="breadcrumb-item active">Jatah Pengajuan Cuti</li>
</ol>
<div class="row">
    <div class="col-xl-12">
        <div class="card mb-12">
            <div class="card-header">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#myModal"><i class="fa fa-plus"></i> Tambah
                </button>
            </div>
            <div class="card-body">
                <div class="table table-responsive">
                <table class="table table-bordered table-striped table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-center">NIK</th>
                            <th class="text-center">Nama</th>
                            <th class="text-center">Unit</th>
                            <th class="text-center">Cuti Tahunan</th>
                            <th class="text-center">Cuti Bersama</th>
                            <th class="text-center">Cuti Berjalan</th>
                            <th class="text-center">Cuti Lain</th>
                            <th class="text-center" width="10%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no=1; ?>
                        @foreach($data as $row)
                        <tr>
                            <td align="center">{{$no}}</td>
                            <td align="center">{{$row->nik}}</td>
                            <td>{{$row->nama}}</td>
                            <td align="center">{{$row->unit}}</td>
                            <td align="center">{{$row->cuti_tahunan}}</td>
                            <td align="center">{{$row->cuti_bersama}}</td>
                            <td align="center">{{$row->cuti_berjalan}}</td>
                            <td align="center">{{$row->cuti_lain}}</td>
                            <td align="center">
                                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#largeModal{{$row->id}}"> <i class="fa fa-edit"></i></button>
                                <a title="Hapus" href="#" type="button" class="btn btn-danger btn-sm tooltipku" data-bs-toggle="modal" data-bs-target="#myModal{{$row->id}}"><i class="fa fa-trash"></i></a>
                            </td>
                        </tr>
                        <?php $no++;?>
                        @endforeach
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document" >
        {!! Form::open(['url' => 'simpan-jatah-cuti']) !!}
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Jatah Cuti</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="form-group row mb-3">
                    <label for="nama" class="col-md-4 col-form-label">{{ __('Nama *') }}</label>
                    <div class="col-md-8">
                    <select name="id_user" class="form-control" data-live-search="true">
                        <option value="" data-tokens="mustard">- Pilih -</option>
                        @foreach($nama as $row)
                        <option value="{{$row->id}}" data-tokens="mustard">{{$row->nama}}</option>
                        @endforeach
                    </select>
                    </div>
                </div>

                <div class="form-group row mb-3">
                    <label for="cuti_tahunan" class="col-md-4 col-form-label">{{ __('Cuti Tahunan *') }}</label>
                    <div class="col-md-3">
                    <input id="cuti_tahunan" type="number" class="form-control{{ $errors->has('cuti_tahunan') ? ' is-invalid' : '' }}" name="cuti_tahunan" value="{{ old('cuti_tahunan') }}" required autofocus>

                    @if ($errors->has('cuti_tahunan'))
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first('cuti_tahunan') }}</strong>
                    </span>
                    @endif
                    </div>
                </div>

                <div class="form-group row mb-3">
                    <label for="cuti_bersama" class="col-md-4 col-form-label">{{ __('Cuti bersama *') }}</label>
                    <div class="col-md-3">
                    <input id="cuti_bersama" type="number" class="form-control{{ $errors->has('cuti_bersama') ? ' is-invalid' : '' }}" name="cuti_bersama" value="{{ old('cuti_bersama') }}" required autofocus>

                    @if ($errors->has('cuti_bersama'))
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first('cuti_bersama') }}</strong>
                    </span>
                    @endif
                    </div>
                </div>
                <div class="form-group row mb-3">
                    <label for="cuti_berjalan" class="col-md-4 col-form-label">{{ __('Cuti Berjalan *') }}</label>
                    <div class="col-md-3">
                        <input id="cuti_berjalan" type="number" value="0" class="form-control{{ $errors->has('cuti_berjalan') ? ' is-invalid' : '' }}" name="cuti_berjalan" required autofocus>

                        @if ($errors->has('cuti_berjalan'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('cuti_berjalan') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>
                <div class="form-group row mb-3">
                    <label for="cuti_lain" class="col-md-4 col-form-label">{{ __('Cuti Lain *') }}</label>
                    <div class="col-md-3">
                    <input id="cuti_lain" type="number" value="0" class="form-control{{ $errors->has('cuti_lain') ? ' is-invalid' : '' }}" name="cuti_lain" required autofocus>

                    @if ($errors->has('cuti_lain'))
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first('cuti_lain') }}</strong>
                    </span>
                    @endif
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
            </div>
        </div>
        {!! Form::close() !!}
    </div>      
</div>

@foreach ($data as $row)    
<div class="modal fade" id="largeModal{{$row->id}}" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
    {!! Form::model($row, ['url' => ['/update-jatah-cuti', $row->id]]) !!}
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title">Edit Jatah Cuti</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
        
        <div class="modal-body">
            <div class="form-group row mb-3">
                <label for="cuti_tahunan" class="col-md-4 col-form-label">{{ __('Cuti Tahunan *') }}</label>
                <div class="col-md-3">
                  <input id="cuti_tahunan" type="number" class="form-control{{ $errors->has('cuti_tahunan') ? ' is-invalid' : '' }}" name="cuti_tahunan" value="{{$row->cuti_tahunan}}" required autofocus>
                  @if ($errors->has('cuti_tahunan'))
                  <span class="invalid-feedback">
                    <strong>{{ $errors->first('cuti_tahunan') }}</strong>
                </span>
                @endif
                </div>
            </div>
            <div class="form-group row mb-3">
                <label for="cuti_bersama" class="col-md-4 col-form-label">{{ __('Cuti bersama *') }}</label>
                <div class="col-md-3">
                <input id="cuti_bersama" type="number" class="form-control{{ $errors->has('cuti_bersama') ? ' is-invalid' : '' }}" name="cuti_bersama" value="{{ $row->cuti_bersama }}" required autofocus>
                @if ($errors->has('cuti_bersama'))
                <span class="invalid-feedback">
                    <strong>{{ $errors->first('cuti_bersama') }}</strong>
                </span>
                @endif
                </div>
            </div>
            <div class="form-group row mb-3">
                <label for="cuti_berjalan" class="col-md-4 col-form-label">{{ __('Cuti Berjalan *') }}</label>
                <div class="col-md-3">
                    <input id="cuti_berjalan" type="number" value="{{ $row->cuti_berjalan }}" class="form-control{{ $errors->has('cuti_berjalan') ? ' is-invalid' : '' }}" name="cuti_berjalan" required autofocus>

                    @if ($errors->has('cuti_berjalan'))
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first('cuti_berjalan') }}</strong>
                    </span>
                    @endif
                </div>
            </div>
            <div class="form-group row mb-3">
                <label for="cuti_lain" class="col-md-4 col-form-label">{{ __('Cuti Lain *') }}</label>
                <div class="col-md-3">
                <input id="cuti_lain" type="number" value="{{ $row->cuti_lain }}" class="form-control{{ $errors->has('cuti_lain') ? ' is-invalid' : '' }}" name="cuti_lain" required autofocus>
                @if ($errors->has('cuti_lain'))
                <span class="invalid-feedback">
                    <strong>{{ $errors->first('cuti_lain') }}</strong>
                </span>
                @endif
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
        </div>
    </div>
    {!! Form::close() !!}
    </div>   
</div>    

@endforeach

@foreach($data as $row)
<div class="modal fade" id="myModal{{$row->id}}" tabindex="-1" aria-hidden="true" aria-labelledby="myModal{{$row->id}}">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title">Hapus Data ?</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            Apakah Anda yakin ingin menghapus Data?
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            
            <a title="Hapus" href="{!! url('/'.$row->id.'/delete-jatah-cuti') !!}" class="btn btn-danger" ><i class="fa fa-trash"></i> OK</a>
           
            </div>
        </div>
    </div>
</div>
@endforeach

@endsection