@extends('layouts.app-admin')
@section('content')
<h2 class="mt-3">Users</h2>
<ol class="breadcrumb mb-3">
    <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">Home</a></li>
    <li class="breadcrumb-item active">Users</li>
</ol>

<div class="card">
    {!! Form::open(['url' => 'hapus-data/{id}']) !!}
    <div class="card-header">
        <a href="{{url('/tambah-user')}}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah User
        </a>
        <a href="{{url('/arsip-user')}}" class="btn btn-secondary">
            <i class="fas fa-archive"></i> Lihat Arsip User
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-bordered table-hover table-striped" id="dataTable2" width="100%"
                cellspacing="0">
                <thead>
                    <tr>
                        <th class="text-center" width="5%"><input type="checkbox" name="select_all" id="select_all"
                                value="" /></th>
                        <th class="text-center">No</th>
                        <th class="text-center">NIK</th>
                        <th class="text-center">Nama</th>
                        <th class="text-center">Level</th>
                        <th class="text-center">Cluster</th>
                        <th class="text-center">Rincian</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; ?>
                    @foreach($data as $row)
                    <tr>
                        <td align="center"><label class="checkbox-inline"><input type="checkbox" name="checked_id[]"
                                    class="checkbox" value="{{$row->id}}" /></label></td>
                        <td align="center">{{$no}}</td>
                        <td align="center">{{$row->nik}}</td>
                        <td>{{$row->nama}}</td>
                        <td>
                            @if($row->level == 0)
                            Admin
                            @elseif($row->level == 1)
                            Pimpinan Yayasan
                            @elseif($row->level == 2)
                            Pimpinan Fakultas
                            @elseif($row->level == 3)
                            Pimpinan Program Studi
                            @elseif($row->level == 4)
                            Pimpinan Divisi
                            @elseif($row->level == 5)
                            Non Jabatan
                            @endif
                        </td>
                        <td>{{$row->cluster}}</td>
                        <td align="center">
                            <a href="{!! url('/'.$row->id_user.'/detail-biodata') !!}">
                                <input type="button" class="btn btn-outline-primary btn-sm" value="Lihat">
                            </a>
                        </td>
                        <td align="center">
                            <a href="{!! url('/'.$row->id_user.'/edit-data') !!}">
                                <input type="button" class="btn btn-primary btn-sm" value="Edit"></a>
                        </td>
                    </tr>
                    <?php $no++; ?>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="card-footer">
        <input type="hidden" name="delete_submit">
        <button class="btn btn-danger" onclick="return confirm('Arsipkan Data Pegawai?')"><i class="fas fa-archive"></i>
            Arsipkan</button>
    </div>

</div>
{{Form::close()}}
</div>

<script type="text/javascript">
$(function() {
    $('#example1').dataTable();
});
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