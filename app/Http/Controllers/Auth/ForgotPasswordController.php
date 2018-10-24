<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }
    public function phoneCheck(){
        if(!\request()->has('phone_number')) abort(404);
        if(!User::all()->where('phone_number', \request('phone_code').\request('phone_number'))->first()) return redirect()->back()->withErrors(trans('auth.phone_number_not_registered'));
        return redirect()->route('password.change')->withInput();
    }
}
