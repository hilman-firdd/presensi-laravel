@extends('layouts.app-admin')

@section('content')

<?php
$data = DB::table('tb_header')->where('id', '1')->first();
$latitude = $data->lat;
$longitude = $data->lng;
?>

<h2 class="mt-3">Pengaturan Radius Kantor</h2>

<ol class="breadcrumb mb-3">
    <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">Home</a></li>
    <li class="breadcrumb-item active">Pengaturan Radius Kantor</li>
</ol>


<div class="card">
    <div class="card-body">
        @foreach($radius as $row => $value)
        {!! Form::model($value, ['url' => ['/update-radius', $value->id]]) !!}
        <div class="table-responsive">
            <table class="table table-borderless">
                <tr>
                    <td width="25%"><strong>Lokasi Kantor</strong><br/><a href="{{url('/atur-header')}}">Atur Lokasi Kantor</a></td>
                    <td width="1%">:</td>
                    <td>
                        <input type="text" class="form-control" value="<?= $latitude ?>" disabled>
                        <input type="text" class="form-control" value="<?= $longitude ?>" disabled>
                    </td>
                </tr>
                <tr>
                    <td width="25%"><strong>Latitude Atas</strong></td>
                    <td width="1%">:</td>
                    <td>
                        <input id="Latitude2" type="text" name="lat_atas" class="form-control" value="{{$value->lat_atas}}">
                    </td>
                </tr>
                <tr>
                    <td width="25%"><strong>Latitude Bawah</strong></td>
                    <td width="1%">:</td>
                    <td>
                        <input id="Latitude" type="text" name="lat_bawah" class="form-control" value="{{$value->lat_bawah}}">
                    </td>
                </tr>
                <tr>
                    <td width="25%"><strong>Longitude Atas</strong></td>
                    <td width="1%">:</td>
                    <td>
                        <input id="Longitude2" type="text" name="long_atas" class="form-control" value="{{$value->long_atas}}">
                    </td>
                </tr>
                <tr>
                    <td width="25%"><strong>Longitude Bawah</strong></td>
                    <td width="1%">:</td>
                    <td>
                        <input id="Longitude" type="text" name="long_bawah" class="form-control" value="{{$value->long_bawah}}">
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td>
                        <div id="mapid" class="mapid"></div>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td>
                        <div class="alert alert-info mb-0">
                            <small>
                                <b>Informasi:</b> Pindahkan 2 Marker Lokasi <i class="fas fa-map-marker-alt"></i> diatas pada posisi Atas dan Bawah pada lingkaran radius dengan klik mouse kiri</small>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td>
                    <button class="btn btn-primary btn-lg"><i class="fa fa-save"></i> Simpan</button>
                    </td>
                </tr>
            </table>
        </div>
        {{Form::close()}}
        @endforeach
    </div>
</div>

<script type='text/javascript'>
    var curLocation = [0, 0];
    if (curLocation[0] == 0 && curLocation[1] == 0) {
        curLocation = [<?= $latitude ?>, <?= $longitude ?>];
    }

    var curLocation2 = [0, 0];
    if (curLocation2[0] == 0 && curLocation2[1] == 0) {
        curLocation2 = [<?= $latitude ?>, <?= $longitude ?>];
    }

    var L = window.L;

    var mymap = L.map('mapid').setView([<?= $latitude ?>, <?= $longitude ?>], 16);
    L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw', {
        attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, ' +
            '<a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
            'Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
        id: 'mapbox/streets-v11'
    }).addTo(mymap);

    L.circle([<?= $latitude ?>, <?= $longitude ?>], 500, {
        color: 'red',
        fillColor: '#f03',
        fillOpacity: 0.5
    }).addTo(mymap).bindPopup("Radius Lokasi Kantor.");

    mymap.attributionControl.setPrefix(false);
    var marker = new L.marker(curLocation, {
        draggable: 'true',
        title: "Marker Bawah",
        alt: "Marker Bawah",
        riseOnHover: true,
    });

    marker.on('dragend', function(event) {
        var position = marker.getLatLng();
        marker.setLatLng(position, {
            draggable: 'true'
        }).bindPopup(position).update();
        $("#Latitude").val(position.lat);
        $("#Longitude").val(position.lng).keyup();
    });


    document.addEventListener("DOMContentLoaded", function(event) {
        $("#Latitude, #Longitude").change(function() {
            var position = [parseInt($("#Latitude").val()), parseInt($("#Longitude").val())];
            marker.setLatLng(position, {
                draggable: 'true'
            }).bindPopup(position).update();
            mymap.panTo(position);
        });
    });

    mymap.addLayer(marker);

    mymap.attributionControl.setPrefix(false);
    var marker2 = new L.marker(curLocation2, {
        draggable: 'true',
        title: "Marker Atas",
        alt: "Marker Atas",
        riseOnHover: true,
        bubblingMouseEvents: true,
    });

    marker2.on('dragend', function(event) {
        var position2 = marker2.getLatLng();
        marker2.setLatLng(position2, {
            draggable: 'true'
        }).bindPopup(position2).update();
        $("#Latitude2").val(position2.lat);
        $("#Longitude2").val(position2.lng).keyup();
    });


    document.addEventListener("DOMContentLoaded", function(event) {
        $("#Latitude2, #Longitude2").change(function() {
            var position2 = [parseInt($("#Latitude2").val()), parseInt($("#Longitude2").val())];
            marker2.setLatLng(position2, {
                draggable: 'true'
            }).bindPopup(position2).update();
            mymap.panTo(position2);
        });
    });

    mymap.addLayer(marker2);

    //var popup = L.popup();

    //function onMapClick(e) {
    //popup
    //.setLatLng(e.latlng)
    //.setContent("You clicked the map at " + e.latlng.toString())
    //.openOn(mymap);
    //}

    //mymap.on('click', onMapClick);

    //});
</script>
@endsection