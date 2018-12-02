<?php

namespace almacenGH;

use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
     protected $table='venta';

    protected $primaryKey='idventa';

    public $timestamps=false;

    protected $fillable =[
    	'idcliente',
        'idlocalidad',
    	'idred',
    	'fecha_hora',
    	'estado'
    ];
    protected $guarded =[
    ];
}
