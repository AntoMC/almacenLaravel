<?php

namespace almacenGH;

use Illuminate\Database\Eloquent\Model;

class Localidad extends Model
{
   protected $table = 'localidad';
    protected $primaryKey = 'idlocalidad';
    public $timestamps = false;

    protected $fillable = [
    	'nombre',
    	'condicion'
    ];

    protected $guarder = [

    ];
}
