<?php

namespace almacenGH;

use Illuminate\Database\Eloquent\Model;

class Ingreso extends Model
{
   protected $table='ingreso';

    protected $primaryKey='idingreso';

    public $timestamps=false;

    protected $fillable =[
    	'idproveedor',
    	'num_comprobante',
    	'fecha_hora',
    	'estado'
    ];
    protected $guarded =[
    ];
}
