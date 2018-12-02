<?php

namespace almacenGH\Http\Controllers;

use Illuminate\Http\Request;
use almacenGH\Http\Requests;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use almacenGH\Http\Requests\VentaFormRequest;
use almacenGH\Venta;
use almacenGH\DetalleVenta;
use DB;

use Fpdf;

use Carbon\Carbon;
use Response;
use Illuminate\Support\Collection;
class VentaController extends Controller
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
           $ventas=DB::table('venta as v')
            ->join('persona as p','v.idcliente','=','p.idpersona')
            ->join('detalle_venta as dv','v.idventa','=','dv.idventa')
            ->join('localidad as l','v.idlocalidad','=','l.idlocalidad')
            ->join('red as r','v.idred','=','r.idred')
            ->select('v.idventa','l.nombre as localidad','r.abrev as red','v.fecha_hora','p.nombre','v.estado')
            ->where('p.nombre','LIKE','%'.$query.'%')
            ->orwhere('l.nombre','LIKE','%'.$query.'%')
            ->where('v.estado','=','A')
            ->orderBy('v.idventa','desc')
            //->groupBy('v.idventa','v.idlocalidad','v.idred','v.fecha_hora','p.nombre','v.estado')
            ->paginate(100);
            return view('ventas/venta.index',["ventas"=>$ventas,"searchText"=>$query]);

        }
    }
    public function create()
{
       $localidades=DB::table('localidad')->where('condicion','=','1')->get();
       $redes=DB::table('red')->where('condicion','=','1')->get();
        $personas=DB::table('persona')->where('tipo_persona','=','Cliente')->get();
        $articulos = DB::table('articulo as art')
            ->select(DB::raw('CONCAT(art.codigo, " ",art.nombre) AS articulo'),'art.idarticulo','stock')
            ->where('art.estado','=','Activo')
            ->get();
        return view("ventas/venta.create",["personas"=>$personas,"articulos"=>$articulos ,"localidades"=>$localidades,"redes"=>$redes]);
    }

     public function store (VentaFormRequest $request)
    {
        //try{
            DB::beginTransaction();
            $venta=new Venta;
            $venta->idcliente=$request->get('idcliente');
            $venta->idlocalidad=$request->get('idlocalidad');
            $venta->idred=$request->get('idred');
            
            $mytime = Carbon::now('America/Lima');
           // $venta->fecha_hora=$mytime->toDateTimeString();
            $venta->fecha_hora =$request->get('fecha');
           /* if ($request->get('impuesto')=='1')
            {
                $ingreso->impuesto='18'; 
            }
            else
            {
                $ingreso->impuesto='0';
            } */           
            $venta->estado='A';
            $venta->save();

            $idarticulo = $request->get('idarticulo');
            $cantidad = $request->get('cantidad');
           // $precio_compra = $request->get('precio_compra');
            //$precio_venta = $request->get('precio_venta');

            $cont = 0;

            while($cont < count($idarticulo)){
                $detalle = new DetalleVenta();
                $detalle->idventa= $venta->idventa; 
                $detalle->idarticulo= $idarticulo[$cont];
                $detalle->cantidad= $cantidad[$cont];
                //$detalle->precio_compra= $precio_compra[$cont];
                //$detalle->precio_venta= $precio_venta[$cont];
                $detalle->save();
                $cont=$cont+1;            
            }

            DB::commit();

       /* }catch(\Exception $e)
        {
            DB::rollback();
        }*/

        return Redirect::to('ventas/venta');
    }

    public function show($id)
    {
       $venta=DB::table('venta as v')
            ->join('persona as p','v.idcliente','=','p.idpersona')
            ->join('detalle_venta as dv','v.idventa','=','dv.idventa')
            ->join('localidad as l','v.idlocalidad','=','l.idlocalidad')
            ->join('red as r','v.idred','=','r.idred')
            ->select('v.idventa','l.nombre as localidad','r.abrev as red','v.fecha_hora','p.nombre','v.estado')
            ->where('v.idventa','=',$id)
            ->first();

        $detalles=DB::table('detalle_venta as dv')
             ->join('articulo as a','dv.idarticulo','=','a.idarticulo')
             ->select('a.nombre as articulo','dv.cantidad')
             ->where('dv.idventa','=',$id)
             ->get();
        return view("ventas/venta.show",["venta"=>$venta,"detalles"=>$detalles]);
    }

    public function destroy($id)
    {
        $ingreso=Venta::findOrFail($id);
        $ingreso->Estado='C';
        $ingreso->update();
        return Redirect::to('ventas/venta');
    }
    public function reportec($id){
         //Obtengo los datos
        
    $venta=DB::table('venta as v')
            ->join('persona as p','v.idcliente','=','p.idpersona')
            ->join('detalle_venta as dv','v.idventa','=','dv.idventa')
            ->join('localidad as l','v.idlocalidad','=','l.idlocalidad')
            ->join('red as r','v.idred','=','r.idred')
            ->select('v.idventa','l.nombre as localidad','r.abrev as red','v.fecha_hora','p.nombre','v.estado')
            ->where('v.idventa','=',$id)
            ->first();

        $detalles=DB::table('detalle_venta as d')
             ->join('articulo as a','d.idarticulo','=','a.idarticulo')
             ->select('a.nombre as articulo','d.cantidad')
             ->where('d.idventa','=',$id)
             ->get();


        
        $pdf = new Fpdf();
        $pdf::AddPage();
        $pdf::SetTextColor(35,56,113);
        $pdf::SetFont('Arial','B',24);
        $pdf::Image('../public/img/logo.png',18,7,20);
        $pdf::Cell(0,10,utf8_decode("Dellate de Repartición"),0,"","C");
        $pdf::Ln();
        $pdf::Ln();
        $pdf::SetXY(30,30);
        $pdf::SetTextColor(244,100,0);
        $pdf::SetFont('Arial','B',13);
        $pdf::cell(40,10,utf8_decode("Operario"));
        $pdf::cell(40,10,utf8_decode("Localidad"));
        $pdf::cell(40,10,utf8_decode("Tipo Red"));
        $pdf::Cell(40,10,utf8_decode("Fecha"));
        $pdf::Ln();
        $pdf::Ln();
        $pdf::SetXY(30,40);
        $pdf::SetTextColor(0,100,149);
        $pdf::SetFont('Arial','B',11);
        $pdf::Cell(40,4,utf8_decode($venta->nombre));
        $pdf::Cell(40,4,utf8_decode($venta->localidad));
        $pdf::Cell(40,4,substr($venta->red,0,10));
        $pdf::Cell(40,4,substr($venta->fecha_hora,0,10));
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
         $registros=DB::table('venta as v')
            ->join('persona as p','v.idcliente','=','p.idpersona')
            ->join('detalle_venta as dv','v.idventa','=','dv.idventa')
            ->select('v.idventa','v.idlocalidad','v.idred','v.fecha_hora','p.nombre','v.estado')
            ->orderBy('v.idventa','desc')
            ->groupBy('v.idventa','v.idlocalidad','v.idred','v.fecha_hora','p.nombre','v.estado')
            ->get();

         //Ponemos la hoja Horizontal (L)
         $pdf = new Fpdf('L','mm','A4');
         $pdf::AddPage();
         $pdf::SetTextColor(35,56,113);
         $pdf::SetFont('Arial','B',11);
         $pdf::Cell(0,10,utf8_decode("Listado de Reparticiones"),0,"","C");
         $pdf::Ln();
         $pdf::Ln();
         $pdf::SetTextColor(0,0,0);  // Establece el color del texto 
         $pdf::SetFillColor(206, 246, 245); // establece el color del fondo de la celda 
         $pdf::SetFont('Arial','B',10); 
         //El ancho de las columnas debe de sumar promedio 190        
         $pdf::cell(35,8,utf8_decode("Fecha"),1,"","L",true);
         $pdf::cell(80,8,utf8_decode("Operario"),1,"","L",true);
         $pdf::cell(45,8,utf8_decode("Localidad"),1,"","L",true);
         $pdf::cell(10,8,utf8_decode("Red"),1,"","C",true);
         $pdf::cell(25,8,utf8_decode("Estado"),1,"","R",true);
         
         $pdf::Ln();
         $pdf::SetTextColor(0,0,0);  // Establece el color del texto 
         $pdf::SetFillColor(255, 255, 255); // establece el color del fondo de la celda
         $pdf::SetFont("Arial","",9);
         
         foreach ($registros as $reg)
         {
            $pdf::cell(35,8,utf8_decode($reg->fecha_hora),1,"","L",true);
            $pdf::cell(80,8,utf8_decode($reg->nombre),1,"","L",true);
            $pdf::cell(45,8,utf8_decode($reg->idlocalidad),1,"","L",true);
            $pdf::cell(10,8,utf8_decode($reg->idred),1,"","C",true);
            $pdf::cell(25,8,utf8_decode($reg->estado),1,"","R",true);
            $pdf::Ln(); 
         }

         $pdf::Output();
         exit;
    }
}
