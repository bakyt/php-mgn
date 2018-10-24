<div class="modal modal-messenger fade scroll" id="modal-message">
    <div id="feedback" data-toggle="modal" data-target="#modal-message" style="display: none"></div>
    <div id="feedback-link" style="display: none"></div>
    <div id="feedback-text" style="display: none">{{ trans('app.feedback') }}</div>
    <div id="already-registered" style="display: none">{{ trans('app.you_have_already_registered_for_guest') }}</div>
    <div class="modal-dialog">
        <div class="modal-content">
            <div id="loading-messenger" style="z-index:5;position:absolute;right:0;left:0;bottom:0;top:0">
                <div class="box box-default no-border flat" style="margin:0;">
                    <div id="already-registered" style="display: none;"></div>
                    <div class="box-header" style="background-color: #555299;color:#ffffff">
                        {{ trans('auth.messenger') }}
                    </div>
                    <div class="box-body" style="text-align:center;height: 360px; overflow: hidden;background: #222d32;">
                        <div class="top-center">
                            <div class="pace"></div>
                        </div>                    
                    </div>
                </div>
            </div>
            <div id="empty" style="display: none;z-index:2;position:absolute;right:0;left:0;bottom:0;top:0">
                <div class="box box-default no-border flat" style="margin:0;">
                    <div id="already-registered" style="display: none;"></div>
                    <div class="box-header" style="background-color: #555299;color:#ffffff">
                        {{ trans('auth.messenger') }}
                    </div>
                    <div class="box-body" style="text-align:center;height: 360px; overflow: hidden;background: #222d32;">
                        <h4 style="z-index:1;color:white;">{{ trans('auth.empty_messenger') }}</h4>
                    </div>
                </div>
            </div>
            <div id="guest" style="display:none;z-index:3;position:absolute;right:0;left:0;bottom:0;top:0">
                <div id="error_phone" style="display: none">{{ trans("auth.wrong_phone_number") }}</div>
                <div id="code-sent-text" style="display: none">{{ trans("auth.code_sent") }}</div>
                <div id="error-code" style="display: none">{{ trans("auth.code_sent") }}</div>
                <div class="box box-default no-border flat" style="margin:0;">
                    <div id="already-registered" style="display: none;"></div>
                    <div class="box-header" style="background-color: #555299;color:#ffffff">
                        {{ trans('auth.guest') }}
                    </div>
                    <div class="box-body" style="text-align:center;height: 360px; overflow: hidden;background: #222d32;">
                        <form action="#" method="post" id="guest-form">
                        <div style="text-align:left;position:relative;margin: auto; display: inline-block;max-width:250px;">
                            <div class="form-group has-feedback{{ $errors->has('password') ? ' has-error' : '' }}">
                                <label style="color:white">{{ trans('auth.name') }}</label>
                                <input required name="name-guest" type="text" class="form-control" placeholder="{{ trans('auth.name') }}">
                                <input id="redirect-id"  type="hidden">
                                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                            </div>
                            <div class="form-group has-feedback">
                                <label style="color:white">{{ trans('auth.phone_number') }}</label>
                                <div class="input-group">
                                    <span class="input-group-btn"><select id="phone-code-guest" name="phone_code" class="form-control" style="width:auto;padding-right:0"><option value="996">+996</option></select></span>
                                    <input required id="phone-number-guest" maxlength="9" type="text" src="{{ trans('auth.example') }}" name="phone_number" class="form-control{{ $errors->has('phone_number') ? ' has-error' : '' }}" placeholder="{{ trans('auth.example') }}: 702772317">
                                    <span class="glyphicon glyphicon-phone form-control-feedback"></span>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="input-group">
                                    <input id="guest-code" required disabled style="display: none" type="text" class="form-control" placeholder="{{ trans('auth.code') }}">
                                    <span class="input-group-btn"><button id="guest-sign-in" disabled type="submit" class="btn btn-primary btn-flat pull-right">{{ trans("auth.sign_in") }}</button><button id="guest-verify" disabled type="button" class="btn btn-primary btn-flat pull-right hidden">{{ trans("auth.sign_in") }}</button></span>
                                </div>
                            </div>
                        </div>
                        </form>
                        <!-- /.col -->
                    </div>
                </div>
            </div>
            <div id="mobcomp" class="box box-primary no-border direct-chat direct-chat-primary direct-chat-contacts-open">
                <button id="close-messenger" type="button" class="close" style="position:absolute;z-index:5;right: 10px;top:10px" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <div class="box-body">
                    <div class="col-sm-4 col-xs-12" style="padding: 0;margin: 0">
                        <div class="direct-chat-messages" style="height:400px;margin:0;padding:0">
                            <div class="box box-default no-border" style="margin:0;">
                                <div class="box-header" style="background-color: #555299;color:#ffffff">
                                    {{ trans('auth.contacts') }}
                                </div>
                                <div class="box-body scroll" style="height: 360px; overflow: auto;background: #222d32;">
                                    <div id="notice-proto" style="display:none;"><span style="position: absolute;font-size: 9px;top: 5px;left: 5px; padding: 3px;" class="label label-danger">{number}</span></div>
                                    <div id="contact-proto" style="display: none">
                                        <div id="contact-toggle" class="cursor-pointer contact-list" data-toggle="tooltip" title="" data-widget="chat-pane-toggle">
                                            <img class="contacts-list-img" {avatar} alt="User Image">
                                            <div class="contacts-list-info">
                                                <span class="contacts-list-name">{name}
                                                    <small style="z-index: 5" {delete} class="contacts-list-date pull-right cursor-pointer"><i class="fa fa-trash"></i></small>
                                                </span>
                                                <span class="contacts-list-msg" style="font-size:8pt;">{message}</span>
                                            </div>
                                            <!-- /.contacts-list-info -->
                                        </div>
                                    </div>
                                    <ul id="contacts-list-new" class="contacts-list"></ul>
                                    <ul id="contacts-list" class="contacts-list"></ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-8 col-xs-12" style="padding: 0;margin: 0;position: absolute;right:0;">
                        <div class="direct-chat-contacts" style="height: 400px">
                            <div class="box box-default no-border" style="padding: 0;margin: 0">
                                <div class="box-header" style="background-color: #605ca8;color:#ffffff">
                                    <a id="back-btn" type="button" class="btn btn-primary no-border btn-flat hidden-sm hidden-lg hidden-md" style="padding:5px;margin:-15px 0 -10px -5px" data-toggle="tooltip" title="" data-widget="chat-pane-toggle" data-original-title="Contacts">
                                        <i class="fa fa-arrow-left"></i></a>
                                    <div id="sender-title-proto" style="display: none;"><div style="position: relative"><img class="direct-chat-img" style="width:20px;height:20px;margin-right:5px;" {avatar} alt="Image">{name}<span class="" style="left:26px;bottom:-11px;font-size:8pt;position: absolute">{visited}</span></div></div>
                                    <input type="hidden" id="open-id">
                                    <h3 id="sender-title" class="messenger-box-title"></h3>
                                </div>
                                <div id="from-message" style="display: none;position: relative;">
                                    <div class="direct-chat-msg" style="margin:0">
                                        <div class="direct-chat-text" style="min-width:50px;margin-left: 0;display: inline-block;padding-bottom:12px;">
                                            <span style="white-space: pre-line">{message}</span>
                                            <span class="direct-chat-timestamp" style="font-size:8pt;padding-top:7px;color: black;position:absolute;bottom:0;left:2px;"><i {delete} class="fa fa-trash cursor-pointer"></i> <i class="{delivered}"></i></span>

                                            <span class="direct-chat-timestamp" style="position:absolute;bottom:0;right:1px;font-size:8pt; color:#333333;">{date}</span>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.direct-chat-msg -->

                                <!-- Message to the right -->
                                <div id="my-message" style="display: none;position: relative;">
                                    <div class="direct-chat-msg right" style="margin: 0">
                                        <!-- /.direct-chat-info -->
                                        <div class="direct-chat-text pull-right" style="min-width:50px;margin-right: 0;display: inline-block;padding-bottom:12px;">
                                            <span class="my-message-text" style="white-space: pre-line">{message}</span>
                                            <span class="direct-chat-timestamp" style="font-size:8pt;padding-top:7px;color: white;position:absolute;bottom:0;left:2px;"><i {delete} class="fa fa-trash cursor-pointer"></i> <i class="{delivered}"></i></span>
                                            <span class="direct-chat-timestamp" style="font-size:8pt;padding-top:7px;color: white;position:absolute;bottom:0;right:1px;">{date}</span>
                                        </div>
                                        <!-- /.direct-chat-text -->
                                    </div>
                                </div>
                                <div id="message-box" class="box-body scroll" style="height: 314px; padding: 10px">
                                    <div style="color: #000;">{{ trans("auth.select_contact") }}</div>
                                </div>
                                <div class="box-footer" style="padding: 5px">
                                    <div class="input-group">
                                        <textarea id="message-input" style="resize: none;height:34px" name="message" placeholder="{{ trans('auth.type_message') }} ..." class="form-control"></textarea>
                                        <span class="input-group-btn"><button id="message-sender" type="button" class="btn btn-primary btn-flat">{{ trans('auth.send') }}</button></span>
                                    </div>
                                </div>
                            </div>

                            <!-- /.contacts-list -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>