@extends('layouts.app')

@section('content')
<main>
    <div id="mapid" class="mapid"></div>
    <div class="row justify-content-center">
        <div class="col-md-4" style="position: absolute;bottom:20px;margin: 0 auto;">
            <div class="bg-light text-center py-1">
                <h6 id="tanggal" class="text-primary mb-0" style="color: #1F3BB3 !important;">memuat...</h6>
                <h3 id="jam" class="text-primary fw-bolder mb-0" style="color: #1F3BB3 !important;"></h3>
            </div>

            <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ url('/presensi-berangkat') }}" action="GET">
                                <div class="input-group my-1">
                                    <input type="text" name="nik" class="form-control input-lg" placeholder="Masukan NIK Anda" value="@if(Auth::user()) {{ Auth::user()->nik}} @endif" required>
                                    <button class="btn btn-primary fw-bold" style="background-color: #1F3BB3;">Check-in</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="pills-pulang" role="tabpanel" aria-labelledby="pills-profile-tab">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ url('/presensi-pulang') }}" action="GET">
                                <div class="input-group my-1">
                                    <input type="text" name="nik" class="form-control input-lg" placeholder="Masukan NIK Anda" value="@if(Auth::user()) {{ Auth::user()->nik}} @endif" required>
                                    <button class="btn btn-danger fw-bold" style="background-color: #e81500;">Check-out</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <ul class="nav nav-pills nav-justified bg-light" id="pills-tab" role="tablist">
                    <li class="nav-item pill-1" role="presentation">
                        <a class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true"><i class="fa fa-plane"></i> Masuk</a>
                    </li>
                    <li class="nav-item pill-2" role="presentation">
                        <a class="nav-link" id="pills-pulang-tab" data-bs-toggle="pill" href="#pills-pulang" role="tab" aria-controls="pills-pulang" aria-selected="false"><i class="fa fa-calendar"></i> Pulang</a>
                    </li>
                </ul>
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
@endsection