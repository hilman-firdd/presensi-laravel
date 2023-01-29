@extends('layouts.app-admin')
@section('content')
<h1 class="h2 mt-3">Pengajuan Cuti</h1>
<ol class="breadcrumb mb-3">
<li class="breadcrumb-item"><a href="{{url('/dashboard')}}">Home</a></li>
<li class="breadcrumb-item active">Pengajuan Cuti</li>
</ol>
<div class="row">
  <div class="col-xl-12">
    <div class="card mb-12">
      <div class="card-body">
       {!! Form::open(['files'=>true, 'url' => ['/simpan-pengajuan-cuti']]) !!}
       <div class="row">
        <div class="col-md-5">
          <div class="form-group mb-3">
            <label class="col-md-7 col-sm-7 col-xs-12 ">Alasan Pengajuan Cuti*</label>
            <div class="col-md-12 col-sm-12 col-xs-12">
              <textarea type="text" class="form-control" name="alasan" rows="5"></textarea>
            </div>
          </div>
          <div class="form-group mb-3">
                <label class="col-md-12 col-sm-12 col-xs-12 ">Pilih Jenis Cuti*</label>
                <div class="col-md-12 col-sm-12 col-xs-12">
                  <select name="id_cuti" class="form-select">
                    <option value="">- Pilih -</option>
                    @foreach($jeniscuti as $row)
                    <option value="{{$row->id}}">{{$row->jenis_cuti}}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="form-group mb-3">
                <label class="col-md-12 col-sm-12 col-xs-12 ">Lampiran Pendukung (.PDF)</label>
                <div class="col-md-12 col-sm-12 col-xs-12">
                  <input type="file" name="lampiran" class="form-control">
                </div>
              </div>
        </div>
        <div class="col-md-7">
        <div class="card">
            <div class="card-header">
              Pilih Tanggal Cuti
            </div>
            <div class="card-body row">
              <?php
              while (strtotime($tanggal1)<strtotime($tanggal2)){
                $tanggal1 = mktime(0,0,0,date("m",strtotime($tanggal1)),date("d",strtotime($tanggal1))+1,date("Y",strtotime($tanggal1)));
                $tanggal1=date("Y-m-d", $tanggal1);
                echo "<div class='col-md-4'>";
                echo "<input type='checkbox' name='tanggal[]' value='$tanggal1'> ".date_format(date_create($tanggal1),"d F Y");
                echo "</div>";
              }
              ?>
            </div>
          </div>
          
        </div>
      </div>
      <button class="btn btn-primary float-right" type="submit"><i class="fas fa-save"></i> Simpan</button>
      {!! Form::close() !!}
    </div>
  </div>
</div>
</div>
</div>
</div>

@endsection