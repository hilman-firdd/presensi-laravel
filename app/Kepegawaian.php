<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Kepegawaian extends Model
{
    protected $table = 'tb_kepegawaian';
    protected $fillable = [
    'id_pegawai',
    'status_kepegawaian',
    'nig',
    'niy_nigk',
    'nuptk',
    'sk_pengangkatan'
    ];	
}
