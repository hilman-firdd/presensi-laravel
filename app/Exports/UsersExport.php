<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;

class UsersExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $tanggal = DB::select('select date_format(tanggal, "%d") as tanggal from tb_presensi where tanggal between "' . $dari . '" and "' . $sampai . '" group by tanggal');

        $detail = DB::select('select s.nik, s.nama,

        MAX(CASE

        WHEN date_format(b.tanggal, "%d") = "01" and b.cuti = "D" THEN "D"
        WHEN date_format(b.tanggal, "%d") = "01" and b.cuti = "I1" THEN "I1"
        WHEN date_format(b.tanggal, "%d") = "01" and b.cuti = "]I2" THEN "]I2"

        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Monday" and b.cuti = "4" and j.wf1 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Monday" and b.cuti = "1" and j.wf1 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "4" and j.wf2 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "1" and j.wf2 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "4" and j.wf3 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "1" and j.wf3 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "4" and j.wf4 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "1" and j.wf4 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Friday" and b.cuti = "4" and j.wf5 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Friday" and b.cuti = "1" and j.wf5 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "4" and j.wf6 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "1" and j.wf6 = "WFO" THEN "C" 
        
        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "4" and j.wf7 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "1" and j.wf7 = "WFO" THEN "C" 

        ELSE "" END ) as h1,

        MAX(CASE
        
        WHEN date_format(b.tanggal, "%d") = "02" and b.cuti = "D" THEN "D"
        WHEN date_format(b.tanggal, "%d") = "02" and b.cuti = "I1" THEN "I1"
        WHEN date_format(b.tanggal, "%d") = "02" and b.cuti = "]I2" THEN "]I2"

        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Monday" and b.cuti = "4" and j.wf1 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Monday" and b.cuti = "1" and j.wf1 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "4" and j.wf2 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "1" and j.wf2 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "4" and j.wf3 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "1" and j.wf3 = "WFO" THEN "C" 
        
        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "4" and j.wf4 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "1" and j.wf4 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Friday" and b.cuti = "4" and j.wf5 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Friday" and b.cuti = "1" and j.wf5 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "4" and j.wf6 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "1" and j.wf6 = "WFO" THEN "C"
        
        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "4" and j.wf7 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "1" and j.wf7 = "WFO" THEN "C"
    
        ELSE "" END ) as h2,

        MAX(CASE

        WHEN date_format(b.tanggal, "%d") = "03" and b.cuti = "D" THEN "D"
        WHEN date_format(b.tanggal, "%d") = "03" and b.cuti = "I1" THEN "I1"
        WHEN date_format(b.tanggal, "%d") = "03" and b.cuti = "]I2" THEN "]I2"

        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Monday" and b.cuti = "4" and j.wf1 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Monday" and b.cuti = "1" and j.wf1 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "4" and j.wf2 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "1" and j.wf2 = "WFO" THEN "C" 
    
        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "4" and j.wf3 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "1" and j.wf3 = "WFO" THEN "C" 
        
        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "4" and j.wf4 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "1" and j.wf4 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Friday" and b.cuti = "4" and j.wf5 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Friday" and b.cuti = "1" and j.wf5 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "4" and j.wf6 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "1" and j.wf6 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "4" and j.wf7 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "1" and j.wf7 = "WFO" THEN "C" 

        ELSE "" END ) as h3,

        MAX(CASE

        WHEN date_format(b.tanggal, "%d") = "04" and b.cuti = "D" THEN "D"
        WHEN date_format(b.tanggal, "%d") = "04" and b.cuti = "I1" THEN "I1"
        WHEN date_format(b.tanggal, "%d") = "04" and b.cuti = "]I2" THEN "]I2"

        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Monday" and b.cuti = "4" and j.wf1 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Monday" and b.cuti = "1" and j.wf1 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "4" and j.wf2 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "1" and j.wf2 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "4" and j.wf3 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "1" and j.wf3 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "4" and j.wf4 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "1" and j.wf4 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Friday" and b.cuti = "4" and j.wf5 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Friday" and b.cuti = "1" and j.wf5 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "4" and j.wf6 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "1" and j.wf6 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "4" and j.wf7 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "1" and j.wf7 = "WFO" THEN "C" 

        ELSE "" END ) as h4,

        MAX(CASE

        WHEN date_format(b.tanggal, "%d") = "05" and b.cuti = "D" THEN "D"
        WHEN date_format(b.tanggal, "%d") = "05" and b.cuti = "I1" THEN "I1"
        WHEN date_format(b.tanggal, "%d") = "05" and b.cuti = "]I2" THEN "]I2"

        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Monday" and b.cuti = "4" and j.wf1 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Monday" and b.cuti = "1" and j.wf1 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "4" and j.wf2 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "1" and j.wf2 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "4" and j.wf3 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "1" and j.wf3 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "4" and j.wf4 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "1" and j.wf4 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Friday" and b.cuti = "4" and j.wf5 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Friday" and b.cuti = "1" and j.wf5 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "4" and j.wf6 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "1" and j.wf6 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "4" and j.wf7 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "1" and j.wf7 = "WFO" THEN "C" 

        ELSE "" END ) as h5,

        MAX(CASE

        WHEN date_format(b.tanggal, "%d") = "06" and b.cuti = "D" THEN "D"
        WHEN date_format(b.tanggal, "%d") = "06" and b.cuti = "I1" THEN "I1"
        WHEN date_format(b.tanggal, "%d") = "06" and b.cuti = "]I2" THEN "]I2"

        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Monday" and b.cuti = "4" and j.wf1 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Monday" and b.cuti = "1" and j.wf1 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "4" and j.wf2 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "1" and j.wf2 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "4" and j.wf3 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "1" and j.wf3 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "4" and j.wf4 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "1" and j.wf4 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Friday" and b.cuti = "4" and j.wf5 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Friday" and b.cuti = "1" and j.wf5 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "4" and j.wf6 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "1" and j.wf6 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "4" and j.wf7 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "1" and j.wf7 = "WFO" THEN "C" 

        ELSE "" END ) as h6,

        MAX(CASE

        WHEN date_format(b.tanggal, "%d") = "07" and b.cuti = "D" THEN "D"
        WHEN date_format(b.tanggal, "%d") = "07" and b.cuti = "I1" THEN "I1"
        WHEN date_format(b.tanggal, "%d") = "07" and b.cuti = "]I2" THEN "]I2"

        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Monday" and b.cuti = "4" and j.wf1 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Monday" and b.cuti = "1" and j.wf1 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "4" and j.wf2 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "1" and j.wf2 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "4" and j.wf3 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "1" and j.wf3 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "4" and j.wf4 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "1" and j.wf4 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Friday" and b.cuti = "4" and j.wf5 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Friday" and b.cuti = "1" and j.wf5 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "4" and j.wf6 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "1" and j.wf6 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "4" and j.wf7 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "1" and j.wf7 = "WFO" THEN "C" 

        ELSE "" END ) as h7,

        MAX(CASE

        WHEN date_format(b.tanggal, "%d") = "08" and b.cuti = "D" THEN "D"
        WHEN date_format(b.tanggal, "%d") = "08" and b.cuti = "I1" THEN "I1"
        WHEN date_format(b.tanggal, "%d") = "08" and b.cuti = "]I2" THEN "]I2"

        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Monday" and b.cuti = "4" and j.wf1 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Monday" and b.cuti = "1" and j.wf1 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "4" and j.wf2 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "1" and j.wf2 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "4" and j.wf3 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "1" and j.wf3 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "4" and j.wf4 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "1" and j.wf4 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Friday" and b.cuti = "4" and j.wf5 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Friday" and b.cuti = "1" and j.wf5 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "4" and j.wf6 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "1" and j.wf6 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "4" and j.wf7 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "1" and j.wf7 = "WFO" THEN "C" 

        ELSE "" END ) as h8,

        MAX(CASE

        WHEN date_format(b.tanggal, "%d") = "09" and b.cuti = "D" THEN "D"
        WHEN date_format(b.tanggal, "%d") = "09" and b.cuti = "I1" THEN "I1"
        WHEN date_format(b.tanggal, "%d") = "09" and b.cuti = "]I2" THEN "]I2"

        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Monday" and b.cuti = "4" and j.wf1 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Monday" and b.cuti = "1" and j.wf1 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "4" and j.wf2 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "1" and j.wf2 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "4" and j.wf3 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "1" and j.wf3 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "4" and j.wf4 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "1" and j.wf4 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Friday" and b.cuti = "4" and j.wf5 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Friday" and b.cuti = "1" and j.wf5 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "4" and j.wf6 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "1" and j.wf6 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "4" and j.wf7 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "1" and j.wf7 = "WFO" THEN "C" 

        ELSE "" END ) as h9,

        MAX(CASE

        WHEN date_format(b.tanggal, "%d") = "10" and b.cuti = "D" THEN "D"
        WHEN date_format(b.tanggal, "%d") = "10" and b.cuti = "I1" THEN "I1"
        WHEN date_format(b.tanggal, "%d") = "10" and b.cuti = "]I2" THEN "]I2"

        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Monday" and b.cuti = "4" and j.wf1 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Monday" and b.cuti = "1" and j.wf1 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "4" and j.wf2 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "1" and j.wf2 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "4" and j.wf3 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "1" and j.wf3 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "4" and j.wf4 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "1" and j.wf4 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Friday" and b.cuti = "4" and j.wf5 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Friday" and b.cuti = "1" and j.wf5 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "4" and j.wf6 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "1" and j.wf6 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "4" and j.wf7 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "1" and j.wf7 = "WFO" THEN "C" 

        ELSE "" END ) as h10,

        MAX(CASE

        WHEN date_format(b.tanggal, "%d") = "11" and b.cuti = "D" THEN "D"
        WHEN date_format(b.tanggal, "%d") = "11" and b.cuti = "I1" THEN "I1"
        WHEN date_format(b.tanggal, "%d") = "11" and b.cuti = "]I2" THEN "]I2"

        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Monday" and b.cuti = "4" and j.wf1 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Monday" and b.cuti = "1" and j.wf1 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "4" and j.wf2 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "1" and j.wf2 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "4" and j.wf3 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "1" and j.wf3 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "4" and j.wf4 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "1" and j.wf4 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Friday" and b.cuti = "4" and j.wf5 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Friday" and b.cuti = "1" and j.wf5 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "4" and j.wf6 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "1" and j.wf6 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "4" and j.wf7 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "1" and j.wf7 = "WFO" THEN "C" 

        ELSE "" END ) as h11,

        MAX(CASE

        WHEN date_format(b.tanggal, "%d") = "12" and b.cuti = "D" THEN "D"
        WHEN date_format(b.tanggal, "%d") = "12" and b.cuti = "I1" THEN "I1"
        WHEN date_format(b.tanggal, "%d") = "12" and b.cuti = "]I2" THEN "]I2"

        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Monday" and  j.masuk_senin < b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Monday" and b.cuti = "4" and j.wf1 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Monday" and b.cuti = "1" and j.wf1 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "4" and j.wf2 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "1" and j.wf2 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "4" and j.wf3 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "1" and j.wf3 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "4" and j.wf4 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "1" and j.wf4 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Friday" and b.cuti = "4" and j.wf5 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Friday" and b.cuti = "1" and j.wf5 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "4" and j.wf6 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "1" and j.wf6 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "4" and j.wf7 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "1" and j.wf7 = "WFO" THEN "C" 

        ELSE "" END ) as h12,

        MAX(CASE

        WHEN date_format(b.tanggal, "%d") = "13" and b.cuti = "D" THEN "D"
        WHEN date_format(b.tanggal, "%d") = "13" and b.cuti = "I1" THEN "I1"
        WHEN date_format(b.tanggal, "%d") = "13" and b.cuti = "]I2" THEN "]I2"

        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Monday" and b.cuti = "4" and j.wf1 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Monday" and b.cuti = "1" and j.wf1 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "4" and j.wf2 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "1" and j.wf2 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "4" and j.wf3 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "1" and j.wf3 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "4" and j.wf4 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "1" and j.wf4 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Friday" and b.cuti = "4" and j.wf5 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Friday" and b.cuti = "1" and j.wf5 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Saturday" and  j.masuk_sabtu < b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "4" and j.wf6 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "1" and j.wf6 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "4" and j.wf7 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "1" and j.wf7 = "WFO" THEN "C"

        ELSE "" END ) as h13,

        MAX(CASE

        WHEN date_format(b.tanggal, "%d") = "14" and b.cuti = "D" THEN "D"
        WHEN date_format(b.tanggal, "%d") = "14" and b.cuti = "I1" THEN "I1"
        WHEN date_format(b.tanggal, "%d") = "14" and b.cuti = "]I2" THEN "]I2"

        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Monday" and b.cuti = "4" and j.wf1 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Monday" and b.cuti = "1" and j.wf1 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "4" and j.wf2 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "1" and j.wf2 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "4" and j.wf3 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "1" and j.wf3 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "4" and j.wf4 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "1" and j.wf4 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Friday" and b.cuti = "4" and j.wf5 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Friday" and b.cuti = "1" and j.wf5 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "4" and j.wf6 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "1" and j.wf6 = "WFO" THEN "C"
        
        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "4" and j.wf7 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "1" and j.wf7 = "WFO" THEN "C"

        ELSE "" END ) as h14,

        MAX(CASE

        WHEN date_format(b.tanggal, "%d") = "15" and b.cuti = "D" THEN "D"
        WHEN date_format(b.tanggal, "%d") = "15" and b.cuti = "I1" THEN "I1"
        WHEN date_format(b.tanggal, "%d") = "15" and b.cuti = "]I2" THEN "]I2"

        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Monday" and b.cuti = "4" and j.wf1 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Monday" and b.cuti = "1" and j.wf1 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "4" and j.wf2 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "1" and j.wf2 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "4" and j.wf3 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "1" and j.wf3 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "4" and j.wf4 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "1" and j.wf4 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Friday" and b.cuti = "4" and j.wf5 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Friday" and b.cuti = "1" and j.wf5 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "4" and j.wf6 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "1" and j.wf6 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "4" and j.wf7 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "1" and j.wf7 = "WFO" THEN "C"

        ELSE "" END ) as h15,

        MAX(CASE

        WHEN date_format(b.tanggal, "%d") = "16" and b.cuti = "D" THEN "D"
        WHEN date_format(b.tanggal, "%d") = "16" and b.cuti = "I1" THEN "I1"
        WHEN date_format(b.tanggal, "%d") = "16" and b.cuti = "]I2" THEN "]I2"

        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Monday" and b.cuti = "4" and j.wf1 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Monday" and b.cuti = "1" and j.wf1 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "4" and j.wf2 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "1" and j.wf2 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "4" and j.wf3 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "1" and j.wf3 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "4" and j.wf4 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "1" and j.wf4 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Friday" and b.cuti = "4" and j.wf5 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Friday" and b.cuti = "1" and j.wf5 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "4" and j.wf6 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "1" and j.wf6 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "4" and j.wf7 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "1" and j.wf7 = "WFO" THEN "C"

        ELSE "" END ) as h16,

        MAX(CASE

        WHEN date_format(b.tanggal, "%d") = "17" and b.cuti = "D" THEN "D"
        WHEN date_format(b.tanggal, "%d") = "17" and b.cuti = "I1" THEN "I1"
        WHEN date_format(b.tanggal, "%d") = "17" and b.cuti = "]I2" THEN "]I2"

        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Monday" and b.cuti = "4" and j.wf1 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Monday" and b.cuti = "1" and j.wf1 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "4" and j.wf2 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "1" and j.wf2 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "4" and j.wf3 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "1" and j.wf3 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "4" and j.wf4 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "1" and j.wf4 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Friday" and b.cuti = "4" and j.wf5 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Friday" and b.cuti = "1" and j.wf5 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "4" and j.wf6 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "1" and j.wf6 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "4" and j.wf7 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "1" and j.wf7 = "WFO" THEN "C"

        ELSE "" END ) as h17,

        MAX(CASE

        WHEN date_format(b.tanggal, "%d") = "18" and b.cuti = "D" THEN "D"
        WHEN date_format(b.tanggal, "%d") = "18" and b.cuti = "I1" THEN "I1"
        WHEN date_format(b.tanggal, "%d") = "18" and b.cuti = "]I2" THEN "]I2"

        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Monday" and b.cuti = "4" and j.wf1 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Monday" and b.cuti = "1" and j.wf1 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "4" and j.wf2 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "1" and j.wf2 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "4" and j.wf3 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "1" and j.wf3 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "4" and j.wf4 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "1" and j.wf4 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Friday" and b.cuti = "4" and j.wf5 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Friday" and b.cuti = "1" and j.wf5 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "4" and j.wf6 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "1" and j.wf6 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "4" and j.wf7 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "1" and j.wf7 = "WFO" THEN "C"

        ELSE "" END ) as h18,

        MAX(CASE

        WHEN date_format(b.tanggal, "%d") = "19" and b.cuti = "D" THEN "D"
        WHEN date_format(b.tanggal, "%d") = "19" and b.cuti = "I1" THEN "I1"
        WHEN date_format(b.tanggal, "%d") = "19" and b.cuti = "]I2" THEN "]I2"

        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Monday" and b.cuti = "4" and j.wf1 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Monday" and b.cuti = "1" and j.wf1 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "4" and j.wf2 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "1" and j.wf2 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "4" and j.wf3 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "1" and j.wf3 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "4" and j.wf4 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "1" and j.wf4 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Friday" and b.cuti = "4" and j.wf5 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Friday" and b.cuti = "1" and j.wf5 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "4" and j.wf6 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "1" and j.wf6 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "4" and j.wf7 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "1" and j.wf7 = "WFO" THEN "C"

        ELSE "" END ) as h19,

        MAX(CASE

        WHEN date_format(b.tanggal, "%d") = "20" and b.cuti = "D" THEN "D"
        WHEN date_format(b.tanggal, "%d") = "20" and b.cuti = "I1" THEN "I1"
        WHEN date_format(b.tanggal, "%d") = "20" and b.cuti = "]I2" THEN "]I2"

        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Monday" and b.cuti = "4" and j.wf1 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Monday" and b.cuti = "1" and j.wf1 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "4" and j.wf2 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "1" and j.wf2 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "4" and j.wf3 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "1" and j.wf3 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "4" and j.wf4 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "1" and j.wf4 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Friday" and b.cuti = "4" and j.wf5 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Friday" and b.cuti = "1" and j.wf5 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "4" and j.wf6 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "1" and j.wf6 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "4" and j.wf7 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "1" and j.wf7 = "WFO" THEN "C"

        ELSE "" END ) as h20,

        MAX(CASE

        WHEN date_format(b.tanggal, "%d") = "21" and b.cuti = "D" THEN "D"
        WHEN date_format(b.tanggal, "%d") = "21" and b.cuti = "I1" THEN "I1"
        WHEN date_format(b.tanggal, "%d") = "21" and b.cuti = "]I2" THEN "]I2"

        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Monday" and b.cuti = "4" and j.wf1 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Monday" and b.cuti = "1" and j.wf1 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "4" and j.wf2 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "1" and j.wf2 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "4" and j.wf3 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "1" and j.wf3 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "4" and j.wf4 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "1" and j.wf4 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Friday" and b.cuti = "4" and j.wf5 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Friday" and b.cuti = "1" and j.wf5 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "4" and j.wf6 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "1" and j.wf6 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "4" and j.wf7 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "1" and j.wf7 = "WFO" THEN "C"

        ELSE "" END ) as h21,

        MAX(CASE

        WHEN date_format(b.tanggal, "%d") = "22" and b.cuti = "D" THEN "D"
        WHEN date_format(b.tanggal, "%d") = "22" and b.cuti = "I1" THEN "I1"
        WHEN date_format(b.tanggal, "%d") = "22" and b.cuti = "]I2" THEN "]I2"

        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Monday" and b.cuti = "4" and j.wf1 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Monday" and b.cuti = "1" and j.wf1 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "4" and j.wf2 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "1" and j.wf2 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "4" and j.wf3 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "1" and j.wf3 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "4" and j.wf4 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "1" and j.wf4 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Friday" and b.cuti = "4" and j.wf5 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Friday" and b.cuti = "1" and j.wf5 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "4" and j.wf6 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "1" and j.wf6 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "4" and j.wf7 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "1" and j.wf7 = "WFO" THEN "C"

        ELSE "" END ) as h22,

        MAX(CASE

        WHEN date_format(b.tanggal, "%d") = "23" and b.cuti = "D" THEN "D"
        WHEN date_format(b.tanggal, "%d") = "23" and b.cuti = "I1" THEN "I1"
        WHEN date_format(b.tanggal, "%d") = "23" and b.cuti = "]I2" THEN "]I2"

        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Monday" and b.cuti = "4" and j.wf1 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Monday" and b.cuti = "1" and j.wf1 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "4" and j.wf2 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "1" and j.wf2 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "4" and j.wf3 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "1" and j.wf3 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "4" and j.wf4 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "1" and j.wf4 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Friday" and  j.masuk_jumat < b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Friday" and b.cuti = "4" and j.wf5 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Friday" and b.cuti = "1" and j.wf5 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "4" and j.wf6 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "1" and j.wf6 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "4" and j.wf7 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "1" and j.wf7 = "WFO" THEN "C"

        ELSE "" END ) as h23,

        MAX(CASE

        WHEN date_format(b.tanggal, "%d") = "24" and b.cuti = "D" THEN "D"
        WHEN date_format(b.tanggal, "%d") = "24" and b.cuti = "I1" THEN "I1"
        WHEN date_format(b.tanggal, "%d") = "24" and b.cuti = "]I2" THEN "]I2"

        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Monday" and b.cuti = "4" and j.wf1 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Monday" and b.cuti = "1" and j.wf1 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "4" and j.wf2 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "1" and j.wf2 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "4" and j.wf3 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "1" and j.wf3 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "4" and j.wf4 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "1" and j.wf4 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Friday" and b.cuti = "4" and j.wf5 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Friday" and b.cuti = "1" and j.wf5 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "4" and j.wf6 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "1" and j.wf6 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "4" and j.wf7 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "1" and j.wf7 = "WFO" THEN "C"

        ELSE "" END ) as h24,

        MAX(CASE

        WHEN date_format(b.tanggal, "%d") = "25" and b.cuti = "D" THEN "D"
        WHEN date_format(b.tanggal, "%d") = "25" and b.cuti = "I1" THEN "I1"
        WHEN date_format(b.tanggal, "%d") = "25" and b.cuti = "]I2" THEN "]I2"

        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Monday" and b.cuti = "4" and j.wf1 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Monday" and b.cuti = "1" and j.wf1 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "4" and j.wf2 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "1" and j.wf2 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "4" and j.wf3 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "1" and j.wf3 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "4" and j.wf4 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "1" and j.wf4 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Friday" and b.cuti = "4" and j.wf5 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Friday" and b.cuti = "1" and j.wf5 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "4" and j.wf6 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "1" and j.wf6 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "4" and j.wf7 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "1" and j.wf7 = "WFO" THEN "C"

        ELSE "" END ) as h25,

        MAX(CASE

        WHEN date_format(b.tanggal, "%d") = "26" and b.cuti = "D" THEN "D"
        WHEN date_format(b.tanggal, "%d") = "26" and b.cuti = "I1" THEN "I1"
        WHEN date_format(b.tanggal, "%d") = "26" and b.cuti = "]I2" THEN "]I2"

        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Monday" and b.cuti = "4" and j.wf1 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Monday" and b.cuti = "1" and j.wf1 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "4" and j.wf2 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "1" and j.wf2 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "4" and j.wf3 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "1" and j.wf3 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "4" and j.wf4 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "1" and j.wf4 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Friday" and b.cuti = "4" and j.wf5 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Friday" and b.cuti = "1" and j.wf5 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "4" and j.wf6 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "1" and j.wf6 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "4" and j.wf7 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "1" and j.wf7 = "WFO" THEN "C"

        ELSE "" END ) as h26,

        MAX(CASE

        WHEN date_format(b.tanggal, "%d") = "27" and b.cuti = "D" THEN "D"
        WHEN date_format(b.tanggal, "%d") = "27" and b.cuti = "I1" THEN "I1"
        WHEN date_format(b.tanggal, "%d") = "27" and b.cuti = "]I2" THEN "]I2"

        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Monday" and b.cuti = "4" and j.wf1 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Monday" and b.cuti = "1" and j.wf1 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "4" and j.wf2 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "1" and j.wf2 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "4" and j.wf3 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "1" and j.wf3 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "4" and j.wf4 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "1" and j.wf4 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Friday" and b.cuti = "4" and j.wf5 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Friday" and b.cuti = "1" and j.wf5 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "4" and j.wf6 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "1" and j.wf6 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "4" and j.wf7 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "1" and j.wf7 = "WFO" THEN "C"

        ELSE "" END ) as h27,

        MAX(CASE

        WHEN date_format(b.tanggal, "%d") = "28" and b.cuti = "D" THEN "D"
        WHEN date_format(b.tanggal, "%d") = "28" and b.cuti = "I1" THEN "I1"
        WHEN date_format(b.tanggal, "%d") = "28" and b.cuti = "]I2" THEN "]I2"

        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Monday" and b.cuti = "4" and j.wf1 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Monday" and b.cuti = "1" and j.wf1 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "4" and j.wf2 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "1" and j.wf2 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "4" and j.wf3 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "1" and j.wf3 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "4" and j.wf4 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "1" and j.wf4 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Friday" and b.cuti = "4" and j.wf5 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Friday" and b.cuti = "1" and j.wf5 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "4" and j.wf6 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "1" and j.wf6 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "4" and j.wf7 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "1" and j.wf7 = "WFO" THEN "C"

        ELSE "" END ) as h28,

        MAX(CASE

        WHEN date_format(b.tanggal, "%d") = "29" and b.cuti = "D" THEN "D"
        WHEN date_format(b.tanggal, "%d") = "29" and b.cuti = "I1" THEN "I1"
        WHEN date_format(b.tanggal, "%d") = "29" and b.cuti = "]I2" THEN "]I2"

        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Monday" and b.cuti = "4" and j.wf1 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Monday" and b.cuti = "1" and j.wf1 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "4" and j.wf2 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "1" and j.wf2 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "4" and j.wf3 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "1" and j.wf3 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "4" and j.wf4 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "1" and j.wf4 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Friday" and b.cuti = "4" and j.wf5 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Friday" and b.cuti = "1" and j.wf5 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "4" and j.wf6 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "1" and j.wf6 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "4" and j.wf7 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "1" and j.wf7 = "WFO" THEN "C"

        ELSE "" END ) as h29,

        MAX(CASE

        WHEN date_format(b.tanggal, "%d") = "30" and b.cuti = "D" THEN "D"
        WHEN date_format(b.tanggal, "%d") = "30" and b.cuti = "I1" THEN "I1"
        WHEN date_format(b.tanggal, "%d") = "30" and b.cuti = "]I2" THEN "]I2"

        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Monday" and b.cuti = "4" and j.wf1 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Monday" and b.cuti = "1" and j.wf1 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "4" and j.wf2 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "1" and j.wf2 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "4" and j.wf3 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "1" and j.wf3 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "4" and j.wf4 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "1" and j.wf4 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Friday" and b.cuti = "4" and j.wf5 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Friday" and b.cuti = "1" and j.wf5 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "4" and j.wf6 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "1" and j.wf6 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "4" and j.wf7 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "1" and j.wf7 = "WFO" THEN "C"

        ELSE "" END ) as h30,

        MAX(CASE

        WHEN date_format(b.tanggal, "%d") = "31" and b.cuti = "D" THEN "D"
        WHEN date_format(b.tanggal, "%d") = "31" and b.cuti = "I1" THEN "I1"
        WHEN date_format(b.tanggal, "%d") = "31" and b.cuti = "]I2" THEN "]I2"

        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Monday" and b.cuti = "4" and j.wf1 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Monday" and b.cuti = "1" and j.wf1 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "4" and j.wf2 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "1" and j.wf2 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "4" and j.wf3 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "1" and j.wf3 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "4" and j.wf4 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "1" and j.wf4 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Friday" and b.cuti = "4" and j.wf5 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Friday" and b.cuti = "1" and j.wf5 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "4" and j.wf6 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "1" and j.wf6 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "4" and j.wf7 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "1" and j.wf7 = "WFO" THEN "C"

        ELSE "" END ) as h31

        from users s
        LEFT join tb_presensi b on s.id = b.id_user
        LEFT join tb_jammasuk j on s.id = j.id_user
        where b.tanggal between "' . $dari . '" and "' . $sampai . '" and s.cluster = "' . $request->cluster . '"
        group by s.id, b.id_user
        order by s.nama ASC');
        $cluster = $request->cluster;
        return view('admin.laporan.lihat-laporan', compact('detail', 'dari', 'sampai', 'data', 'tanggal', 'tanggal1', 'dari1', 'sampai1', 'cluster'));
    }

    public function downloadpresensi(Request $request, $type)
    {
        error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
        $dari1 = $request->dari;
        $sampai1 = $request->sampai;
        $dari  = date('Y-m-d', strtotime($request['dari']));
        $sampai  = date('Y-m-d', strtotime('1 DAY', strtotime($_GET['sampai'])));

        $tanggal1 = $request['dari'];

        $gettanggal = strtotime($sampai) -  strtotime($dari);

        $tanggal = DB::select('select date_format(tanggal, "%d") as tanggal from tb_presensi where tanggal between "' . $dari . '" and "' . $sampai . '" group by tanggal');

        $data = DB::select('select s.nik, s.nama,

        MAX(CASE

        WHEN date_format(b.tanggal, "%d") = "01" and b.cuti = "D" THEN "D"
        WHEN date_format(b.tanggal, "%d") = "01" and b.cuti = "I1" THEN "I1"
        WHEN date_format(b.tanggal, "%d") = "01" and b.cuti = "]I2" THEN "]I2"

        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Monday" and b.cuti = "4" and j.wf1 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Monday" and b.cuti = "1" and j.wf1 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "4" and j.wf2 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "1" and j.wf2 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "4" and j.wf3 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "1" and j.wf3 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "4" and j.wf4 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "1" and j.wf4 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Friday" and b.cuti = "4" and j.wf5 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Friday" and b.cuti = "1" and j.wf5 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "4" and j.wf6 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "1" and j.wf6 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "4" and j.wf7 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "1" and j.wf7 = "WFO" THEN "C" 

        ELSE "" END ) as h1,

        MAX(CASE

        WHEN date_format(b.tanggal, "%d") = "02" and b.cuti = "D" THEN "D"
        WHEN date_format(b.tanggal, "%d") = "02" and b.cuti = "I1" THEN "I1"
        WHEN date_format(b.tanggal, "%d") = "02" and b.cuti = "]I2" THEN "]I2"

        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Monday" and b.cuti = "4" and j.wf1 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Monday" and b.cuti = "1" and j.wf1 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "4" and j.wf2 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "1" and j.wf2 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "4" and j.wf3 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "1" and j.wf3 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "4" and j.wf4 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "1" and j.wf4 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Friday" and b.cuti = "4" and j.wf5 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Friday" and b.cuti = "1" and j.wf5 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "4" and j.wf6 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "1" and j.wf6 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "4" and j.wf7 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "1" and j.wf7 = "WFO" THEN "C" 

        ELSE "" END ) as h2,

        MAX(CASE

        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Monday" and b.cuti = "4" and j.wf1 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Monday" and b.cuti = "1" and j.wf1 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "4" and j.wf2 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "1" and j.wf2 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "4" and j.wf3 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "1" and j.wf3 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "4" and j.wf4 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "1" and j.wf4 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Friday" and b.cuti = "4" and j.wf5 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Friday" and b.cuti = "1" and j.wf5 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "4" and j.wf6 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "1" and j.wf6 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "4" and j.wf7 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "1" and j.wf7 = "WFO" THEN "C" 

        ELSE "" END ) as h3,

        MAX(CASE

        WHEN date_format(b.tanggal, "%d") = "04" and b.cuti = "D" THEN "D"
        WHEN date_format(b.tanggal, "%d") = "04" and b.cuti = "I1" THEN "I1"
        WHEN date_format(b.tanggal, "%d") = "04" and b.cuti = "]I2" THEN "]I2"

        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Monday" and b.cuti = "4" and j.wf1 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Monday" and b.cuti = "1" and j.wf1 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "4" and j.wf2 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "1" and j.wf2 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "4" and j.wf3 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "1" and j.wf3 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "4" and j.wf4 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "1" and j.wf4 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Friday" and b.cuti = "4" and j.wf5 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Friday" and b.cuti = "1" and j.wf5 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "4" and j.wf6 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "1" and j.wf6 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "4" and j.wf7 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "1" and j.wf7 = "WFO" THEN "C" 

        ELSE "" END ) as h4,

        MAX(CASE

        WHEN date_format(b.tanggal, "%d") = "05" and b.cuti = "D" THEN "D"
        WHEN date_format(b.tanggal, "%d") = "05" and b.cuti = "I1" THEN "I1"
        WHEN date_format(b.tanggal, "%d") = "05" and b.cuti = "]I2" THEN "]I2"

        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Monday" and b.cuti = "4" and j.wf1 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Monday" and b.cuti = "1" and j.wf1 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "4" and j.wf2 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "1" and j.wf2 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "4" and j.wf3 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "1" and j.wf3 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "4" and j.wf4 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "1" and j.wf4 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Friday" and b.cuti = "4" and j.wf5 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Friday" and b.cuti = "1" and j.wf5 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "4" and j.wf6 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "1" and j.wf6 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "4" and j.wf7 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "1" and j.wf7 = "WFO" THEN "C" 

        ELSE "" END ) as h5,

        MAX(CASE

        WHEN date_format(b.tanggal, "%d") = "06" and b.cuti = "D" THEN "D"
        WHEN date_format(b.tanggal, "%d") = "06" and b.cuti = "I1" THEN "I1"
        WHEN date_format(b.tanggal, "%d") = "06" and b.cuti = "]I2" THEN "]I2"

        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Monday" and b.cuti = "4" and j.wf1 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Monday" and b.cuti = "1" and j.wf1 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "4" and j.wf2 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "1" and j.wf2 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "4" and j.wf3 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "1" and j.wf3 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "4" and j.wf4 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "1" and j.wf4 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Friday" and b.cuti = "4" and j.wf5 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Friday" and b.cuti = "1" and j.wf5 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "4" and j.wf6 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "1" and j.wf6 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "4" and j.wf7 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "1" and j.wf7 = "WFO" THEN "C" 

        ELSE "" END ) as h6,

        MAX(CASE

        WHEN date_format(b.tanggal, "%d") = "07" and b.cuti = "D" THEN "D"
        WHEN date_format(b.tanggal, "%d") = "07" and b.cuti = "I1" THEN "I1"
        WHEN date_format(b.tanggal, "%d") = "07" and b.cuti = "]I2" THEN "]I2"

        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Monday" and b.cuti = "4" and j.wf1 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Monday" and b.cuti = "1" and j.wf1 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "4" and j.wf2 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "1" and j.wf2 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "4" and j.wf3 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "1" and j.wf3 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "4" and j.wf4 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "1" and j.wf4 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Friday" and b.cuti = "4" and j.wf5 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Friday" and b.cuti = "1" and j.wf5 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "4" and j.wf6 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "1" and j.wf6 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "4" and j.wf7 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "1" and j.wf7 = "WFO" THEN "C" 

        ELSE "" END ) as h7,

        MAX(CASE

        WHEN date_format(b.tanggal, "%d") = "08" and b.cuti = "D" THEN "D"
        WHEN date_format(b.tanggal, "%d") = "08" and b.cuti = "I1" THEN "I1"
        WHEN date_format(b.tanggal, "%d") = "08" and b.cuti = "]I2" THEN "]I2"

        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Monday" and b.cuti = "4" and j.wf1 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Monday" and b.cuti = "1" and j.wf1 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "4" and j.wf2 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "1" and j.wf2 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "4" and j.wf3 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "1" and j.wf3 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "4" and j.wf4 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "1" and j.wf4 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Friday" and b.cuti = "4" and j.wf5 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Friday" and b.cuti = "1" and j.wf5 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "4" and j.wf6 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "1" and j.wf6 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "4" and j.wf7 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "1" and j.wf7 = "WFO" THEN "C" 

        ELSE "" END ) as h8,

        MAX(CASE

        WHEN date_format(b.tanggal, "%d") = "09" and b.cuti = "D" THEN "D"
        WHEN date_format(b.tanggal, "%d") = "09" and b.cuti = "I1" THEN "I1"
        WHEN date_format(b.tanggal, "%d") = "09" and b.cuti = "]I2" THEN "]I2"

        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Monday" and b.cuti = "4" and j.wf1 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Monday" and b.cuti = "1" and j.wf1 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "4" and j.wf2 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "1" and j.wf2 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "4" and j.wf3 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "1" and j.wf3 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "4" and j.wf4 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "1" and j.wf4 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Friday" and b.cuti = "4" and j.wf5 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Friday" and b.cuti = "1" and j.wf5 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "4" and j.wf6 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "1" and j.wf6 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "4" and j.wf7 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "1" and j.wf7 = "WFO" THEN "C" 

        ELSE "" END ) as h9,

        MAX(CASE

        WHEN date_format(b.tanggal, "%d") = "10" and b.cuti = "D" THEN "D"
        WHEN date_format(b.tanggal, "%d") = "10" and b.cuti = "I1" THEN "I1"
        WHEN date_format(b.tanggal, "%d") = "10" and b.cuti = "]I2" THEN "]I2"

        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Monday" and b.cuti = "4" and j.wf1 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Monday" and b.cuti = "1" and j.wf1 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "4" and j.wf2 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "1" and j.wf2 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "4" and j.wf3 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "1" and j.wf3 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "4" and j.wf4 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "1" and j.wf4 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Friday" and b.cuti = "4" and j.wf5 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Friday" and b.cuti = "1" and j.wf5 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "4" and j.wf6 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "1" and j.wf6 = "WFO" THEN "C"
        
        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "4" and j.wf7 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "1" and j.wf7 = "WFO" THEN "C" 

        ELSE "" END ) as h10,

        MAX(CASE

        WHEN date_format(b.tanggal, "%d") = "11" and b.cuti = "D" THEN "D"
        WHEN date_format(b.tanggal, "%d") = "11" and b.cuti = "I1" THEN "I1"
        WHEN date_format(b.tanggal, "%d") = "11" and b.cuti = "]I2" THEN "]I2"

        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Monday" and b.cuti = "4" and j.wf1 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Monday" and b.cuti = "1" and j.wf1 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "4" and j.wf2 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "1" and j.wf2 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "4" and j.wf3 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "1" and j.wf3 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "4" and j.wf4 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "1" and j.wf4 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Friday" and b.cuti = "4" and j.wf5 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Friday" and b.cuti = "1" and j.wf5 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "4" and j.wf6 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "1" and j.wf6 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "4" and j.wf7 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "1" and j.wf7 = "WFO" THEN "C" 

        ELSE "" END ) as h11,

        MAX(CASE

        WHEN date_format(b.tanggal, "%d") = "12" and b.cuti = "D" THEN "D"
        WHEN date_format(b.tanggal, "%d") = "12" and b.cuti = "I1" THEN "I1"
        WHEN date_format(b.tanggal, "%d") = "12" and b.cuti = "]I2" THEN "]I2"

        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Monday" and b.cuti = "4" and j.wf1 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Monday" and b.cuti = "1" and j.wf1 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "4" and j.wf2 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "1" and j.wf2 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "4" and j.wf3 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "1" and j.wf3 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "4" and j.wf4 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "1" and j.wf4 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Friday" and b.cuti = "4" and j.wf5 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Friday" and b.cuti = "1" and j.wf5 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "4" and j.wf6 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "1" and j.wf6 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "4" and j.wf7 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "1" and j.wf7 = "WFO" THEN "C" 

        ELSE "" END ) as h12,

        MAX(CASE

        WHEN date_format(b.tanggal, "%d") = "13" and b.cuti = "D" THEN "D"
        WHEN date_format(b.tanggal, "%d") = "13" and b.cuti = "I1" THEN "I1"
        WHEN date_format(b.tanggal, "%d") = "13" and b.cuti = "]I2" THEN "]I2"

        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Monday" and b.cuti = "4" and j.wf1 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Monday" and b.cuti = "1" and j.wf1 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "4" and j.wf2 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "1" and j.wf2 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "4" and j.wf3 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "1" and j.wf3 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "4" and j.wf4 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "1" and j.wf4 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Friday" and b.cuti = "4" and j.wf5 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Friday" and b.cuti = "1" and j.wf5 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "4" and j.wf6 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "1" and j.wf6 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "4" and j.wf7 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "1" and j.wf7 = "WFO" THEN "C" 

        ELSE "" END ) as h13,

        MAX(CASE

        WHEN date_format(b.tanggal, "%d") = "14" and b.cuti = "D" THEN "D"
        WHEN date_format(b.tanggal, "%d") = "14" and b.cuti = "I1" THEN "I1"
        WHEN date_format(b.tanggal, "%d") = "14" and b.cuti = "]I2" THEN "]I2"

        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Monday" and b.cuti = "4" and j.wf1 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Monday" and b.cuti = "1" and j.wf1 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "4" and j.wf2 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "1" and j.wf2 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "4" and j.wf3 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "1" and j.wf3 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "4" and j.wf4 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "1" and j.wf4 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Friday" and b.cuti = "4" and j.wf5 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Friday" and b.cuti = "1" and j.wf5 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "4" and j.wf6 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "1" and j.wf6 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "4" and j.wf7 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "1" and j.wf7 = "WFO" THEN "C" 

        ELSE "" END ) as h14,

        MAX(CASE

        WHEN date_format(b.tanggal, "%d") = "15" and b.cuti = "D" THEN "D"
        WHEN date_format(b.tanggal, "%d") = "15" and b.cuti = "I1" THEN "I1"
        WHEN date_format(b.tanggal, "%d") = "15" and b.cuti = "]I2" THEN "]I2"

        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Monday" and b.cuti = "4" and j.wf1 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Monday" and b.cuti = "1" and j.wf1 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "4" and j.wf2 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "1" and j.wf2 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "4" and j.wf3 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "1" and j.wf3 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "4" and j.wf4 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "1" and j.wf4 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Friday" and b.cuti = "4" and j.wf5 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Friday" and b.cuti = "1" and j.wf5 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "4" and j.wf6 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "1" and j.wf6 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "4" and j.wf7 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "1" and j.wf7 = "WFO" THEN "C" 

        ELSE "" END ) as h15,

        MAX(CASE

        WHEN date_format(b.tanggal, "%d") = "16" and b.cuti = "D" THEN "D"
        WHEN date_format(b.tanggal, "%d") = "16" and b.cuti = "I1" THEN "I1"
        WHEN date_format(b.tanggal, "%d") = "16" and b.cuti = "]I2" THEN "]I2"

        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Monday" and b.cuti = "4" and j.wf1 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Monday" and b.cuti = "1" and j.wf1 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "4" and j.wf2 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "1" and j.wf2 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "4" and j.wf3 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "1" and j.wf3 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "4" and j.wf4 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "1" and j.wf4 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Friday" and b.cuti = "4" and j.wf5 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Friday" and b.cuti = "1" and j.wf5 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "4" and j.wf6 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "1" and j.wf6 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "4" and j.wf7 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "1" and j.wf7 = "WFO" THEN "C" 

        ELSE "" END ) as h16,

        MAX(CASE

        WHEN date_format(b.tanggal, "%d") = "17" and b.cuti = "D" THEN "D"
        WHEN date_format(b.tanggal, "%d") = "17" and b.cuti = "I1" THEN "I1"
        WHEN date_format(b.tanggal, "%d") = "17" and b.cuti = "]I2" THEN "]I2"

        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Monday" and b.cuti = "4" and j.wf1 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Monday" and b.cuti = "1" and j.wf1 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "4" and j.wf2 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "1" and j.wf2 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "4" and j.wf3 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "1" and j.wf3 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "4" and j.wf4 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "1" and j.wf4 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Friday" and b.cuti = "4" and j.wf5 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Friday" and b.cuti = "1" and j.wf5 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "4" and j.wf6 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "1" and j.wf6 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "4" and j.wf7 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "1" and j.wf7 = "WFO" THEN "C" 

        ELSE "" END ) as h17,

        MAX(CASE

        WHEN date_format(b.tanggal, "%d") = "18" and b.cuti = "D" THEN "D"
        WHEN date_format(b.tanggal, "%d") = "18" and b.cuti = "I1" THEN "I1"
        WHEN date_format(b.tanggal, "%d") = "18" and b.cuti = "]I2" THEN "]I2"

        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Monday" and b.cuti = "4" and j.wf1 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Monday" and b.cuti = "1" and j.wf1 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "4" and j.wf2 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "1" and j.wf2 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "4" and j.wf3 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "1" and j.wf3 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "4" and j.wf4 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "1" and j.wf4 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Friday" and b.cuti = "4" and j.wf5 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Friday" and b.cuti = "1" and j.wf5 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "4" and j.wf6 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "1" and j.wf6 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "4" and j.wf7 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "1" and j.wf7 = "WFO" THEN "C" 

        ELSE "" END ) as h18,

        MAX(CASE

        WHEN date_format(b.tanggal, "%d") = "19" and b.cuti = "D" THEN "D"
        WHEN date_format(b.tanggal, "%d") = "19" and b.cuti = "I1" THEN "I1"
        WHEN date_format(b.tanggal, "%d") = "19" and b.cuti = "]I2" THEN "]I2"

        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Monday" and b.cuti = "4" and j.wf1 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Monday" and b.cuti = "1" and j.wf1 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "4" and j.wf2 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "1" and j.wf2 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "4" and j.wf3 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "1" and j.wf3 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "4" and j.wf4 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "1" and j.wf4 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Friday" and b.cuti = "4" and j.wf5 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Friday" and b.cuti = "1" and j.wf5 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "4" and j.wf6 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "1" and j.wf6 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "4" and j.wf7 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "1" and j.wf7 = "WFO" THEN "C" 

        ELSE "" END ) as h19,

        MAX(CASE

        WHEN date_format(b.tanggal, "%d") = "20" and b.cuti = "D" THEN "D"
        WHEN date_format(b.tanggal, "%d") = "20" and b.cuti = "I1" THEN "I1"
        WHEN date_format(b.tanggal, "%d") = "20" and b.cuti = "]I2" THEN "]I2"

        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Monday" and b.cuti = "4" and j.wf1 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Monday" and b.cuti = "1" and j.wf1 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "4" and j.wf2 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "1" and j.wf2 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "4" and j.wf3 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "1" and j.wf3 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "4" and j.wf4 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "1" and j.wf4 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Friday" and b.cuti = "4" and j.wf5 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Friday" and b.cuti = "1" and j.wf5 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "4" and j.wf6 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "1" and j.wf6 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "4" and j.wf7 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "1" and j.wf7 = "WFO" THEN "C" 

        ELSE "" END ) as h20,

        MAX(CASE

        WHEN date_format(b.tanggal, "%d") = "21" and b.cuti = "D" THEN "D"
        WHEN date_format(b.tanggal, "%d") = "21" and b.cuti = "I1" THEN "I1"
        WHEN date_format(b.tanggal, "%d") = "21" and b.cuti = "]I2" THEN "]I2"

        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Monday" and b.cuti = "4" and j.wf1 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Monday" and b.cuti = "1" and j.wf1 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "4" and j.wf2 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "1" and j.wf2 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "4" and j.wf3 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "1" and j.wf3 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "4" and j.wf4 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "1" and j.wf4 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Friday" and b.cuti = "4" and j.wf5 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Friday" and b.cuti = "1" and j.wf5 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "4" and j.wf6 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "1" and j.wf6 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "4" and j.wf7 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "1" and j.wf7 = "WFO" THEN "C" 

        ELSE "" END ) as h21,

        MAX(CASE

        WHEN date_format(b.tanggal, "%d") = "22" and b.cuti = "D" THEN "D"
        WHEN date_format(b.tanggal, "%d") = "22" and b.cuti = "I1" THEN "I1"
        WHEN date_format(b.tanggal, "%d") = "22" and b.cuti = "]I2" THEN "]I2"

        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Monday" and b.cuti = "4" and j.wf1 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Monday" and b.cuti = "1" and j.wf1 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "4" and j.wf2 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "1" and j.wf2 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "4" and j.wf3 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "1" and j.wf3 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "4" and j.wf4 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "1" and j.wf4 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Friday" and b.cuti = "4" and j.wf5 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Friday" and b.cuti = "1" and j.wf5 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "4" and j.wf6 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "1" and j.wf6 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "4" and j.wf7 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "1" and j.wf7 = "WFO" THEN "C" 

        ELSE "" END ) as h22,

        MAX(CASE

        WHEN date_format(b.tanggal, "%d") = "23" and b.cuti = "D" THEN "D"
        WHEN date_format(b.tanggal, "%d") = "23" and b.cuti = "I1" THEN "I1"
        WHEN date_format(b.tanggal, "%d") = "23" and b.cuti = "]I2" THEN "]I2"

        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Monday" and b.cuti = "4" and j.wf1 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Monday" and b.cuti = "1" and j.wf1 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "4" and j.wf2 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "1" and j.wf2 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "4" and j.wf3 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "1" and j.wf3 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "4" and j.wf4 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "1" and j.wf4 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Friday" and b.cuti = "4" and j.wf5 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Friday" and b.cuti = "1" and j.wf5 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "4" and j.wf6 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "1" and j.wf6 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "4" and j.wf7 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "1" and j.wf7 = "WFO" THEN "C" 

        ELSE "" END ) as h23,

        MAX(CASE

        WHEN date_format(b.tanggal, "%d") = "24" and b.cuti = "D" THEN "D"
        WHEN date_format(b.tanggal, "%d") = "24" and b.cuti = "I1" THEN "I1"
        WHEN date_format(b.tanggal, "%d") = "24" and b.cuti = "]I2" THEN "]I2"

        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Monday" and b.cuti = "4" and j.wf1 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Monday" and b.cuti = "1" and j.wf1 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "4" and j.wf2 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "1" and j.wf2 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "4" and j.wf3 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "1" and j.wf3 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "4" and j.wf4 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "1" and j.wf4 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Friday" and b.cuti = "4" and j.wf5 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Friday" and b.cuti = "1" and j.wf5 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "4" and j.wf6 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "1" and j.wf6 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "4" and j.wf7 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "1" and j.wf7 = "WFO" THEN "C" 

        ELSE "" END ) as h24,

        MAX(CASE

        WHEN date_format(b.tanggal, "%d") = "25" and b.cuti = "D" THEN "D"
        WHEN date_format(b.tanggal, "%d") = "25" and b.cuti = "I1" THEN "I1"
        WHEN date_format(b.tanggal, "%d") = "25" and b.cuti = "]I2" THEN "]I2"

        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Monday" and b.cuti = "4" and j.wf1 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Monday" and b.cuti = "1" and j.wf1 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "4" and j.wf2 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "1" and j.wf2 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "4" and j.wf3 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "1" and j.wf3 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "4" and j.wf4 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "1" and j.wf4 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Friday" and b.cuti = "4" and j.wf5 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Friday" and b.cuti = "1" and j.wf5 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "4" and j.wf6 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "1" and j.wf6 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "4" and j.wf7 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "1" and j.wf7 = "WFO" THEN "C" 

        ELSE "" END ) as h25,

        MAX(CASE

        WHEN date_format(b.tanggal, "%d") = "26" and b.cuti = "D" THEN "D"
        WHEN date_format(b.tanggal, "%d") = "26" and b.cuti = "I1" THEN "I1"
        WHEN date_format(b.tanggal, "%d") = "26" and b.cuti = "]I2" THEN "]I2"

        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Monday" and b.cuti = "4" and j.wf1 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Monday" and b.cuti = "1" and j.wf1 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "4" and j.wf2 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "1" and j.wf2 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "4" and j.wf3 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "1" and j.wf3 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "4" and j.wf4 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "1" and j.wf4 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Friday" and b.cuti = "4" and j.wf5 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Friday" and b.cuti = "1" and j.wf5 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "4" and j.wf6 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "1" and j.wf6 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "4" and j.wf7 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "1" and j.wf7 = "WFO" THEN "C" 

        ELSE "" END ) as h26,

        MAX(CASE

        WHEN date_format(b.tanggal, "%d") = "27" and b.cuti = "D" THEN "D"
        WHEN date_format(b.tanggal, "%d") = "27" and b.cuti = "I1" THEN "I1"
        WHEN date_format(b.tanggal, "%d") = "27" and b.cuti = "]I2" THEN "]I2"

        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Monday" and b.cuti = "4" and j.wf1 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Monday" and b.cuti = "1" and j.wf1 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "4" and j.wf2 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "1" and j.wf2 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "4" and j.wf3 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "1" and j.wf3 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "4" and j.wf4 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "1" and j.wf4 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Friday" and b.cuti = "4" and j.wf5 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Friday" and b.cuti = "1" and j.wf5 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "4" and j.wf6 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "1" and j.wf6 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "4" and j.wf7 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "1" and j.wf7 = "WFO" THEN "C" 

        ELSE "" END ) as h27,

        MAX(CASE

        WHEN date_format(b.tanggal, "%d") = "28" and b.cuti = "D" THEN "D"
        WHEN date_format(b.tanggal, "%d") = "28" and b.cuti = "I1" THEN "I1"
        WHEN date_format(b.tanggal, "%d") = "28" and b.cuti = "]I2" THEN "]I2"

        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Monday" and b.cuti = "4" and j.wf1 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Monday" and b.cuti = "1" and j.wf1 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "4" and j.wf2 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "1" and j.wf2 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "4" and j.wf3 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "1" and j.wf3 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "4" and j.wf4 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "1" and j.wf4 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Friday" and b.cuti = "4" and j.wf5 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Friday" and b.cuti = "1" and j.wf5 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "4" and j.wf6 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "1" and j.wf6 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "4" and j.wf7 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "1" and j.wf7 = "WFO" THEN "C" 

        ELSE "" END ) as h28,

        MAX(CASE

        WHEN date_format(b.tanggal, "%d") = "29" and b.cuti = "D" THEN "D"
        WHEN date_format(b.tanggal, "%d") = "29" and b.cuti = "I1" THEN "I1"
        WHEN date_format(b.tanggal, "%d") = "29" and b.cuti = "]I2" THEN "]I2"

        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Monday" and b.cuti = "4" and j.wf1 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Monday" and b.cuti = "1" and j.wf1 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "4" and j.wf2 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "1" and j.wf2 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "4" and j.wf3 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "1" and j.wf3 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "4" and j.wf4 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "1" and j.wf4 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Friday" and b.cuti = "4" and j.wf5 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Friday" and b.cuti = "1" and j.wf5 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "4" and j.wf6 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "1" and j.wf6 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "4" and j.wf7 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "1" and j.wf7 = "WFO" THEN "C" 

        ELSE "" END ) as h29,

        MAX(CASE

        WHEN date_format(b.tanggal, "%d") = "30" and b.cuti = "D" THEN "D"
        WHEN date_format(b.tanggal, "%d") = "30" and b.cuti = "I1" THEN "I1"
        WHEN date_format(b.tanggal, "%d") = "30" and b.cuti = "]I2" THEN "]I2"

        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Monday" and b.cuti = "4" and j.wf1 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Monday" and b.cuti = "1" and j.wf1 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "4" and j.wf2 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "1" and j.wf2 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "4" and j.wf3 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "1" and j.wf3 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "4" and j.wf4 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "1" and j.wf4 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Friday" and b.cuti = "4" and j.wf5 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Friday" and b.cuti = "1" and j.wf5 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "4" and j.wf6 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "1" and j.wf6 = "WFO" THEN "C"
        
        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "4" and j.wf7 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "1" and j.wf7 = "WFO" THEN "C" 

        ELSE "" END ) as h30,

        MAX(CASE

        WHEN date_format(b.tanggal, "%d") = "31" and b.cuti = "D" THEN "D"
        WHEN date_format(b.tanggal, "%d") = "31" and b.cuti = "I1" THEN "I1"
        WHEN date_format(b.tanggal, "%d") = "31" and b.cuti = "]I2" THEN "]I2"

        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Monday" and b.cuti = "4" and j.wf1 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Monday" and b.cuti = "1" and j.wf1 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "4" and j.wf2 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "1" and j.wf2 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "4" and j.wf3 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "1" and j.wf3 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "4" and j.wf4 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "1" and j.wf4 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Friday" and b.cuti = "4" and j.wf5 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Friday" and b.cuti = "1" and j.wf5 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "4" and j.wf6 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "1" and j.wf6 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "4" and j.wf7 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "1" and j.wf7 = "WFO" THEN "C" 
 
        ELSE "" END ) as h31

        from users s
        LEFT join tb_presensi b on s.id = b.id_user
        LEFT join tb_jammasuk j on s.id = j.id_user
        where b.tanggal between "' . $dari . '" and "' . $sampai . '" and s.cluster = "' . $request->cluster . '"
        group by s.id, b.id_user
        order by s.nama ASC');
        return $detail;
    }
}
