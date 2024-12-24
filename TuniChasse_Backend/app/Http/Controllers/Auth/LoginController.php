<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;


class LoginController extends Controller
{  use AuthenticatesUsers;

     /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

        $this->middleware('guest:admin')->except('logout');

    }

    public function ShowloginAdmin()
    {
        return view('auth.login_admin');
    }

    public function loginAdmin(Request $request)
    {
        $this->validate($request, [
            'email'   => 'required|email',
            'password' => 'required|min:6',

        ]);
        if (Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password], $request->get('remember'))) {
            return redirect()->route('admin.index');
        }
        return back()->withInput($request->only('email'));
    }
    public function logout(Request $request)
    {


        if(Auth::guard('admin')->check()) { // this means that the admin was logged in.
            Auth::guard('admin')->logout();
            return redirect()->route('admin.login.show');

        }
        $this->guard()->logout();
        $request->session()->invalidate();




    }

}
