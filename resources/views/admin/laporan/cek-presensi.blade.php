@extends('layouts.app-admin')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">
            Data presensi
        </h1>
        <div class="alert alert-danger">
            <i class="fa fa-list"></i> List presensi
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
            </div>
            <div class="panel-body">
                <div class="table table-responsive">
                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Hari</th>
                                <th>Berangkat <br> (H:I:S)</th>
                                <th>Pulang <br> (H:I:S)</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; ?>
                            @foreach($data as $row)

                            <!-- Hari -->


                            <!--  -->
                            @if($row->hari == 'Saturday')
                            @if($row->sabtu < $row->berangkat)
                                <tr class="danger">
                                    <td>{{$no}}</td>
                                    <td>{{$row->name}}</td>
                                    <td>@if($row->hari == 'Sunday')
                                        Minggu
                                        @elseif($row->hari == 'Monday')
                                        Senin
                                        @elseif($row->hari == 'Tuesday')
                                        Selasa
                                        @elseif($row->hari == 'Wednesday')
                                        Rabu
                                        @elseif($row->hari == 'Thursday')
                                        Kamis
                                        @elseif($row->hari == 'Friday')
                                        Jumat
                                        @elseif($row->hari == 'Saturday')
                                        Sabtu
                                        @else
                                        @endif </td>
                                    <td>{{$row->berangkat}}</td>
                                    <td>{{$row->pulang}}</td>
                                    <td>{{$row->tanggal}}</td>
                                </tr>
                                @else
                                <tr>
                                    <td>{{$no}}</td>
                                    <td>{{$row->name}}</td>
                                    <td>@if($row->hari == 'Sunday')
                                        Minggu
                                        @elseif($row->hari == 'Monday')
                                        Senin
                                        @elseif($row->hari == 'Tuesday')
                                        Selasa
                                        @elseif($row->hari == 'Wednesday')
                                        Rabu
                                        @elseif($row->hari == 'Thursday')
                                        Kamis
                                        @elseif($row->hari == 'Friday')
                                        Jumat
                                        @elseif($row->hari == 'Saturday')
                                        Sabtu
                                        @else
                                        @endif </td>
                                    <td>{{$row->berangkat}}</td>
                                    <td>{{$row->pulang}}</td>
                                    <td>{{$row->tanggal}}</td>
                                </tr>
                                @endif
                                @elseif($row->hari == 'Sunday')
                                @if($row->minggu < $row->berangkat)
                                    <tr class="danger">
                                        <td>{{$no}}</td>
                                        <td>{{$row->name}}</td>
                                        <td>@if($row->hari == 'Sunday')
                                            Minggu
                                            @elseif($row->hari == 'Monday')
                                            Senin
                                            @elseif($row->hari == 'Tuesday')
                                            Selasa
                                            @elseif($row->hari == 'Wednesday')
                                            Rabu
                                            @elseif($row->hari == 'Thursday')
                                            Kamis
                                            @elseif($row->hari == 'Friday')
                                            Jumat
                                            @elseif($row->hari == 'Saturday')
                                            Sabtu
                                            @else
                                            @endif </td>
                                        <td>{{$row->berangkat}}</td>
                                        <td>{{$row->pulang}}</td>
                                        <td>{{$row->tanggal}}</td>
                                    </tr>
                                    @else
                                    <tr>
                                        <td>{{$no}}</td>
                                        <td>{{$row->name}}</td>
                                        <td>@if($row->hari == 'Sunday')
                                            Minggu
                                            @elseif($row->hari == 'Monday')
                                            Senin
                                            @elseif($row->hari == 'Tuesday')
                                            Selasa
                                            @elseif($row->hari == 'Wednesday')
                                            Rabu
                                            @elseif($row->hari == 'Thursday')
                                            Kamis
                                            @elseif($row->hari == 'Friday')
                                            Jumat
                                            @elseif($row->hari == 'Saturday')
                                            Sabtu
                                            @else
                                            @endif </td>
                                        <td>{{$row->berangkat}}</td>
                                        <td>{{$row->pulang}}</td>
                                        <td>{{$row->tanggal}}</td>
                                    </tr>
                                    @endif
                                    @else
                                    @if($row->jammasuk < $row->berangkat)
                                        <tr class="danger">
                                            <td>{{$no}}</td>
                                            <td>{{$row->name}}</td>
                                            <td>@if($row->hari == 'Sunday')
                                                Minggu
                                                @elseif($row->hari == 'Monday')
                                                Senin
                                                @elseif($row->hari == 'Tuesday')
                                                Selasa
                                                @elseif($row->hari == 'Wednesday')
                                                Rabu
                                                @elseif($row->hari == 'Thursday')
                                                Kamis
                                                @elseif($row->hari == 'Friday')
                                                Jumat
                                                @elseif($row->hari == 'Saturday')
                                                Sabtu
                                                @else
                                                @endif </td>
                                            <td>{{$row->berangkat}}</td>
                                            <td>{{$row->pulang}}</td>
                                            <td>{{$row->tanggal}}</td>
                                        </tr>
                                        @else
                                        <tr>
                                            <td>{{$no}}</td>
                                            <td>{{$row->name}}</td>
                                            <td>@if($row->hari == 'Sunday')
                                                Minggu
                                                @elseif($row->hari == 'Monday')
                                                Senin
                                                @elseif($row->hari == 'Tuesday')
                                                Selasa
                                                @elseif($row->hari == 'Wednesday')
                                                Rabu
                                                @elseif($row->hari == 'Thursday')
                                                Kamis
                                                @elseif($row->hari == 'Friday')
                                                Jumat
                                                @elseif($row->hari == 'Saturday')
                                                Sabtu
                                                @else
                                                @endif </td>
                                            <td>{{$row->berangkat}}</td>
                                            <td>{{$row->pulang}}</td>
                                            <td>{{$row->tanggal}}</td>
                                        </tr>
                                        @endif
                                        @endif
                                        <?php $no++; ?>
                                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<script src="{{asset('assets/js/jquery-1.10.2.js')}}"></script>
<script src="{{asset('assets/js/bootstrap.js')}}"></script>
<script src="{{asset('assets/js/dataTables/jquery.dataTables.js')}}"></script>
<script src="{{asset('assets/js/dataTables/dataTables.bootstrap.js')}}"></script>
<script src="{{asset('assets/js/custom.js')}}"></script>
@endsection