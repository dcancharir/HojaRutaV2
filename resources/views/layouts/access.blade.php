<!DOCTYPE html>
<html lang="en">

<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="csrf-token" content="{{ csrf_token() }}" />
	<title>Sistema Hoja Ruta</title>

	<!-- Global stylesheets -->
	<link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
	<link href="{{asset('login_assets/global_assets/css/icons/icomoon/styles.min.css')}}" rel="stylesheet" type="text/css">
	<link href="{{asset('login_assets/assets/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css">
	<link href="{{asset('login_assets/assets/css/bootstrap_limitless.min.css')}}" rel="stylesheet" type="text/css">
	<link href="{{asset('login_assets/assets/css/layout.min.css')}}" rel="stylesheet" type="text/css">
	<link href="{{asset('login_assets/assets/css/components.min.css')}}" rel="stylesheet" type="text/css">
	<link href="{{asset('login_assets/assets/css/colors.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('login_assets/assets/css/estilos.css')}}" rel="stylesheet" type="text/css">
    <!-- /global stylesheets -->
	@stack('styles')
</head>

<body class="bg-slate-800">
<input type="hidden" id="PathProyecto" value="{{Request::root()}}">
	<div class="page-content">
		<div class="content-wrapper">
			<div class="content d-flex justify-content-center align-items-center">
                @yield('content')
			</div>
		</div>
	</div>

	<!-- Core JS files -->
	<script src="{{asset('login_assets/global_assets/js/main/jquery.min.js')}}"></script>
	<script src="{{asset('login_assets/global_assets/js/main/bootstrap.bundle.min.js')}}"></script>
	<script src="{{asset('login_assets/global_assets/js/plugins/loaders/blockui.min.js')}}"></script>
	<!-- /core JS files -->

	<!-- Theme JS files -->

	<script src="{{asset('login_assets/global_assets/js/plugins/forms/styling/uniform.min.js')}}"></script>

	<script src="{{asset('login_assets/assets/js/app.js')}}"></script>
    <script src="{{asset('login_assets/global_assets/js/demo_pages/login.js')}}"></script>
	<!-- <script src="{{asset('login_assets/assets/js/general.js')}}"></script> -->
	@stack('js')
</body>
</html>
