@if(!Auth::guest())
<div class="user-panel">
    <div class="pull-left image">
        <img onclick="window.location.href='/users/{{ Auth::id() }}'" src="/storage/{{ Auth::user()->avatar }}" class="img-circle" alt="User Image">
    </div>
    <div class="pull-left info">
        <p><a href="/users/{{ Auth::id() }}">{{ Auth::user()->name }}</a></p>
        <a class="pull-left" href="/users/{{ Auth::id() }}"><i class="fa fa-circle text-success"></i> Online</a>
        <a class="pull-right" href="{{ route('logout') }}"
           onclick="event.preventDefault();
                   document.getElementById('logout-form').submit();">
            <i class="fa fa-btn fa-sign-out"></i> {{ trans('app.logout') }}</a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            {{ csrf_field() }}
        </form>
    </div>
</div>
<div id="user-menu-content-sidebar">
<div class="input-group" id="user-menu">
    <span class="input-group-btn">
        <a class="form-control btn btn-primary" id="message-btn" data-toggle="modal" data-target="#modal-message"><i class="fa fa-envelope"></i> <span id="has-message" class="label label-danger"></span></a>
    </span>
    <span class="input-group-btn">
        <a href="/users/{{ Auth::id() }}#timeline" id="timeline-btn" class="form-control btn btn-primary"><i class="fa fa-bell"></i> <span id="has-notice" class="label label-danger"></span></a>
    </span>
    @if(Auth::user()->role_id != 2) <span class="input-group-btn">
        <a href="/users/{{ Auth::id() }}#moderate" id="moderate-btn" class="form-control btn btn-primary"><i class="fa fa-briefcase"></i> <span id="has-moderate" class="label label-danger"></span></a>
    </span> @endif
    <span class="input-group-btn">
        <a href="/users/{{ Auth::id() }}#settings" id="settings-btn" class="form-control btn btn-primary"><i class="fa fa-cog"></i></a>
    </span>
</div>
</div>
@else
    @if(session()->has('guest'))
        <div class="user-panel">
            <div class="pull-left image">
                <img src="/storage/users/default.png" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p>{{ trans("auth.guest")." (".session()->get('guest').")" }}</p>
                <a class="pull-right" href="{{ route('logout.guest') }}"
                   onclick="event.preventDefault();
                   document.getElementById('logout-form').submit();">
                    <i class="fa fa-btn fa-sign-out"></i> {{ trans('app.logout') }}</a>
                <form id="logout-form" action="{{ route('logout.guest') }}" method="POST" style="display: none;">
                    {{ csrf_field() }}
                </form>
            </div>
        </div>
        <div id="user-menu-content-sidebar">
            <div class="input-group" id="user-menu">
    <span class="input-group-btn">
        <a class="form-control btn btn-primary" id="message-btn" data-toggle="modal" data-target="#modal-message"><i class="fa fa-envelope"></i> <span id="has-message" class="label label-danger"></span></a>
    </span>
            </div>
        </div>
    @endif
    <ul class="sidebar-menu">
        <li class="header">{{ trans('app.user') }}</li>
        <li><a href="/login"><i class="fa fa-btn fa-sign-in"></i><span>{{ trans('app.enter') }}</span></a></li>
        <li><a href="/register"><i class="fa fa-btn fa-user-plus"></i><span>{{ trans('app.register') }}</span></a></li>
        @if(!session()->has('guest'))
            <li class="cursor-pointer"><a id="message-btn" data-toggle="modal" data-target="#modal-message"><i class="fa fa-btn fa-sign-in"></i><span>{{ trans('auth.guest') }}</span></a></li>
        @endif
    </ul>
@endif