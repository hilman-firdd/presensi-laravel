@extends('layouts.app-admin')
@section('content')

<h2 class="mt-3">Arsip User</h2>
<ol class="breadcrumb mb-3">
    <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{url('/manajemen-user')}}">Master User</a></li>
    <li class="breadcrumb-item active">Arsip User</li>
</ol>

<div class="card">
    <div class="card-header">
        <a href="{{url('/manajemen-user')}}" class="text-dark text-decoration-none">
            <i class="fas fa-arrow-left"></i>&ensp;Kembali
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
        <table class="table table-bordered table-bordered table-hover table-striped" id="dataTable" width="100%"
                cellspacing="0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>NIK</th>
                        <th>Nama</th>
                        <th>JK</th>
                        <th>HP</th>
                        <th>Email</th>
                        <th>Jabatan</th>
                        <th>Unit</th>
                        <th>Status Pegawai</th>
                        <th>Alamat</th>
                        <th>Tgl Arsip</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; ?>
                    @foreach($data as $row)
                    <tr>
                        <td>{{$no}}</td>
                        <td>{{$row->nig}}</td>
                        <td>{{$row->nama}}</td>
                        <td>{{$row->jk}}</td>
                        <td>{{$row->no_hp}}</td>
                        <td>{{$row->email}}</td>
                        <td>{{$row->level}}</td>
                        <td>{{$row->unit}}</td>
                        <td>{{$row->status_kepegawaian}}</td>
                        <td>Jl. {{$row->jl}}, Rt. {{$row->rt}}, Rw. {{$row->rw}}, Dusun: {{$row->dusun}}, Desa:
                            {{$row->desa}}, Kecamatan: {{$row->kecamatan}}</td>
                        <td>{{$row->created_at}}</td>
                    </tr>
                    <?php $no++; ?>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function() {
    $('#select_all').on('click', function() {
        if (this.checked) {
            $('.checkbox').each(function() {
                this.checked = true;
            });
        } else {
            $('.checkbox').each(function() {
                this.checked = false;
            });
        }
    });
});
</script>
@endsection