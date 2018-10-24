@extends('layouts.app')
@section('php')
    @php $title = trans('auth.reset_password') @endphp
@endsection
@section('content')
    <div class="row">
        <div class="col-md-8 col-lg-offset-2">
            <div class="box box-primary">
                <div class="box-header with-border"><i class="fa fa-refresh"></i></div>

                <div class="box-body">
                    <form id="reset-form" class="form-horizontal" method="POST" action="{{ route('password.update') }}">
                        {{ csrf_field() }}

                        <div class="form-group">
                            <label for="phone-number" class="col-md-4 control-label">{{ trans('auth.phone_number') }}</label>

                            <div class="col-md-6">
                                <div class="input-group">
                                    <span class="input-group-btn"><select id="phone-code" disabled name="phone_code" class="form-control" style="width:auto;padding-right:0"><option selected value="{{ old('phone_code') }}">+{{ old('phone_code') }}</option></select></span>
                                    <input type="hidden" name="phone_number" value="{{ old('phone_number') }}"/>
                                    <input disabled value="{{ old('phone_number') }}" id="phone-number" type="text" class="form-control{{ $errors->has('phone_number') ? ' has-error' : '' }}">
                                    <span class="input-group-btn"><button class="btn btn-primary btn-flat" id="send-code" type="button"><i class="fa fa-undo"></i></button></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="verificationcode" class="col-md-4 control-label">{{ trans('auth.code') }}</label>

                            <div class="col-md-6">
                                <div class="input-group">
                                    <input id="verificationcode" type="number" maxlength="6" class="form-control" placeholder="{{ trans('auth.enter_code') }}">
                                    <span class="input-group-btn">
                                        <button class="btn btn-primary btn-flat" id="verify" type="button">{{ trans('auth.verify') }}</button>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div id="password-content" style="display: none">
                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="col-md-4 control-label">{{ trans('auth.new_password') }}</label>

                            <div class="col-md-6">
                                <input id="password" disabled required type="password" class="form-control" name="password">

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                            <label for="password-confirm" class="col-md-4 control-label">{{ trans('auth.retype_password') }}</label>
                            <div class="col-md-6">
                                <input id="password-confirm" disabled required type="password" class="form-control" name="password_confirmation">

                                @if ($errors->has('password_confirmation'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" disabled id="reset" class="btn btn-primary btn-flat">
                                    {{ trans('auth.reset_password') }}
                                </button>
                            </div>
                        </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div id="verified" style="display: none">{{ trans('auth.verified') }}</div>
    <div id="not-match" style="display: none">{{ trans('auth.passwords_not_match') }}</div>
@endsection
@section('after_scripts')
    <script type="text/javascript">
        // Initialize Firebase
        $(function () {
            $.Nukura.initializePhoneActivator('send-code');
            var sendCode = $('#send-code');
            var verify = $('#verify');
            var reset = $('#reset');
            var resetForm = $('#reset-form');
            resetForm.on('submit', function () {
                if($('#password').val() !== $('#password-confirm').val()) {
                    new PNotify({
                        title: '{{ trans("app.error") }}',
                        text: document.getElementById('not-match').innerHTML,
                        type: "error",
                        icon: "fa fa-close"
                    });
                    event.preventDefault();
                }
                else {
                    $("#phone-code").removeAttr("disabled");
                    sessionStorage.removeItem("verified");
                }
            });
            sendCode.on('click', function () {
                $.Nukura.sendActivationCode("+"+document.getElementById("phone-code").value + document.getElementById("phone-number").value);
            });
            verify.on('click', function () {
                $.Nukura.confirmActivationCode("verificationcode", function () {
                    new PNotify({
                        title: '{{ trans("app.success") }}',
                        text: document.getElementById('verified').innerHTML,
                        type: "success",
                        icon: "fa fa-check"
                    });
                    $('#password-content').slideToggle();
                    sendCode.attr("disabled", "disabled");
                    $("#verificationcode").attr("disabled", "disabled");
                    verify.attr("disabled", "disabled");
                    $("#password").removeAttr("disabled");
                    $("#password-confirm").removeAttr("disabled");
                    reset.removeAttr("disabled");
                    sessionStorage.setItem("verified", document.getElementById("verificationcode").value);
                });
            });
            if (Boolean(sessionStorage.getItem("verified"))) {
                document.getElementById("verificationcode").value = sessionStorage.getItem("verified");
                $('#password-content').slideToggle();
                sendCode.attr("disabled", "disabled");
                $("#verificationcode").attr("disabled", "disabled");
                verify.attr("disabled", "disabled");
                $("#password").removeAttr("disabled");
                $("#password-confirm").removeAttr("disabled");
                reset.removeAttr("disabled");
            }
            else sendCode.click();
        });
    </script>
@endsection
