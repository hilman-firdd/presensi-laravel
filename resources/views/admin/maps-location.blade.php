@extends('layouts.app-admin')

@section('content')

<h2 class="mt-3">Lokasi MAPS</h2>
<ol class="breadcrumb mb-3">
<li class="breadcrumb-item"><a href="{{url('/dashboard')}}">Home</a></li>
<li class="breadcrumb-item active">Lokasi MAPS</li>
</ol>

<div class="card">

<div class="card-body">
<center>
<div class="col-5">
    <form action="{{ url('/maps-location') }}" method="GET">
        <div class="input-group mb-3">
        <label for="cari" class="me-3 col-form-label">Pilih Tanggal</label>
        <input type="date" id="cari" class="form-control" name="cari" value="<?= $_GET['cari']; ?>" >
        <button class="btn btn-primary ml-1"><i class="fa fa-search"></i></button>
        </div>
    </form> 
</div>
</center> 

<div id="mapid" class="mapid"></div>

</div>
</div>

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
        var mymap = L.map('mapid').setView([<?=$latitude?>, <?=$longitude?>], 5);
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