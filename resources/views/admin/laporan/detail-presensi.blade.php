
@extends('layouts.app-admin')
@section('content')
<h2 class="mt-3">Detail Presensi</h2>
<ol class="breadcrumb mb-3">
<li class="breadcrumb-item"><a href="{{url('/dashboard')}}">Home</a></li>
<li class="breadcrumb-item active">Detail Laporan Presensi</li>
</ol>
<div class="row">
  <div class="col-md-12">
    <div class="panel panel-success">
      <div class="panel panel-body">
        <form action="{{ url('/laporan') }}" method="GET" target="_blank">
          <div class="col-md-5">
            Tanggal Mulai
            <div class="input-group">
             <input type="date"  class="form-control" name="dari" value="{{ old('tanggal') }}" >
           </div>
         </div><br>
         <div class="col-md-5">
           Tanggal Akhir
           <div class="input-group">
             <input type="date"  class="form-control" name="sampai" value="{{ old('tanggal') }}" >
           </div>
         </div><br>
         <div class="col-md-3">
           <button class="btn btn-primary"><i class="fa fa-search"></i> Cari</button>
         </div>
       </form>
     </div>
   </div>
 </div>
</div><br><br>
</div>
</div>
</div>
</div>

<script type="text/javascript">
 $(document).ready(function() {
  $('#birthday').daterangepicker({
    singleDatePicker: true,
    locale: {
      format: 'DD-MM-YYYY'
    },
    maxDate: new Date(),
    calender_style: "picker_1"
  });
});
</script>
@endsection