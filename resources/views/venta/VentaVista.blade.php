@extends('layouts.app')

@section('content')
<!-- Page header -->
<div class="page-header page-header-light">
	<div class="page-header-content header-elements-md-inline">
		<div class="page-title d-flex" style="padding-top:0;padding-bottom:0;">
			<h4><i class="icon-arrow-left52 mr-2"></i> <span class="font-weight-semibold">Ventas Tienda</h4>
			<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
		</div>

		<div class="header-elements d-none">
			<div class="d-flex justify-content-center">
				<a href="#" class="btn btn-link btn-float text-danger btnRegresar">
					<i class="icon-arrow-left7 text-danger"></i> <span>Regresar</span>
				</a>
				<a href="#" class="btn btn-link btn-float text-danger btnRecargar">
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
					Tienda
				</a>
				<span class="breadcrumb-item active">Ventas</span>

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
						<a href="#" id="btnExcel" class="dropdown-item">
							<i class="icon-file-excel"></i> Excel
						</a>
						<a href="#" id="btnPdf" class="dropdown-item">
						<i class="icon-file-pdf"></i> Pdf o   <i class="icon-printer"></i>
						</a>

					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- /page header -->
<input type="hidden" id="txt_tienda_id" name="id" value="{{$tienda_id}}">

<!-- Content area -->
<div class="content">
	<!-- Basic datatable -->
	<div class="card">
		<div class="card-body">
			<table class="datatable-venta table table-xs table-hover" style="width:100%">
				<tbody><tr><td colspan="6"><div class="alert alert-warning alert-dismissible text-center">Cargando...</span></td></tr></tbody>
			</table>
		</div>
	</div>
	<!-- /basic datatable -->
</div>
<!-- /content area -->
@stop

@push('js')
<script src="{{asset('/template_assets/viewjs/Venta/VentaVista.js')}}"></script>
@endpush
