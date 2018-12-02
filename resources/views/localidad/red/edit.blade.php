@extends ('layouts.admin')
@section ('contenido')
	<div class="row">
		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
			<h3>Editar Red: {{$red->nombre}}</h3>
			@if(count($errors)>0)
				<div class="alert alert.danger">
					<ul>
						@foreach($errors->all() as $error)
							<li>{{$error}}</li>
						@endforeach
					</ul>
				</div>
			@endif
				
			{!!Form::model($red,['method'=>'PATCH','route'=>['localidad.red.update',$red->idred]])!!}
			{{Form::token()}}
			<div class="form-group">
				<label for="nombre">Nombre</label>
				<input type="text" name="nombre" class="form-control" value="{{$red->nombre}}" placeholder="Nombre..">
			</div>
			<div class="form-group">
				<label for="abrev">Abrev</label>
				<input type="text" name="abrev" class="form-control" value="{{$red->abrev}}" placeholder="Abrev">
			</div>
			
			<div class="form-group">
				<button class="btn btn-primary" type="submit">Guardar</button>
				<button type="reset" class="btn btn-danger">Cancelar</button>
			</div>
			{!!Form::close()!!}
		</div>
	</div>
@endsection