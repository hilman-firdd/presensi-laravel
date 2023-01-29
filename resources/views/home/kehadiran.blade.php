@extends('layouts.app')

@section('content')

<div class="content-wrapper">

    <div class="container">

        <div class="row pad-botm">

            <div class="col-md-12">

                <h4 class="header-line"><i class="fa fa-calendar"></i> Data Presensi</h4>

            </div>

        </div>

        <div class="row">

            <div class="col-md-12">

                <div class="panel panel-default">

                    <div class="panel-heading">

                    </div>

                    <div class="panel-body">

                        <div class="alert alert-info">

                            <strong>* Data presensi hari ini</strong>
                        </div>

                        <div class="table table-responsive">

                            <table class="table table-striped table-bordered table-hover" id="dataTables-example">

                                <thead>

                                    <tr>

                                        <th>No</th>

                                        <th>Nama</th>

                                        <th>Berangkat <br> (H:I:S)</th>

                                        <th>Pulang <br> (H:I:S)</th>

                                        <th>Waktu Kehadiran</th>

                                    </tr>

                                </thead>

                                <tbody>

                                    <?php $no = 1; ?>

                                    @foreach($data as $row)

                                    @if ($namahari == 'Saturday')
                                    @if( $row->berangkat > $row->sabtu )
                                    <tr class="danger">
                                        <td>{{$no}}</td>
                                        <td>{{$row->name}}</td>
                                        <td>{{$row->berangkat}}</td>
                                        <td>{{$row->pulang}}</td>
                                        <td>{{$row->created_at}}</td>
                                    </tr>
                                    @else
                                    <tr>
                                        <td>{{$no}}</td>
                                        <td>{{$row->name}}</td>
                                        <td>{{$row->berangkat}}</td>
                                        <td>{{$row->pulang}}</td>
                                        <td>{{$row->created_at}}</td>
                                    </tr>
                                    @endif
                                    @elseif ($namahari == 'Sunday')
                                    @if( $row->berangkat > $row->minggu )
                                    <tr class="danger">
                                        <td>{{$no}}</td>
                                        <td>{{$row->name}}</td>
                                        <td>{{$row->berangkat}}</td>
                                        <td>{{$row->pulang}}</td>
                                        <td>{{$row->created_at}}</td>
                                    </tr>
                                    @else
                                    <tr>
                                        <td>{{$no}}</td>
                                        <td>{{$row->name}}</td>
                                        <td>{{$row->berangkat}}</td>
                                        <td>{{$row->pulang}}</td>
                                        <td>{{$row->created_at}}</td>
                                    </tr>
                                    @endif
                                    @else
                                    @if( $row->berangkat > $row->jammasuk )
                                    <tr class="danger">
                                        <td>{{$no}}</td>
                                        <td>{{$row->name}}</td>
                                        <td>{{$row->berangkat}}</td>
                                        <td>{{$row->pulang}}</td>
                                        <td>{{$row->created_at}}</td>
                                    </tr>
                                    @else
                                    <tr>
                                        <td>{{$no}}</td>
                                        <td>{{$row->name}}</td>
                                        <td>{{$row->berangkat}}</td>
                                        <td>{{$row->pulang}}</td>
                                        <td>{{$row->created_at}}</td>
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

</div>

<script src="{{asset('assets/js/jquery-1.10.2.js')}}"></script>

<script src="{{asset('assets/js/bootstrap.js')}}"></script>

<script src="{{asset('assets/js/dataTables/jquery.dataTables.js')}}"></script>

<script src="{{asset('assets/js/dataTables/dataTables.bootstrap.js')}}"></script>

<script src="{{asset('assets/js/custom.js')}}"></script>

@endsection