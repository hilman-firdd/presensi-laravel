@extends('layouts.app-admin')
@section('content')
<h2 class="mt-3">Pengaturan Header</h2>
<ol class="breadcrumb mb-3">
    <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">Home</a></li>
    <li class="breadcrumb-item active">Pengaturan Header</li>
</ol>
<div class="row">
    <div class="col-12">
        <div class="card mb-3">
            @foreach($data as $value)
            {!! Form::model($value, ['files'=>true, 'url' => ['/update-header', $value->id]]) !!}

            <div class="card-body">
                    <div class="form-group row mb-3">
                        <label for="yayasan" class="col-md-2 col-form-label">{{ __('Yayasan') }}</label>
                        <div class="col-md-10">
                            <input id="yayasan" type="text" class="form-control{{ $errors->has('yayasan') ? ' is-invalid' : '' }}" name="yayasan" value="{{ $value->yayasan }}">

                            @if ($errors->has('yayasan'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('yayasan') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group row mb-3">
                        <label for="unit" class="col-md-2 col-form-label">{{ __('Unit *') }}</label>
                        <div class="col-md-10">
                            <input id="unit" type="text" class="form-control{{ $errors->has('unit') ? ' is-invalid' : '' }}" name="unit" value="{{ $value->unit }}" required autofocus>

                            @if ($errors->has('unit'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('unit') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group row mb-3">
                        <label class="col-md-2 col-form-label">{{ __('Logo') }}</label>
                        <div class="col-md-5">

                            <img src="{{asset('/img/header/')}}/{{$value->logo}}" class="mb-2" style="width: 100px;">

                            <input onchange="readlogo(event)" name="logo" id="logo" type="file" class="form-control{{ $errors->has('logo') ? ' is-invalid' : '' }}" logo="logo" value="{{ old('logo') }}" autofocus>
                            <small class="text-primary">*Format logo JPG/PNG</small>
                            @if ($errors->has('logo'))
                            <span class="invalid-feedback">
                                {{ $errors->first('logo') }}
                            </span>
                            @endif
                        </div>

                        <div class="col-md-5">
                            <img id='output' style="width: 200px;">
                        </div>

                    </div>
                    <div class="form-group row mb-3">
                        <label for="Latitude" class="col-md-2 col-form-label">{{ __('Latitude *') }}</label>
                        <div class="col-md-10">
                            <input id="Latitude" type="text" class="form-control{{ $errors->has('lat') ? ' is-invalid' : '' }}" name="lat" value="{{ $value->lat }}" readonly>

                            @if ($errors->has('lat'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('lat') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group row mb-3">
                        <label for="Longitude" class="col-md-2 col-form-label">{{ __('Longitude *') }}</label>
                        <div class="col-md-10">
                            <input id="Longitude" type="text" class="form-control{{ $errors->has('lng') ? ' is-invalid' : '' }}" name="lng" value="{{ $value->lng }}" readonly>

                            @if ($errors->has('lng'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('lng') }}</strong>
                            </span>
                            @endif

                            <div id="mapid" class="mapid mt-3"></div>

                            <div class="alert alert-info mt-3 mb-0">
                                <small>
                                    <b>Informasi:</b>
                                    Pindahkan Icon Cursor/Pin Lokasi <i class="fas fa-map-marker-alt"></i> pada maps diatas dengan klik kiri pada Mouse sesuai lokasi kantor anda.
                                </small>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row mb-3">
                        <label class="col-md-2 col-form-label"></label>
                        <div class="col-md-10">
                        <button type="submit" class="btn btn-primary btn-lg"><i class="fas fa-save"></i> Simpan</button>
                        </div>
                    </div>
            </div>
            {!! Form::close() !!}
            @endforeach
        </div>
    </div>
</div>
</div>
<?php
$data = DB::table('tb_header')->where('id', '1')->first();
$latitude = $data->lat;
$longitude = $data->lng;
?>
<script type="text/javascript">
    var readlogo = function(event) {
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
<script type='text/javascript'>
    var curLocation = [0, 0];
    if (curLocation[0] == 0 && curLocation[1] == 0) {
        curLocation = [<?= $latitude ?>, <?= $longitude ?>];
    }

    var L = window.L;

    var mymap = L.map('mapid').setView([<?= $latitude ?>, <?= $longitude ?>], 14);
    L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw', {
        attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, ' +
            '<a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
            'Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
        id: 'mapbox/streets-v11'
    }).addTo(mymap);

    mymap.attributionControl.setPrefix(false);
    var marker = new L.marker(curLocation, {
        draggable: 'true'
    }).bindPopup("Lokasi kantor anda!").openPopup();

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
</script>
@endsection