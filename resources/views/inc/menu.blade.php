<div class="search-mobile-container">
    <div class="search-mobile-close-btn" onclick="$('.search-mobile-container').hide()">
        <i class="fa fa-arrow-left"></i>
    </div>
    @widget('Search')
</div>
<div class="navbar-custom-menu">
    <div class="pull-right cart-container">
        <div class="cart-button disable-scroll" data-toggle="modal" data-target="#modal-orders" title="{{ trans('rent.shopping_cart') }}">
            <a title="{{ trans('rent.shopping_cart') }}">
                <span id="cart-quantity" class="label label-danger hidden"></span>
                <i class="fa fa-shopping-cart"></i>
            </a>
        </div>
    </div>
    <ul class="nav navbar-nav">
        <li class="visible-xs visible-sm search-mobile-open-btn" onclick="$('.search-mobile-container').show();$('.main-header input[name=query]').focus()">
            <a href="#" title="{{ __('rent.search') }}">
                <i class="fa fa-search"></i>
            </a>
        </li>
        <li>
            <a href="/item/create" aria-expanded="false" title="{{ trans('rent.new_item') }}">
                <i class="fa fa-plus"></i>
                <b class="add-text">{{ trans('rent.new_item') }}</b>
            </a>
        </li>
        <li>
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false" title="{{ trans('app.language') }}">
                <b>{{ mb_strtoupper(App::getLocale()) }}</b>
            </a>
            <ul class="dropdown-menu" style="left:0;width:auto">
                @foreach(config("app.locales") as $value)
                    <li><a style="color: #000;" href="?lang={{ $value }}">{{ mb_strtoupper($value) }}</a></li>
                @endforeach
            </ul>
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
                                <a href="#" class="form-control disable-scroll" id="message-btn" data-toggle="modal" data-target="#modal-message"><i class="fa fa-envelope"></i> <span id="has-message" class="label label-danger"></span></a>
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
<div class="hidden-sm hidden-xs">
    @widget('Search')
</div>
