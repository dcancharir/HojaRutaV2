<?php

namespace App\Http\Controllers\Auth;

use App\Supervisor;
use App\ApiApuestaTotal;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Hash;
class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    public function username(){
        return 'usuario';
    }
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
            return redirect('/home');
        }
        return $next($request);
    }
    public function login(Request $request){
        $request->session()->flush();
        $request->session()->regenerate();

        $credentials=$this->validate(request(),[
            'usuario'=>'required|string',
            'password'=>'required|string'
        ]);
        //validacion de API
        $apiApuesta = new ApiApuestaTotal();
        $respuesta_api = $apiApuesta->ValidarLoginTokenApi($credentials['usuario'], $credentials['password']);
        $respuesta_api = (string)$respuesta_api;
        $respuesta = json_decode($respuesta_api,true);
        if($respuesta){
            if($respuesta['success']){
                request()->session()->put(['tokenApuesta'=>$respuesta["token"]]);
                $supervisor=Supervisor::where('usuario',$credentials['usuario'])->first();
                if($supervisor){
                    $supervisor->password=bcrypt($credentials['password']);
                    $supervisor->save();
                    if(Auth::attempt($credentials)){
                        request()->session()->put(['supervisor_id'=>$supervisor->supervisor_id]);
                    }
                    else{
                        return back()->withErrors(['usuario'=>trans('auth.failed')])
                                ->withInput(request(['usuario']));
                    }
                }
                else{
                    $usuarioAPI=$respuesta['user'];
                    $supervisor=new Supervisor();
                    $supervisor->usuario=$credentials['usuario'];
                    $supervisor->password=bcrypt($credentials['password']);
                    $supervisor->nombres=$usuarioAPI["nombres"]." ".$usuarioAPI["apellidos"];
                    $supervisor->estado=1;
                    $supervisor->save();
                    Auth::attempt($credentials);
                    $user=auth()->user();
                    request()->session()->put(['supervisor_id'=>$user->supervisor_id]);
                }
                return redirect()->intended('/');
            }
            else{
                return back()->withErrors(['usuario'=>trans('auth.failed')])
                ->withInput(request(['usuario']));
            }
        }
        else{
            return back()->withErrors(['usuario'=>trans('auth.failed')])
            ->withInput(request(['usuario']));
        }
    }
    public function logout(Request $request)
    {
        $request->session()->flush();
        $request->session()->regenerate();
        return redirect('/');
    }
}
