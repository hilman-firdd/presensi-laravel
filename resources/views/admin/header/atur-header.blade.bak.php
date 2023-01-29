@extends('layouts.app-admin')
@section('content')
<h2 class="mt-3">Pengaturan Header</h2>
<ol class="breadcrumb mb-3">
    <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">Home</a></li>
    <li class="breadcrumb-item active">Atur Header</li>
</ol>
<div class="row">
    <div class="col-12">
    <div class="card mb-3">
            @foreach($data as $value)
            {!! Form::model($value, ['files'=>true, 'url' => ['/update-header', $value->id]]) !!}
        
                <div class="card-body">
                 <div class="col-md-12">
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
                        <small class="text-muted">*Format logo JPG/PNG</small>
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
                        <label for="lat" class="col-md-2 col-form-label">{{ __('Latitude *') }}</label>
                        <div class="col-md-10">
                            <input id="lat" type="text" class="form-control{{ $errors->has('lat') ? ' is-invalid' : '' }}" name="lat" value="{{ $value->lat }}" required autofocus>

                            @if ($errors->has('lat'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('lat') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group row mb-3">
                        <label for="lng" class="col-md-2 col-form-label">{{ __('Longitude *') }}</label>
                        <div class="col-md-10">
                            <input id="lng" type="text" class="form-control{{ $errors->has('lng') ? ' is-invalid' : '' }}" name="lng" value="{{ $value->lng }}" required autofocus>

                            @if ($errors->has('lng'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('lng') }}</strong>
                            </span>
                            @endif
                            <div class="alert alert-info mt-2 mb-0">
                            <small>
                            Informasi: 
                            <ul>
                            <li>Data Latitude dan Longitude dapat anda peroleh pada Maps dibawah, caranya: Atur Zoom in/Zoom Out pada Maps, lalu Klik pada Maps lalu akan muncul info <b>You clicked the map at LatLng(-7.410968, 109.271489)</b>. Isikan pada kolom diatas, <b>Latitude: -7.410968</b> , <b>Longitude: 109.271489</b></li>  
                            <li>
                            Atau di website https://www.google.com/maps/. Caranya: Pilih pada Maps titik Lokasinya, lalu Klik Kanan pada Mouse, maka akan mendapatkan lokasi seperti ini: -7.410913314229929, 109.27147197550117 , kemudian simpan dalam pengaturan <a href="{{url('/atur-header')}}">Atur Header</a>. Keterangan: Latitude (Lat)=<b>-7.410913314229929</b>, Longitude (Long)=<b>109.27147197550117</b>
                            </li>
                            </ul>
                            </small>
                            </div>
                        </div>
                        
                    </div>

                    <div class="form-group row mb-3">
                        <label class="col-md-2 col-form-label"></label>
                        <div class="col-md-10">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
                        </div>
                    </div>

                    <div id="mapid" class="mapid"></div>
                
            </div>
            
        </div>
        {!! Form::close() !!}
        @endforeach
    </div>
</div>
</div>
</div>
<script type="text/javascript">
    var readlogo= function(event) {
        var input = event.target;

        var reader = new FileReader();
        reader.onload = function(){
          var dataURL = reader.result;
          var output = document.getElementById('output');
          output.src = dataURL;
      };
      reader.readAsDataURL(input.files[0]);
  };
</script>
<script type='text/javascript'>
    navigator.geolocation.getCurrentPosition(function(location) {
        var latlng = new L.LatLng(location.coords.latitude, location.coords.longitude);

        //map view
        console.log("Lokasi Saat Ini :" + location.coords.latitude, location.coords.longitude);
        // var L = window.L;
        var mymap = L.map('mapid').setView([location.coords.latitude, location.coords.longitude], 13);
        L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw', {
            maxZoom: 18,
            attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, ' +
                '<a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
                'Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
            id: 'mapbox/streets-v11',
            tileSize: 512,
            zoomOffset: -1,
        }).addTo(mymap);

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