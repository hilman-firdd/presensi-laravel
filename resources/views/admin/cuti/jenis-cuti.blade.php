@extends('layouts.app-admin')
@section('content')
<h2 class="mt-3">Jenis Cuti</h2>
<ol class="breadcrumb mb-3">
    <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">Home</a></li>
    <li class="breadcrumb-item active">Jenis Cuti</li>
</ol>
<div class="row">
    <div class="col-xl-12">
        <div class="card mb-12">
            <div class="card-header">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#myModal">
                 <i class="fa fa-plus"></i> Tambah
             </button></div>
             <div class="card-body">
                <div class="table table-responsive">
                    <table class="table table-bordered table-striped table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-center" width="20%">Jenis Cuti</th>
                                <th class="text-center">Keterangan</th>
                                <th class="text-center" width="10%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; ?>
                            @foreach($data as $key => $value)
                            <tr>
                                <td align="center">{{$no}}</td>
                                <td>{!!$value->jenis_cuti!!}</td>
                                <td>{!!$value->keterangan!!}</td>
                                <td align="center">
                                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#largeModal{{$value->id}}"> <i class="fa fa-edit"></i></button>
                                    <a title="Hapus" href="#" type="button" class="btn btn-danger btn-sm tooltipku" data-bs-toggle="modal" data-bs-target="#myModal1{{$value->id}}"><i class="fa fa-trash"></i></a>
                                </tr>
                                <?php $no++; ?>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<div class="modal fade " id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document" >
  {!! Form::open(['url' => 'simpan-jenis-cuti']) !!}
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Tambah Jenis Cuti</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">

        <div class="form-group row mb-3">
            <label for="jenis_cuti" class="col-md-2 col-form-label">{{ __('Jenis Cuti *') }}</label>
            <div class="col-md-10">
              <input id="jenis_cuti" type="text" class="form-control{{ $errors->has('jenis_cuti') ? ' is-invalid' : '' }}" name="jenis_cuti" value="{{ old('jenis_cuti') }}" required autofocus>
              @if ($errors->has('jenis_cuti'))
              <span class="invalid-feedback">
                <strong>{{ $errors->first('jenis_cuti') }}</strong>
            </span>
            @endif
            </div>
        </div>
        <div class="form-group row mb-3">
            <label for="jenis_cuti" class="col-md-2 col-form-label">{{ __('Keterangan *') }}</label>
            <div class="col-md-10">
          <textarea class="form-control mb-3" name="keterangan" id="summernote" required></textarea>
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
{!! Form::model($row, ['url' => ['/update-jenis-cuti', $row->id]]) !!}
<div class="modal fade" id="largeModal{{$row->id}}" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
    {!! Form::open(['url' => 'simpan-jenis-cuti']) !!}
        <div class="modal-content">
          <div class="modal-header">
          <h5 class="modal-title">Edit Jenis Cuti</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
           
            <div class="form-group row mb-3">
                <label for="jenis_cuti" class="col-md-2 col-form-label">{{ __('Jenis Cuti *') }}</label>
                <div class="col-md-10">
                    <input id="jenis_cuti" type="text" class="form-control{{ $errors->has('jenis_cuti') ? ' is-invalid' : '' }}" name="jenis_cuti" value="{{ $row->jenis_cuti }}" required autofocus>

                  @if ($errors->has('jenis_cuti'))
                  <span class="invalid-feedback">
                    <strong>{{ $errors->first('jenis_cuti') }}</strong>
                </span>
                @endif
                </div>
            </div>
            <div class="form-group row mb-3">
            <label for="jenis_cuti" class="col-md-2 col-form-label">{{ __('Keterangan *') }}</label>
                <div class="col-md-10">
                  <textarea class="form-control" name="keterangan" id="summernote2" required>{{$row->keterangan}}</textarea>
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
<div class="modal fade" id="myModal1{{$row->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
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
            <a title="Hapus" href="{!! url('/'.$row->id.'/delete-jenis-cuti') !!}" class="btn btn-danger" ><i class="fa fa-trash"></i> Ok</a>
        </div>
    </div>
</div>
</div>
@endforeach
@endsection