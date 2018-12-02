@extends ('layouts.admin')
@section ('contenido')
	<div class="row">
		<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
			<h3>Listado de Almacenes <a href="almacen/create"><button class="btn btn-success fa fa-file-o"></button></a><a href="{{url('reportealmacenes')}}" target="_blank"><button class="btn btn-info fa fa-file-pdf-o"></button></a></h3>
			@include('almacen.almacen.search')
		</div>
	</div>

	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="table-responsive">
				<table class="table table-borderless table-hover">
					<thead class="texto-amc">
						<th>Id</th>
	 					<th>Nombre</th>
						<th>abrev</th>
						<th>Opciones</th>
					</thead>
					@foreach ($almacenes as $alm)
						<tr>
							<td>{{ $alm->idalmacen}}</td>
							<td>{{ $alm->nombre}}</td>
							<td>{{ $alm->abrev}}</td>
							<td>
								<a href="{{URL::action('AlmacenController@edit',$alm->idalmacen)}}"><button class="btn btn-info fa fa-edit"></button></a>
								<a href="" data-target="#modal-delete-{{$alm->idalmacen}}" data-toggle="modal"><button class="btn btn-danger fa fa-trash-o"></button></a>
							</td>
						</tr>
					@include('almacen.almacen.modal')	
					@endforeach
				</table>
			</div>
			{{$almacenes->render()}}
		</div>
	</div>
@endsection