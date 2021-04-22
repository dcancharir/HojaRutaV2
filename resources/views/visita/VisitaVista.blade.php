@extends('layouts.app')

@section('content')
<!-- Page header -->
<div class="page-header page-header-light">
	<div class="page-header-content header-elements-md-inline">
		<div class="page-title d-flex" style="padding-top:0;padding-bottom:0;">
			<h4><i class="icon-arrow-left52 mr-2"></i> <span class="font-weight-semibold">Visita</h4>
			<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
		</div>

		<div class="header-elements d-none">
			<div class="d-flex justify-content-center">
				<a href="#" class="btn btn-link btn-float text-danger btn_recargar">
					<i class="icon-reset text-danger"></i><span>Recargar</span>
				</a>
			</div>
		</div>
	</div>

	<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
		<div class="d-flex">
			<div class="breadcrumb">
				<a href="#" class="breadcrumb-item">
					<i class="icon-home2 mr-2"></i> Inicio
				</a>
				<a href="#" class="breadcrumb-item">
					Visita
				</a>
				<span class="breadcrumb-item active">Listado</span>

			</div>
			<a href="#" class="header-elements-toggle text-default d-md-none">
					<i class="icon-more"></i>
				</a>
		</div>
		<!-- <div class="header-elements d-none">
			<div class="breadcrumb justify-content-center">
				<div class="breadcrumb-elements-item dropdown p-0">
					<a href="#" class="breadcrumb-elements-item dropdown-toggle" data-toggle="dropdown">
						<i class="icon-gear mr-2"></i>
						Acciones
					</a>

					<div class="dropdown-menu dropdown-menu-right">
						<a id="btn_excel" href="#" class="dropdown-item">
							<i class="icon-file-excel"></i> Excel
						</a>
						<a id="btn_pdf" href="#" class="dropdown-item">
						<i class="icon-file-pdf"></i> Pdf o   <i class="icon-printer"></i>
						</a>

					</div>
				</div>
			</div>
		</div> -->
	</div>
</div>
<!-- /page header -->


<!-- Content area -->
<div class="content">
     <!-- Form Section -->
     <div class="card">
		<div class="card-body">
            <form>
				@csrf
				<div class="form-group">
					<div class="row">
						<div class="col-4">
							<label>Tienda</label>
							<div class="form-group form-group-feedback form-group-feedback-right">
								<select name="cboTienda" id="cboTienda" class="form-control input-sm"></select>
							</div>
						</div>
						<div class="col-4">
							<label>Fecha Inicio</label>
							<div class="form-group form-group-feedback form-group-feedback-right">
								<input class="form-control input-sm" id="fechaInicio" name="fechaInicio" value="" type="text">
							</div>
						</div>
						<div class="col-4">
							<label>Fecha Final</label>
							<div class="form-group form-group-feedback form-group-feedback-right">
								<input class="form-control input-sm" id="fechaFin" name="fechaFin" value="" type="text">
							</div>
						</div>
					</div>
				</div>
				<div class="">
					<div class="row">
						<div class="col-12 text-right">
							<input id="btnBuscar" type="button" class="col-12 btn btn-danger" value="BUSCAR">
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
    <!-- /Form Section -->
    <div id="contenedorTable">
	<!-- Basic datatable -->
	<!-- <div class="card">
		<div class="card-body">
			<table class="datatableVisita table table-xs table-hover" style="width:100%">
				<tbody><tr><td colspan="6"><div class="alert alert-warning alert-dismissible text-center">Cargando...</span></td></tr></tbody>
			</table>
		</div>
	</div> -->
	<!-- /basic datatable -->

    </div>

</div>
<!-- /content area -->
<!-- Modal Respuestas por Visita -->
<div id="modalRespuestas" class="modal fade" tabindex="-1">
	<div class="modal-dialog modal-dialog-scrollable">
		<div class="modal-content">
			<div class="modal-header">
				<h3 class="modal-title">Detalle de Visita a: <span id="nombreTienda"></span></h3>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>

			<div class="modal-body">
		    <form id="frmVisitaForm" class="" method="post">
				<input type="hidden" name="visita_id" id="visita_id" />
				<div id="seccionPreguntas">
					<h3>Responsabilidad del Cajero</h3>
					<div id="responsabilidadCajero">

					</div>
					<h3>Responsabilidad del Supervisor</h3>
					<div id="responsabilidadSupervisor">

					</div>
				</div>
            </form>

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-warning" id="btnPdfRespuestas">Pdf</button>
				<button type="button" class="btn btn-danger" id="btnExcelRespuestas">Excel</button>
                <button type="button" class="btn btn-outline-danger" id="btnCerrarModal">Cerrar</button>
			</div>

		</div>
	</div>
</div>
<!-- /basic modal -->
@stop

@push('js')
<script src="{{asset('/template_assets/viewjs/Visita/VisitaVista.js')}}"></script>
@endpush
