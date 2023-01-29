@extends('layouts.app-admin')
@section('content')
<h2 class="mt-3">Konfirmasi Pengajuan Cuti</h2>
<ol class="breadcrumb mb-3">
  <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">Home</a></li>
  <li class="breadcrumb-item active">Daftar Konfirmasi Pengajuan Cuti</li>
</ol>

<div class="card mb-12">

  <div class="card-body">
    <!-- {!! Form::open(['url' => 'konfirmasi-cuti/{id}']) !!} -->
    <div class="table-responsive">
      <table class="table table-bordered table-striped table-bordered table-hover" id="dataTable" width="100%">
        <thead>
          <tr>
            <!-- <th width="2%" class="text-center"><input type="checkbox" name="select_all" id="select_all" value=""/></th> -->
            <th class="text-center">No</th>
            <th class="text-center">Nama</th>
            <th class="text-center">Tanggal Pengajuan</th>
            <th class="text-center">Alasan</th>
            <th class="text-center">Jenis Cuti</th>
            <th class="text-center">Lampiran</th>
            <th class="text-center">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php $no = 1; ?>
          @foreach($data as $row)
          <tr>
            <!-- <td align="center"><label class="checkbox-inline"><input type="checkbox" name="checked_id[]" class="checkbox" value="{{$row->id}}"/></label></td> -->
            <td align="center">{{$no}}</td>
            <td>{{$row->nama}}</td>
            <td align="center">{{date_format(date_create($row->tanggal),"d F Y")}}</td>
            <td>{!!$row->alasan!!}</td>
            <td>{!!$row->jenis_cuti!!}</td>
            <td align="center"><a target="_blank" href="{{asset('/assets/file/cuti')}}/{{$row->bukti_pendukung}}"><i class="fa fa-download"></i></a></td>
            <td align="center">
              <a href="{!! url('/'.$row->id.'/konfirmasi-tolak-cuti') !!}" name="tolak_pengajuan" class="btn btn-danger btn-sm tooltipku">
                Tolak
              </a>
              <a href="{!! url('/'.$row->id.'/konfirmasi-izinkan-cuti') !!}" name="terima_pengajuan" class="btn btn-primary btn-sm tooltipku">
                Izinkan
              </a>
            </td>
          </tr>
          <?php $no++; ?>
          @endforeach
        </tbody>
      </table>
    </div>
    <!--  <div class="row">
                  <div class="col-xl-12">
                    <div class="card mb-4">
                      <div class="card-header"><strong>ALL ACTION SELECTION</strong></div>
                      <div class="card-body">
                        <div class="table table-responsive">
                          <table class="table table-bordered table-hover table-striped">
                            <tbody>
                              <tr>
                                <td align="center"><input type="submit" class="btn btn-danger btn-block" name="tolak_pengajuan" value="TOLAK PENGAJUAN"/>
                                  <div class="alert alert-danger">
                                    <strong>Keterangan:</strong> Tandai pengajuan cuti yang di tolak kemudian klik tombol Tolak Pengajuan
                                </div></td>
                                <td align="center">
                                    <button class="btn btn-primary btn-block" name="terima_pengajuan">TERIMA PENGAJUAN</button>
                                    <div class="alert alert-info">
                                      <strong>Keterangan:</strong> Tandai pengajuan cuti yang di terimakemudian klik tombol Terima Pengajuan
                                  </div>
                              </td>
                          </tr>
                      </tbody>
                  </table>
              </div>
          </div>
      </div>
  </div>
</div> -->
    <!-- {{Form::close()}} -->
  </div>
</div>
<br />
<div class="card mb-12">
  <div class="card-header bg-primary" style="color: white;"><i class="fas fa-address-book mr-1"></i> Daftar Terkonfirmasi</div>
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-bordered table-striped table-bordered table-hover" id="dataTable2">
        <thead>
          <tr>
            <th class="text-center">No</th>
            <th class="text-center">Nama</th>
            <th class="text-center">Tanggal Pengajuan</th>
            <th class="text-center">Alasan</th>
            <th class="text-center">Jenis cuti</th>
            <th class="text-center">Lampiran</th>
            <th class="text-center">Status</th>
          </tr>
        </thead>
        <tbody>
          <?php $no = 1; ?>
          @foreach($data2 as $row)
          <tr>
            <td align="center">{{$no}}</td>
            <td>{{$row->nama}}</td>
            <td align="center">{{date_format(date_create($row->tanggal),"d F Y")}}</td>
            <td>{!!$row->alasan!!}</td>
            <td>{{$row->jenis_cuti}}</td>
            <td align="center"><a target="_blank" href="{{asset('/assets/file/cuti')}}/{{$row->bukti_pendukung}}"><i class="fa fa-download"></i></a></td>
            @if($row->status == 'di terima')
            <td align="center" class="bg-success" style="color:white;">{{$row->status}}</td>
            @elseif($row->status == 'di tolak')
            <td align="center" class="bg-danger" style="color:white;">{{$row->status}}</td>
            @endif
          </tr>
          <?php $no++; ?>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>



<script type="text/javascript">
  $(document).ready(function() {
    $('#select_all').on('click', function() {
      if (this.checked) {
        $('.checkbox').each(function() {
          this.checked = true;
        });
      } else {
        $('.checkbox').each(function() {
          this.checked = false;
        });
      }
    });
  });

  $('input[type="checkbox"]').on('change', function() {
    $(this).closest('td').find('input').not(this).prop('checked', false);
  });
</script>
@endsection