<?php

namespace almacenGH\Http\Controllers;

use Illuminate\Http\Request;
use almacenGH\Http\Requests;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use almacenGH\Http\Requests\ArticuloFormRequest;
use almacenGH\Articulo;
use DB;
use Fpdf;

class ArticuloController extends Controller
{
        public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(Request $request)
    {
        if ($request)
        {
            $query=trim($request->get('searchText'));
            $articulos=DB::table('articulo as a')
            ->join('categoria as c','a.idcategoria','=','c.idcategoria')
            ->join('almacen as al','a.idalmacen','=','al.idalmacen')
            ->select('a.idarticulo','a.nombre','a.codigo','a.stock','c.nombre as categoria','a.descripcion','a.imagen','al.abrev as almacen','a.estado')
            ->where('a.nombre','LIKE','%'.$query.'%')
            ->orwhere('a.codigo','LIKE','%'.$query.'%')
            ->orderBy('a.idarticulo','desc')
            ->paginate(100);
            return view('almacen.articulo.index',["articulos"=>$articulos,"searchText"=>$query]);
        }
    }
    public function create()
    {
        $categorias=DB::table('categoria')->where('condicion','=','1')->get();
        $almacenes=DB::table('almacen')->where('condicion','=','1')->get();
        return view("almacen.articulo.create",["categorias"=>$categorias,"almacenes"=>$almacenes]);
    }
    public function store (ArticuloFormRequest $request)
    {
        $articulo=new Articulo;
        $articulo->idcategoria=$request->get('idcategoria');
        $articulo->idalmacen=$request->get('idalmacen');
        $articulo->codigo=$request->get('codigo');
        $articulo->nombre=$request->get('nombre');
        $articulo->stock=$request->get('stock');
        $articulo->descripcion=$request->get('descripcion');
                $articulo->estado='Activo';

        if (Input::hasFile('imagen')){
        	$file=Input::file('imagen');
        	$file->move(public_path().'/imagenes/articulos/',$file->getClientOriginalName());
            $articulo->imagen=$file->getClientOriginalName();
        }
        $articulo->save();
        return Redirect::to('almacen/articulo');

    }
    public function show($id)
    {
        return view("almacen.articulo.show",["articulo"=>Articulo::findOrFail($id)]);
    }
    public function edit($id)
    {
        $articulo=Articulo::findOrFail($id);
        $categorias=DB::table('categoria')->where('condicion','=','1')->get();
        $almacenes=DB::table('almacen')->where('condicion','=','1')->get();
        return view("almacen.articulo.edit",["articulo"=>$articulo,"categorias"=>$categorias,"almacenes"=>$almacenes]);
    }
    
    
    public function update(ArticuloFormRequest $request,$id)
    {
        $articulo=Articulo::findOrFail($id);

        $articulo->idcategoria=$request->get('idcategoria');
        $articulo->idalmacen=$request->get('idalmacen');
        $articulo->codigo=$request->get('codigo');
        $articulo->nombre=$request->get('nombre');
        $articulo->stock=$request->get('stock');
        $articulo->descripcion=$request->get('descripcion');
        $articulo->estado='Activo';

        if (Input::hasFile('imagen')){
        	$file=Input::file('imagen');
        	$file->move(public_path().'/imagenes/articulos/',$file->getClientOriginalName());
        	$articulo->imagen=$file->getClientOriginalName();
        }

        $articulo->update();
        return Redirect::to('almacen/articulo');
    }
    public function destroy($id)
    {
        $articulo=Articulo::findOrFail($id);
        $articulo->Estado='Inactivo';
        $articulo->update();
        return Redirect::to('almacen/articulo');
    }
    public function reporte(){
         //Obtenemos los registros
         $registros=DB::table('articulo as a')
            ->join('categoria as c','a.idcategoria','=','c.idcategoria')
            ->join('almacen as al','a.idalmacen','=','al.idalmacen')
            ->select('a.idarticulo','a.nombre','a.codigo','a.stock','c.nombre as categoria','al.abrev as almacen','a.descripcion','a.estado')
            ->orderBy('a.nombre','asc')
            ->get();

        $pdf = new Fpdf(); 
         $pdf::AddPage('L','A3');
         $pdf::SetTextColor(35,56,113);
         $pdf::SetFont('Arial','B',24);
         $pdf::Image('../public/img/logo.png',18,7,20);
         $pdf::Cell(0,10,utf8_decode("Listado Artículos"),0,"","C");
         $pdf::Ln();
         $pdf::Ln();
         $pdf::SetTextColor(0,0,0);  // Establece el color del texto 
         $pdf::SetFillColor(206, 246, 245); // establece el color del fondo de la celda 
         $pdf::SetFont('Arial','B',10); 
         //El ancho de las columnas debe de sumar promedio 190        
         $pdf::cell(24,8,utf8_decode("Código"),1,"","L",true);
         $pdf::cell(240,8,utf8_decode("Nombre"),1,"","L",true);
         $pdf::cell(120,8,utf8_decode("Categoría"),1,"","L",true);
         //$pdf::cell(20,8,utf8_decode("Almacen"),1,"","L",true);
         $pdf::cell(15,8,utf8_decode("Stock"),1,"","L",true);
         
         $pdf::Ln();
         $pdf::SetTextColor(0,0,0);  // Establece el color del texto 
         $pdf::SetFillColor(255, 255, 255); // establece el color del fondo de la celda
         $pdf::SetFont("Arial","",9);
         
         foreach ($registros as $reg)
         {
            $pdf::cell(24,7,utf8_decode($reg->codigo),1,"","L",true);
            $pdf::cell(240,7,utf8_decode($reg->nombre),1,"","L",true);
            $pdf::cell(120,7,utf8_decode($reg->categoria),1,"","L",true);
           // $pdf::cell(20,6,utf8_decode($reg->almacen),1,"","L",true);
            $pdf::cell(15,7,utf8_decode($reg->stock),1,"","L",true);
            $pdf::Ln(); 
         }

         $pdf::Output();
         exit;
    }
}
