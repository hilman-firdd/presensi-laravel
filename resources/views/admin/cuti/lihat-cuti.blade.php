@extends('layouts.app-admin')
@section('content')
<h2 class="mt-3">Lihat Jatah Cuti</h2>
<ol class="breadcrumb mb-3">
    <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">Home</a></li>
    <li class="breadcrumb-item active">Jatah Pengajuan Cuti</li>
</ol>
<div class="row">
    <div class="col-xl-12">
        <div class="card mb-12">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th class="text-center" width="5%">No</th>
                                <th class="text-center">Jenis Cuti</th>
                                <th class="text-center">Keterangan Cuti</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $row)
                            <tr>
                                <td align="center">1</td>
                                <td>Cuti Tahunan</td>
                                <td>Sisa Cuti : {{$row->cuti_tahunan}}</td>
                            </tr>
                            <tr>
                                <td align="center">2</td>
                                <td>Cuti Bersama</td>
                                <td>Sisa Cuti : {{$row->cuti_bersama}}</td>
                            </tr>
                            <tr>
                                <td align="center">3</td>
                                <td>Cuti Lain</td>
                                <td>Cuti Terpakai : {{$row->cuti_lain}}</td>
                            </tr>
                            <tr>
                                <td align="center">4</td>
                                <td>Cuti Berjalan</td>
                                <td>Cuti Terpakai : {{$row->cuti_berjalan}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>
@endsection