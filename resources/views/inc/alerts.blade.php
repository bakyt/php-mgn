<script src="{{ asset('custom/pnotify/pnotify.custom.min.js') }}"></script>

{{-- Bootstrap Notifications using Prologue Alerts --}}
<script type="text/javascript">
  jQuery(document).ready(function($) {

    PNotify.prototype.options.styling = "bootstrap3";
    PNotify.prototype.options.styling = "fontawesome";
    @if($errors->any())
        @foreach ($errors->all() as $message)

            $(function(){
              new PNotify({
                title: "{{ trans("app.error") }}",
                text: "{!! $message !!}",
                type: "error",
                icon: "fa fa-warning"
              });
            });

        @endforeach
        @elseif(request()->session()->has("success"))
        $(function(){
            new PNotify({
                title: '{{ trans("app.success") }}',
                text: "{!! session()->get("success") !!}",
                type: "success",
                icon: "fa fa-check"
            });
        });
        @else
        if(Boolean(sessionStorage.getItem("success"))) {
            $(function () {
                new PNotify({
                    title: '{{ trans("app.success") }}',
                    text: sessionStorage.getItem("success"),
                    type: "success",
                    icon: "fa fa-check"
                });
                sessionStorage.removeItem("success");
            });
        }
    @endif
  });
</script>