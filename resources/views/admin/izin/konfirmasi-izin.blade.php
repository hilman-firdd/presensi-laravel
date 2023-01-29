@extends('layouts.app-admin')
@section('content')
<h2 class="mt-3">Konfirmasi Pengajuan Izin</h2>
<ol class="breadcrumb mb-3">
  <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">Home</a></li>
  <li class="breadcrumb-item active">Daftar Konfirmasi Pengajuan Izin</li>
</ol>

<div class="card mb-12">
      <div class="card-body">
        <div class="table table-responsive">
          <table class="display table table-bordered table-striped table-bordered table-hover" id="dataTable">
            <thead>
              <tr>
                <th class="text-center">No</th>
                <th class="text-center">Nama</th>
                <th class="text-center">Tanggal Pengajuan</th>
                <th class="text-center">Alasan</th>
                <th class="text-center">Jenis izin</th>
                <th class="text-center">Lampiran</th>
                <th class="text-center">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php $no = 1; ?> 
              @foreach($data as $row)
              <tr>
                <td align="center">{{$no}}</td>
                <td>{{$row->nama}}</td>
                <td align="center">{{date_format(date_create($row->tanggal),"d F Y")}}</td>
                <td>{!!$row->alasan!!}</td>
                <td>
                  @if($row->jenis_izin == 'D')
                  Dinas
                  @elseif($row->jenis_izin == ']I2')
                  Tidak presensi pulang
                  @elseif($row->jenis_izin == 'I1')
                  Izin Telat
                  @endif
                </td>
                <td align="center"><a target="_blank" href="{{asset('/img/izin')}}/{{$row->bukti_pendukung}}"><i class="fa fa-download"></i></a></td>
                <td align="center">
                  <a href="{!! url('/'.$row->id.'/konfirmasi-tolak-izin') !!}">
                    <button type="submit" name="tolak_pengajuan" class="btn btn-danger btn-sm tooltipku">Tolak</button>
                  </a>
                  <a href="{!! url('/'.$row->id.'/konfirmasi-izinkan-izin') !!}">
                    <button type="submit" name="terima_pengajuan" class="btn btn-primary btn-sm tooltipku">Izinkan</button>
                  </a>
                </td>
              </tr>
              <?php $no++; ?>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
</div>
<br/>
<div class="card mb-12">
      <div class="card-header bg-primary" style="color: white;"><i class="fas fa-address-book mr-1"></i> Daftar Terkonfirmasi</div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="display table table-bordered table-striped table-bordered table-hover" id="dataTable2">
            <thead>
              <tr>
                <th class="text-center">No</th>
                <th class="text-center">Nama</th>
                <th class="text-center">Tanggal Pengajuan</th>
                <th class="text-center">Alasan</th>
                <th class="text-center">Jenis izin</th>
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
                <td>
                  @if($row->jenis_izin == 'D')
                  Dinas
                  @elseif($row->jenis_izin == ']I2')
                  Tidak presensi pulang
                  @elseif($row->jenis_izin == 'I1')
                  Izin Telat
                  @endif
                </td>
                <td align="center"><a target="_blank" href="{{asset('/assets/file/izin')}}/{{$row->bukti_pendukung}}"><i class="fa fa-download"></i></a></td>
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

  </div>
</div>

<script type="text/javascript">
  $(document).ready(function(){
    $('#select_all').on('click',function(){
      if(this.checked){
        $('.checkbox').each(function(){
          this.checked = true;
        });
      }else{
       $('.checkbox').each(function(){
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