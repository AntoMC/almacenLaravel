<?php

namespace almacenGH\Http\Controllers;

use Illuminate\Http\Request;
use almacenGH\Http\Requests;
use almacenGH\Red;
use Illuminate\Support\Facades\Redirect;
use almacenGH\Http\Requests\RedFormRequest;
use DB;

use Fpdf;
class RedController extends Controller
{
    
     public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(Request $request)
    {
    	if ($request) {
    		$query=trim($request->get('searchText'));
    		$redes=DB::table('red')->where('nombre','LIKE','%'.$query.'%')
    		->where('condicion','=','1')
    		->orderBy('idred','desc')
    		->paginate(7);

    		return view('localidad.red.index',["redes"=>$redes, "searchText"=>$query]);
    	}
    }
    public function create()
    {
    	return view("localidad.red.create");
    }
    public function store(RedFormRequest $request)
    {
    	$red =  new Red;
    	$red->nombre = $request->get('nombre');    
        $red->abrev = $request->get('abrev');
    	$red->condicion = '1';
    	$red->save();

    	return Redirect::to('localidad/red');
    }
    public function show($id)
    {
    	return view ('localidad.red.show',["red"=>Red::findOrFail($id)]);
    }
    public function edit($id)
    {
    	return view ('localidad.red.edit',["red"=>Red::findOrFail($id)]);
    }
    public function update(RedFormRequest $request, $id)
    {
    	$red = Red::findOrFail($id);
    	$red->nombre = $request->get('nombre');
    	$red->abrev = $request->get('abrev');
    	$red->update();

    	return Redirect::to('localidad/red');
    }
    public function destroy($id) 
    {
    	$red = Red::findOrFail($id);
    	$red->condicion = '0';
    	$red->update();

    	return Redirect::to('localidad/red');
    }

    public function reporte()
    {
        //obtenemos los registros
        $registros=DB::table('red')
            ->where ('condicion','=','1')
            ->orderBy('nombre','asc')
            ->get();

        //creamos un objeto de la clase Fpdf y de damos valores a sus atributos para darle forma
        $pdf = new Fpdf;
        $pdf::AddPage();
        $pdf::SetTextColor(35,56,113);
        $pdf::SetFont('Arial','B',11);
        $pdf::Cell(0,10,utf8_decode("Listado de redes"),0,"","C");
        $pdf::Ln();    
        $pdf::Ln();
        $pdf::SetTextColor(0,0,0); //establece el color del texto
        $pdf::SetFillColor(206,246,245);//establece el color de fondo de la celda
        $pdf::SetFont('Arial','B',10);

        //el ancho de las columnas debe sumar un promedio de 190
        $pdf::Cell(45,8,utf8_decode("Código"),1,"","L",true);
        $pdf::Cell(50,8,utf8_decode("Nombre"),1,"","L",true);
        $pdf::Cell(50,8,utf8_decode("Abrev."),1,"","L",true);
        $pdf::Cell(45,8,utf8_decode("Condición"),1,"","L",true);

        $pdf::Ln();
        $pdf::SetTextColor(0,0,0);  // Establece el color del texto 
        $pdf::SetFillColor(255, 255, 255); // establece el color del fondo de la celda
        $pdf::SetFont("Arial","",9);

        foreach ($registros as $reg)
         {
            $pdf::cell(45,6,utf8_decode($reg->idred),1,"","L",true);
            $pdf::cell(50,6,utf8_decode($reg->nombre),1,"","L",true);
            $pdf::cell(50,6,utf8_decode($reg->abrev),1,"","L",true);
            $pdf::cell(45,6,utf8_decode($reg->condicion),1,"","L",true);
            $pdf::Ln(); 
         }

         $pdf::Output();
         exit;
     }
}
