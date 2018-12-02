@extends ('layouts.admin')
@section ('contenido')
<div class="row">
	<div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
		<h3>Listado de Artículos <a href="articulo/create"><button class="btn btn-success fa fa-file-o"></button></a> <a href="{{url('reportearticulos')}}" target="_blank"><button class="btn btn-info fa fa-file-pdf-o"></button></a></h3>
	</div>
	<div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
		<br>
		@include('almacen.articulo.search')
	</div>

</div>

<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="table-responsive">
			<table class="table table-borderless table-hover">
				<thead class="texto-amc">
					<th>Id</th>
					<th>Nombre</th>
					<th>Código</th>
					<th>Categoría</th>
					<th>Stock</th>
					<th>Imagen</th>
					<th>Opciones</th>
				</thead>
               @foreach ($articulos as $art)
				<tr>
					<td>{{ $art->idarticulo}}</td>
					<td>{{ $art->nombre}}</td>
					<td>{{ $art->codigo}}</td>
					<td>{{ $art->categoria}}</td>
					<td>{{ $art->stock}}</td>
					<td>
						<img src="{{asset('imagenes/articulos/'.$art->imagen)}}" alt="{{ $art->nombre}}" height="30px" width="30px" class="img-thumbnail">
					</td>
					<td>
						<a href="{{URL::action('ArticuloController@edit',$art->idarticulo)}}"><button class="btn btn-info"><i class="fa fa-edit"></i></button></a>
                         <a href="" data-target="#modal-delete-{{$art->idarticulo}}" data-toggle="modal"><button class="btn btn-danger fa fa-trash-o"> <i class=""></i></button></a>
					</td>
				</tr>
				@include('almacen.articulo.modal')
				@endforeach
			</table>
		</div>
		{{$articulos->render()}}
	</div>
</div>
@push ('scripts')
<script>
$('#liAlmacen').addClass("treeview active");
$('#liArticulos').addClass("active");
</script>
@endpush
@endsection