@extends('layouts.app-admin')

@section('content')
<script type='text/javascript' src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script type='text/javascript' src="https://maps.google.com/maps/api/js?key=AIzaSyD95RyzZ5IEngrkYckzqRMAwyCZ7-eezMw"></script>
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA3k-pbMEQyMgis8LZAACDUyjdBnHoatwQ&callback=initMap" type="text/javascript"></script>
  <script async defer src="https://maps.googleapis.com/maps/api/js?sensor=false&callback=initMap" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gmaps.js/0.4.24/gmaps.js"></script>
    
    <style type="text/css">
      #mymap {
          border:1px solid red;
          width: 100%;
          height:500px;
      }
    </style>
<h2 class="mt-3">Lokasi Pegawai</h2>
<ol class="breadcrumb mb-3">
<li class="breadcrumb-item"><a href="{{url('/dashboard')}}">Home</a></li>
<li class="breadcrumb-item active">Lokasi MAPS</li>
</ol>

<div class="row">
    <div class="col-lg-12">
<div id="mymap"></div>
  <script type="text/javascript">
   <?php
            $data = DB::select('select * from tb_header');
            foreach ($data as $key => $value) {
            ?>

    var locations = <?php print_r(json_encode($locations)) ?>;
    var mymap = new GMaps({
      el: '#mymap',
      lat: '{{$value->lat}}',
      lng: '{{$value->lng}}',
      zoom:15
    });
        
    <?php
            }
            ?>
    $.each( locations, function( index, value ){
        mymap.addMarker({
          lat: value.lat,
          lng: value.lng,
          title: value.nama,
        });
   });
  </script>
</div>
</div>
@endsection