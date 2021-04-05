@extends('layouts.access')

@section('content')

<div class="banner">
    <img src="{{asset('login_assets/global_assets/images/at_banner.png')}}" alt="" class="banner_img">
</div>

<div class="formulario">
    <form class="login-form" method="POST" action="{{route('login')}}">
    {{csrf_field()}}
        <div class="card mb-0">
            <div class="card-body">
                <div class="text-center mb-3">
                    <!-- <i class="icon-reading icon-2x text-slate-300 border-slate-300 border-3 rounded-round p-3 mb-3 mt-1"></i> -->
                    <div class="logo">
                        <img src="{{asset('login_assets/global_assets/images/at_logo.jpg')}}" alt="" class="image">
                    </div>
                    <h2 class="mb-0"><strong>Sistema Hoja de Ruta</strong></h2>
                </div>
                <div class="form-group">
                        <input placeholder="Usuario" id="usuario" type="text" class="form-control @error('usuario') is-invalid @enderror" name="usuario" value="{{ old('usuario') }}"  autocomplete="usuario" autofocus>
                        @error('usuario')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                </div>
                <div class="form-group">
                <input placeholder="Password" id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password"  autocomplete="current-password">
                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                </div>
                <div class="form-group">
                    <button class="btn btn-at btn-block">Acceder <i class="icon-circle-right2 ml-2"></i></button>
                </div>
            </div>
        </div>
    </form>
</div>

<footer>
    <p class="copy">Software 3000 &copy; {{ date('Y') }}</p>
</footer>



@endsection
