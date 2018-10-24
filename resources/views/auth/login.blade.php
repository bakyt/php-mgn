@extends('layouts.app')
@section('php')
    @php $title = trans('auth.sign_in') @endphp
@endsection
@section('content')
    <div class="row">
        <div class="login-box box box-primary">
            <div class="box-header">
                <i class="fa fa-sign-in"></i>
            </div>
            <div class="box-body">
                <form action="{{ route("login")."?redirect=".request()->get("redirect") }}" method="post" id="login-form">
                    {{ csrf_field() }}
                    <div class="form-group has-feedback">
                        <label>{{ trans('auth.phone_number') }}</label>
                        <div class="input-group">
                            <span class="input-group-btn"><select id="phone-code" name="phone_code" class="form-control" style="width:auto;padding-right:0"><option value="996">+996</option></select></span>
                            <input required id="phone-number-only" maxlength="9" type="text" name="phone_number_only" class="form-control{{ $errors->has('phone_number') ? ' has-error' : '' }}" src="{{ trans('auth.example') }}" placeholder="{{ trans('auth.example') }}: 702772317">
                            <input type="hidden" id="phone-number" name="phone_number">
                            <span class="glyphicon glyphicon-phone form-control-feedback"></span>
                        </div>
                    </div>
                    <div class="form-group has-feedback{{ $errors->has('password') ? ' has-error' : '' }}">
                        <input required name="password" type="password" class="form-control" placeholder="{{ trans('auth.password') }}">
                        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                    </div>
                    <div class="row">
                        <div class="col-xs-7">
                            <div class="checkbox icheck">
                                <label>
                                    <input type="checkbox"  name="remember"> {{ trans('auth.remember_me') }}
                                </label>
                            </div>
                        </div>
                        <!-- /.col -->
                        <div class="col-md-5">
                            <button type="submit" class="btn btn-primary btn-flat pull-right">{{ trans("auth.enter") }}</button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>

                {{--<div class="social-auth-links text-center">--}}
                    {{--<p>- {{ trans('auth.or') }}-</p>--}}
                    {{--<a href="#" class="btn btn-block btn-social btn-facebook btn-flat"><i class="fa fa-facebook"></i> {{ trans('auth.sign_in_using',['messenger'=>'Facebook']) }}--}}
                        {{--</a>--}}
                    {{--<a href="#" class="btn btn-block btn-social btn-google btn-flat"><i class="fa fa-google-plus"></i> {{ trans('auth.sign_in_using',['messenger'=>'Google+']) }}--}}
                        {{--</a>--}}
                {{--</div>--}}
                <!-- /.social-auth-links -->

                <a href="{{ route('password.request') }}">{{ trans('auth.forgot_password') }}</a><br>
                <a href="{{ route('register') }}">{{ trans('auth.sign_up') }}</a>

            </div>
            <!-- /.login-box-body -->
        </div>
    </div>
@endsection
@section("after_styles")
    <link rel="stylesheet" href="{{ asset('plugins/iCheck/square/blue.css') }}">
@endsection
@section("after_scripts")
    <script src="{{ asset('js/functions.js') }}"></script>
    <script src="{{ asset('plugins/iCheck/icheck.min.js') }}"></script>
    <script>
        $(function () {
            $('input').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '20%' // optional
            });
            $.Nukura.initPhoneField('phone-code', 'phone-number-only', true);
            $("#login-form").on("submit", function () {
                $("#phone-number").val($("#phone-code").val()+$("#phone-number-only").val());
            })
        });
    </script>
@endsection
