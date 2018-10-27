<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }} Error 404</title>

    <link href='//fonts.googleapis.com/css?family=Lato:100' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="{{ asset('plugins/select2/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">

    <link rel="stylesheet" href="{{ asset('dist/css/AdminLTE.css') }}">
    <!-- AdminLTE Skins. Choose a skin from the css/skins folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="{{ asset('dist/css/skins/'.config('app.skin').'.min.css') }}">


    <link rel="stylesheet" href="{{ asset('custom/pnotify/pnotify.custom.min.css') }}">
    <!-- BackPack Base CSS -->
    <link rel="stylesheet" href="{{ asset('css/nukurax.base.css') }}">
  </head>
  <body>

      <div class="content">
        <div class="container">
        <div class="error-page" style="position: absolute; top:30%;margin-right:auto;margin-left:auto">
          <h2 class="headline text-yellow" style="margin-top:0"> 404</h2>

          <div class="search-page">
            <h3><i class="fa fa-warning text-yellow"></i> Oops! Page not found.</h3>

            <p>
              We could not find the page you were looking for.
              Meanwhile, you may <a href="{{ route('home') }}">return to dashboard</a> or try using the search form.
            </p>

            <div class="row">
              @widget('search')
            </div>
          </div>
          <!-- /.error-content -->
        </div>
      </div>
    </div>
    <script src="{{ asset('plugins/jQuery/jquery-2.2.3.min.js') }}"></script>
    <script src="{{ asset('bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('plugins/select2/select2.min.js') }}"></script>
      <script src="{{ asset('js/site.js') }}"></script>

      <script>
      $(function () {
          $(".select2").select2();
      });
  </script>
  </body>
</html>
