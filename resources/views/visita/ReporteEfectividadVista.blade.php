@extends('layouts.app')

@section('content')
<!-- Page header -->
<div class="page-header page-header-light">
	<div class="page-header-content header-elements-md-inline">
		<div class="page-title d-flex" style="padding-top:0;padding-bottom:0;">
			<h4><i class="icon-arrow-left52 mr-2"></i> <span class="font-weight-semibold">Efectividad</h4>
			<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
		</div>

		<div class="header-elements d-none">
			<div class="d-flex justify-content-center">
				<a href="#" class="btn btn-link btn-float text-danger btn_recargar">
					<i class="icon-reset text-danger"></i><span>Recargar</span>
				</a>
			</div>
            <div class="d-flex justify-content-center">
				<a href="#" class="btn btn-link btn-float text-danger btnMostrarChart">
					<i class="icon-stats-dots text-danger"></i><span>Chart</span>
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
					Menus
				</a>
				<span class="breadcrumb-item active">Efectividad</span>

			</div>
			<a href="#" class="header-elements-toggle text-default d-md-none">
					<i class="icon-more"></i>
				</a>
		</div>
		<div class="header-elements d-none">
			<div class="breadcrumb justify-content-center">
				<div class="breadcrumb-elements-item dropdown p-0">
					<a href="#" class="breadcrumb-elements-item dropdown-toggle" data-toggle="dropdown">
						<i class="icon-gear mr-2"></i>
						Acciones
					</a>
					<div class="dropdown-menu dropdown-menu-right">
						<!-- <a id="btn_imprimir" href="#" class="dropdown-item">
							<i class="icon-printer"></i> Imprimir
						</a> -->
						<a id="btn_excel" href="#" class="dropdown-item">
							<i class="icon-file-excel"></i> Excel
						</a>
						<a id="btn_pdf" href="#" class="dropdown-item">
						<i class="icon-file-pdf"></i> Pdf o   <i class="icon-printer"></i>
						</a>

					</div>
				</div>
			</div>
		</div>
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
						<div class="col-6">
							<label>Fecha Inicio</label>
							<div class="form-group form-group-feedback form-group-feedback-right">
								<input class="form-control input-sm" id="fechaInicio" name="fechaInicio" value="" type="text">
							</div>
						</div>
						<div class="col-6">
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
    <!-- <button type="submit" class="btn btn-danger btnMostrarChart" style="display:none">Grafico<i class="icon-paperplane ml-2"></i></button> -->
    <!-- /Form Section -->
	<!-- Basic datatable -->
    <div id="contenedorTable">

    </div>
	<!-- /basic datatable -->
</div>
<!-- /content area -->

<!-- Modal -->
<div id="modalChart" class="modal fade" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Grafico Reporte Efectividad</h5>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>

			<div class="modal-body">
				<!-- Basic line -->
				<div class="card">
					<div class="card-body">
						<div class="chart-container">
							<div class="chart has-fixed-height" id="lineBasic"></div>
						</div>
					</div>
				</div>
				<!-- /basic line -->
			</div>

		</div>
	</div>
</div>
<!-- End Modal -->
@stop

@push('js')
<script src="{{asset('/template_assets/viewjs/Visita/ReporteEfectividad.js')}}"></script>
@endpush
