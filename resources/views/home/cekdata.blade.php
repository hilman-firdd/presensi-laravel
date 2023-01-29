@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-4 col-md-offset-4">
        <div class="row">
            <div class="col-md-12">
                <div class="bs-callout bs-callout-default" style="background-color: white;">
                    {!! Form::open(['url' => ['simpan-presensi-berangkat']]) !!}
                    @if (count($data) > 0)
                    @foreach($data as $row)
                    <center>
                        <input type="hidden" name="id_user" value="{{ $row-> id}}">
                        <img src="{{$row->foto}}">
                    </center>
                    @endforeach
                    <br>
                    <center>
                        <a href="{{url('/')}}">
                            <input type="button" value="Kembali" class="btn btn-default"></a>
                        <button class="btn btn-primary"><i class="fa fa-save"></i> Simpan Presensi</button>
                    </center>
                    {!! Form::close() !!}
                    @else
                    <br>
                    <div class="alert alert-danger" role="alert">Data tidak ditemukan</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{asset('assets/js/jquery-1.10.2.js')}}"></script>
<script src="{{asset('assets/js/bootstrap.js')}}"></script>
@endsection