@extends('layouts.app')

@section('content')
<script>
    if (geo_position_js.init()) {
        geo_position_js.getCurrentPosition(success_callback, error_callback, {
            enableHighAccuracy: true
        });
    } else {
        lokasi = document.getElementById("lokasi");
        lokasi.innerHTML = "Tidak ada fungsi geolocation";
    }

    function success_callback(p) {
        latitude = p.coords.latitude;
        longitude = p.coords.longitude;
        pesan = +latitude + ',' + longitude;
        pesan = pesan + "";

        lat = +latitude;
        lat = lat + "";
        document.getElementById("Lokasi").value = pesan;
    }

    function error_callback(p) {
        lokasi = document.getElementById("lokasi");
        lokasi.innerHTML = 'error=' + p.message;
    }
</script>
<main>
    <div id="mapid" class="mapid"></div>
    <div class="row justify-content-center">
        <div class="col-md-6" style="position: absolute;top:130px;margin: 0 auto;">
            <div class="card">
                <div class="card-header"><a href="{{url('/')}}" class="text-dark text-decoration-none"><i class="fas fa-arrow-left"></i> Kembali</a></div>
                <div class="card-body p-3">
                    <div class="text-center">
                        <h6 id="tanggal" class="text-primary mb-0" style="color: #1F3BB3 !important;">memuat...</h6>
                        <h3 id="jam" class="text-primary fw-bolder mb-0" style="color: #1F3BB3 !important;"></h3>
                    </div>
                    <hr />
                    <div class="bs-callout bs-callout-default" style="background-color: white;">
                        {!! Form::open(['url' => ['simpan-presensi-berangkat'], 'class' => 'form', 'files'=>true]) !!}
                        @if (count($data) > 0)
                        @foreach($data as $row)
                        <center>
                            <input type="hidden" name="id_user" value="{{ $row-> id}}">
                            <h2>{{$row->nik}} / {{$row->nama}}</h2>
                        </center>
                        @endforeach
                        <input type="hidden" name="lokasi" id="Lokasi">
                        <div class="form-group mb-3">
                            <label class="col-form-label">Swafoto*
                                <small class="text-primary">
                                    Format foto JPG/JPEG, PNG
                                </small>
                            </label>
                            <input onchange="readGambar(event)" name="gambar" id="gambar" type="file" class="form-control{{ $errors->has('gambar') ? ' is-invalid' : '' }}" value="{{ old('gambar') }}" accept="image/*" capture="capture" autofocus>
                            @if ($errors->has('gambar'))
                            <span class="invalid-feedback">
                                {{ $errors->first('gambar') }}
                            </span>
                            @endif

                            <img id="output" class="img-fluid py-2">
                        </div>
                        <nav class="navbar fixed-bottom navbar-light bg-light">
                            <div class="container-fluid">
                                <ul class="navbar-nav mx-auto">
                                    <button class="btn btn-primary"><i class="fa fa-check-circle"></i> Confirm Check-in</button>
                                </ul>
                            </div>
                        </nav>
                        {!! Form::close() !!}
                        @else
                        <div class="py-3">
                            <a href="{{url('/')}}">
                                <i class="fas fa-arrow-left"></i>&ensp;Kembali
                            </a>
                            <h2 class="h1 mt-2">404</h2>
                            <h3>Data Karyawan tidak ditemukan</h3>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection

@section('script')
<script type='text/javascript'>
    navigator.geolocation.getCurrentPosition(function(location) {
        var latlng = new L.LatLng(location.coords.latitude, location.coords.longitude);
        //map view
        console.log("Lokasi Saat Ini :" + location.coords.latitude, location.coords.longitude);
        // var L = window.L;
        var mymap = L.map('mapid').setView([location.coords.latitude, location.coords.longitude], 14);
        L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw', {
            maxZoom: 18,
            attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, ' +
                '<a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
                'Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
            id: 'mapbox/streets-v11',
            tileSize: 512,
            zoomOffset: -1,
        }).addTo(mymap);

        L.marker([location.coords.latitude, location.coords.longitude]).addTo(mymap).bindPopup('Lokasi Anda: ' + location.coords.latitude + ',' + location.coords.longitude).openPopup();
    });
</script>
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