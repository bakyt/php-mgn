<div class="navbar-custom-menu">
    <ul class="nav navbar-nav">
        <li>
            <a href="#" class="dropdown-toggle" onclick="setTimeout(function(){$('#searching').focus()},100)" data-toggle="dropdown" aria-expanded="false">
                <i class="fa fa-search"></i>
            </a>
            <div class="dropdown-menu pull-left" style="background:#555299;width: 300px;">
                <input value="{{ request()->has('query')?request()->get('query'):"" }}" tabindex="0" id="searching" title="{{ trans('rent.search_market', ['name'=>$Market->name]) }}" autocomplete="off" name="query" class="form-control my-search-input dropdown-toggle" placeholder="{{ trans('rent.search_market', ['name'=>$Market->name]) }}" type="search">
                <div id="my-drop-parameter" onclick="$(this).hide()" style="background: #ffffff" class="dropdown-menu my-drop-parameter scroll"></div>
                <div class="type-what-to-serach" style="display: none"></div>
                <div class="search-results" style="display: none"><span class="users-list-date">{{ trans('rent.search_results') }}:</span></div>
            </div>
        </li>
        <li>
                <a href="#" data-toggle="modal" data-target="#modal-orders" title="{{ trans('rent.shopping_cart') }}">
                    <i class="fa fa-shopping-cart"></i>
                    <b class="hidden-xs">{{ trans('rent.shopping_cart') }}</b>
                    <span id="cart-quantity" class="label label-danger hidden"></span>
                </a>
        </li>
        <li class="dropdown user user-menu">
            <!-- Menu Toggle Button -->
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                <!-- The user image in the navbar-->
                @if(!Auth::guest())
                    <img src="/storage/{{ Auth::user()->avatar }}" class="user-image" alt="User Image">
                    <span class="hidden-xs">{{ Auth::user()->name }}</span>
                    <span id="user-notice" style="display: none" class="label label-danger">0</span>
                @else
                    <i class="fa fa-user"></i>
            @endif
            <!-- hidden-xs hides the username on small devices so only the image appears. -->
            </a>
            <ul class="dropdown-menu">
            @if(!Auth::guest())
                <!-- The user image in the menu -->
                    <li class="user-header">
                        <img src="/storage/{{ Auth::user()->avatar }}" class="img-circle" alt="User Image">

                        <p>
                            {{ Auth::user()->name }}
                            <small>{{ Auth::user()->role->display_name }}</small>
                        </p>
                    </li>
                    <!-- Menu Body -->
                    <li class="user-body">
                        <div class="row">
                            <div class="col-xs-3 text-center">
                                <a href="#" class="form-control" id="message-btn" data-toggle="modal" data-target="#modal-message"><i class="fa fa-envelope"></i> <span id="has-message" class="label label-danger"></span></a>
                            </div>
                            <div class="col-xs-3 text-center">
                                <a href="/users/{{ Auth::id() }}#timeline" id="timeline-btn" class="form-control"><i class="fa fa-bell"></i> <span id="has-notice" class="label label-danger"></span></a>
                            </div>
                            <div class="col-xs-3 text-center">
                                <a href="/users/{{ Auth::id() }}#settings" id="settings-btn" class="form-control"><i class="fa fa-cog"></i></a>
                            </div>
                            @if(Auth::user()->role_id != 2)
                                <div class="col-xs-3 text-center">
                                    <a href="/users/{{ Auth::id() }}#moderate" id="moderate-btn" class="form-control"><i class="fa fa-briefcase"></i> <span id="has-moderate" class="label label-danger"></span></a>
                                </div>
                            @endif
                        </div>
                        <!-- /.row -->
                    </li>
                    <!-- Menu Footer-->
                    <li class="user-footer">
                        <div class="pull-left">
                            <a href="/users/{{ Auth::id() }}" class="btn btn-default btn-flat">{{ trans('app.profile') }}</a>
                        </div>
                        <div class="pull-right">
                            <a href="{{ route('logout') }}" class="btn btn-default btn-flat" onclick="event.preventDefault();
                   document.getElementById('logout-form').submit();"><i class="fa fa-btn fa-sign-out"></i> {{ trans('app.logout') }}</a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                {{ csrf_field() }}
                            </form>
                        </div>
                    </li>
            </ul>
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
        <li><a style="color: #000;" href="/login"><i class="fa fa-btn fa-sign-in"></i><span>{{ trans('app.enter') }}</span></a></li>
        <li><a style="color: #000;" href="/register"><i class="fa fa-btn fa-user-plus"></i><span>{{ trans('app.register') }}</span></a></li>
        @if(!session()->has('guest'))
            <li class="cursor-pointer"><a style="color: #000;" id="message-btn" data-toggle="modal" data-target="#modal-message"><i class="fa fa-btn fa-sign-in"></i><span>{{ trans('auth.guest') }}</span></a></li>
        @endif
        @endif
    </ul>
</div>
<div class="navbar-custom-menu pull-left">
    <ul class="nav navbar-nav">
        <li>@if(Auth::id() == $Market->administrator or auth()->check() and auth()->user()->role_id == 1)
                <a href="/{{ $Market->slug }}/create" aria-expanded="false" title="{{ trans('rent.new_product') }}">
                    <i class="fa fa-plus"></i>
                    <b class="hidden-xs">{{ trans('rent.new_product') }}</b>
                </a>
            @else
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false" title="{{ trans('app.language') }}">
                <b>{{ mb_strtoupper(App::getLocale()) }}</b>
            </a>
            <ul class="dropdown-menu" style="left:0;width:auto">
                @foreach(config("app.locales") as $value)
                    <li><a style="color: #000;" href="?lang={{ $value }}">{{ mb_strtoupper($value) }}</a></li>
                @endforeach
            </ul>
            @endif
        </li>
    </ul>
</div>