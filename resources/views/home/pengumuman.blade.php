@extends('layouts.app')

@section('content')
<div class="py-3">
    <div class="card">
        <div class="card-body">
            <h2 class="mb-3">Pengumuman</h2>
            @foreach($data as $row)
            <h4 class="fw-bold">{!! $row->judul !!}</h4>
            <h6 class="mb-4">Tanggal: {{date_format(date_create($row->tanggal),"d F Y")}} - Penulis: </strong> {{$row->nama}} - <i>Dilihat: {{$row->view}} kali</i></h6>
            <p align="justify">
                {!! $row->isi !!}
                <img src="{{asset('img/pengumuman')}}/{{$row->gambar}}" alt="{{$row->judul}}" title="{{$row->judul}}" width="100%" />
                @endforeach
            </p>
        </div>
    </div>
</div>

</div>
@endsection