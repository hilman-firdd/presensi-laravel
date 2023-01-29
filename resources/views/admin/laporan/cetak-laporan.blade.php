@extends('layouts.app-admin')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">
            Cetak Laporan
        </h1>
        <ol class="breadcrumb">
            <li class="active">
                <i class="fa fa-print"></i> Cetak Laporan
            </li>
        </ol>
        <div class="row">
        <div class="col-md-12">
        <div class="panel panel-info">
        <div class="panel panel-heading">
        <i class="fa fa-download"></i> Cetak detail presensi
        </div>
        <div class="panel panel-body">
        <form action="{{ url('/laporan') }}" method="GET" target="_blank">
            <div class="col-md-3">
            Dari tanggal
               <div class="input-group">
                   <span class="input-group-addon" id="basic-addon1"><i class="fa fa-calendar"></i></span>
                   <input type="date"  class="form-control" name="dari" value="{{ old('tanggal') }}" >
               </div>
           </div>
           <div class="col-md-3">
           Sampai tanggal
               <div class="input-group">
                   <span class="input-group-addon" id="basic-addon1"><i class="fa fa-calendar"></i></span>
                   <input type="date"  class="form-control" name="sampai" value="{{ old('tanggal') }}" > 
               </div>
           </div>
           <div class="col-md-1">
           &nbsp;
               <button class="btn btn-primary"><i class="fa fa-search"></i> Cari</button>
           </div>
        </form>
        </div>
        </div>
        </div>
       </div><br>

       <div class="row">
        <div class="col-md-12">
        <div class="panel panel-success">
        <div class="panel panel-heading">
        <i class="fa fa-download"></i> Cetak rekap presensi
        </div>
        <div class="panel panel-body">
        <form action="{{ url('/rekap-laporan') }}" method="GET" target="_blank">
            <div class="col-md-3">
            Dari tanggal
               <div class="input-group">
                   <span class="input-group-addon" id="basic-addon1"><i class="fa fa-calendar"></i></span>
                   <input type="date"  class="form-control" name="dari" value="{{ old('tanggal') }}" >
               </div>
           </div>
           <div class="col-md-3">
           Sampai tanggal
               <div class="input-group">
                   <span class="input-group-addon" id="basic-addon1"><i class="fa fa-calendar"></i></span>
                   <input type="date"  class="form-control" name="sampai" value="{{ old('tanggal') }}" > 
               </div>
           </div>
           <div class="col-md-1">
           &nbsp;
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