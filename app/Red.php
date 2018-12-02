<?php

namespace almacenGH;

use Illuminate\Database\Eloquent\Model;

class Red extends Model
{
     protected $table = 'red';
    protected $primaryKey = 'idred';
    public $timestamps = false;

    protected $fillable = [
    	'nombre',
    	'abrev',
    	'condicion'
    ];

    protected $guarder = [

    ];
}
