@extends ('layouts.admin')
@section ('contenido')
	<div class="row">
		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
			<h3>Nuevo Ingreso</h3>
			@if (count($errors)>0)
			<div class="alert alert-danger">
				<ul>
				@foreach ($errors->all() as $error)
					<li>{{$error}}</li>
				@endforeach
				</ul>
			</div>
			@endif
		</div> 
	</div>
			{!!Form::open(array('url'=>'compras/ingreso','method'=>'POST','autocomplete'=>'off'))!!}
            {{Form::token()}}
    <div class="row">
    	<div class="col-lg-4 col-sm-4 col-md-4 col-xs-12">
    		<div class="form-group">
            	<label for="proveedor">Proveedor</label>
            	<select name="idproveedor" id="idproveedor" class="form-control selectpicker" data-live-search="true" data-style="btn-primary">
                    @foreach($personas as $persona)
                     <option value="{{$persona->idpersona}}" class="dropdown-amc">{{$persona->nombre}}</option>
                     @endforeach
                </select>
            </div>
    	</div>
    	
        <div class="col-lg-4 col-sm-4 col-md-4 col-xs-12">
            <div class="form-group">
                <label for="num_comprobante">Número de Guía</label>
                <input type="text" name="num_comprobante" required value="{{old('num_comprobante')}}" class="form-control" placeholder="Número comprobante...">
            </div>
        </div>

      <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
          <div class="form-group">
          <label for="fecha">Fecha</label>
           <input type="date" name="fecha" class="form-control">
         </div>
      </div>
        
    </div>
    <div class="row">
        <div class="panel panel-primary fondo-transp">
            <div class="panel-body fondo-transp">
                <div class="col-lg-8 col-sm-8 col-md-8 col-xs-12">
                    <div class="form-group">
                        <label>Artículo</label>
                        <select name="pidarticulo" class="form-control selectpicker" id="pidarticulo" data-live-search="true" data-style="btn-primary">
                            @foreach($articulos as $articulo)
                            <option value="{{$articulo->idarticulo}}" class="dropdown-amc">{{$articulo->articulo}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-lg-2 col-sm-2 col-md-2 col-xs-12">
                    <div class="form-group">
                        <label for="cantidad">Cantidad</label>
                        <input type="number" name="pcantidad" id="pcantidad" class="form-control" 
                        placeholder="cantidad">
                    </div>
                </div> 
                <div class="col-lg-2 col-sm-2 col-md-2 col-xs-12">
                    <div class="form-group">
                       <button type="button" id="bt_add" class="btn btn-primary">Agregar</button>
                    </div>
                </div>

                <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                    <table id="detalles" class="table table-borderless table-hover">
                        <thead class="texto-amc">
                            <th>Opciones</th>
                            <th>Artículo</th>
                            <th>Cantidad</th>
                        </thead>
                        <tfoot>
                           
                            <th></th>
                            <th></th>
                            <th></th>
                            
                        </tfoot>
                        <tbody class="filas">
                            
                        </tbody>
                    </table>
                 </div>
            </div>
        </div>
    	<div class="col-lg-6 col-sm-6 col-md-6 col-xs-12" id="guardar">
    		<div class="form-group">
            	<input name"_token" value="{{ csrf_token() }}" type="hidden"></input>
                <button class="btn btn-primary" type="submit">Guardar</button>
            	<button class="btn btn-danger" type="reset">Cancelar</button>
            </div>
    	</div>
    </div>   
			{!!Form::close()!!}		

@push ('scripts')
<script>
  $(document).ready(function(){
    $('#bt_add').click(function(){
      agregar();
    });
  });

  var cont=0;
  //total=0;
 // subtotal=[];
  //$("#guardar").hide();
  //$("#tipo_comprobante").change(marcarImpuesto);

  /*function marcarImpuesto()
  {
    tipo_comprobante=$("#tipo_comprobante option:selected").text();
    if (tipo_comprobante=='Factura')
    {
        $("#impuesto").prop("checked", true); 
    }
    else
    {
        $("#impuesto").prop("checked", false);
    }
  }*/
  function agregar()
  {
    idarticulo=$("#pidarticulo").val();
    articulo=$("#pidarticulo option:selected").text();
    cantidad=$("#pcantidad").val();
    //precio_compra=$("#pprecio_compra").val();
    //precio_venta=$("#pprecio_venta").val();

    if (idarticulo!="" && cantidad!="" && cantidad>0)
    {
        //subtotal[cont]=(cantidad*precio_compra);
        //total=total+subtotal[cont];

        var fila='<tr class="selected" id="fila'+cont+'"><td><button type="button" class="btn btn-warning" onclick="eliminar('+cont+');">X</button></td><td><input type="hidden" name="idarticulo[]" value="'+idarticulo+'">'+articulo+'</td><td><input type="number" name="cantidad[]" value="'+cantidad+'"></td></tr>';
        cont++;
        limpiar();
       // $("#total").html("S/. " + total);
        //evaluar();
        $('#detalles').append(fila);
    }
    else
    {
        alert("Error al ingresar el detalle del ingreso, revise los datos del artículo");
    }
  }
  function limpiar(){
    $("#pcantidad").val("");
    //$("#pprecio_compra").val("");
   //$("#pprecio_venta").val("");
  }

  /*function evaluar()
  {
    if (total>0)
    {
      $("#guardar").show();
    }
    else
    {
      $("#guardar").hide(); 
    }
   }*/

   function eliminar(index){
    //total=total-subtotal[index]; 
    //$("#total").html("S/. " + total);   
    $("#fila" + index).remove();
    evaluar();

  }
  $('#liCompras').addClass("treeview active");
$('#liIngresos').addClass("active");
</script>
@endpush
@endsection