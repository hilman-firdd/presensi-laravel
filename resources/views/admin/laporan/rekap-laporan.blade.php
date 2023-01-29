
@extends('layouts.app-admin')
@section('content')
<h1 class="mt-4">Laporan Kehadiran</h1><hr>
<div class="row hidden-print">
    <div class="col-lg-12">
     <div class="panel panel-default">
        <div class="panel-heading">
        </div>
        <div class="panel-body">
            <div class="alert alert-info">
                Data presensi dari tanggal <strong>{{$dari}} s/d {{$sampai}}</strong></div>
                <a id="print" onclick="window.print()" class="btn btn-primary d-print-none" style="color: white;"><i class="fa fa-print"></i> Cetak</a>
              </button>
              <div class="table table-bordered">
                <table class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Jumlah Kehadiran</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        @foreach($data as $row)
                        <tr>
                            <td>{{$no}}</td>
                            <td>{{$row->name}}</td>
                            <td>{{$row->jumlah}}</td>
                            <td>
                                @if($row->cuti <= 0)
                                -
                                @else
                                Tidak berangkat : {{$row->cuti}} Kali
                                @endif
                            </td>
                        </tr>
                        <?php $no++; ?>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</div>
@endsection