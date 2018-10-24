$(function () {
    $('#phone-number-guest').on('input', function () {
        var text = $(this).val().replace(/[^0-9 ]/i, "").replace(" ", "");
        $(this).val(text==="0"?'':text);
    });
});