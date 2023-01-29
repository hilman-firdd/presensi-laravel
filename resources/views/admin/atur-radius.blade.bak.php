@extends('layouts.app-admin')

@section('content')
<h2 class="mt-3">Atur Radius Kantor</h2>

<ol class="breadcrumb mb-3">
<li class="breadcrumb-item"><a href="{{url('/dashboard')}}">Home</a></li>
<li class="breadcrumb-item active">Atur Radius Latitude dan Longitude</li>
</ol>


<div class="card">
    <div class="card-body">
        @foreach($radius as $row => $value)
        {!! Form::model($value, ['url' => ['/update-radius', $value->id]]) !!}
        <div class="table-responsive">
            <table class="table">
                <tr>
                    <td width="25%"><strong>Latitude Bawah</strong></td>
                    <td width="1%">:</td>
                    <td>
                        <input type="text" name="lat_bawah" class="form-control" value="{{$value->lat_bawah}}">
                    </td>
                </tr>
                <tr>
                    <td width="25%"><strong>Longitude Kiri/Bawah</strong></td>
                    <td width="1%">:</td>
                    <td>
                        <input type="text" name="long_bawah" class="form-control" value="{{$value->long_bawah}}">
                    </td>
                </tr>
                <tr>
                    <td width="25%"><strong>Latitude Atas</strong></td>
                    <td width="1%">:</td>
                    <td>
                        <input type="text" name="lat_atas" class="form-control" value="{{$value->lat_atas}}">
                    </td>
                </tr>
                <tr>
                    <td width="25%"><strong>Longitude Kanan/Atas</strong></td>
                    <td width="1%">:</td>
                    <td>
                        <input type="text" name="long_atas" class="form-control" value="{{$value->long_atas}}">
                    </td>
                </tr>
            </table>
        </div>

        <button class="btn btn-primary mb-3"><i class="fa fa-save"></i> Simpan</button>
        {{Form::close()}}
        @endforeach

        <div class="alert alert-info">
        <small>
        Informasi: Pilih Radius Lokasi Kantor, Klik Kiri pada Maps diluar lingkaran Radius maka akan mendapatkan lokasi seperti ini: You clicked the map at LatLng(-7.400856, 109.27165), kemudian simpan dalam isian diatas. Contoh: Latitude (Lat)=<b>-7.410913314229929</b>, Longitude (Long)=<b>109.27147197550117</b></small>
        </div>
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
        var mymap = L.map('mapid').setView([<?=$latitude?>, <?=$longitude?>], 14);
        L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw', {
            maxZoom: 18,
            attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, ' +
                '<a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
                'Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
            id: 'mapbox/streets-v11',
            tileSize: 512,
            zoomOffset: -1,
        }).addTo(mymap);

        L.marker([<?=$latitude?>, <?=$longitude?>]).addTo(mymap).bindPopup('Lokasi Kantor: ' + <?=$latitude?> + ',' + <?=$longitude?>).openPopup();

        L.circle([<?=$latitude?>, <?=$longitude?>], 500, {
            color: 'red',
            fillColor: '#f03',
            fillOpacity: 0.5
	    }).addTo(mymap).bindPopup("I am a circle.");

        var popup = L.popup();

        function onMapClick(e) {
		popup
			.setLatLng(e.latlng)
			.setContent("You clicked the map at " + e.latlng.toString())
			.openOn(mymap);
	}

	mymap.on('click', onMapClick);

    });
</script>
@endsection