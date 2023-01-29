
@extends('layouts.app-admin')
@section('content')
<style type="text/css">
   .table th { 
      background-color: #f5f5f5 !important; 
 } 
 .red  { 
      color: red !important; 
 } 
 .table td.red { 
      background-color: red !important; 
 } 

.table td.red { 
   background-color: red !important; 
} 

.ungu  { 
   color: #d961f9 !important; 
} 
.table td.ungu { 
   background-color: #d961f9 !important; 
} 

.table td.ungu { 
   background-color: #d961f9 !important; 
} 
@media print { 
 .table th { 
      background-color: #f5f5f5 !important; 
 } 
 .red  { 
      color: red !important; 
 } 
 .table td.red { 
      background-color: red !important; 
 } 
}

.table td.red { 
   background-color: red !important; 
} 

.ungu  { 
   color: #d961f9 !important; 
} 
.table td.ungu { 
   background-color: #d961f9 !important; 
} 

.table td.ungu { 
   background-color: #d961f9 !important; 
} 

</style>
<h2 class="mt-3">Detail Laporan Presensi</h2>
<ol class="breadcrumb mb-3">
<li class="breadcrumb-item"><a href="{{url('/dashboard')}}">Home</a></li>
<li class="breadcrumb-item active">Detail Laporan Presensi</li>
</ol>
<form action="{{url('downloadLaporanExcel/xlsx')}}" method="GET" target="_blank">
     <input type="hidden" name="dari" value="{{$dari}}">
     <input type="hidden" name="sampai" value="{{$sampai}}">
     <input type="hidden" name="cluster" value="{{$cluster}}">
<button class="btn btn-success mb-3">Download Laporan (Excel .xlsx)</button>
</form>
<div class="row">
   <div class="col-md-12 col-xs-12 col-lg-12 col-sm-12">
      <div class="table-responsive">
           <table class="table table-striped table-bordered table-hover" style="font-size:8px; color:black;">
              <thead>
                 <tr>
                    <th class="text-center" rowspan="2">NO</th>
                    <th class="text-center" rowspan="2">Nama</th>
                    <th class="text-center" colspan="31">Tanggal</th>
               </tr>
               <tr>
                    <th>1</th>
                    <th>2</th>
                    <th>3</th>
                    <th>4</th>
                    <th>5</th>
                    <th>6</th>
                    <th>7</th>
                    <th>8</th>
                    <th>9</th>
                    <th>10</th>
                    <th>11</th>
                    <th>12</th>
                    <th>13</th>
                    <th>14</th>
                    <th>15</th>
                    <th>16</th>
                    <th>17</th>
                    <th>18</th>
                    <th>19</th>
                    <th>20</th>
                    <th>21</th>
                    <th>22</th>
                    <th>23</th>
                    <th>24</th>
                    <th>25</th>
                    <th>26</th>
                    <th>27</th>
                    <th>28</th>
                    <th>29</th>
                    <th>30</th>
                    <th>31</th>
               </tr>
          </thead>
          <tbody>
            <?php $no = 1; ?>
            @foreach($detail as $row)
            <tr>
               <td>{{$no}}</td>
               <td>{{$row->nama}}</td>
               <td align="center">{{$row->h1}}</td>
               <td align="center">{{$row->h2}}</td>
               <td align="center">{{$row->h3}}</td>
               <td align="center">{{$row->h4}}</td>
               <td align="center">{{$row->h5}}</td>
               <td align="center">{{$row->h6}}</td>
               <td align="center">{{$row->h7}}</td>
               <td align="center">{{$row->h8}}</td>
               <td align="center">{{$row->h9}}</td>
               <td align="center">{{$row->h10}}</td>
               <td align="center">{{$row->h11}}</td>
               <td align="center">{{$row->h12}}</td>
               <td align="center">{{$row->h13}}</td>
               <td align="center">{{$row->h14}}</td>
               <td align="center">{{$row->h15}}</td>
               <td align="center">{{$row->h16}}</td>
               <td align="center">{{$row->h17}}</td>
               <td align="center">{{$row->h18}}</td>
               <td align="center">{{$row->h19}}</td>
               <td align="center">{{$row->h20}}</td>
               <td align="center">{{$row->h21}}</td>
               <td align="center">{{$row->h22}}</td>
               <td align="center">{{$row->h23}}</td>
               <td align="center">{{$row->h24}}</td>
               <td align="center">{{$row->h25}}</td>
               <td align="center">{{$row->h26}}</td>
               <td align="center">{{$row->h27}}</td>
               <td align="center">{{$row->h28}}</td>
               <td align="center">{{$row->h29}}</td>
               <td align="center">{{$row->h30}}</td>
               <td align="center">{{$row->h31}}</td>
          </tr>
          <?php $no++; ?>
          @endforeach
     </tbody>
</table>
</div><hr>
   <div class="alert alert-info">   
      <small>
      <strong>KETERANGAN</strong>
      <ul>
         <li>H      = Datang tepat waktu dan presensi pulang</li>
         <li>T      = Telat dan Presensi Pulang</li>
         <li>]      = Datang tepat waktu dan tidak presensi pulang</li>
         <li>T]     = Telat dan tidak presensi pulang</li>
         <li>D      = Dinas</li>
         <li>C      = Cuti Tahunan</li>
         <li>S      = Sakit</li>
         <li>]I2    = Izin tidak presensi pulang</li>
         <li>I1     = Izin telat presensi datang </li>
      </ul>
      </small>
   </div>
</div>
</div>
</div>

@endsection