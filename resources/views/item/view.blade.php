@extends("layouts.app")
@section("content")
    @include("inc.item")
@endsection
@section('after_styles')
    <link rel='stylesheet' href='{{ asset('plugins/unitegallery/themes/default/ug-theme-default.css')}}' type='text/css' />
    <link rel='stylesheet' href='{{ asset('plugins/unitegallery/css/unite-gallery.css')}}' type='text/css' />
@endsection
@section('after_scripts')
    <script type='text/javascript' src='{{ asset('plugins/unitegallery/js/unitegallery.min.js')}}'></script>
    <script type='text/javascript' src='{{ asset('plugins/unitegallery/themes/compact/ug-theme-compact.js')}}'></script>
    <script src="https://api-maps.yandex.ru/2.1/?load=Geolink&amp;lang={{ app()->getLocale() ==='en'?'en_US':'ru_RU' }}" type="text/javascript"></script>
    <script type="text/javascript">
        jQuery(document).ready(function(){
            jQuery("#gallery").unitegallery();
        });
        $(function () {
            if(window.location.hash === "#messenger") {
                $("#rent-auth-"+$.Nukura.request("messenger")).click();
                setTimeout(function () {
                    $("#rent-box-"+$.Nukura.request("messenger")).click();
                }, 500)
            }
            $.Nukura.viewPlus({{ $item->id }});
        });
    </script>
@endsection