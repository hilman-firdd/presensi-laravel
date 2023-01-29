@extends('layouts.app')
@section('content')
<div class="content-wrapper">
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="card">

          <div class="card-body">
            <h2><i class="fa fa-pencil"></i> Input Izin</h2>
            @if ($jam < '08:00:00' ) <div class="alert alert-danger"><i class="fas fa-info"></i>&ensp;Input izin pegawai baru bisa dilakukan diatas jam 08.00
          </div>
          @else
          <hr />
          <div class="row">
            <div class="col-md-12">
              {!! Form::open(['files'=>true, 'url' => 'simpan-izin-pegawai']) !!}
              <div class="form-group">
                <label>NIK*</label>
                <input type="text" name="nik" class="form-control {{ $errors->has('nik') ? 'is-invalid' : '' }}" placeholder="Masukan NIK">
                @if ($errors->has('nik'))
                <div class="invalid-feedback">
                  {{ $errors->first('nik') }}
                </div>
                @endif

              </div>
              <div class="form-group row">
                <div class="col">
                  <label>Tanggal*</label>
                  <input type="date" name="tanggal" class="form-control" value="{{$tanggal}}">
                </div>
                <div class="col">
                  <label>Jenis Izin*</label>
                  <select name="jenis_izin" class="form-control">
                    <option>- Keterangan -</option>
                    <option value="D">Dinas</option>
                    <option value="]I2">Tidak presensi pulang</option>
                    <option value="I1">Izin Telat</option>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label>Alasan</label>
                <textarea type="text" name="alasan" class="form-control"></textarea>
              </div>
              <div class="form-group row">
                <label for="gambar" class="col-md-2 col-form-label">{{ __('Gambar*') }}</label>
                <div class="col-md-5">
                  <input onchange="readGambar(event)" name="gambar" id="gambar" type="file" class="form-control{{ $errors->has('gambar') ? ' is-invalid' : '' }}" gambar="gambar" value="{{ old('gambar') }}" autofocus>
                  @if ($errors->has('gambar'))
                  <span class="invalid-feedback">
                    {{ $errors->first('gambar') }}
                  </span>
                  @endif
                  <div class="alert alert-light text-info mt-2">
                    *Format gambar JPG/ PNG), dengan ukuran 1280 x 300
                  </div>
                  <img id='output' style="width: 400px;">
                </div>
              </div>

            </div>
          </div>
          <div class="row">
            <div class="col-md-12 col-md-offset-2">
              <button class="btn btn-primary btn-wide transition-3d-hover">Simpan</button>
            </div>
            {!! Form::close() !!}
          </div>
          @endif
          <hr />
          <div class="row">
            <div class="col-md-12">
              <div class="table table-responsive">

                <table class="table table-striped table-bordered table-hover" id="dataTable">

                  <thead>

                    <tr>

                      <th>No</th>

                      <th>Nama</th>

                      <th>Keterangan</th>
                      <th>Keterangan Detail</th>
                      <th>Tanggal</th>

                    </tr>

                  </thead>

                  <tbody>

                    <?php $no = 1; ?>

                    @foreach($data as $row)

                    <tr>

                      <td>{{$no}}</td>

                      <td>{{$row->nama}}</td>

                      <td>
                        @if($row->keterangan == 1)
                        Ijin
                        @elseif($row->keterangan == 2)
                        Alpa
                        @elseif($row->keterangan == 3)
                        Cuti
                        @endif</td>
                      <td>{{$row->keterangan_rinci}}</td>
                      <td>{{$row->tanggal}}</td>

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
</div>
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