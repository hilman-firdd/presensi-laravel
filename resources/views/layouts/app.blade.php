<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Presensi Online" />
    <meta name="author" content="IT-SHOP Purwokerto" />
    <link rel="shortcut icon" href="{{asset('img/icon/favicon.png')}}">
    <title>Presensi Online WFO</title>
    <!-- Fontawesome -->
    <link href="{{asset('assets/plugins/fontawesome-free/css/all.min.css')}}" rel="stylesheet" />
    <!-- Vanilajs Datatables -->
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" />
    <!-- Bootstrap -->
    <link href="{{asset('assets/css/bootstrap.min.css')}}" rel="stylesheet" />
    <link href="{{asset('assets/css/custom.css')}}" rel="stylesheet" />
    <!-- Jquery -->
    <script src="{{asset('assets/plugins/jquery/jquery.min.js')}}"></script>
    <!-- Leaflet -->
    <link rel="stylesheet" href="{{asset('assets/plugins/leaflet/leaflet.css')}}" />
    <!-- Sweetalert -->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}">
    <!-- Geo-location 
<script src="http://maps.google.com/maps/api/js"></script>-->
    <script src="{{ asset('assets/js/geo-min.js')}}" type="text/javascript" charset="utf-8"></script>
    <!-- Moment -->
    <script src="{{asset('assets/plugins/moment/moment.min.js')}}"></script>
    <script src="{{asset('assets/plugins/moment/locale/id.js')}}"></script>
    <script>
        $(document).ready(function() {
            $(".preloader").fadeOut();
        })
    </script>
    <script type="text/javascript">
        $(document).ready(function() {
            function tanggal() {
                $('#tanggal').html(moment().format('D MMMM YYYY'));
            }
            setInterval(tanggal, 1000);

            function jam() {
                $('#jam').html(moment().format('H:mm:ss'));
            }
            setInterval(jam, 1000);
        });
    </script>
    <script>
        if (geo_position_js.init()) {
            geo_position_js.getCurrentPosition(success_callback, error_callback, {
                enableHighAccuracy: true
            });
        } else {
            div_isi = document.getElementById("div_isi");
            div_isi.innerHTML = "Tidak ada fungsi geolocation";
        }

        function success_callback(p) {
            latitude = p.coords.latitude;
            longitude = p.coords.longitude;
            pesan = +latitude + ',' + longitude;
            pesan = pesan + "<br/>";
            div_isi = document.getElementById("div_isi");
            //alert(pesan);
            div_isi.innerHTML = pesan;
        }

        function error_callback(p) {
            div_isi = document.getElementById("div_isi");
            div_isi.innerHTML = 'error=' + p.message;
        }
    </script>
</head>

<body>

    <div class="bg-primary py-1" style="background-color: #1F3BB3 !important;">
        <div class="container">
            <p class="text-light mb-0" style="font-size:1rem"><i class="fas fa-map-marker-alt text-danger"></i> <strong>Lokasi:</strong> <span id="div_isi"></span></p>
        </div>
    </div>

    <div class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm py-2 sticky-top" style="background-color: #1F3BB3 !important;">
        <div class="container">
            <?php
            $data = DB::select('select * from tb_header');
            foreach ($data as $key => $value) {
            ?>
                <a class="navbar-brand" href="{{url('/')}}">
                    <img src="{{asset('/img/header/')}}/{{$value->logo}}" class="float-start me-2" width="50">
                    <span class="d-block" style="font-size: 1rem;margin-bottom: -10px;">{{$value->yayasan}}{{$value->unit}}</span>
                    <span style="font-size: 28px;">Presensi Online</span>
                </a>

            <?php
            }
            ?>
            <!-- Responsive Toggle Button -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <!-- End Responsive Toggle Button -->
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto">
                    <li class="navbar-nav-item"><a href="{{url('/')}}" class="nav-link me-2">Home</a></li>
                    <li class="navbar-nav-item"><a href="{{url('/data-pegawai')}}" class="nav-link me-2">Data Pegawai</a></li>
                    <li class="navbar-nav-item"><a href="{{url('panduan')}}" class="nav-link me-2">Panduan</a></li>
                </ul>
                <div>
                    @if(!Auth::user())
                    <a href="{{url('log-in')}}" class="btn btn-outline-light"><i class="fas fa-sign-in-alt"></i> Login</a>
                    @else
                    <ul class="navbar-nav ml-auto ml-md-0">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" id="userDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-user fa-fw"></i> {{ Auth::user()->nama}}</a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="{{url('/dashboard')}}"><i class="fa fa-fw fa-tachometer-alt"></i> Dashboard</a>
                                <a class="dropdown-item" href="{{url('/biodata')}}"><i class="fa fa-fw fa-user"></i> Edit Profil</a>

                                <a class="dropdown-item" href="{{ url('/logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fa fa-fw fa-sign-out-alt"></i> Logout</a>
                                <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                                    {{ csrf_field() }}
                                </form>
                            </div>
                        </li>
                    </ul>
                    @endif
                </div>

            </div>

        </div>
    </div>

    <div id="app">
        <div class="preloader">
            <div class="loading">
                <img src="{{asset('img/poi.gif') }}" width="80">
                <p>Harap Tunggu</p>
            </div>
        </div>
        <div class="container">
            @yield('content')
        </div>

    </div>
    <!-- ========== FOOTER ========== -->
    <footer class="py-3">
        <div class="container">
            <!-- Copyright -->
            <div class="text-center">
                <p class="text-dark mb-0">© <?= date('Y'); ?> IT Shop Purwokerto. All rights reserved.</p>
            </div>
            <!-- End Copyright -->
        </div>
    </footer>
    <!-- ========== END FOOTER ========== -->
    <!-- Bootstrap -->
    <script src="{{asset('assets/js/bootstrap.bundle.min.js')}}"></script>
    <!-- Vanillajs Datatables -->
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" crossorigin="anonymous"></script>
    <!-- Leaflet -->
    <script src="{{asset('assets/plugins/leaflet/leaflet.js')}}" type='text/javascript'></script>
    <!-- Sweetalert -->
    <script src="{{asset('assets/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <script>
        window.addEventListener('DOMContentLoaded', event => {
            // Simple-DataTables
            // https://github.com/fiduswriter/Simple-DataTables/wiki
            const datatablesSimple = document.getElementById('dataTable');
            if (datatablesSimple) {
                new simpleDatatables.DataTable(datatablesSimple);
            }

            const datatablesSimple2 = document.getElementById('dataTable2');
            if (datatablesSimple2) {
                new simpleDatatables.DataTable(datatablesSimple2, {
                    columns: [{
                        select: 0,
                        sortable: false
                    }]
                });
            }
        });
    </script>
    <script>
        const Toast = Swal.mixin({
            toast: false,
            showConfirmButton: true,
            timer: 5000
        });
        @if($message = Session::get('sukses'))
        Toast.fire({
            icon: 'success',
            title: '<?php echo $message ?>.'
        })
        @endif

        @if($message = Session::get('gagal'))
        Toast.fire({
            icon: 'error',
            title: '<?php echo $message ?>.'
        })
        @endif
    </script>
    @yield('script')
</body>

</html>