
@extends('layouts.app-admin')
@section('content')
<h1 class="mt-4">Admin</h1>
<ol class="breadcrumb mb-4">
  Data Admin
</ol>
<div class="panel panel-info">
 <div class="panel-body">
   {!! Form::open(['url' => 'hapus-data/{id}']) !!}
   <div class="table table-responsive">
     <table class="table table-bordered table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
      <thead>
        <tr>
          <th class="text-center" width="5%"><input type="checkbox" name="select_all" id="select_all" value=""/></th>
          <th class="text-center">No</th>
          <th class="text-center">NIK</th>
          <th class="text-center">Nama</th>
          <th class="text-center">Rincian</th>
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
          <td align="center">
            <a href="{!! url('/'.$row->id.'/detail-biodata') !!}">
              <input type="button" class="btn btn-warning" value="Detail">
            </a></td>
            <td align="center">
              <a href="{!! url('/'.$row->id.'/edit-data') !!}">
                <input type="button" class="btn btn-primary" value="Edit"></a>
              </td>
            </tr>
            <?php $no++; ?>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
    <div class="row">
      <div class="col-md-2">
        <input type="submit" class="btn btn-danger btn-block" name="delete_submit" value="ARSIPKAN"/><br>
      </div>
    </div>
  </div>
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