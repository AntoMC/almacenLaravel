<?php

namespace almacenGH\Http\Controllers;

use Illuminate\Http\Request;
use almacenGH\Http\Requests;
use almacenGH\Almacen;
use Illuminate\Support\Facades\Redirect;
use almacenGH\Http\Requests\AlmacenFormRequest;
use DB;
use Fpdf;

class AlmacenController extends Controller
{
    
     public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(Request $request)
    {
    	if ($request) {
    		$query=trim($request->get('searchText'));
    		$almacenes=DB::table('almacen')->where('nombre','LIKE','%'.$query.'%')
    		->where('condicion','=','1')
    		->orderBy('idalmacen','desc')
    		->paginate(100);

    		return view('almacen.almacen.index',["almacenes"=>$almacenes, "searchText"=>$query]);
    	}
    }
    public function create()
    {
    	return view("almacen.almacen.create");
    }
    public function store(AlmacenFormRequest $request)
    {
    	$almacen =  new Almacen;
    	$almacen->nombre = $request->get('nombre');
    	$almacen->abrev = $request->get('abrev');
    	$almacen->condicion = '1';
    	$almacen->save();

    	return Redirect::to('almacen/almacen');
    }
    public function show($id)
    {
    	return view ('almacen.almacen.show',["almacen"=>Almacen::findOrFail($id)]);
    }
    public function edit($id)
    {
    	return view ('almacen.almacen.edit',["almacen"=>Almacen::findOrFail($id)]);
    }
    public function update(AlmacenFormRequest $request, $id)
    {
    	$almacen = Almacen::findOrFail($id);
    	$almacen->nombre = $request->get('nombre');
    	$almacen->abrev = $request->get('abrev');
    	$almacen->update();

    	return Redirect::to('almacen/almacen');
    }
    public function destroy($id)
    {
    	$almacen = Almacen::findOrFail($id);
    	$almacen->condicion = '0';
    	$almacen->update();

    	return Redirect::to('almacen/almacen');
    }

    //reporte
     public function reporte()
    {
        //obtenemos los registros
        $registros=DB::table('almacen')
            ->where ('condicion','=','1')
            ->orderBy('nombre','asc')
            ->get();

        //creamos un objeto de la clase Fpdf y de damos valores a sus atributos para darle forma
        $pdf = new Fpdf;
        $pdf::AddPage();
        $pdf::SetTextColor(35,56,113);
        $pdf::SetFont('Arial','B',11);
        $pdf::Cell(0,10,utf8_decode("Listado de Almacenes"),0,"","C");
        $pdf::Ln();    
        $pdf::Ln();
        $pdf::SetTextColor(0,0,0); //establece el color del texto
        $pdf::SetFillColor(206,246,245);//establece el color de fondo de la celda
        $pdf::SetFont('Arial','B',10);

        //el ancho de las columnas debe sumar un promedio de 190
        $pdf::Cell(45,8,utf8_decode("Código"),1,"","L",true);
        $pdf::Cell(50,8,utf8_decode("Nombre"),1,"","L",true);
        $pdf::Cell(50,8,utf8_decode("Abrev"),1,"","L",true);
        $pdf::Cell(45,8,utf8_decode("Condición"),1,"","L",true);

        $pdf::Ln();
        $pdf::SetTextColor(0,0,0);  // Establece el color del texto 
        $pdf::SetFillColor(255, 255, 255); // establece el color del fondo de la celda
        $pdf::SetFont("Arial","",9);

        foreach ($registros as $reg)
         {
            $pdf::cell(45,6,utf8_decode($reg->idalmacen),1,"","L",true);
            $pdf::cell(50,6,utf8_decode($reg->nombre),1,"","L",true);
            $pdf::cell(50,6,utf8_decode($reg->abrev),1,"","L",true);
            $pdf::cell(45,6,utf8_decode($reg->condicion),1,"","L",true);
            $pdf::Ln(); 
         }

         $pdf::Output();
         exit;
    }
}
