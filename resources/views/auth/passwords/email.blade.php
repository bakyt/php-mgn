@extends('layouts.app')
@section('php')
    @php $title = trans('auth.reset_password') @endphp
@endsection
@section('content')
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="box box-primary">
                <div class="box-header with-border"><i class="fa fa-refresh"></i> </div>
                <form action="{{ route('password.check') }}" method="post">
                <div class="box-body">
                        {{ csrf_field() }}
                        <div class="form-group has-feedback">
                            <label>{{ trans('auth.phone_number') }}</label>
                            <div class="input-group">
                                <span class="input-group-btn"><select id="phone-code" name="phone_code" class="form-control" style="width:auto;padding-right:0"><option value="996">+996</option></select></span>
                                <input required id="phone-number" maxlength="9" type="text" name="phone_number" class="form-control{{ $errors->has('phone_number') ? ' has-error' : '' }}" src="{{ trans('auth.example') }}" placeholder="{{ trans('auth.example') }}: 702772317">
                                <span class="fa fa-mobile form-control-feedback"></span>
                            </div>
                            <span id="phone-error-message" class="text-red"></span>
                        </div>

                </div>
                <div class="box-footer">
                    <button id="send-code" disabled class="btn btn-primary btn-flat pull-right">{{ trans('auth.get_code') }}</button>
                    <a href="/login">{{ trans('auth.sign_in') }}</a><br/>
                    <a href="/register">{{ trans('auth.sign_up') }}</a>
                </div>
                </form>
            </div>
        </div>
    </div>
    <div id="phone-not-registered" style="display: none">{{ trans('auth.phone_not_registered') }}</div>
@endsection
@section('after_scripts')
    <script>
        $(function () {
            $.Nukura.initPhoneField('phone-code', 'phone-number', true);
            var sendCode = $("#send-code"), errorMessage = $("#phone-error-message"), not_reg = $("#phone-not-registered").html();
            $('#phone-number').on('input', function () {
                var $this=$(this), text = $(this).val().replace(/[^0-9 ]/i, "").replace(" ", "");
                $.Nukura.checkPhoneNumber($this, $("#phone-code").val(), function (data) {
                    if(!data['has']) {
                        $this.parent().addClass('has-error');
                        errorMessage.html(not_reg);
                    }
                    else {
                        errorMessage.html("");
                        sendCode.removeAttr("disabled");
                        $this.parent().addClass('has-success');
                    }
                }, function () {
                    sendCode.attr("disabled","disabled");
                    if($this.parent().hasClass('has-error')) {
                        errorMessage.html("");
                        sendCode.attr("disabled", "disabled");
                        $this.parent().removeClass('has-error');
                    }
                    else if($this.parent().hasClass('has-success')) $this.parent().removeClass('has-success');

                });

            });
        });
    </script>
@endsection