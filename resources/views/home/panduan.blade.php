@extends('layouts.app')
@section('content')
<div class="py-4">
    <div class="row">
        <div class="col-md-9">
            <h2>Panduan Presensi Online</h2>
            <h3 class="mb-3">Pengumuman</h3>
            <?php

            use Illuminate\Support\Str; ?>
            @foreach($data as $row)
            <div class="card card-body mb-3">
                <!--<img src="{{asset('img/pengumuman')}}/{{$row->gambar}}" class="img-fluid" alt="{{$row->judul}}" title="{{$row->judul}}" />-->
                <small class="mb-3"><i class="fas fa-calendar-day"></i> {{date_format(date_create($row->tanggal),"d/m/Y")}} - Oleh {{$row->nama}}</small>
                <h3>
                    <a href="{!! url('/post/'.$row->url.'') !!}" class="text-decoration-none" title="{{$row->judul}}">{{$row->judul}}</a>
                </h3>
                {!! Str::limit($row->isi, 150, ' ...') !!} <a href="{!! url('/post/'.$row->url.'') !!}" class="text-decoration-none" title="{{$row->judul}}">Selengkapnya</a>
            </div>
            @endforeach
        </div>
        <div class="col-md-3">

        </div>
    </div>

</div>
@endsection