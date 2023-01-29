<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Alamat extends Model
{
    protected $table = 'tb_alamat';
    protected $fillable = [
    'id_pegawai',
    'jl',
    'rt',
    'rw',
    'dusun',
    'desa',
    'kecamatan',
    'kode_pos'
    ];	
}
