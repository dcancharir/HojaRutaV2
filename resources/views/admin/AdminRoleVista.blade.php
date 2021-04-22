@extends('layouts.app')

@section('content')
<!-- Page header -->
<div class="page-header page-header-light">
	<div class="page-header-content header-elements-md-inline">
		<div class="page-title d-flex" style="padding-top:0;padding-bottom:0;">
			<h4><i class="icon-arrow-left52 mr-2"></i> <span class="font-weight-semibold">Roles</h4>
			<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
		</div>

		<div class="header-elements d-none">
			<div class="d-flex justify-content-center">
				<a href="#" class="btn btn-link btn-float text-danger btnRecargar">
					<i class="icon-reset text-danger"></i><span>Recargar</span>
				</a>
			</div>
            <div class="d-flex justify-content-center">
				<a href="#" class="btn btn-link btn-float text-danger btnNuevo">
					<i class="icon-add text-danger"></i><span>Nuevo</span>
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
					Roles
				</a>
				<span class="breadcrumb-item active">Listado</span>

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
	<!-- Basic datatable -->
	<div class="card">
		<div class="card-body">
			<table class="datatableRole table table-xs text-nowrap table-hover" style="width:100%">
				<tbody><tr><td colspan="6"><div class="alert alert-warning alert-dismissible text-center">Cargando...</span></td></tr></tbody>
			</table>
		</div>
	</div>
	<!-- /basic datatable -->
</div>
<!-- /content area -->
<!-- Modal Rol -->
<div id="modalRole" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-sm modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title"><span id="titulo"></span></h3>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">
            <form id="frmRoleForm" class="form form-group" method="post">
                <input type="hidden" name="role_id" id="role_id" value="0" />
                <div class="row">
                    <div class="col-12">
                        <label>Nombre Rol</label>
                        <div class="form-group form-group-feedback form-group-feedback-right">
                            <input class="form-control input-sm" id="name" name="name" value="" type="text">
                        </div>
                    </div>
                </div>
            </form>
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-primary" id="btnGuardar">Guardar</button>
            <button type="button" class="btn btn-danger" id="btnCancelar">Cancelar</button>
            </div>

        </div>
    </div>
</div>
<!-- /basic modal -->
@stop

@push('js')
<script src="{{asset('/template_assets/viewjs/Admin/AdminRoleVista.js')}}"></script>
@endpush
