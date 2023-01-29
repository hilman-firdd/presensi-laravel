@extends('layouts.app-admin')
@section('content')
<h2 class="mt-3">Ijin/ Cuti Kerja</h2>
<ol class="breadcrumb mb-3">
  <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#myModal">
   <i class="fa fa-plus"></i> Input Ijin / Cuti
 </button>
</ol>

<div class="panel panel-info">

 <div class="panel-body">

   {!! Form::open(['url' => 'hapus-ijin/{id}']) !!}

   <div class="table table-responsive">

    <table class="table table-bordered table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
      <thead>
        <tr>
          <th class="text-center" width="5%"><input type="checkbox" name="select_all" id="select_all" value=""/></th>
          <th class="text-center">No</th>

          <th class="text-center">NIK</th>

          <th class="text-center">Nama</th>

          <th class="text-center">Keterangan</th>
          <th class="text-center">Keterangan Detail</th>
          <th class="text-center">Tanggal</th>

          <th class="text-center">Aksi</th>

        </tr>

      </thead>

      <tbody>

        <?php $no = 1; ?>

        @foreach($data as $row)

        <tr>

          <td align="center"><label class="checkbox-inline"><input type="checkbox" name="checked_id[]" class="checkbox" value="{{$row->id}}"/></label></td>

          <td align="center">{{$no}}</td>

          <td align="center">{{$row->password_presensi}}</td>

          <td>{{$row->name}}</td>

          <td  align="center">
            @if($row->keterangan == 1)
            Ijin
            @elseif($row->keterangan == 2)
            Alpa
            @elseif($row->keterangan == 3)
            Cuti
            @endif</td>
          <td>{{$row->keterangan_rinci}}</td>
          <td  align="center">{{$row->tanggal}}</td>

          <td align="center">

            <input type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#yourModal1{{$row->id}}" value="Edit">

          </td>

        </tr>

        <?php $no++; ?>

        @endforeach

      </tbody>

    </table>

  </div>

</div>

<div class="row">

  <div class="col-md-2 col-md-offset-10">

    <input type="submit" class="btn btn-danger btn-block" name="delete_submit" value="HAPUS"/><br>

  </div>

</div>

{{Form::close()}}

</div>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">

  <div class="modal-dialog" role="document">

    <div class="modal-content">

      <div class="modal-header">

        <strong>

          Tambah Ijin / Cuti</strong>

          <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

        </div>

        <div class="modal-body">

          {!! Form::open(['url' => 'simpan-ijin']) !!}

          Nama:

          <select name="id_user" class="form-control" data-live-search="true">
            <option value="" data-tokens="mustard">- Pilih -</option>
            @foreach($nama as $row)

            <option value="{{$row->id}}" data-tokens="mustard">{{$row->name}}</option>

            @endforeach
          </select><br>

          Tanggal:
          <input type="text" name="tanggal" class="form-control" value="{{$tanggal}}"><br>

          Keterangan:
          <select name="keterangan" class="form-control" data-live-search="true">
              <option value="">- Keterangan-</option>
              <option value="1" data-tokens="mustard">Sakit</option>
              <option value="2" data-tokens="mustard">Ijin</option>
              <option value="3" data-tokens="mustard">Alfa</option>
          </select><br>

          Keterangan Detail:
          <input type="text" name="keterangan_rinci" class="form-control"><br>

          <button class="btn btn-primary">Simpan</button>

          {!! Form::close() !!}

        </div>

      </div>

    </div>

  </div>



  @foreach ($data as $row)    

  {!! Form::model($row, ['url' => ['/update-ijin', $row->id]]) !!}

  <div class="modal fade" id="yourModal1{{$row->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">

    <div class="modal-dialog" role="document">

      <div class="modal-content">

        <div class="modal-header">

          Edit Ijin / Cuti

        </div>

        <div class="modal-body"> 
          <strong>Keterangan:</strong>
          <select name="keterangan" class="form-control" data-live-search="true">
            <option>- Keterangan-</option>
            <option value="1" data-tokens="mustard">Sakit</option>
            <option value="2" data-tokens="mustard">Ijin</option>
            <option value="3" data-tokens="mustard">Alpa</option>
          </select><br>
          Keterangan Detail:
          <input type="text" name="keterangan_rinci" class="form-control" value="{{$row->keterangan_rinci}}"><br>
        </div>

        <div class="modal-footer">

          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

          <button class="btn btn-primary">Update</button>

        </div>

      </div>

    </div>

  </div>

  {!!Form::close()!!}

  @endforeach


  <script type="text/javascript">

   $(function() {

    $('#example1').dataTable();

  });

   $(document).ready(function(){

    $('#select_all').on('click',function(){

      if(this.checked){

        $('.checkbox').each(function(){

          this.checked = true;

        });

      }else{

       $('.checkbox').each(function(){

        this.checked = false;

      });

     }

   });

  });

</script>

@endsection