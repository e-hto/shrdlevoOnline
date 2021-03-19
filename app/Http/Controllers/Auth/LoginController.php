<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Auth;
use Illuminate\Http\Request;

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
    protected $redirectTo;
    public function redirectTo()
    {
        switch(Auth::user()->role){
            case "admin":
                $this->redirectTo = route('admin.game_dash.index');
                return $this->redirectTo;
                break;
            case "user":
                $this->redirectTo = route('client.home1');
                return $this->redirectTo;
                break;
            default:
                $this->redirectTo = '/login';
                return $this->redirectTo;
        }
    }
    public function logout(Request $request)
    {
        Auth::logout();
        \Session::flush();
        return redirect('/login');
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
//        $this->middleware('guest')->except('logout');
    }
}
