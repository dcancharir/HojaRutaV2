@extends('layouts.app')

@section('content')

<div class="page-header page-header-light">
	<div class="page-header-content header-elements-md-inline">
		<div class="page-title d-flex">
			<h4><i class="icon-arrow-left52 mr-2"></i> <span class="font-weight-semibold">Ruta</h4>
			<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>

		</div>

	</div>
<!--
	<div class="container">
	<p><button class="mylocation">Show my location</button></p>
	<div id="out"></div>
	</div> -->

	<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
		<div class="d-flex">
			<div class="breadcrumb">
				<a href="#" class="breadcrumb-item">
					<i class="icon-home2 mr-2"></i> Seleccione Ruta
				</a>
			</div>
		</div>

	</div>
</div>
<div class="content">
	<div class="row">
		<div class="col-lg-4">
							<div class="card card-body border-top-primary">
								<div class="text-center">
									<h6 class="m-0 font-weight-semibold">Montos de Venta</h6>
			                        <button type="button" class="btn bg-success-400 btn-float"><i class="icon-spinner4 spinner"></i></button>
			                        <button type="button" class="btn btn-danger btn-float ruta" data-ruta="1" data-toggle="modal" data-target="#modal_default"><i class="icon-air icon-2x"></i></button>
			                        <button type="button" class="btn bg-indigo-400 btn-float"><i class="icon-spinner4 spinner"></i></button>
		                        </div>
							</div>
		</div>

		<div class="col-lg-4">
							<div class="card card-body border-top-primary">
								<div class="text-center">
									<h6 class="m-0 font-weight-semibold">Ultimas Visitas</h6>
			                        <button type="button" class="btn bg-success-400 btn-float"><i class="icon-spinner4 spinner"></i></button>
			                        <button type="button" class="btn btn-info btn-float ruta" data-ruta="2" data-toggle="modal" data-target="#modal_default"><i class="icon-air icon-2x"></i></button>
			                        <button type="button" class="btn bg-indigo-400 btn-float"><i class="icon-spinner4 spinner"></i></button>
		                        </div>
							</div>
		</div>

		<div class="col-lg-4">
							<div class="card card-body border-top-primary">
								<div class="text-center">
									<h6 class="m-0 font-weight-semibold">Frecuencia Semanal</h6>
			                        <button type="button" class="btn bg-success-400 btn-float"><i class="icon-spinner4 spinner"></i></button>
			                        <button type="button" class="btn btn-success btn-float ruta" data-ruta="3" data-toggle="modal" data-target="#modal_default"><i class="icon-air icon-2x"></i></button>
			                        <button type="button" class="btn bg-indigo-400 btn-float"><i class="icon-spinner4 spinner"></i></button>
								</div>
							</div>
		</div>
	</div>

	<div class="card">
		<div class="card-body">
			<table class="table datatable-rutas table-hover" id="table_rutas">
				<tbody><tr><td colspan="6"><div class="alert alert-warning alert-dismissible text-center">Cargando...</span></td></tr></tbody>
			</table>
		</div>
	</div>
</div>
<!-- Basic modal -->
<div id="modal_default" class="modal fade" tabindex="-1">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title">Tipo de Ruta</h5>
								<button type="button" class="close" data-dismiss="modal">&times;</button>
							</div>

							<div class="modal-body">
								<table id="Ruta-table" class="table table-bordered table-condensed table-hover">
									<thead>
										<tr>
											<th>Id</span></th>
											<th>Orden</th>
											<th>Tienda</th>
										</tr>
									</thead>
									<tbody id="tbody_Ruta"></tbody>
								</table>
							</div>
							<div class="modal-footer">
							<button id="AceparRuta" class="btn btn-danger">Selecciona Ruta</button>
							<button id="Cerrar" class="btn btn-primary">Cerrar</button>
							</div>


						</div>
					</div>
</div>
<!-- /basic modal -->
@endsection

