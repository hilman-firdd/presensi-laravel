@extends('layouts.app-admin')
@section('content')
<h1 class="mt-3 mb-3">Dashboard</h1>

<!--<div class="alert alert-info alert-dismissible fade show" role="alert">
<?php
$data = DB::select('select * from tb_header');
foreach ($data as $key => $value) {
?>
<span style="font-size: 14px">Selamat datang di dashboard sistem <strong>Presensi Online</strong> karyawan //{{$value->yayasan}}{{$value->unit}}. Sistem ini membantu mendata daftar hadir karyawan saat Work From Home (WFH) dan bisa juga untuk Work From Office (WFO).</span>
<?php
}
?>
<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>-->

@if(Auth::user()->level == 0)
<div class="row">
<div class="col-xl-3 col-md-6">
<div class="card bg-success text-white mb-4">
<div class="card-body">Presensi Hari Ini</div>
<div class="card-footer d-flex align-items-center justify-content-between">
<span class="h2 text-white stretched-link mb-0" href="#">{{$jml_masuk}}</i>
</div>
</div>
</div>
<div class="col-xl-3 col-md-6">
<div class="card bg-warning text-dark mb-4">
<div class="card-body">Cuti Hari Ini</div>
<div class="card-footer d-flex align-items-center justify-content-between">
    <span class="h2 text-dark stretched-link mb-0" href="#">{{$jml_izin}}</i>
    </div>
</div>
</div>
<div class="col-xl-3 col-md-6">
<div class="card bg-danger text-white mb-4">
    <div class="card-body">Alpa Hari Ini</div>
    <div class="card-footer d-flex align-items-center justify-content-between">
        <span class="h2 text-white stretched-link mb-0" href="#">{{$jml_alfa}}</i>
        </div>
    </div>
</div>
<div class="col-xl-3 col-md-6">
    <div class="card bg-secondary text-white mb-4">
        <div class="card-body">Tidak Presensi Hari Ini</div>
        <div class="card-footer d-flex align-items-center justify-content-between">
            <span class="h2 text-white stretched-link mb-0" href="#">{{$jml_tidak_berangkat}}</i>
            </div>
        </div>
    </div>
</div>

<div class="card mb-3">
    <div class="card-body">
    <h5 class="card-title mb-3"><i class="fas fa-map-marked-alt"></i> Lokasi Maps</h5>
        <div id="mapid" class="mapid"></div>
    </div>
</div>

<div class="card mb-3">
    <div class="card-body">
    <h5 class="card-title mb-3"><i class="fas fa-users"></i> Titip Presensi</h5>
        <div class="table-responsive">
            <table class="table table-bordered table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                <thead>
                <tr>
                    <th class="text-center">No</th>
                    <th class="text-center">Nama</th>
                    <th class="text-center">Hari</th>
                    <th class="text-center">Presensi</th>
                    <th class="text-center">Tanggal</th>
                    <th class="text-center">Lokasi</th>
                    <th class="text-center">Device</th>
                    <th class="text-center">IP</th>
                    <th class="text-center">ID Session</th>
                </tr>
            </thead>
            <tbody>
                @if(!$data))
                @else
                <?php $no = 1; ?>
                @foreach( $titip_presensi as $row)
                <tr class="danger red">
                    <td align="center">{{$no}}</td>
                    <td>{{$row->nama}}</td>
                    <td align="center">@if($row->hari == 'Sunday')
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
                    @endif </td>
                    <td align="center"><strong>Berangkat</strong>: {{$row->berangkat}}<br><strong>Pulang</strong>: {{$row->pulang}}</td>
                    <td align="center">{{$row->tanggal}}</td>
                    <td align="center"><strong>Lokasi Berangkat</strong>: <a href="https://www.google.com/search?q={{$row->lokasi_berangkat}}&oq={{$row->lokasi_berangkat}}" target='_blank'>{{$row->lokasi_berangkat}}</a><br><strong>Lokasi Pulang</strong>: <a href="https://www.google.com/search?q={{$row->lokasi_pulang}}&oq={{$row->lokasi_pulang}}" target='_blank'>{{$row->lokasi_pulang}}</a></td>
                    <td align="center">{{$row->hardware}}</td>
                    <td align="center">{{$row->ip}}</td>
                    <td align="center"><strong>Berangkat</strong><p>{{$row->id_session}}<p><strong>Pulang</strong><p>{{$row->id_session_pulang}}</td>
                    </tr>
                    <?php $no++; ?>
                    @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif

<?php
$data = DB::table('tb_header')->where('id', '1')->first();
$latitude = $data->lat;
$longitude = $data->lng;
?>
<script type='text/javascript'>
    navigator.geolocation.getCurrentPosition(function(location) {
        var latlng = new L.LatLng(location.coords.latitude, location.coords.longitude);

        //map view
        console.log("Lokasi Saat Ini :" + location.coords.latitude, location.coords.longitude);
        // var L = window.L;
        var mymap = L.map('mapid').setView([<?=$latitude?>, <?=$longitude?>], 10);
        L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw', {
            maxZoom: 18,
            attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, ' +
                '<a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
                'Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
            id: 'mapbox/streets-v11',
            tileSize: 512,
            zoomOffset: -1,
        }).addTo(mymap);

        L.marker([<?=$latitude?>, <?=$longitude?>]).addTo(mymap).bindPopup('Kantor').openPopup();

        <?php foreach ($locations as $i) { ?>
                L.marker([<?= $i->lat; ?><?= $i->lng; ?>]).bindPopup(
                    "<h6><?= $i->nama; ?></h6>" +
                    "<a href='https://www.google.com/maps/dir/?api=1&origin=" +
                    <?=$latitude?> + "," + <?=$longitude?> + "&destination=<?= $i->lat; ?>,<?= $i->lng; ?>' class='btn btn-outline-primary btn-sm' target='_blank'>Rute</a>").addTo(mymap);
            <?php } ?>
    });
</script>
@endsection