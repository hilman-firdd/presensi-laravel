<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <meta name="description" content="Presensi Online" />
  <meta name="author" content="IT-SHOP Purwokerto" />
  <link rel="shortcut icon" href="{{asset('img/icon/favicon.png')}}">
  <title>Admin Sistem Presensi Online WFO</title>
  <!-- Fontawesome -->
  <link href="{{asset('assets/plugins/fontawesome-free/css/all.min.css')}}" rel="stylesheet" />
  <!-- Vanilajs Datatables -->
  <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" />
  <!-- Bootstrap -->
  <link href="{{asset('assets/css/styles.css')}}" rel="stylesheet" />
  <link href="{{asset('assets/css/custom.css')}}" rel="stylesheet" />
  <!-- Sweetalert -->
  <link rel="stylesheet" type="text/css" href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}">
  <!-- summernote -->
  <link rel="stylesheet" type="text/css" href="{{ asset('assets/plugins/summernote/summernote.min.css') }}">
  <!-- Choices.js-->
  <link type="text/css" href="{{ asset('assets/plugins/choices.js/public/assets/styles/choices.min.css') }}" rel="stylesheet">
  <!-- jquery -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <!-- overlayscrollbars -->
  <link href="{{asset('assets/plugins/overlayScrollbars/css/OverlayScrollbars.min.css')}}" rel="stylesheet" />
  <script src="{{asset('assets/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js')}}"></script>
  <!-- Leaflet -->
  <link rel="stylesheet" href="{{asset('assets/plugins/leaflet/leaflet.css')}}" />
  <script src="{{asset('assets/plugins/leaflet/leaflet.js')}}" type='text/javascript'></script>
  <script>
    $(function() {

      $("#sidenavAccordion").overlayScrollbars({
        className: "os-theme-light",
        resize: "none",
        sizeAutoCapable: true,
        paddingAbsolute: true,
        scrollbars: {
          clickScrolling: true
        }
      });
    });
  </script>
  <script>
    function check() {
      return confirm("Apakah anda yakin?");
    }
  </script>
</head>

<body class="sb-nav-fixed">
  <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark shadow" style="background-color: #1F3BB3 !important;">
    <a class="navbar-brand ps-3" href="{{url('/dashboard')}}">PRESENSI</a><button class="btn btn-link btn-sm order-1 order-lg-0" id="sidebarToggle" href="#"><i class="fas fa-bars"></i></button>
    <!-- Navbar-->
	<ul class="navbar-nav me-auto mb-lg-0">
      <li class="nav-item"><a class="nav-link" href="{{url('/')}}"><i class="fa fa-fw fa-home"></i></a></li>
    </ul>
    <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" id="userDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-user fa-fw"></i> {{ Auth::user()->nama}}</a>
        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
          <a class="dropdown-item" href="{{url('/biodata')}}"><i class="fa fa-fw fa-user"></i> Edit Profil</a>

          <a class="dropdown-item" href="{{ url('/logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fa fa-fw fa-sign-out-alt"></i> Logout</a>
          <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
            {{ csrf_field() }}
          </form>
        </div>
      </li>
    </ul>
  </nav>
  <div id="layoutSidenav">
    <div id="layoutSidenav_nav" class="d-print-none border-end">
      <nav class="sb-sidenav accordion sb-sidenav-dark">
        <div class="sb-sidenav-menu" id="sidenavAccordion">
          <div class="nav">
            <!--<a class="nav-link mt-1" href="{{url('/dashboard')}}">
              <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
              Dashboard
            </a>-->
            @if(!Auth::user()->level == 0)
            <a class="nav-link" href="{{url('/rekap-presensi')}}">
              <div class="sb-nav-link-icon"><i class="fas fa-calendar"></i></div>
              Rekap Presensi
            </a>
            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseCuti" aria-expanded="false" aria-controls="collapseCuti">
              <div class="sb-nav-link-icon"><i class="fas fa-address-book"></i></div>
              Cuti
              <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse" id="collapseCuti" aria-labelledby="headingTwo" data-bs-parent="#sidenavAccordion">
              <nav class="sb-sidenav-menu-nested nav">
                <a class="nav-link" href="{{url('/pengajuan-cuti')}}">Pengajuan Cuti</a>
                <a class="nav-link" href="{{url('/daftar-cuti')}}">Daftar Cuti</a>
                <a class="nav-link" href="{{url('/jatah-cuti')}}">Jatah Cuti</a>
              </nav>
            </div>
            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseizin" aria-expanded="false" aria-controls="collapseizin">
              <div class="sb-nav-link-icon"><i class="fas fa-circle"></i></div>
              Izin
              <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse" id="collapseizin" aria-labelledby="headingTwo" data-bs-parent="#sidenavAccordion">
              <nav class="sb-sidenav-menu-nested nav">
                <a class="nav-link" href="{{url('/pengajuan-izin')}}">Pengajuan izin</a>
                <a class="nav-link" href="{{url('/daftar-izin')}}">Daftar izin</a>
              </nav>
            </div>
            @endif
            @if(Auth::user()->level == 0)
            <div class="sb-sidenav-menu-heading">Presensi</div>
            <a class="nav-link" href="{{url('/presensi-harian')}}">
              <div class="sb-nav-link-icon"><i class="fas fa-list"></i></div>Presensi Harian
            </a>
            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapsePresensi" aria-expanded="false" aria-controls="collapsePresensi">
              <div class="sb-nav-link-icon"><i class="fas fa-list"></i></div>
              Data Presensi
              <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse" id="collapsePresensi" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
              <nav class="sb-sidenav-menu-nested nav">
                <a class="nav-link" href="{{url('/semua-presensi')}}">Semua Presensi</a>
                <a class="nav-link" href="{{url('/laporan-presensi')}}">Laporan Presensi</a>
              </nav>
            </div>
            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseKonfirmasi" aria-expanded="false" aria-controls="collapseKonfirmasi">
              <div class="sb-nav-link-icon"><i class="fas fa-address-book"></i></div>
              Konfirmasi
              <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse" id="collapseKonfirmasi" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
              <nav class="sb-sidenav-menu-nested nav">
                <a class="nav-link" href="{{url('/konfirmasi-cuti')}}">Konfirmasi Cuti</a>
                <a class="nav-link" href="{{url('/konfirmasi-izin')}}">Konfirmasi Izin</a>
              </nav>
            </div>
            <a class="nav-link" href="{{url('/maps-location')}}">
              <div class="sb-nav-link-icon"><i class="fas fa-map"></i></div>Lokasi Maps
            </a>
            <div class="sb-sidenav-menu-heading">Master</div>
            <a class="nav-link" href="{{url('/manajemen-pengumuman')}}">
              <div class="sb-nav-link-icon"><i class="fas fa-bullhorn"></i></div>Pengumuman
            </a>
            <a class="nav-link" href="{{url('/manajemen-user')}}">
              <div class="sb-nav-link-icon"><i class="fas fa-user"></i></div>Master User
            </a>
            <a class="nav-link" href="{{url('/jam-masuk')}}">
              <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>Master Jam Kerja
            </a>
            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseManajemencuti" aria-expanded="false" aria-controls="collapseManajemencuti">
              <div class="sb-nav-link-icon"><i class="fas fa-address-book"></i></div>
              Master Cuti
              <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse" id="collapseManajemencuti" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
              <nav class="sb-sidenav-menu-nested nav">
                <a class="nav-link" href="{{url('/jenis-cuti')}}">Jenis Cuti</a>
                <a class="nav-link" href="{{url('/manajemen-jatah-cuti')}}">Jatah Cuti</a>
              </nav>
            </div>
            <a class="nav-link" href="{{url('/atur-header')}}">
              <div class="sb-nav-link-icon"><i class="fas fa-image"></i></div>Atur Header
            </a>
            <a class="nav-link" href="{{url('/atur-radius')}}">
              <div class="sb-nav-link-icon"><i class="fas fa-image"></i></div>Atur Radius
            </a>
            @endif
          </div>
        </div>
        <div class="sb-sidenav-footer">
          <div class="small">Logged in as:</div>
          {{Auth::user()->nama}}
        </div>
      </nav>
    </div>
    <div id="layoutSidenav_content">
      <main>
        <div class="container-fluid px-4">
          @yield('content')
        </div>
      </main>
    </div>
  </div>
  <!-- Bootstrap -->
  <script src="{{asset('assets/js/bootstrap.bundle.min.js')}}"></script>
  <script src="{{asset('assets/js/scripts.js')}}"></script>
  <!-- Vanillajs Datatables -->
  <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" crossorigin="anonymous"></script>
  <!-- Chart.js -->
  <script src="{{asset('assets/plugins/chart.js/Chart.min.js')}}"></script>
  <!-- Summernote -->
  <script src="{{asset('assets/plugins/summernote/summernote.min.js')}}"></script>
  <!-- Choices.js-->
  <script src="{{asset('assets/plugins/choices.js/public/assets/scripts/choices.min.js')}}"></script>
  <!-- Sweetalert -->
  <script src="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
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
    $(document).ready(function() {
      $('#summernote').summernote();
      $('#summernote2').summernote();
    });
  </script>
  <script>
    // Choices.js
    var selectStateInputEl = document.querySelector('#choices-select');
    if (selectStateInputEl) {
      const choices = new Choices(selectStateInputEl);
    }
  </script>
  <script>
    const Toast = Swal.mixin({
      toast: true,
      position: 'top-end',
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
</body>

</html>