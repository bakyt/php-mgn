<div class="modal fade" id="modal-orders">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title"><i class="fa fa-shopping-cart"></i> {{ trans('rent.shopping_cart') }}</h4>
            </div>
            <form action="/item/order/create" method="post" id="cart">
                {{ csrf_field() }}
                <input type="hidden" id="cart-items" name="items" value=""/>
            <div class="modal-body">
                <div class="table-responsive" style="border:0;" id="orders-box">
                    <div id="order-table-abstract" class="hidden">
                        <table class="table no-margin">
                            <thead>
                            <tr>
                                <th>{{ trans('rent.quantity') }}</th>
                                <th>{{ trans('rent.product') }}</th>
                                <th>{{ trans('rent.price') }}({{trans('rent.som')}})</th>
                                <th>{{ trans('rent.category') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="panel panel-default flat"><div class="panel-body"><button type="button" style="margin: -5px" class="truncate btn btn-danger btn-sm">Сбросить</button><span class="pull-right">{{ trans('rent.total_all_price') }}: <div class="total" style="display:inline-block">0</div>{{ trans('rent.som') }}</span></div></div>
                <div id="cart-order" style="display: none">
                    <label><i class="fa fa-address-card"></i> {{ trans('rent.contact_information') }}</label>
                    <div class="form-group">
                        <label>{{ trans('auth.name') }}</label>
                        <input required class="form-control" placeholder="{{ trans('auth.name') }}" name="name" type="text">
                    </div>
                    <div class="form-group">
                        <label>{{ trans('auth.phone_number') }}</label>
                        <div class="input-group">
                            <span class="input-group-btn"><select id="client-phone-code" name="phone_code" class="form-control" style="width:auto;padding-right:0"><option value="996">+996</option></select></span>
                            <input required id="client-phone-number" maxlength="9" type="text" src="{{ trans('auth.example') }}" name="phone" class="form-control{{ $errors->has('phone_number') ? ' has-error' : '' }}" placeholder="{{ trans('auth.example') }}: 702772317">
                            <span class="glyphicon glyphicon-phone form-control-feedback"></span>
                        </div>
                        <span id="phone-error-message" class="text-red"></span>
                    </div>
                    <div id="client-confirm-content">
                        <div class="form-group" id="client-code-content" style="display: none">
                            <div class="input-group">
                                <span class="input-group-addon">{{ trans('auth.code') }}</span>
                                <input id="vercode" maxlength="6" type="number" name="code" class="form-control" placeholder="{{ trans('auth.enter_code') }}">
                                <span class="input-group-btn">
                            <button class="btn btn-primary btn-flat" id="client-verify" type="button">{{ trans('auth.verify') }}</button>
                        </span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>{{ trans('rent.address') }}</label>
                        <input required class="form-control" placeholder="{{ trans('rent.address') }}" name="address" type="text">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">{{ trans('rent.close') }}</button>
                <button type="button" id="client-form" class="btn btn-primary">{{ trans('auth.send') }}</button>
                <button type="button" id="client-send-code" class="btn btn-primary hidden">{{ trans('auth.send') }}</button>
                <button type="button" id="code-resend" onclick="$('#client-send-code').click()" class="btn btn-primary hidden">{{ trans('auth.resend_code') }}</button>
                <button type="submit" disabled id="client-order" class="btn btn-primary hidden">{{ trans('auth.send') }}</button>
            </div>
            </form>
            <div class="total-price hidden">{{ trans('rent.total_price') }}</div>
            <div class="som-text hidden">{{ trans('rent.som') }}</div>
            <div id="verified" style="display: none">{{ trans('auth.verified') }}</div>
            <div id="code-sent" style="display: none">{{ trans('auth.code_sent') }}</div>
            <div id="success" style="display: none">{{ trans('app.success') }}</div>
            <div id="connection_seems_dead" style="display: none">{{ trans('rent.connection_seems_dead') }}</div>
            <div id="order_cant_be_empty" style="display: none">{{ trans('rent.order_cant_be_empty') }}</div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>