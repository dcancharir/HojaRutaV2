@extends('layouts.app')

@section('content')

<div class="page-header page-header-light">
	<div class="page-header-content header-elements-md-inline">
		<div class="page-title d-flex" style="padding-top:10px;padding-bottom:10px;">
        <h4><i class="icon-arrow-left52 mr-2"></i> <span class="font-weight-semibold">Home</span> - Dashboard</h4>
			<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
		</div>

	</div>

</div>
<div class="content">
    <!-- Dashboard content -->
    <div class="row">
        <div class="col-xl-12">
            <!-- Marketing campaigns -->
            <div class="card">
                <div class="card-body d-md-flex align-items-md-center justify-content-md-between flex-md-wrap">
                    <div class="d-flex align-items-center mb-3 mb-md-0">
                    <a href="#" class="btn bg-transparent border-warning-400 text-warning-400 rounded-round border-2 btn-icon">
                            <i class="icon-watch"></i>
                        </a>
                        <div class="ml-3">
                            <h5 class="font-weight-semibold mb-0"><span id="reloj">16, 10:00 am</span></h5>
                            <span class="badge badge-mark border-warning mr-1"></span> <span class="font-weight-semibold mb-0">Hora</span>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mb-3 mb-md-0">
                        <a href="#" class="btn bg-transparent border-primary-400 text-primary-400 rounded-round border-2 btn-icon">
                            <i class="icon-trophy3"></i>
                        </a>
                        <div class="ml-3">
                            <h5 class="font-weight-semibold mb-0"><span id="totalTiendas">1</span></h5>
                            <span class="font-weight-semibold mb-0">Total Tiendas</span>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mb-3 mb-md-0">
                        <a href="#" class="btn bg-transparent border-success-400 text-success-400 rounded-round border-2 btn-icon">
                            <i class="icon-checkmark3"></i>
                        </a>
                        <div class="ml-3">
                            <h5 class="font-weight-semibold mb-0"><span id="totalVisitadas">1</span></h5>
                            <span class="font-weight-semibold mb-0">Visitadas</span>
                        </div>
                    </div>

                    <div class="d-flex align-items-center mb-3 mb-md-0">
                        <a href="#" class="btn bg-transparent border-danger-400 text-danger-400 rounded-round border-2 btn-icon">
                            <i class="icon-cross2"></i>
                        </a>
                        <div class="ml-3">
                            <h5 class="font-weight-semibold mb-0"><span id="totalPendientes">1</span></h5>
                            <span class="font-weight-semibold mb-0">Pendientes</span>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mb-3 mb-md-0">

                        <div class="ml-3">
                            <a href="javascript:void(0)" class="btn btn-outline-danger" id="btnNuevoDetalleRuta">Nueva Tienda</a>
                        </div>
                    </div>

                </div>
                <div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
                    <div class="d-flex">
                        <div class="breadcrumb">
                            <a href="#" class="breadcrumb-item font-weight-semibold">
                                <i class="icon-home2 mr-2"></i> Tiendas a visitar hoy - {{ date('Y-m-d') }}
                            </a>
                        </div>
                    </div>

                </div>
                <div class="table-responsive">
                    <table class="table text-nowrap table-sm table-striped" id="tableDetalleRuta">
                        <thead>
                            <tr>
                                <th>Orden</th>
                                <th>Tienda</th>
                                <th>Dirección</th>
                                <th>Tipo</th>
                                <th>Observación</th>
                                <th >Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    1
                                </td>
                                <td><span class="font-weight-semibold mb-0">Mintlime</span></td>
                                <td><span class="">Direccion Tienda</td>
                                <td><h6 class="text-success-600 badge bg-blue">Sistema</h6></td>
                                <td><span class="text-muted">Active</span></td>
                                <td>
                                    <a href="javascript:void(0);" class="btn btn-sm bg-teal-400">Visitar</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- /marketing campaigns -->
            <!-- Maps section -->
            <div class="card">
                <div id="map" style="height: 50vh;">
                </div>
            </div>
            <!-- /Maps section -->

        </div>
    </div>
				<!-- /dashboard content -->
</div>

<!-- Modal Visita -->
<div id="modalVisita" class="modal fade" tabindex="-1">
					<div class="modal-dialog modal-dialog-scrollable">
						<div class="modal-content">
							<div class="modal-header">
								<h3 class="modal-title">Agregar Visita a : <span id="nombreTienda"></span></h3>
								<button type="button" class="close" data-dismiss="modal">&times;</button>
							</div>

							<div class="modal-body">
							<input type="hidden" name="ruta_id" id="ruta_id" />
							<input type="hidden" name="detalle_ruta_id" id="detalle_ruta_id" />
							<input type="hidden" name="nombre_tienda" id="nombre_tienda" />
							<input type="hidden" name="distancia" id="distancia" />
							<form id="frmVisitaForm" class="" method="post">
								<div id="seccionPreguntas">
								<h3>Responsabilidad del Cajero</h3>
								<div id="responsabilidadCajero">

								</div>
								<h3>Responsabilidad del Supervisor</h3>
								<div id="responsabilidadSupervisor">

								</div>
								<!-- <div class="form-group row"><label class="col-md-12 font-weight-semibold">Pregunta 1</label>
		                        	<div class="col-md-4">
			                            <select class="form-control">
			                                <option value="opt1">Seleccione</option>
			                                <option value="opt2">Si</option>
			                                <option value="opt3">No</option>
			                                <option value="opt4">No Aplica</option>
			                            </select>
									</div>
									<div class="col-md-4">
										<input type="button" data-id="1" class="btn btn-primary btn_observacion" value="Agregar Observacion"/>
									</div>
									<hr>
									<div class="col-md-12" style="display:none" id="observacion1">
										<input type="text" placeholder="Observacion" class="form-control" name="observacion1">
									</div>
								</div> -->
								</div>
								</form>

							</div>
							<div class="modal-footer">
							<button type="button" class="btn btn-primary" id="btnVisitar">Visitar</button>
							<button type="button" class="btn btn-danger" id="btnCancelarVisita">Cancelar</button>
							</div>

						</div>
					</div>
				</div>
<!-- /basic modal -->
<!-- Basic modal -->
<div id="modalNuevoDetalleRuta" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-full modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-semibold mb-0">Agregar Nueva Tienda a Visitar</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>

            </div>

            <div class="modal-body">
                <button type="button" class="btn btn-block btn-danger" id="btnGuardarDetalles">Agregar Tiendas</button>
                <hr>
                <h6 class="font-weight-semibold">Seleccione las tiendas que desee agregar para la ruta del día y agregue una observación del porqué se estan agregando.</h6>
                <h6 class="font-weight-semibold">Luego haga click en el boton "Agregar Tiendas"</h6>
                <hr>
                <div class="form-group">
                    <label for="observaciondetalle">Observación</label>
                    <input type="text" class="form-control" id="observaciondetalle" name="observaciondetalle" placeholder="Observacion">
                </div>
                <hr>
                <h6 class="font-weight-semibold">Tiendas Disponibles</h6>
                <div class="table-responsive">
                    <table id="Tiendas-table" class="table table-sm text-nowrap">
                                <thead>
                                    <tr>
                                        <th width="10%">Seleccionar</th>
                                        <th width="10%">CC</th>
                                        <th width="40%">Tienda</th>
                                        <th width="400%">Direccion</th>
                                    </tr>
                                </thead>
                                <tbody id="tbodyTiendas"></tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>
<!-- /basic modal -->
@stop

@push('js')
<script>
	var distancia_aceptada={!! ENV('DISTANCIA_ACEPTADA','20') !!};
</script>
<script src="{{asset('/template_assets/viewjs/Home/Home.js')}}"></script>
@endpush

