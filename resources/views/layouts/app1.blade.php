@yield('php')
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="@if(isset($Description)){{ $Description }}@endif">
    <meta name="keywords" content="@if(isset($Keywords)){{ $Keywords }}@endif">
    <meta property="og:title" content="{{ (isset($title_add)?$title_add.": ":"").(isset($title) ? $title.(isset($title_right)?$title_right:"").' - '.setting('site.title') : setting('site.title')) }}">
    <meta property="og:image" content="@if(isset($Image)){{ 'https://ijara.kg/storage/'.$Image }}@else{{ 'https://ijara.kg/storage/'.setting('site.logo') }}@endif">
    <meta property="og:description" content="@if(isset($Description)){{ $Description }}@endif">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website">
    {{-- Encrypted CSRF token for Laravel, in order for Ajax requests to work --}}
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <title>
      {{ (isset($title_add)?$title_add.": ":"").(isset($title) ? $title.(isset($title_right)?$title_right:"").' - '.setting('site.title') : setting('site.title')) }}
    </title>

    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.5 -->
    <link rel="icon" href="/favicon.ico" type="image/x-icon"/>
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
<link rel="manifest" href="/site.webmanifest">
<link rel="mask-icon" href="/safari-pinned-tab.svg" color="#3b286e">
<meta name="msapplication-TileColor" content="#603cba">
<meta name="theme-color" content="#ffffff">
    @yield('before_styles')
    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">

    <link rel="stylesheet" href="{{ asset('dist/css/AdminLTE.css') }}">
    <!-- AdminLTE Skins. Choose a skin from the css/skins folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="{{ asset('dist/css/skins/'.config('app.skin').'.min.css') }}">


    <link rel="stylesheet" href="{{ asset('custom/pnotify/pnotify.custom.min.css') }}">

    <!-- BackPack Base CSS -->
    <link rel="stylesheet" href="{{ asset('css/nukurax.base.css') }}">

    @yield('after_styles')
    <!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-118476778-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-118476778-1');
</script>

    <!--<script src="/bower_components/angular/angular.min.js"></script>
    <script src="/bower_components/lodash/lodash.js"></script>
    <script src="/bower_components/angular-route/angular-route.min.js"></script>
    <script src="/bower_components/angular-local-storage/dist/angular-local-storage.min.js"></script>
    <script src="/bower_components/restangular/dist/restangular.min.js"></script>-->

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <!-- jQuery 2.2.3 -->
    {{--<script src="https://code.jquery.com/jquery-2.2.3.min.js"></script>--}}
    <script src="{{ asset('plugins/jQuery/jquery-2.2.3.min.js') }}"></script>
    @yield('after_jquery')
</head>
<body id="body" class="{{ config('app.skin') }} sidebar-mini fixed layout-boxed scrollBody">
	<script type="text/javascript">
		/* Recover sidebar state */
		(function () {
            document.getElementById("body").style.overflow = "false";
			if (Boolean(sessionStorage.getItem('sidebar-toggle-collapsed'))) {
				var body = document.getElementsByTagName('body')[0];
				body.className = body.className + ' sidebar-collapse';
			}
		})();
	</script>
    <!-- Site wrapper -->
    <div class="wrapper">
        <header class="main-header">
        <!-- Logo -->
        <a href="/" class="logo">
          <!-- mini logo for sidebar mini 50x50 pixels -->
          <span class="logo-mini" style="text-align:center;"><img style="height:28px;float:none;" src="/storage/{{ setting('site.logo') }}"></span>
          <!-- logo for regular state and mobile devices -->
          <span class="logo-lg" style="position:relative"><img style="height:28px;position:absolute;left:-10px;top:10px;" src="/storage/{{ setting('site.logo') }}">{!! setting('site.title') !!}</span>
        </a>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top" role="navigation">
          <!-- Sidebar toggle button-->
          <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>

          @include('inc.menu')
        </nav>
      </header>

      <!-- =============================================== -->

      @include('inc.sidebar')

      <!-- =============================================== -->

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
          @if(request()->decodedPath()!="/")
          <section class="content-header">
              <h1>
                  {{ trim(isset($title)?$title:"") }}
              </h1>
                  <ol class="breadcrumb">
                      <li><a href="/"><i class="fa fa-home"></i> {{ trans("app.home") }}</a></li>
                      @if(isset($pathway)) @foreach($pathway as $way)
                          @if(@$way['link'])
                              <li><a href="{{ $way['link'] }}">@if(@$way['icon']) <i class="{{ $way['icon'] }}"></i> @endif {{ $way['title'] }}</a></li>
                          @else
                          <li class="active">@if(@$way['icon']) <i class="{{ $way['icon'] }}"></i> @endif{{ $way['title'] }}</li>
                          @endif
                      @endforeach
                      @elseif(isset($title))
                          <li class="active">{{ $title }}</li>
                      @endif
                  </ol>
          </section>
        @endif
        <!-- Main content -->
          @yield('after_header')
        <section class="content">

          @yield('content')

        </section>
        <!-- /.content -->
      </div>
      <!-- /.content-wrapper -->
      @include('inc.pace')
        @include('inc.messenger')
      <footer class="main-footer">
            <div id="net-kg" class="pull-right">
            </div>
          <div id="user-menu-content"></div>
          </footer>


    </div>
    <p id="user-id" style="display: none">@if(Auth::check()){{ Auth::id() }}@elseif(session()->has('guest')) {{ session()->get('guest') }} @endif</p>
    <p id="current-id" style="display: none"></p>
    <!-- ./wrapper -->
    @yield('before_scripts')
    <!-- Bootstrap 3.3.5 -->
    <script src="{{ asset('bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('plugins/slimScroll/jquery.slimscroll.min.js') }}"></script>
    <script src="{{ asset('plugins/input-mask/jquery.inputmask.js') }}"></script>
    <script src="{{ asset('plugins/input-mask/jquery.inputmask.date.extensions.js') }}"></script>
    <script src="{{ asset('plugins/input-mask/jquery.inputmask.extensions.js') }}"></script>
    <script src="{{ asset('plugins/fastclick/fastclick.js') }}"></script>
    <script src="{{ asset('dist/js/app.js') }}"></script>
    <script src="https://www.gstatic.com/firebasejs/4.8.1/firebase.js"></script>
    <script src="{{ asset('js/site.js') }}"></script>
    <!--<script src="{{ asset('js/angularRedirecting.js') }}"></script>-->

    <!-- page script -->
    
    <script type="text/javascript">
        jQuery(window).load(function () {
            $('body').css("overflow", "auto");
        });
        /* Store sidebar state */
        $('.sidebar-toggle').click(function(event) {
          event.preventDefault();
          if (Boolean(sessionStorage.getItem('sidebar-toggle-collapsed'))) {
            sessionStorage.setItem('sidebar-toggle-collapsed', '');
          } else {
            sessionStorage.setItem('sidebar-toggle-collapsed', '1');
          }
        });

        // Set active state on menu element
        var current_url = "{{ $_SERVER['REQUEST_URI'] }}";
        var full_url = current_url+location.search;
        var $navLinks = $("ul.sidebar-menu li a");
        // First look for an exact match including the search string
        var $curentPageLink = $navLinks.filter(
            function() { return $(this).attr('href') === full_url; }
        );
        // If not found, look for the link that starts with the url
        if(!$curentPageLink.length > 1){
            $curentPageLink = $navLinks.filter(
                function() { return $(this).attr('href').startsWith(current_url) || current_url.startsWith($(this).attr('href')); }
            );
        }

        $curentPageLink.parents('li').addClass('active');
        document.addEventListener("DOMContentLoaded", function() {
            var elements = document.getElementsByTagName("INPUT");
            for (var i = 0; i < elements.length; i++) {
                elements[i].oninvalid = function(e) {
                    e.target.setCustomValidity("");
                    if (!e.target.validity.valid) {
                        e.target.setCustomValidity("{{ trans('app.please_fill_out_this_field') }}");
                    }
                };
                elements[i].oninput = function(e) {
                    e.target.setCustomValidity("");
                };
            }
        })

    </script>
    @include('inc.alerts')

    @yield('after_scripts')
    <!-- WWW.NET.KG , code for http://ijara.kg -->
<script language="javascript" type="text/javascript">
 java="1.0";
 java1=""+"refer="+escape(document.referrer)+"&amp;page="+escape(window.location.href);
 document.cookie="astratop=1; path=/";
 java1+="&amp;c="+(document.cookie?"yes":"now");
</script>
<script language="javascript1.1" type="text/javascript">
 java="1.1";
 java1+="&amp;java="+(navigator.javaEnabled()?"yes":"now");
</script>
<script language="javascript1.2" type="text/javascript">
 java="1.2";
 java1+="&amp;razresh="+screen.width+'x'+screen.height+"&amp;cvet="+
 (((navigator.appName.substring(0,3)=="Mic"))?
 screen.colorDepth:screen.pixelDepth);
</script>
<script language="javascript1.3" type="text/javascript">java="1.3"</script>
<script language="javascript" type="text/javascript">
 java1+="&amp;jscript="+java+"&amp;rand="+Math.random();
 document.getElementById("net-kg").innerHTML = ("<a href='https://www.net.kg/stat.php?id=6122&amp;fromsite=6122' target='_blank'>"+
 "<img src='https://www.net.kg/img.php?id=6122&amp;"+java1+
 "' border='0' alt='WWW.NET.KG' width='21' style='position:absolute;right:10px;bottom:5px;' height='16' /></a>");
</script>
<noscript>
 <a href='https://www.net.kg/stat.php?id=6122&amp;fromsite=6122' target='_blank'><img
  src="https://www.net.kg/img.php?id=6122" border='0' alt='WWW.NET.KG' width='21'
  height='16' /></a>
</noscript>
<!-- /WWW.NET.KG -->

    <!-- JavaScripts -->
     <script src="{{ mix('js/app.js') }}"></script>
</body>
</html>
