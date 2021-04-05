<?php

namespace App\Http\Controllers\Auth;

use App\Supervisor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

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
    protected $redirectTo = '/';

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
        // $credentials =$request->only('usuario','password');

        // $credentials=$this->validate(request(),[
        //     'usuario'=>'required|string',
        //     'password'=>'required|string'
        // ]);
        $credentials=$request->validate([
            'usuario'=> 'required|string',
            'password' => 'required|string',
        ]);
        $supervisor=new Supervisor();
        $supervisor->usuario=$credentials['usuario'];
        Auth::login($supervisor);
        if($credentials){
            $request->session()->regenerate();
            $request->session()->put('authenticated', time());
            return redirect('home')->header('Cache-Control', 'no-store, no-cache, must-revalidate');;
        }
        return redirect('login');
    }

}
