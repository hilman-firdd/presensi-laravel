<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Detailuser extends Model
{
    protected $table = 'tb_detail_user';
    protected $fillable = [
    'id',
    'nm_lengkap',
    'jk',
    'nik',
    'tempat',
    'tgl_lahir',
    'agama',
    'status',
    'kewarganegaraan',
    'nm_ibu'
    ];	
}
