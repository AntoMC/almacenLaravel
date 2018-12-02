@extends ('layouts.admin')
@section ('contenido')
    <div class="row">
    	<div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
    		<div class="form-group">
            	<label for="fecha" class="h3">Fecha</label>
            	<p>{{$venta->fecha_hora}}</p>
            </div>
    	</div>
        <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
            <div class="form-group">
                <label for="cliente" class="display-4">Operario</label>
                <p>{{$venta->nombre}}</p>
            </div>
        </div>
     </div>   
     <div class="row">
    	<div class="col-lg-4 col-sm-4 col-md-4 col-xs-12">
    		<div class="form-group">
    			<label>Localidad</label>
    			<p>{{$venta->localidad}}</p>
    		</div>
    	</div>
    	<div class="col-lg-4 col-sm-4 col-md-4 col-xs-12">
            <div class="form-group">
                <label for="red">Red</label>
                <p>{{$venta->red}}</p>
            </div>
        </div>
        <div class="col-lg-4 col-sm-4 col-md-4 col-xs-12">
            <div class="form-group">
                <label for="estado"> Estado</label>
                <p>{{$venta->estado}}</p>
            </div>
        </div>
       
    </div>
    <div class="row">
        <div class="panel panel-primary fondo-transp">
            <div class="panel-body fondo-transp">            

                <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                    <table id="detalles" class="table table-borderless table-hover">
                        <thead class="texto-amc"">
                            <th>Artículo</th>
                            <th>Cantidad</th>
                        </thead>
                        <tfoot>
                        
                        </tfoot>
                        <tbody>
                            @foreach($detalles as $det)
                            <tr>
                                <td>{{$det->articulo}}</td>
                                <td>{{$det->cantidad}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                 </div>
            </div>
        </div>
    	
    </div>   
@push ('scripts')
<script>
$('#liVentas').addClass("treeview active");
$('#liVentass').addClass("active");
</script>
@endpush
@endsection