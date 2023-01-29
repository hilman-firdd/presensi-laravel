
@extends('layouts.app-admin')
@section('content')
<h1 class="mt-4">Jumlah Kehadiran</h1>
<ol class="breadcrumb mb-4"> Lihat Laporan Jumlah Kehadiran
</ol>
<div class="row">
  <div class="col-md-12">
    <div class="panel panel-success">
      <div class="panel panel-body">
        <form action="{{ url('/rekap-laporan') }}" method="GET" target="_blank">
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