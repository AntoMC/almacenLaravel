@extends ('layouts.admin')
@section ('contenido')
	<div class="row">
		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
			<h3>Nueva Repartición</h3>
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
			{!!Form::open(array('url'=>'ventas/venta','method'=>'POST','autocomplete'=>'off'))!!}
            {{Form::token()}}
    <div class="row">
    	<div class="col-lg-3 col-sm-3 col-md-3 col-xs-12">
    		<div class="form-group">
            	<label for="Operario">Operario</label>
            	<select name="idcliente" id="idcliente" class="form-control selectpicker" data-live-search="true" data-style="btn-primary">
                    @foreach($personas as $persona)
                     <option value="{{$persona->idpersona}}" class="dropdown-amc">{{$persona->nombre}}</option>
                     @endforeach
                </select>
            </div>
    	</div>
       <div class="col-lg-3 col-sm-3 col-md-3 col-xs-12">
            <div class="form-group">
                <label for="red">Red</label>
                <select name="idred" id="idred" class="form-control selectpicker" data-live-search="true" data-style="btn-primary">
                    @foreach($redes as $red)
                     <option value="{{$red->idred}}" class="dropdown-amc">{{$red->nombre}}</option>
                     @endforeach
                </select>
            </div>
      </div>
        <div class="col-lg-3 col-sm-3 col-md-3 col-xs-12">
            <div class="form-group">
                <label for="localidad">Localidad</label>
                <select name="idlocalidad" id="idlocalidad" class="form-control selectpicker" data-live-search="true" data-style="btn-primary">
                    @foreach($localidades as $loc)
                     <option value="{{$loc->idlocalidad}}" class="dropdown-amc">{{$loc->nombre}}</option>
                     @endforeach
                </select>
            </div>
        </div>
      <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
          <div class="form-group">
          <label for="fecha">Fecha</label>
           <input type="date" name="fecha" class="form-control">
         </div>
      </div>
    	
    </div>
    <div class="row">
        <div class="panel panel-primary fondo-transp">
            <div class="panel-body fondo-transp">
                <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
                    <div class="form-group">
                        <label>Artículo</label>
                        <select name="pidarticulo" class="form-control selectpicker" id="pidarticulo" data-live-search="true" data-style="btn-primary">
                            @foreach($articulos as $articulo)
                            <option value="{{$articulo->idarticulo}}_{{$articulo->stock}}" class="dropdown-amc">{{$articulo->articulo}}</option>
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
                        <label for="stock">Stock</label>
                        <input type="number" disabled name="pstock" id="pstock" class="form-control" 
                        placeholder="Stock">
                    </div>
                </div>
                
                
                <div class="col-lg-2 col-sm-2 col-md-2 col-xs-12">
                    <div class="form-group">
                        <br>
                       <button type="button" id="bt_add" class="btn btn-success">Agregar</button>
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
  //subtotal=[];
  //$("#guardar").hide();
  $("#pidarticulo").change(mostrarValores);
  //$("#tipo_comprobante").change(marcarImpuesto);

  function mostrarValores()
  {
    datosArticulo=document.getElementById('pidarticulo').value.split('_');
    //$("#pprecio_venta").val(datosArticulo[2]);
    $("#pstock").val(datosArticulo[1]);    
  }
  /*
  function marcarImpuesto()
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
  }
    */
  function agregar()
  {
    datosArticulo=document.getElementById('pidarticulo').value.split('_');

    idarticulo=datosArticulo[0];
    articulo=$("#pidarticulo option:selected").text();
    cantidad=$("#pcantidad").val();

    //descuento=$("#pdescuento").val();
    //precio_venta=$("#pprecio_venta").val();
    stock=$("#pstock").val();

    if (idarticulo!="" && cantidad!="" && cantidad>0)
    {
        if (parseInt(stock)>=parseInt(cantidad))
        {
        //subtotal[cont]=(cantidad*precio_venta-descuento);
        //total=total+subtotal[cont];

        var fila='<tr class="selected" id="fila'+cont+'"><td><button type="button" class="btn btn-warning" onclick="eliminar('+cont+');">X</button></td><td><input type="hidden" id="idarticulo" name="idarticulo[]" value="'+idarticulo+'">'+articulo+'</td><td><input type="number" id="cantidad" name="cantidad[]" value="'+cantidad+'"></td></tr>';
        cont++;
        limpiar();
        //totales();
        //evaluar();
        $('#detalles').append(fila);   
        }
        else
        {
            alert ('La cantidad a vender supera el stock');
        }
        
    }
    else
    {
        alert("Error al ingresar el detalle de la venta, revise los datos del artículo");
    }
  }
  function limpiar(){
    $("#pcantidad").val("");
    //$("#pdescuento").val("0");
    //$("#pprecio_venta").val("");
  }
  /*
  function totales()
  {
        $("#total").html("S/. " + total.toFixed(2));
        $("#total_venta").val(total.toFixed(2));
        
        //Calcumos el impuesto
        if ($("#impuesto").is(":checked"))
        {
            por_impuesto=18;
        }
        else
        {
            por_impuesto=0;   
        }
        total_impuesto=total*por_impuesto/100;
        total_pagar=total+total_impuesto;
        $("#total_impuesto").html("S/. " + total_impuesto.toFixed(2));
        $("#total_pagar").html("S/. " + total_pagar.toFixed(2));
        
  }
    
  function evaluar()
  {
    if (total>0)
    {
      $("#guardar").show();
    }
    else
    {
      $("#guardar").hide(); 
    }
   }
   */

   function eliminar(index){
    //total=total-subtotal[index]; 
   // totales();  
    $("#fila" + index).remove();
    evaluar();

  }
$('#liVentas').addClass("treeview active");
$('#liVentass').addClass("active");
  
</script>
@endpush
@endsection