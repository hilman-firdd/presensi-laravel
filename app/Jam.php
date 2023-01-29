<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Jam extends Model
{
    protected $table = 'tb_jammasuk';
    protected $fillable = [
    'id',
    'id_user',
    'jammasuk'
    ];	
}
