<div class="modal fade modal-slide-in-right fondo-window-transp" aria-hidden="true"
role="dialog" tabindex="-1" id="modal-delete-{{$red->idred}}">
	{{Form::Open(array('action'=>array('RedController@destroy',$red->idred),'method'=>'delete'))}}
	<div class="modal-dialog fondo-modal-transp">
		<div class="modal-content fondo-transp">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" 
				aria-label="Close">
                     <span aria-hidden="true" class="btn btn-danger">×</span>
                </button>
                <h4 class="modal-title texto-amc">Eliminar Red</h4>
			</div>
			<div class="modal-body">
				<p>Confirme si desea Eliminar la red</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-info" data-dismiss="modal">Cerrar</button>
				<button type="submit" class="btn btn-info">Confirmar</button>
			</div>
		</div>
	</div>
	{{Form::Close()}}

</div>