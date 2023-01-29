@extends('layouts.app-admin')
@section('content')
<h2 class="mt-2 mb-3">Rekap Presensi</h2>
<div class="row">
    <div class="col-xl-12">
        <div class="card mb-12">
            <div class="card-header"><i class="fas fa-calendar mr-1"></i> Data Presensi <strong>{{$date2}}</strong></div>
            <div class="card-body">
                <form action="{{url('/presensi-cari-bulan')}}" method="POST">
                    {!! csrf_field() !!}
                    <div class="row">
                        <label class="col-md-1 col-sm-1 col-xs-1 col-form-label">Bulan</label>
                        <div class="col-md-3 col-sm-3 col-xs-3">
                            <select name="bulan" class="form-select">
                                <option value="{{$data_bulan}}">{{$nm_bulan}}</option>
                              @for ($n=1; $n <= 12 ; $n++)
                              <option value="{{$n}}" >{{$namaBulan[$n]}}</option>
                              @endfor
                          </select>
                          @if ($errors->has('bulan'))
                          <span class="help-block">
                           <strong>{{ $errors->first('bulan') }}</strong>
                       </span>
                       @endif
                   </div>
                   <label class="col-md-1 col-sm-1 col-xs-1 col-form-label">Tahun</label>
                   <div class="col-md-3 col-sm-3 col-xs-3">
                      <select name="tahun" id="tahun" class="form-select">
                         @for ($n= $tahun ; $n >= $tahun-10 ; $n--)
                         @if($n==$tahun)
                         <option value="{{$n}}" selected>{{$n}}</option>
                         @else
                         <option value="{{$n}}" >{{$n}}</option>
                         @endif
                         @endfor
                     </select>
                 </div>
                 <div class="col-md-3 col-sm-3 col-xs-3">
                     <button type="submit" name="tampilkan" class="btn btn-primary">Tampilkan</button>
                 </div>
             </form>
         </div><br>
         <div class="table-responsive">
            <table id="dataTable">
              <thead>
                <tr>
                    <th class="text-center">No</th>
                    <th class="text-center">Tanggal</th>
                    <th class="text-center">Berangkat</th>
                    <th class="text-center">Pulang</th>
                    <th class="text-center">Ket. Kerja</th>
                    <th class="text-center">Ket. Presensi</th>
                </tr>
            </thead>
            <tbody>
                @if(!$datapresensi)
                @else
                <?php $no = 1; ?>
                @foreach( $datapresensi as $row)
                <tr class="danger red">
                    <td align="center">{{$no}}</td>
                    <td align="center">{{$row->tanggal}}</td>
                    <td align="center">{{$row->berangkat}}</td>
                    <td align="center">{{$row->pulang}}</td>
                    <td align="center">{{$row->keterangan_kerja}}</td>
                    @if($row->keterangan_presensi == '')
                    <td align="center" class="bg-success" style="color: white;">Sukses</td>
                    @else
                    <td align="center" class="bg-danger" style="color: white;">{{$row->keterangan_presensi}}</td>
                    @endif
                </tr>
                <?php $no++; ?>
                @endforeach
                @endif
            </tbody>
        </table>

    </div>
</div>
</div>
</div>
</div>
@endsection