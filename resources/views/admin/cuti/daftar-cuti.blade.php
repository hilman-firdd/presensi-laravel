@extends('layouts.app-admin')
@section('content')
<h2 class="mt-3">Daftar Pengajuan Cuti</h2>
<ol class="breadcrumb mb-3">
    <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">Home</a></li>
    <li class="breadcrumb-item active">Daftar Pengajuan Cuti</li>
</ol>
<div class="row">
    <div class="col-xl-12">
        <div class="card mb-12">
            <div class="card-body">
                {!! Form::open(['url' => 'batal-pengajuan-cuti/{id}']) !!}
                <div class="table-responsive">
                    <table id="dataTable2">
                        <thead>
                            <tr>
                                <th width="2%" class="text-center"><input type="checkbox" name="select_all" id="select_all" value=""/></th>
                                <th class="text-center">No</th>
                                <th class="text-center">Tanggal</th>
                                <th class="text-center">Alasan</th>
                                <th class="text-center">Jenis Cuti</th>
                                <th class="text-center">File</th>
                                <th class="text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; ?>
                            @foreach($data as $row)
                            @if($row->status == 'pengajuan')
                            <tr>
                            @elseif($row->status == 'di terima')
                            <tr class="bg-success" style="color: white;">
                            @elseif($row->status == 'di tolak')
                            <tr class="bg-danger" style="color: white;">
                            @endif
                                <td align="center"><label class="checkbox-inline"><input type="checkbox" name="checked_id[]" class="checkbox" value="{{$row->id}}"/></label></td>
                                <td align="center">{{$no}}</td>
                                <td align="center">{{date_format(date_create($row->tanggal),"d F Y")}}</td>
                                <td>{!!$row->alasan!!}</td>
                                <td>{!!$row->jenis_cuti!!}</td>
                                <td align="center"><a class="text-light" href="{{asset('/assets/file/cuti')}}/{{$row->bukti_pendukung}}" target="_blank" ><i class="fa fa-download"></i></a></td>
                                <td>{{$row->status}}</td>
                            </tr>
                            <?php $no++; ?>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <input type="submit" class="btn btn-danger" name="batal_pengajuan" onclick="return check()" value="BATAL PENGAJUAN"/>
                {{Form::close()}}
            </div>
        </div>
    </div>
</div>
</div>
</div>
<script type="text/javascript">
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

  $('input[type="checkbox"]').on('change', function() {
    $(this).closest('td').find('input').not(this).prop('checked', false);
});
</script>
@endsection