@extends('layouts.app')
@section("content")
{{--<form action="{{ route('test.store') }}" method="post" enctype="multipart/form-data">--}}
    {{--{{ csrf_field() }}--}}
    {{--<input type="file" name="image[]" multiple/>--}}
    {{--<button type="submit">upload</button>--}}
{{--</form>--}}
    {{--<img src="/storage/temp/newfile-1280x720.jpeg"/>--}}
    {{--<img src="/storage/temp/newfile-770x550.jpeg"/>--}}
<form enctype="multipart/form-data" method="post" action="http://www.super.kg/api/tester" id="form">
<div class="tab" data-id="6" style="display: block;">
    <div class="archive-desc">
        <textarea name="archive-desc" id="archive-desc" placeholder="источник"></textarea>
    </div>
    <br>
    <div>
        Аудио файлдар <input type="file" name="audios[]">
    </div>
    <br><br>
    <div>
        Видео файлдар <input type="file" name="videos[]">
    </div>
    <br><br>
    <div>
        Башка файлдар <input type="file" name="others[]">
    </div>
    <button type="submit">Send</button>
</div>
</form>
@endsection
@section('after_scripts')
<script>
    $("#form").on('submit', function(e){
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: 'http://www.super.kg/api/tester',
            processData: false, // important
            contentType: false, // important
            data:new FormData(this),
            dataType:'json',
            success: function(msg){
                alert(msg);
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                alert(errorThrown);
            }
        });
    });
</script>
@endsection