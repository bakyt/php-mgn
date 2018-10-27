@extends('layouts.app')
@section('after_styles')
    <link rel="stylesheet" href="{{ asset('plugins/datatables/dataTables.bootstrap.css') }}">
@endsection
@section('content')
    <div class="row">
        <div class="col-md-4">

            <!-- Profile Image -->
            <div class="box box-primary">
                <div class="box-body box-profile">
                    <form id="avatar-form" enctype="multipart/form-data" action="" method="post">{{ csrf_field() }}<input style="display: none" id="avatar-upload" name="image" type="file" accept="image/*" /></form>
                    <div id="avatar" style="position:relative; margin-right: 10px" class="profile-user-img img-responsive img-circle pull-left">
                        <img id="user-avatar" class="img-circle" style="height: 100%;width:100%;" src="@if( !filter_var($user->avatar, FILTER_VALIDATE_URL)){{ Voyager::image( $user->avatar ) }}@else{{ $user->avatar }}@endif" alt="User profile picture">
                    </div>
                    <div id="username" class="profile-username" style="line-height: 1em;padding-top: 10px">{{ $user->name }}</div>
                    <p class="text-muted text-left">{{ $user->role_id == 2?trans('app.user'):($user->role_id==1?'Administrator':'Moderator')}}</p>
                    @if($user->me)<div style="width: 100%"><button id="avatar-upload-btn" class="btn btn-box-tool"><i class="fa fa-camera"></i></button><button class="btn btn-box-tool" id="delete-avatar"><i class="fa fa-trash"></i></button></div>@endif
                    <button title="{{ trans('rent.message') }}" style="position:absolute;bottom: 10px; right: 10px" type="button" value="{{ $user->id }}" id="new-message" class="btn btn-primary btn-sm pull-right disable-scroll" data-toggle="modal" data-target="#modal-message"><b><i class="fa fa-envelope"></i></b></button>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
        <div class="col-md-8">
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#delivery" data-toggle="tab" title="{{ trans('rent.delivery') }}"><i class="fa fa-truck"></i></a></li>
                    @if($Market)<li><a href="#my-market" data-toggle="tab" title="{{ trans('rent.my_market')  }}"><i class="fa fa-shopping-cart"></i></a></li>@endif
                    @if($user->me)
                        <li><a href="#timeline" data-toggle="tab"><i class="fa fa-bell"></i> @if($newNotices)<span id="notice" style="position: absolute;font-size: 9px;top: 6px;right: 10px; padding: 3px;" class="label label-danger">{{ $newNotices }}</span>@endif</a></li>
                        @if($user->role_id!=2)<li><a href="#moderate" data-toggle="tab"><i class="fa fa fa-briefcase"></i>@if($moderateSize)<span id="notice" style="position: absolute;font-size: 9px;top: 6px;right: 10px; padding: 3px;" class="label label-danger">{{ $moderateSize }}</span>@endif</a></li>@endif
                        <li><a href="#settings" data-toggle="tab"><i class="fa fa-cog"></i></a></li>
                    @endif
                </ul>
                <div class="tab-content">
                    <div class="active tab-pane" id="delivery" style="position: relative">
                        {{ trans('rent.delivery') }}: <div style="display: inline-block;" class="delivery {{ $user->delivery?'':'hidden' }}">{{ $user->delivery }}</div><div class="no-delivery" style="display:{{ $user->delivery?'none':'inline-block' }}">{{ trans('rent.delivery_not_available') }}</div>@if($user->me)&nbsp<i data-toggle="dropdown" class="fa fa-pencil dropdown-toggle cursor-pointer"></i>@endif
                        @if($user->me)
                            <div class="dropdown-menu pull-left" style="background:#555299;width: 300px;">
                                <form id="save-delivery" action="" method="post">
                                    {{ csrf_field() }}
                                    <input name="delivery-edit" placeholder="{{ trans('rent.commas')." (".trans("rent.address").", ".trans("rent.address").")" }}" value="{{ $user->delivery }}" type="text" id="delivery-edit" class="form-control" />
                                    <button style="margin-top:5px;margin-left: 5px" class="btn btn-default btn-sm" type="submit">{{ trans('rent.save') }}</button>
                                </form>
                            </div>
                        @endif
                    </div>
                    @if($Market)
                        <div class="tab-pane" id="my-market">
                            <a href="/{{ $Market->slug }}">
                                <div style="display: inline-block;">
                                    <img src="/storage/{{ $Market->icon }}" style="background-size: auto 100%;height:40px;max-width: 40px; border-radius: 50%; margin-right:5px">
                                </div>
                                <div style="display: inline-block;">
                                    <div style="font-size: 14pt">{{ $Market->name }}</div>
                                </div>
                            </a>
                        </div>
                    @endif
                    @if($user->role_id!=2)
                        <div class="tab-pane" id="moderate">
                            <!-- Post -->
                            <div class="row">
                                @if(!$moderations) <div class="col-md-12 text-center">{{ trans('rent.empty') }} </div>@endif
                                @foreach($moderations as $item)
                                    <div class="col-sm-12 col-md-12">
                                        <div class="box no-border no-shadow" style="margin-bottom: 0">
                                            <div class="box-body">
                                                <a href="/category/moderate/{{ $item->id }}?redirect={{ url()->current() }}*moderate" class="text-purple">
                                                    <h4 style="background-color:#f7f7f7; font-size: 18px; text-align: left; padding: 7px 18px; margin-top: 0;margin-right:-20px;margin-left:-20px;">
                                                        <i class="fa fa-folder"></i> {{ $item->name }}
                                                    </h4>
                                                </a>
                                                <div class="media">
                                                    <div class="row">
                                                        <div class="col-md-3 col-sm-2 col-xs-12">
                                                            <img src="/storage/{{ $item->image }}" alt="image" style="margin-bottom:10px;width:100%;box-shadow: 0 1px 3px rgba(0,0,0,.15);">
                                                        </div>
                                                        <div class="col-md-9 col-sm-10 col-xs-12">
                                                            <div class="clearfix">
                                                                <div class="pull-right">
                                                                    <a href="/category/moderate/{{ $item->id }}?redirect={{ url()->current() }}*moderate" style="margin-bottom: -2px" class="btn btn-sm btn-success">{{ trans('rent.moderate') }}</a>
                                                                </div>
                                                                <p>{{ trans('rent.created_at').": ".$item->created_at->diffForHumans() }}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                @if($quantity)
                                    <div class="col-md-12 text-center">
                                        <div class="btn-group" style="font-size:12pt">
                                            @foreach($pagination_mdr as $page)
                                                <a @if(!$page['link']) class="btn btn-default disabled btn-sm" @else href="{{ $page['link'] }}" class="btn btn-default {{ $page['class'] }} btn-sm" @endif><i class="{{ $page['icon'] }}"></i>{{ $page['value'] }}</a>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                <!-- /.tab-pane -->
                    @if($user->me)
                        <div class="tab-pane" id="timeline">
                            <!-- The timeline -->
                            @if(!$hasNotice) <div class="text-center">{{ trans('auth.no_notifications') }}</div>
                            @else
                                <ul class="timeline timeline-inverse">
                                    <li style="position: absolute;right:0;z-index: 3">
                                        <div class="pull-right">
                                            <form class="notice-form" action="{{ route('notice') }}" method="post">
                                                {{ csrf_field() }}
                                                <button class="btn btn-box-tool pull-right" name="deliver_all">{{ trans('auth.mark_all_as_read') }}</button>
                                            </form>
                                        </div>
                                    </li>
                                    @php $date=""; @endphp
                                    @foreach($notices as $notice)

                                        <li class="time-label">
                                            @php
                                                $difference = ($notice->created_at->diff($now)->days < 1)
                                                    ? trans("app.today")
                                                    : $notice->created_at->diffForHumans($now); @endphp
                                            @if($date != $difference)
                                                @php $date=$difference @endphp
                                                <span class="bg-red">{{ $difference }}</span>
                                            @endif
                                        </li>
                                        <li>
                                            <i class="{{ $notice->icon }} bg-blue"></i>

                                            <div class="timeline-item">
                                                <span class="time"><i class="fa fa-clock-o"></i> {{ $notice->created_at->format('H:i') }}</span>
                                                <h3 class="timeline-header"> {{ $notice->message->title }}</h3>

                                                <div class="timeline-body">
                                                    {{ $notice->message->body }}
                                                </div>
                                                <div class="timeline-footer">
                                                    @if($notice->link) <a href="{{ $notice->link }}" class="btn btn-primary btn-xs pull-right"><i class="fa fa-arrow-right"></i></a>@endif
                                                    <form class="notice-form" action="{{ route('notice') }}" method="post">
                                                        {{ csrf_field() }}
                                                        <input type="hidden" name="id" value="{{ $notice->id }}"/>
                                                        <button name="delete" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i></button>
                                                    </form>
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                    <li>
                                        <i class="fa fa-clock-o bg-gray"></i>
                                    </li>
                                    <li>
                                        <div class="col-md-12 text-center">
                                            <div class="btn-group" style="font-size:12pt">
                                                @foreach($pagination_msg as $page)
                                                    <a @if(!$page['link']) class="btn btn-default disabled btn-sm" @else href="{{ $page['link'] }}#timeline" class="btn btn-default {{ $page['class'] }} btn-sm" @endif><i class="{{ $page['icon'] }}"></i>{{ $page['value'] }}</a>
                                                @endforeach
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            @endif
                        </div>
                        <!-- /.tab-pane -->

                        <div class="tab-pane" id="settings">
                            <form method="post" id="edit" class="form-horizontal" action="{{ route('user.update',['id'=>$user->id]) }}">
                                {{ csrf_field() }}
                                <div class="form-group">
                                    <label for="name" class="col-sm-2 control-label">{{ trans('auth.full_name') }}</label>

                                    <div class="col-sm-10">
                                        <input class="form-control" name="name" required="required" value="{{ old("name")?old("name"):$user->name }}" id="name" placeholder="{{ trans('auth.full_name') }}" type="text">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="phone_number" class="col-sm-2 control-label">{{ trans('auth.phone_number') }}</label>
                                    <div class="col-sm-10">
                                        <div class="input-group">
                                            <span class="input-group-btn"><select disabled id="phone-code" name="phone_code" class="form-control" style="width:auto;padding-right:0"><option value="{{ $user->phone_code }}">{{ $user->phone_code }}</option></select></span>
                                            <div id="current-phone" style="display: none">{{ substr($user->phone_number, -(strlen($user->phone_number)-strlen($user->phone_code)), strlen($user->phone_number)-strlen($user->phone_code)) }}</div>
                                            <div id="current-code" style="display: none">{{ $user->phone_code }}</div>
                                            <input value="{{ substr($user->phone_number, -(strlen($user->phone_number)-strlen($user->phone_code)), strlen($user->phone_number)-strlen($user->phone_code)) }}" disabled required id="phone-number" maxlength="9" type="text" name="phone_number" class="form-control{{ $errors->has('phone_number') ? ' has-error' : '' }}" src="{{ trans('auth.example') }}" placeholder="{{ trans('auth.example') }}: 702772317">
                                            <span class="glyphicon glyphicon-phone form-control-feedback"></span>
                                            <span class="input-group-btn">
                                            <button class="btn btn-primary btn-flat" id="change" type="button">{{ trans('auth.change') }}</button>
                                            <button class="btn btn-primary btn-flat" style="display: none;" id="cancel" type="button">{{ trans('rent.cancel') }}</button>
                                        </span>
                                        </div>
                                        <span id="phone-error-message" class="text-red"></span>
                                    </div>
                                    <div class="col-sm-12">
                                        <button style="display: none" class="btn btn-primary btn-flat pull-right" id="send-code" type="button">{{ trans('auth.get_code') }}</button>
                                    </div>
                                </div>
                                <div class="form-group" id="verify-content" style="display: none">
                                    <label for="verificationcode" class="col-sm-2 control-label">{{ trans('auth.code') }}</label>

                                    <div class="col-sm-10">
                                        <div class="input-group">
                                            <input id="verificationcode" type="number" maxlength="6" class="form-control" placeholder="{{ trans('auth.enter_code') }}">
                                            <span class="input-group-btn">
                                        <button class="btn btn-primary btn-flat" id="verify" type="button">{{ trans('auth.verify') }}</button>
                                    </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputName" class="col-sm-2 control-label">{{ trans('auth.birth_date') }}</label>

                                    <div class="col-sm-10">
                                        <input class="form-control" name="birth_date" id="inputName" value="{{ old("birth_date")?old("birth_date"):date("Y-m-d",strtotime($user->birth_date))}}" placeholder="{{ trans('auth.birth_date') }}" type="date">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputExperience" class="col-sm-2 control-label">{{ trans('auth.gender') }}</label>

                                    <div class="col-sm-10">
                                        {!! Form::select('gender',[trans('auth.select'),trans('auth.male'),trans('auth.female')],old("gender")?old("gender"):$user->gender,["class"=>"form-control"]) !!}
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputSkills" class="col-sm-2 control-label">{{ trans('auth.new_password') }}</label>

                                    <div class="col-sm-10{{ $errors->has('password') ? ' has-error' : '' }}">
                                        <input class="form-control" name="password" id="password" placeholder="{{ trans('auth.new_password') }}" type="password">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="input" class="col-sm-2 control-label">{{ trans('auth.retype_password') }}</label>

                                    <div class="col-sm-10{{ $errors->has('password') ? ' has-error' : '' }}">
                                        <input class="form-control" name="retype_password" id="password-confirm" placeholder="{{ trans('auth.retype_password') }}" type="password">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label"></label>
                                    <div class="col-sm-10">
                                        {{ trans('auth.write_current_password_for_saving') }}
                                        <div class="input-group">
                                            <input required="required" class="form-control" id="current" name="current_password" placeholder="{{ trans('auth.current_password') }}" type="password">
                                            <span class="input-group-btn">
                                            <button type="submit" id="save" name="save" class="btn btn-primary btn-flat">{{ trans('rent.save') }}</button>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                @endif
                <!-- /.tab-pane -->
                </div>
                <!-- /.tab-content -->
            </div>
            <!-- /.nav-tabs-custom -->
        </div>
        <div class="col-md-12">
            @if($user->me)
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#orders" data-toggle="tab" title="{{ trans('rent.orders') }}"><i class="fa fa-cart-arrow-down"></i> {{ trans('rent.orders') }}<span class="label label-danger {{ $order_quantity?'':'hidden' }}">{{ $order_quantity }}</span></a></li>
                        <li><a href="#history" data-toggle="tab" title="{{ trans('rent.history') }}"><i class="fa fa-clock-o"></i> {{ trans('rent.history') }}</a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="active tab-pane" id="orders" style="overflow: auto;">
                            <table id="example2" class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>{{ trans('rent.date') }}</th>
                                    <th>{{ trans('rent.client') }}</th>
                                    <th>{{ trans('rent.products') }}</th>
                                    <th>{{ trans('rent.address') }}</th>
                                    <th>{{ trans('rent.total_price') }}</th>
                                    <th>{{ trans('rent.action') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($Orders as $order)
                                    <tr>
                                        <td>{{ $order->created_at->diffForHumans() }}</td>
                                        <td>{{ $order->name }}<br/>{{ $order->phone }}</td>
                                        <td>
                                            @foreach($order->items as $item)
                                                <li><a href="/view/{{ $item->id }}">{{ $item->title }}</a>, {{ trans('rent.quantity').": ".$item->quantity }} ({{ $item->category }})</li>
                                            @endforeach
                                        </td>
                                        <td>{{ $order->address }}</td>
                                        <td>{{ $order->total_price }} {{ trans('rent.som') }}</td>
                                        <td><form action="/item/order/to_history" method="post">{{ csrf_field() }}<input type="hidden" name="type" value="user" /><input type="hidden" name="id" value="{{ $order->id }}"/><button title="{{ trans('rent.ready') }}" class="btn btn-success btn-sm" type="submit"><i class="fa fa-check"></i> {{ trans('rent.ready') }}</button></form></td>
                                    </tr>
                                @endforeach
                                </tbody>
                                <tfoot>
                                <tr>
                                    <th>{{ trans('rent.date') }}</th>
                                    <th>{{ trans('rent.client') }}</th>
                                    <th>{{ trans('rent.products') }}</th>
                                    <th>{{ trans('rent.address') }}</th>
                                    <th>{{ trans('rent.total_price') }}</th>
                                    <th>{{ trans('rent.action') }}</th>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                        <div class="tab-pane" id="history" style="overflow: auto;">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>{{ trans('rent.date') }}</th>
                                    <th>{{ trans('rent.client') }}</th>
                                    <th>{{ trans('rent.products') }}</th>
                                    <th>{{ trans('rent.address') }}</th>
                                    <th>{{ trans('rent.total_price') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($Orders_history as $order)
                                    <tr>
                                        <td>{{ $order->created_at->diffForHumans() }}</td>
                                        <td>{{ $order->name }}<br/>{{ $order->phone }}</td>
                                        <td>
                                            @foreach($order->items as $item)
                                                <li><a href="/view/{{ $item->id }}">{{ $item->title }}</a>, {{ trans('rent.quantity').": ".$item->quantity }} ({{ $item->category }})</li>
                                            @endforeach
                                        </td>
                                        <td>{{ $order->address }}</td>
                                        <td>{{ $order->total_price }} {{ trans('rent.som') }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                                <tfoot>
                                <tr>
                                    <th>{{ trans('rent.date') }}</th>
                                    <th>{{ trans('rent.client') }}</th>
                                    <th>{{ trans('rent.products') }}</th>
                                    <th>{{ trans('rent.address') }}</th>
                                    <th>{{ trans('rent.total_price') }}</th>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
            <div class="box box-primary">
                <div class="box-header with-border">
                    <div class="box-title"><i class="fa fa-clipboard"></i> {{ trans('rent.my_ads') }}</div>
                </div>
                <div class="box-body">
                    <div class="row">
                        @if(!$quantity) <div class="col-md-12 text-center">{{ trans('rent.no_ads') }} @if($user->me) <a href="/item/create">{{ trans('rent.new_item') }}</a> @endif </div>@endif
                        @foreach($rent as $item)
                            <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                                <div class="flat">
                                    <div class="flat" style="position:relative;height: 158px; background: linear-gradient( rgba(0, 0, 0, 0.3), rgba(0, 0, 0, 0.3) ),url('/storage/{{ $item->images[0] }}') center center; background-size: cover;">
                                        <div class="bg-green" style="padding:5px;position:absolute;top:0;left:0;font-size:10pt;">{{ json_decode($item->category->name_single)->$locale }}</div>
                                        <div class="bg-orange" style="padding:5px;position:absolute;bottom:0;right:0;font-size:11pt;">{{ $item->price.(is_numeric($item->price)?" ".trans('rent.som'):"") }}</div>
                                        <div class="bg-purple" style="position:absolute;top:0;right:0;font-size:10pt;padding: 5px;"> {{ $item->type?trans('rent.sale'):trans('rent.rent') }}</div>
                                        @if($item->market or $item->state and $item->type) <div class="bg-primary" style="position:absolute;bottom:0;lepft:0;font-size:10pt;padding: 5px;"> @if($item->market){{ trans('rent.market') }}@elseif($item->type){{ $item->state==2?trans('rent.new'):trans('rent.secondhand') }}@endif</div>
                                        @elseif($item->state != 2)<div class="bg-primary" style="position:absolute;bottom:0;left:0;font-size:10pt;padding: 5px;">{{ $item->updated->diffForHumans() }}</div>
                                        @endif
                                        <div style="z-index:2;position:absolute;bottom:30px;left:0;font-size:10pt;padding: 5px;">
                                            @if($user->me)
                                                <a title="{{ trans('rent.edit') }}" href="{{ $item->market?"/".$item->market->slug:"/item" }}/edit/{{ $item->id }}/{{ $item->market?"":$item->type }}" class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o"></i> {{ trans('rent.edit') }}</a>
                                                <form action="{{ route('item.update', ["id"=>$item->id, 'type'=>$item->type]) }}" method="post">{{ csrf_field() }}<button name="update" value="ok" style="margin-bottom: -2px" class="btn btn-sm btn-success">{{ trans('rent.update_date') }}</button></form>
                                                <form action="{{ route('item.update', ["id"=>$item->id, 'type'=>$item->type]) }}" method="post">{{ csrf_field() }}<button title="{{ trans('rent.delete') }}" name="delete" value="ok" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> {{ trans('rent.delete') }}</button></form>
                                            @elseif($item->market or $item->state and $item->state != "1" and $item->type)
                                                <button class="btn btn-success add-to-cart" data-price="{{ $item->price }}" data-id="{{ $item->id }}" data-name="{{ $item->title }}" data-category-name="{{ json_decode($item->category->name)->$locale }}" data-category-id="{{ $item->category->id }}" @if($item->market) data-delivery="{{ $item->market->delivery?trans('rent.delivery').": ".implode(", ", json_decode($item->market->delivery)):trans('rent.no_delivery') }}" data-market-name="{{ $item->market->name }}" data-market-slug="{{ $item->market->slug }}" @else data-delivery="{{ $user->delivery?trans('rent.delivery').": ".$user->delivery:trans('rent.no_delivery') }}" data-user-id="{{ $user->id }}" data-user-name="{{ $user->name }}" @endif><i class="fa fa-shopping-cart"></i> {{ trans('rent.to_cart') }}</button>
                                            @endif
                                        </div>
                                    </div>
                                    <div>
                                        <span class="users-list-name" style="font-size: 13pt"><a class="users-list-name" href="/view/{{ $item->id }}">{{ $item->title }}</a></span>
                                        @if($user->me)
                                            <div style="position: relative;">
                                                <div class="dropdown-toggle description" data-toggle="dropdown" aria-expanded="false" style="margin:0;overflow: hidden;white-space: nowrap; text-overflow: ellipsis;">
                                                    <i class="fa fa-eye margin-r5"></i> {{ $item->views }}&nbsp;
                                                </div>
                                            </div>
                                        @endif
                                        <p class="ymaps-geolink users-list-date" style="overflow: hidden;white-space: nowrap; text-overflow: ellipsis;"><i class="fa fa-map-marker"></i> {{ $item->address }} </p>
                                    </div>
                                </div>
                            </div>

                        @endforeach

                    </div>
                </div>
                <div class="box-footer">
                    @if($quantity)
                        <div class="col-md-12 text-center">
                            <div class="btn-group" style="font-size:12pt">
                                @foreach($pagination as $page)
                                    <a @if(!$page['link']) class="btn btn-default disabled btn-sm" @else href="{{ $page['link'] }}" class="btn btn-default {{ $page['class'] }} btn-sm" @endif><i class="{{ $page['icon'] }}"></i>{{ $page['value'] }}</a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <!-- /.col -->
    </div>
    <div id="verified" style="display: none">{{ trans('auth.verified') }}</div>
    <div id="not-match" style="display: none">{{ trans('auth.passwords_not_match') }}</div>
    <div id="success" style="display: none">{{ \session()->has('success')?"true":"" }}</div>
    <div id="already_registered" style="display: none">{{ trans('auth.already_taken') }}</div>
@endsection
@section('after_scripts')
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/dataTables.bootstrap.min.js') }}"></script>
    <script>
        $(function(){
            $('#example1').DataTable( {
                "language": {
                    "url": "/plugins/datatables/langs/{{ $locale }}.json"
                },
                "ordering": false
            } );
            $('#example2').DataTable( {
                "language": {
                    "url": "/plugins/datatables/langs/{{ $locale }}.json"
                },
                "ordering": false
            } );
            $.Nukura.initPhoneField('phone-code', 'phone-number');
            var phoneCode = $("#phone-code");
            phoneCode.val({{ $user->phone_code }});
            if(Boolean($("#success").html()) && Boolean(sessionStorage.getItem('verified'))) sessionStorage.removeItem('verified');
            var tab = location.hash || sessionStorage.getItem('scrolling-id');
            if($(tab).length) {
                if(Boolean(sessionStorage.getItem('scrolling-id'))) {
                    location.hash = sessionStorage.getItem('scrolling-id');
                    sessionStorage.removeItem('scrolling-id');
                }
                $('html, body').animate({
                    scrollTop: $(tab).offset().top
                }, 1000);
                var tabb = $('.nav-tabs a[href="' + tab + '"]');
                tabb.click();
                setTimeout(function () {
                    tabb.click();
                }, 3000);
            }
            $('a[href="#timeline"]').on('click', function () {
                if(Boolean($.Nukura.hasNotice.html())) $.Nukura.query('/notice/deliver', {user_id:$.Nukura.auth}, function (data) {
                    if(data) {
                        $.Nukura.hasNotice.css('display','none');
                        $("#notice").css('display','none');
                    }
                });
            });
            var resetForm = $('#edit');
            var change = $("#change"), cancel=$('#cancel'),phone = $("#phone-number");
            change.on('click', function () {
                phone.removeAttr("disabled");
                phoneCode.removeAttr("disabled");
                phone.focus();
                change.hide();
                cancel.show();
            });
            cancel.on('click', function () {
                phone.val($("#current-phone").html());
                phoneCode.val($("#current-code").html());
                phone.attr("disabled", "disabled");
                phoneCode.attr("disabled", "disabled");
                save.removeAttr("disabled");
                change.show();
                cancel.hide();
                sendCode.hide();
                if(Boolean(sessionStorage.getItem('verified'))) sessionStorage.removeItem('verified');
            });
            phone.on('keyup', function(){
                if(event.keyCode === 13){
                    if(sendCode.css('display') !== "none") sendCode.click();
                }
                if(verifyContent.css("display") !== "none") {
                    verifyContent.hide();
                    sendCode.show();
                }
                var currentPhone = $("#current-phone").html();
                var verified = Boolean(sessionStorage.getItem('verified'))?sessionStorage.getItem('verified').split(","):["0","0"];
                var errorMessage = $("#phone-error-message"), already_reg = $("#already_registered").html(),$this=$(this), text = $(this).val().replace(/[^0-9 ]/i, "").replace(" ", "");
                $.Nukura.checkPhoneNumber($this, $("#phone-code").val(), function (data) {
                    if(data['has']) {
                        if(verified[0] === phone.val() || phone.val() === currentPhone){
                            sendCode.hide();
                            save.removeAttr("disabled");
                            phone.parent().addClass("has-success");
                        }
                        else {
                            $this.parent().addClass('has-error');
                            errorMessage.html(already_reg);
                            sendCode.hide()
                        }
                    }
                    else {
                        errorMessage.html("");
                        sendCode.removeAttr("disabled");
                        $this.parent().addClass('has-success');
                        sendCode.show();
                        $.Nukura.initializePhoneActivator('send-code');
                    }
                }, function () {
                    if($this.parent().hasClass('has-error')) {
                        errorMessage.html("");
                        $this.parent().removeClass('has-error');
                    }
                    else if($this.parent().hasClass('has-success')) $this.parent().removeClass('has-success');
                    sendCode.hide();
                    save.attr("disabled", "disabled");
                });
            });
            var notices = document.getElementsByClassName('notice-form');
            for(var i=0;i<notices.length;i++){
                $(notices[i]).on('submit', function () {
                    sessionStorage.setItem('scrolling-id', "#timeline");
                });
            }
            var verificationCode = $("#verificationcode");
            var sendCode = $('#send-code');
            var save = $('#save');
            var verify = $('#verify');
            var verifyContent = $('#verify-content');
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
                sessionStorage.setItem('scrolling-id', "#settings");
            });
            sendCode.on('click', function () {
                $.Nukura.sendActivationCode("+" + document.getElementById("phone-code").value + document.getElementById("phone-number").value, function () {
                    verifyContent.show();
                    sendCode.hide();
                });
            });
            verify.on('click', function () {
                $.Nukura.confirmActivationCode('verificationcode', function () {
                    new PNotify({
                        title: '{{ trans("app.success") }}',
                        text: document.getElementById('verified').innerHTML,
                        type: "success",
                        icon: "fa fa-check"
                    });
                    verifyContent.hide();
                    save.removeAttr('disabled');
                    sessionStorage.setItem('verified', [phone.val(), document.getElementById("verificationcode").value, phoneCode.val()])
                });
            });
            if (Boolean(sessionStorage.getItem("verified"))) {
                var ver = sessionStorage.getItem("verified").split(",");
                phone.val(ver[0]);
                phoneCode.val(ver[2]);
                verificationCode.val(ver[1]);
                phone.removeAttr('disabled');
                phoneCode.removeAttr('disabled');
                change.hide();
                cancel.show();

            }
            var newMsg = $("#new-message");
            newMsg.on('click', function () {
                $.Nukura.newMessage(this.value, $("#username").html(), $("#avatar").attr("src"));
            });
            if(window.location.hash === "#messenger") {
                newMsg.click();
            }
            $("#avatar-upload-btn").on('click', function () {
                $("#avatar-upload").click();
            });
            $("#avatar-upload").on("change", function () {
                $("#avatar-form").submit();
            });
            $("#avatar-form").on("submit", function (e) {
                e.preventDefault();
                $.Nukura.formSaver('/avatar/upload', this, function (link) {
                    new PNotify({
                        title: '{{ trans('app.success') }}',
                        text: '{{ trans('rent.updating_success') }}',
                        type: "success",
                        icon: "fa fa-check"
                    });
                    if(link) $("#user-avatar").attr("src", "/storage/"+link);
                }, function () {
                    new PNotify({
                        title: '{{ trans('app.error') }}',
                        text: '{{ trans('rent.connection_seems_dead') }}',
                        type: "error",
                        icon: "fa fa-warning"
                    });
                }, true);
            });
            $("#delete-avatar").on('click', function () {
                if(confirm("{{ trans('rent.confirm_deleting') }}")) $.Nukura.query('/avatar/delete', {}, function () {
                    $("#user-avatar").attr("src", "/storage/users/default.png");
                    $(".page-loading").fadeOut();
                });
            });
            $("#save-delivery").on('submit', function (e) {
                e.preventDefault();
                $.Nukura.formSaver('/users/delivery/{{ $user->id }}', this, function (deliveryText) {
                    new PNotify({
                        title: '{{ trans('app.success') }}',
                        text: '{{ trans('rent.updating_success') }}',
                        type: "success",
                        icon: "fa fa-check"
                    });
                    $(".delivery").html($("#delivery-edit").val());
                    if(!deliveryText) $(".no-delivery").css('display', 'inline-block');
                    else $(".no-delivery").css('display', 'none');
                }, function () {
                    new PNotify({
                        title: '{{ trans('app.error') }}',
                        text: '{{ trans('rent.connection_seems_dead') }}',
                        type: "error",
                        icon: "fa fa-warning"
                    });
                }, true);
            });
        });
    </script>
@endsection
