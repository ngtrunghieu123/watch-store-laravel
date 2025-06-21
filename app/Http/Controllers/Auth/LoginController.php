<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Log;


class LoginController extends Controller
{
    /*
    |----------------------------------------------------------------------
    | Login Controller
    |----------------------------------------------------------------------
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
    protected function redirectTo()
    {
        if (Auth::user()->role == 'admin') {
            return route('admin.home');
        }
        if (Auth::user()->role == 'user') {
            Cart::restore(Auth::user()->id);
            Cart::store(Auth::user()->id);
            return route('user.home');
        }
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    /**
     * Determine which field to use for login.
     *
     * @return string
     */
    public function username()
    {
        $identity = request()->get('email');
        if (is_numeric($identity))
            $fieldName = 'phone';
        elseif (filter_var($identity, FILTER_VALIDATE_EMAIL))
            $fieldName = 'email';
        else
            $fieldName = 'username';
        request()->merge([$fieldName => $identity]);
        return $fieldName;
    }

    /**
     * Handle the user authentication.
     *
     * @param  Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    // Xử lý sau khi đăng nhập thành công
}
