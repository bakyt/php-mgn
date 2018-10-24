<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Support\Facades\Validator;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
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
        $this->middleware('guest');
    }
    public function change(){
        if(!old("phone_number")) return abort(404);
        return view('auth.passwords.reset');
    }
    public function update(){
        if(!\request()->has("phone_number")) return abort(404);
        if(\request('password') and \request('password') != \request('password_confirmation')) return redirect()->back()->withInput()->withErrors(["password"=>trans('auth.passwords_not_match')]);
        User::all(['id','phone_number'])->where('phone_number', \request('phone_code').\request('phone_number'))->first()->update(['password'=>bcrypt(\request("password"))]);
        return redirect()->route('login')->with("success", trans('auth.password_resetting_success'));
    }
}
