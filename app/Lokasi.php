<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lokasi extends Model
{
	protected $table = 'tb_lokasi';
	protected $fillable = [
		'name',
		'address_address',
		'address_latitude',
		'address_longitude'
	];	
}
