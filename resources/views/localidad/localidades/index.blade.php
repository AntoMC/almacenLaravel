@extends ('layouts.admin')
@section ('contenido')
	<div class="row">
		<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
			<h3>Listado de Localidades <a href="localidades/create"><button class="btn btn-success fa fa-file-o"></button></a><a href="{{url('reportelocalidades')}}" target="_blank"><button class="btn btn-info fa fa-file-pdf-o"></button></a></h3>
			@include('localidad.localidades.search')
		</div>
	</div>

	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="table-responsive">
				<table class="table table-borderless table-hover">
					<thead>
						<th>Id</th>
						<th>Nombre</th>
						<th>Opciones</th>
					</thead>
					@foreach ($localidades as $loc)
						<tr>
							<td>{{ $loc->idlocalidad}}</td>
							<td>{{ $loc->nombre}}</td>
							<td>
								<a href="{{URL::action('LocalidadController@edit',$loc->idlocalidad)}}"><button class="btn btn-info fa fa-edit"></button></a>
								<a href="" data-target="#modal-delete-{{$loc->idlocalidad}}" data-toggle="modal"><button class="btn btn-danger fa fa-trash-o"></button></a>
							</td>
						</tr>
					@include('localidad.localidades.modal')	
					@endforeach
				</table>
			</div>
			{{$localidades->render()}}
		</div>
	</div>
@endsection