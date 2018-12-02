<?php

namespace almacenGH\Http\Controllers;

use Illuminate\Http\Request;
use almacenGH\Http\Requests;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use almacenGH\Http\Requests\IngresoFormRequest;
use almacenGH\Ingreso;
use almacenGH\DetalleIngreso;
use DB;
use Fpdf;
use Carbon\Carbon;
use Response;
use Illuminate\Support\Collection;

class IngresoController extends Controller
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
           $ingresos=DB::table('ingreso as i')
            ->join('persona as p','i.idproveedor','=','p.idpersona')
            ->join('detalle_ingreso as di','i.idingreso','=','di.idingreso')
            ->select('i.idingreso','i.fecha_hora','p.nombre','i.num_comprobante','i.estado')
            ->where('i.num_comprobante','LIKE','%'.$query.'%')
            ->where('i.estado','=','A')
            ->orderBy('i.idingreso','desc')
            ->groupBy('i.idingreso','i.fecha_hora','p.nombre','i.num_comprobante','i.estado')
            ->paginate(100);
            return view('compras.ingreso.index',["ingresos"=>$ingresos,"searchText"=>$query]);

        }
    }
    public function create()
    {
    	$personas=DB::table('persona')->where('tipo_persona','=','Proveedor')->get();
    	$articulos = DB::table('articulo as art')
            ->select(DB::raw('CONCAT(art.codigo, " ",art.nombre) AS articulo'),'art.idarticulo')
            ->where('art.estado','=','Activo')
            ->get();
        return view("compras.ingreso.create",["personas"=>$personas,"articulos"=>$articulos]);
    }

     public function store (IngresoFormRequest $request)
    {
    	try{
        	DB::beginTransaction();
        	$ingreso=new Ingreso;
	        $ingreso->idproveedor=$request->get('idproveedor');
	        //$ingreso->tipo_comprobante=$request->get('tipo_comprobante');
	       // $ingreso->serie_comprobante=$request->get('serie_comprobante');
	        $ingreso->num_comprobante=$request->get('num_comprobante');
	        
	        $mytime = Carbon::now('America/Lima');
            $ingreso->fecha_hora=$request->get('fecha');
	        //$ingreso->fecha_hora=$mytime->toDateTimeString();
	       /* if ($request->get('impuesto')=='1')
            {
                $ingreso->impuesto='18';
            }
            else
            {
                $ingreso->impuesto='0';
            } */           
	        $ingreso->estado='A';
	        $ingreso->save();

	        $idarticulo = $request->get('idarticulo');
	        $cantidad = $request->get('cantidad');
	       // $precio_compra = $request->get('precio_compra');
	        //$precio_venta = $request->get('precio_venta');

	        $cont = 0;

	        while($cont < count($idarticulo)){
	            $detalle = new DetalleIngreso();
	            $detalle->idingreso= $ingreso->idingreso; 
	            $detalle->idarticulo= $idarticulo[$cont];
	            $detalle->cantidad= $cantidad[$cont];
	            //$detalle->precio_compra= $precio_compra[$cont];
	            //$detalle->precio_venta= $precio_venta[$cont];
	            $detalle->save();
	            $cont=$cont+1;            
	        }

        	DB::commit();

        }catch(\Exception $e)
        {
          	DB::rollback();
        }

        return Redirect::to('compras/ingreso');
    }

    public function show($id)
    {
    	$ingreso=DB::table('ingreso as i')
            ->join('persona as p','i.idproveedor','=','p.idpersona')
            ->join('detalle_ingreso as di','i.idingreso','=','di.idingreso')
            ->select('i.idingreso','i.fecha_hora','p.nombre','i.num_comprobante','i.estado')
            ->where('i.idingreso','=',$id)
            ->first();

        $detalles=DB::table('detalle_ingreso as d')
             ->join('articulo as a','d.idarticulo','=','a.idarticulo')
             ->select('a.nombre as articulo','d.cantidad')
             ->where('d.idingreso','=',$id)
             ->get();
        return view("compras.ingreso.show",["ingreso"=>$ingreso,"detalles"=>$detalles]);
    }

    public function destroy($id)
    {
    	$ingreso=Ingreso::findOrFail($id);
        $ingreso->Estado='C';
        $ingreso->update();
        return Redirect::to('compras/ingreso');
    }
    public function reportec($id){
         //Obtengo los datos
        
    $ingreso=DB::table('ingreso as i')
            ->join('persona as p','i.idproveedor','=','p.idpersona')
            ->join('detalle_ingreso as di','i.idingreso','=','di.idingreso')
            ->select('i.idingreso','i.fecha_hora','p.nombre','p.num_documento','i.num_comprobante','i.estado')
            ->where('i.idingreso','=',$id)
            ->first();

        $detalles=DB::table('detalle_ingreso as d')
             ->join('articulo as a','d.idarticulo','=','a.idarticulo')
             ->select('a.nombre as articulo','d.cantidad')
             ->where('d.idingreso','=',$id)
             ->get();

        $pdf = new Fpdf();
        $pdf::AddPage();
        $pdf::SetTextColor(35,56,113);
        $pdf::SetFont('Arial','B',24);
        $pdf::Image('../public/img/logo.png',18,7,20);
        $pdf::Cell(0,10,utf8_decode("Dellate de Ingreso"),0,"","C");
        $pdf::Ln();
        $pdf::Ln();
        $pdf::SetXY(30,30);
        $pdf::SetTextColor(35,56,113);
        $pdf::SetFont('Arial','B',13);
        $pdf::cell(60,10,utf8_decode("Proveedor"));
        $pdf::cell(60,10,utf8_decode("Num_guia"));
        $pdf::Cell(60,10,utf8_decode("Fecha"));
        $pdf::Ln();
        $pdf::Ln();
        $pdf::SetXY(30,40);
        $pdf::SetTextColor(244,100,0);
        $pdf::SetFont('Arial','B',11);
        $pdf::Cell(60,4,utf8_decode($ingreso->nombre));
        $pdf::Cell(60,4,utf8_decode($ingreso->num_comprobante));
        $pdf::Cell(60,4,substr($ingreso->fecha_hora,0,10));
        //$total=0;
        $pdf::SetXY(5,50);
        $pdf::SetTextColor(0,0,0);
        $pdf::SetFillColor(18,236,236); 
        $pdf::SetFont('Arial','B',11);
        $pdf::cell(180,8,utf8_decode("Articulo"),1,"","L",true);
        $pdf::cell(20,8,utf8_decode("Cantidad"),1,"","L",true);

        $pdf::SetTextColor(0,0,0);
        $pdf::SetFillColor(191, 241, 236); 
        $pdf::SetFont('Arial','',7);
        $y=58;
        foreach($detalles as $det){
            $pdf::SetXY(5,$y);
            $pdf::cell(180,7,utf8_decode($det->articulo),1,"","L",true);
            $pdf::cell(20,7,utf8_decode($det->cantidad),1,"","C",true);
            $y=$y+7;
        }

        $pdf::Output();
        exit;
    }
    public function reporte(){
         //Obtenemos los registros
         $registros=DB::table('ingreso as i')
            ->join('persona as p','i.idproveedor','=','p.idpersona')
            ->join('detalle_ingreso as di','i.idingreso','=','di.idingreso')
            ->select('i.idingreso','i.fecha_hora','p.nombre','i.num_comprobante','i.estado')
            ->orderBy('i.idingreso','desc')
            ->groupBy('i.idingreso','i.fecha_hora','p.nombre','i.num_comprobante','i.estado')
            ->get();

         //Ponemos la hoja Horizontal (L)
         $pdf = new Fpdf();
         $pdf::AddPage();
          $pdf::SetTextColor(35,56,113);
         $pdf::SetFont('Arial','B',24);
         $pdf::Image('../public/img/logo.png',18,7,20);
         $pdf::Cell(0,10,utf8_decode("Listado Reparticiones"),0,"","C");
         $pdf::Ln();
         $pdf::Ln();
         $pdf::SetTextColor(0,0,0);  // Establece el color del texto 
         $pdf::SetFillColor(206, 246, 245); // establece el color del fondo de la celda 
         $pdf::SetFont('Arial','B',10); 
         //El ancho de las columnas debe de sumar promedio 190        
         $pdf::cell(35,8,utf8_decode("Fecha"),1,"","L",true);
         $pdf::cell(100,8,utf8_decode("Proveedor"),1,"","L",true);
         $pdf::cell(45,8,utf8_decode("Comprobante"),1,"","L",true);
         //$pdf::cell(10,8,utf8_decode("Imp"),1,"","C",true);
         //$pdf::cell(25,8,utf8_decode("Total"),1,"","R",true);
         
         $pdf::Ln();
         $pdf::SetTextColor(0,0,0);  // Establece el color del texto 
         $pdf::SetFillColor(255, 255, 255); // establece el color del fondo de la celda
         $pdf::SetFont("Arial","",9);
         
         foreach ($registros as $reg)
         {
            $pdf::cell(35,8,utf8_decode($reg->fecha_hora),1,"","L",true);
            $pdf::cell(100,8,utf8_decode($reg->nombre),1,"","L",true);
            $pdf::cell(45,8,utf8_decode($reg->num_comprobante),1,"","L",true);
           // $pdf::cell(10,8,utf8_decode($reg->impuesto),1,"","C",true);
            //$pdf::cell(25,8,utf8_decode($reg->total),1,"","R",true);
            $pdf::Ln(); 
         }

         $pdf::Output();
         exit;
    }
}
