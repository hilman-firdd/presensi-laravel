@extends('layouts.app-admin')
@section('content')
<h2 class="mt-3">Presensi Harian</h2>
<ol class="breadcrumb mb-3">
    <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">Home</a></li>
    <li class="breadcrumb-item active">Presensi Harian</li>
</ol>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <center>
                <form action="{{ url('/presensi-harian') }}" method="GET">
                    <div class="col-md-5">
                        <div class="input-group">
                            <input type="date" class="form-control" name="cari" value="{{ date('Y-m-d') }}">
                            <button class="btn btn-primary ml-1"><i class="fa fa-search"></i></button>
                        </div>
                    </div>
                </form>
            </center>
            <table class="table table-bordered table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">Nama</th>
                        <th class="text-center">Tanggal/Hari</th>
                        <th class="text-center">Presensi</th>
                        <th class="text-center">Keterangan</th>
                        <th class="text-center">Lokasi</th>
                        <th class="text-center">Device</th>
                        <th class="text-center">IP</th>
                        <th class="text-center">Swafoto</th>
                        <th class="text-center">Laporan WFO</th>
                        <th class="text-center">ID Session</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; ?>
                    @foreach($data as $row)
                    <tr>
                        <td align="center">{{$no}}</td>
                        <td>{{$row->nama}}</td>
                        <td align="center">
                            {{$row->tanggal}},
                            @if($row->hari == 'Sunday')
                            Minggu
                            @elseif($row->hari == 'Monday')
                            Senin
                            @elseif($row->hari == 'Tuesday')
                            Selasa
                            @elseif($row->hari == 'Wednesday')
                            Rabu
                            @elseif($row->hari == 'Thursday')
                            Kamis
                            @elseif($row->hari == 'Friday')
                            Jumat
                            @elseif($row->hari == 'Saturday')
                            Sabtu
                            @else
                            @endif
                        </td>
                        <td align="center"><strong>Berangkat</strong>: {{$row->berangkat}}<br><strong>Pulang</strong>: {{$row->pulang}}</td>
                        @if($row->keterangan_presensi == '')
                        <td align="center" class="bg-success" style="color: white;">Sukses</td>
                        @else
                        <td align="center" class="bg-danger" style="color: white;">{{$row->keterangan_presensi}}</td>
                        @endif
                        <td align="center"><strong>Lokasi Berangkat</strong>: <a href="https://www.google.com/search?q={{$row->lokasi_berangkat}}&oq={{$row->lokasi_berangkat}}" target='_blank'>{{$row->lokasi_berangkat}}</a><br><strong>Lokasi Pulang</strong>: <a href="https://www.google.com/search?q={{$row->lokasi_pulang}}&oq={{$row->lokasi_pulang}}" target='_blank'>{{$row->lokasi_pulang}}</a></td>
                        <td align="center">{{$row->hardware}}</td>
                        <td align="center">{{$row->ip}}</td>
                        <td align="center">
                            @if($row->swafoto1 != '')
                            <a href="{{ asset('img/swafoto1/'.$row->swafoto1) }}" target="_blank"><img src="{{ asset('img/swafoto1/'.$row->swafoto1) }}" class="mb-2" width="50" title="Check-in" alt="Check-in"></a>
                            @endif
                            @if($row->swafoto2 != '')
                            <a href="{{ asset('img/swafoto2/'.$row->swafoto2) }}" target="_blank"><img src="{{ asset('img/swafoto2/'.$row->swafoto2) }}" width="50" title="Check-in" alt="Check-out"></a>
                            @endif
                        </td>
                        <td align="center">
                            {{$row->laporan_wfo}}
                        </td>
                        <td align="center"><strong>Berangkat</strong>
                            <p>{{$row->id_session}}</p>
                            <strong>Pulang</strong>
                            <p>{{$row->id_session_pulang}}</p>
                        <td align="center">
                            <input type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#yourModal1{{$row->id}}" value="Edit">
                        </td>
                    </tr>
                    <?php $no++; ?>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @foreach ($data as $row)
    {!! Form::model($row, ['url' => ['/update-presensi', $row->id]]) !!}
    <div class="modal fade" id="yourModal1{{$row->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    Edit Presensi
                </div>
                <div class="modal-body">
                    <strong>Berangkat:</strong>
                    <input type="text" name="berangkat" value="{{$row->berangkat}}" class="form-control"><br>
                    <strong>Pulang:</strong>
                    <input type="text" name="pulang" value="{{$row->pulang}}" class="form-control"><br>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button class="btn btn-primary"><i class="fa fa-save"></i> Simpan</button>
                </div>
            </div>
        </div>
    </div>
    {!!Form::close()!!}
    @endforeach

    @foreach ($data as $row)
    <div class="modal fade" id="yourModal2{{$row->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    Laporan Kerja
                </div>
                <div class="modal-body">
                    {!!$row->laporan!!}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
    @endforeach

</div>
@endsection