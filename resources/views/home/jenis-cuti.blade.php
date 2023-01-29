@extends('layouts.app')
@section('content')
<div class="content-wrapper">
    <div class="container">
        <div class="row pad-botm">
            <div class="col-md-12">
                <h4 class="header-line"><i class="fa fa-share"></i> Jenis Cuti</h4>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <!-- Advanced Tables -->
                <div class="panel panel-default">
                    <div class="panel-heading">
                    </div>
                    <div class="panel-body">
                        <div class="table table-responsive">
                            <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Jenis Cuti</th>
                                        <th>Hari</th>
                                        <th>Syarat/ Prasyarat</th>
                                        <th>Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no = 1; ?>
                                    @foreach($data as $row)
                                    <tr>
                                        <td>{{$no}}</td>
                                        <td>{{$row->jenis_cuti}}</td>
                                        <td>{{$row->hari}}</td>
                                        <td>{{$row->syarat}}</td>
                                        <td>{{$row->keterangan}}</td>
                                    </tr>
                                    <?php $no++; ?>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="alert alert-info">
                            * Sumber: UU Karyawan No 13 Tahun 2003<br>
                            PP No 78 tahun 2015 <br>
                            <!-- RPL Yayasan ILMI Januari 2017 -->
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