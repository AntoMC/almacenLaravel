<?php

namespace almacenGH;

use Illuminate\Database\Eloquent\Model;

class Almacen extends Model
{
    protected $table = 'almacen';
    protected $primaryKey = 'idalmacen';
    public $timestamps = false;

    protected $fillable = [
    	'nombre',
    	'descripcion',
    	'abrev',
    	'condicion'
    ];

    protected $guarder = [

    ];
}
