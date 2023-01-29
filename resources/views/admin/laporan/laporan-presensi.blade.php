
@extends('layouts.app-admin')
@section('content')
<h2 class="mt-3">Laporan Presensi</h2>
<ol class="breadcrumb mb-3">
<li class="breadcrumb-item"><a href="{{url('/dashboard')}}">Home</a></li>
<li class="breadcrumb-item active">Laporan Presensi</li>
</ol>


<div class="card">
      <div class="card-body">
        <div class="alert alert-warning">
            <small>Cari laporan presensi berdasarkan rentang tanggal</small>
        </div>
        <form action="{{ url('/laporan') }}" method="GET" target="_blank">
          <div class="col-md-6">
            Tanggal Mulai
            <div class="input-group mb-3">
             <input type="date"  class="form-control" name="dari" value="{{ old('tanggal') }}" >
           </div>
         </div>
         <div class="col-md-6">
           Tanggal Akhir
           <div class="input-group mb-3">
             <input type="date"  class="form-control" name="sampai" value="{{ old('tanggal') }}" >
           </div>
         </div>
         <div class="col-md-6">
           Cluster
           <div class="input-group mb-3">
            <select type="text" class="form-select" name="cluster">
              <option value="">- Pilih -</option>
              <option value="Tetap">Tetap</option>
              <option value="Honorer">Honorer</option>
              <option value="Satpam">Satpam</option>
              <option value="OB">OB</option>
              <option value="Lainnya">Lainnya</option>
            </select>
           </div>
         </div>
         <div class="col-md-3">
           <button class="btn btn-primary btn-lg"><i class="fa fa-search"></i> Cari</button>
         </div>
       </form>
     </div>
   </div>
 </div>
</div><br><br>
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