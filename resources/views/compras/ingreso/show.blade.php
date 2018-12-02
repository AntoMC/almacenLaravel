@extends ('layouts.admin')
@section ('contenido')
    <div class="row">
    	<div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
    		<div class="form-group">
            	<label for="proveedor">Proveedor</label>
            	<p>{{$ingreso->nombre}}</p>
            </div>
    	</div>
        <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
            <div class="form-group">
                <label for="num_comprobante">Número Comprobante</label>
                <p>{{$ingreso->num_comprobante}}</p>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="panel panel-primary fondo-transp">
            <div class="panel-body fondo-transp">            

                <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                    <table id="detalles" class="table table-borderless table-hover">
                        <thead class="texto-amc">
                            
                            <th>Artículo</th>
                            <th>Cantidad</th>
                        </thead>
                        <tfoot>
                            
                            <th></th>
                            <th></th>
                          
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
$('#liCompras').addClass("treeview active");
$('#liIngresos').addClass("active");
</script>
@endpush
@endsection