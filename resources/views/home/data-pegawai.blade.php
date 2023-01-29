@extends('layouts.app')

@section('content')
<div class="py-4">
    <div class="card">
        <div class="card-body">
            <h2 class="mb-3">Data Pegawai/Karyawan</h2>
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped" id="dataTable2" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th class="text-center" width="5%">No</th>
                            <th class="text-center">NIK</th>
                            <th class="text-center">Nama</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        @foreach($data as $row)
                        <tr>
                            <td align="center">{{$no}}</td>
                            <td align="center">{{$row->nik}}</td>
                            <td>{{$row->nama}}</td>
                        </tr>
                        <?php $no++; ?>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection