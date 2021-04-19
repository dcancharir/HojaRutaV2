<!DOCTYPE html>
<html lang="en">

<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="csrf-token" content="{{ csrf_token() }}" />
	<title>Apuesta Total </title>

	<!-- Global stylesheets -->
	<!-- 	<link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css"> -->
	<link href="{{asset('template_assets/global_assets/css/icons/icomoon/styles.min.css')}}" rel="stylesheet" type="text/css">
	<link href="{{asset('template_assets/assets/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css">
	<link href="{{asset('template_assets/assets/css/bootstrap_limitless.min.css')}}" rel="stylesheet" type="text/css">
	<link href="{{asset('template_assets/assets/css/layout.min.css')}}" rel="stylesheet" type="text/css">
	<link href="{{asset('template_assets/assets/css/components.min.css')}}" rel="stylesheet" type="text/css">
	<link href="{{asset('template_assets/assets/css/colors.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('template_assets/assets/css/jquery-confirm.css')}}" rel="stylesheet" type="text/css">
	<link href="{{asset('template_assets/assets/css/bootstrap-datetimepicker.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('template_assets/assets/css/estilos.css')}}" rel="stylesheet" type="text/css">

    <script src="{{asset('template_assets/global_assets/js/main/jquery.min.js')}}"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCs0Hfar36tnZyuPv5AkXPu4EFO40FUJAE"></script>
	<!-- /global stylesheets -->
	@stack('styles')
</head>

<body class="navbar-top">
	<input type="hidden" id="PathProyecto" value="{{Request::root()}}">
	<!-- Main navbar -->
	<div class="navbar navbar-expand-md navbar-dark fixed-top">
		<div class="navbar-brand">
			{{--<span>Sistema<em>Hoja de Ruta</em></span>--}}
			<a href="/" class="d-inline-block">
				<img src="{{asset('template_assets/global_assets/images/logo_light.png')}}" alt="">
			</a>
		</div>

		<div class="d-md-none">
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-mobile">
				<i class="icon-tree5"></i>
			</button>
			<button class="navbar-toggler sidebar-mobile-main-toggle" type="button">
				<i class="icon-paragraph-justify3"></i>
			</button>
		</div>

		<div class="collapse navbar-collapse" id="navbar-mobile">
			<ul class="navbar-nav">
				<li class="nav-item">
					<a href="#" class="navbar-nav-link sidebar-control sidebar-main-toggle d-none d-md-block">
						<i class="icon-paragraph-justify3"></i>
					</a>
				</li>

			</ul>

			<span class="ml-md-3 mr-md-auto"></span>

			<ul class="navbar-nav">

				<li class="nav-item dropdown dropdown-user">
					<a href="#" class="navbar-nav-link d-flex align-items-center dropdown-toggle" data-toggle="dropdown">
						<img src="{{asset('template_assets/global_assets/images/demo/users/user.jpg')}}" class="rounded-circle mr-2" height="34" alt="">
						@if(request()->session()->has('supervisor_id'))
						<span><strong>{{auth()->user()->usuario}}</strong></span>
						<!-- <input type="hidden" id="user_id" value="{{ request()->session()->get('supervisor_id')}}"> -->
						@else
						<span><strong>Invitado</strong></span>
						@endif
					</a>

					<div class="dropdown-menu dropdown-menu-right">
						<!-- <a href="#" class="dropdown-item"><i class="icon-user-plus"></i> Cuenta</a>
						<a href="#" class="dropdown-item"><i class="icon-coins"></i> Mi Tareaje</a>
						<div class="dropdown-divider"></div>
						<a href="#" class="dropdown-item"><i class="icon-cog5"></i> Configuracion</a> -->
						<!-- <a href="#" id="btnCerrarSesion" class="dropdown-item"><i class="icon-switch2"></i> Salir</a> -->
						<form action="{{route('logout')}}" method="POST">
							{{csrf_field()}}
							<button class="dropdown-item"><i class="icon-switch2"></i>Cerrar Sesion</button>
						</form>
					</div>
				</li>
			</ul>
		</div>
	</div>
	<!-- /main navbar -->


	<!-- Page content -->
	<div class="page-content">

		<!-- Main sidebar -->
		<div class="sidebar sidebar-dark sidebar-main sidebar-expand-md">

			<!-- Sidebar mobile toggler -->
			<div class="sidebar-mobile-toggler text-center">
				<a href="#" class="sidebar-mobile-main-toggle">
					<i class="icon-arrow-left8"></i>
				</a>
				Navigation
				<a href="#" class="sidebar-mobile-expand">
					<i class="icon-screen-full"></i>
					<i class="icon-screen-normal"></i>
				</a>
			</div>
			<!-- /sidebar mobile toggler -->


			<!-- Sidebar content -->
			<div class="sidebar-content">

				<!-- User menu -->
				<div class="sidebar-user">
					<div class="card-body">
						<div class="media">
							<div class="mr-3">
								<a href="#"><img src="{{asset('template_assets/global_assets/images/demo/users/user.jpg')}}" width="38" height="38" class="rounded-circle" alt=""></a>
							</div>

							<div class="media-body">
								<div class="media-title font-weight-semibold">{{auth()->user()->nombres}}</div>
								<div class="font-size-xs opacity-50">
									<i class="icon-pin font-size-sm"></i> &nbsp; <span id="address"></span>
								</div>
							</div>

							<div class="ml-3 align-self-center">
								<a href="#" class="text-white"><i class="icon-cog3"></i></a>
							</div>
						</div>
					</div>
				</div>
				<!-- /user menu -->


				<!-- Main navigation -->
				<div class="card card-sidebar-mobile">
					<ul class="nav nav-sidebar" data-nav-type="accordion">

						<!-- Main -->
						<li class="nav-item-header"><div class="text-uppercase font-size-xs line-height-xs">Menu</div> <i class="icon-menu" title="Main"></i></li>

                        @role('supervisor')
                        	<li class="nav-item">
							<a href="/home" class="nav-link">
								<i class="icon-home4"></i>
								<span>
									Inicio
								</span>
							</a>
						</li>
                        <li class="nav-item nav-item-submenu">
							<a href="#" class="nav-link"><i class="icon-copy"></i> <span>Menus</span></a>
							<ul class="nav nav-group-sub" data-submenu-title="Menus">
                                <li class="nav-item"><a href="{{route('Tienda')}}" class="nav-link">Tiendas</a></li>
                                <li class="nav-item"><a href="{{route('Ruta')}}" class="nav-link">Hist. de Rutas</a></li>
                                <li class="nav-item"><a href="{{route('Visita')}}" class="nav-link">Reporte Visitas</a></li>
                                <li class="nav-item"><a href="{{route('ReporteEfectividad')}}" class="nav-link">Reporte Efectividad</a></li>
							</ul>
						</li>
                        @else
                        <li class="nav-item nav-item-submenu">
							<a href="#" class="nav-link"><i class="icon-copy"></i> <span>Admin</span></a>
							<ul class="nav nav-group-sub" data-submenu-title="Admin">
							    <li class="nav-item"><a href="{{route('AdminTienda')}}" class="nav-link">Tiendas</a></li>
                                <li class="nav-item"><a href="{{route('AdminSupervisores')}}" class="nav-link">Supervisores</a></li>
							</ul>
						</li>
                        @endrole
						<!-- /main -->

					</ul>
				</div>
				<!-- /main navigation -->

			</div>
			<!-- /sidebar content -->

		</div>
		<!-- /main sidebar -->


		<!-- Main content -->
		<div class="content-wrapper">

				@yield('content')

			<!-- Footer -->
			<div class="navbar navbar-expand-lg navbar-light">
				<div class="text-center d-lg-none w-100">
					<button type="button" class="navbar-toggler dropdown-toggle" data-toggle="collapse" data-target="#navbar-footer">
						<i class="icon-unfold mr-2"></i>
						Software3000
					</button>
				</div>

				<div class="navbar-collapse collapse" id="navbar-footer">
					<span class="navbar-text">
						&copy; {{date('Y')}} <a href="#">Software3000.net</a>
					</span>
				</div>
			</div>
			<!-- /footer -->

		</div>

		<!-- /main content -->

	</div>
	<!-- /page content -->

	<!-- Core JS files -->

	<script src="{{asset('template_assets/global_assets/js/main/bootstrap.bundle.min.js')}}"></script>
	<script src="{{asset('template_assets/global_assets/js/plugins/loaders/blockui.min.js')}}"></script>
	<!-- /core JS files -->

	<!-- Theme JS files -->
	<script src="{{asset('template_assets/global_assets/js/plugins/visualization/d3/d3.min.js')}}"></script>
	<script src="{{asset('template_assets/global_assets/js/plugins/visualization/d3/d3_tooltip.js')}}"></script>
	<script src="{{asset('template_assets/global_assets/js/plugins/forms/styling/switchery.min.js')}}"></script>
    <script src="{{asset('template_assets/global_assets/js/plugins/forms/selects/select2.min.js')}}"></script>
	<script src="{{asset('template_assets/global_assets/js/plugins/forms/selects/bootstrap_multiselect.js')}}"></script>
	<script src="{{asset('template_assets/global_assets/js/plugins/forms/styling/uniform.min.js')}}"></script>
	<script src="{{asset('template_assets/global_assets/js/plugins/ui/moment/moment.min.js')}}"></script>
	<script src="{{asset('template_assets/global_assets/js/plugins/pickers/daterangepicker.js')}}"></script>
	<script src="{{asset('template_assets/global_assets/js/plugins/notifications/noty.min.js')}}"></script>
	<script src="{{asset('template_assets/global_assets/js/plugins/tables/datatables/datatables.min.js')}}"></script>
	<script src="{{asset('template_assets/global_assets/js/plugins/forms/validation/validate.min.js')}}"></script>
    <script src="{{asset('template_assets/global_assets/js/plugins/visualization/echarts/echarts.min.js')}}"></script>
	<script src="{{asset('template_assets/global_assets/js/plugins/pickers/bootstrap-datetimepicker.min.js')}}"></script>
	<script src="{{asset('template_assets/assets/js/jquery-confirm.js')}}"></script>
    <!-- <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script> -->
	<script src="{{asset('template_assets/assets/js/app.js')}}"></script>
    <script src="{{asset('template_assets/generalTemplate.js')}}"></script>
    <script src="{{asset('template_assets/assets/js/geolocalizacion.js')}}"></script>

	@stack('js')
</body>
</html>

