<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Presensi extends Model
{
    protected $table = 'tb_presensi';
    protected $fillable = [
    'id',
    'id_user',
    'berangkat',
    'lokasi_berangkat',
    'pulang',
    'lokasi_pulang',
    'cuti',
    'tanggal',
    'keterangan'
    ];	
}
