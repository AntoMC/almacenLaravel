@extends ('layouts.admin')
@section ('contenido')
	<div class="row">
		<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
			<h3>Listado de Redes <a href="red/create"><button class="btn btn-success fa fa-file-o"></button></a><a href="{{url('reporteredes')}}" target="_blank"><button class="btn btn-info fa fa-file-pdf-o"></button></a></h3>
			@include('localidad.red.search')
		</div>
	</div>

	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="table-responsive">
				<table class="table table-borderless table-hover">
					<thead>
						<th>Id</th>
						<th>Nombre</th>	
                        <th>Abrev</th>
						<th>Opciones</th>
					</thead>
					@foreach ($redes as $red)
						<tr>
							<td>{{ $red->idred}}</td>
							<td>{{ $red->nombre}}</td>	
                            <td>{{ $red->abrev}}</td>

							<td>
								<a href="{{URL::action('RedController@edit',$red->idred)}}"><button class="btn btn-info fa fa-edit"></button></a>
								<a href="" data-target="#modal-delete-{{$red->idred}}" data-toggle="modal"><button class="btn btn-danger fa fa-trash-o"></button></a>
							</td>
						</tr>
					@include('localidad.red.modal')	
					@endforeach
				</table>
			</div>
			{{$redes->render()}}
		</div>
	</div>
@endsection