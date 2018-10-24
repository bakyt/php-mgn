$(function(){
    $.get( "/location/get", {id:1}, function (locations) {
        var location = $('.level-1');
        var selectOpen = '<select name="address[0]" onchange="prepareChildren($(this).val(), 1)" class="address_0 form-control select2">';
        var selectClose = '</select>';
        var options="<option value=0>"+$('.text_select').html()+"</option>";
        var old_address = $('.old_address').html().toString().split("~")[0];
        for (var key in locations) {
            options += '<option value="' + locations[key].id + '">' + locations[key].name + '</option>';
        }
        for(var i=0;i<location.length;i++) $(location).eq(i).before($(selectOpen+options+selectClose));
        if(old_address) {
            $(".address_0").val(old_address);
            prepareChildren(old_address, 1);
        }
    });
});
function prepareChildren(id, level){
    $.get( "/location/get", {id:id}, function (locations) {
        var location = $('.level-'+level);
        var selectOpen = '<select name="address['+level+']" onchange="prepareChildren($(this).val(), '+(level+1)+')" class="address_'+level+' form-control select2">';
        var selectClose = '</select><div class="level-'+(level+1)+'"></div>';
        var options="<option value=0>"+$('.text_select').html()+"</option>";
        var old_address = ($('.old_address').html()).toString().split("~")[level];
        for (var key in locations) {
            options += '<option value="' + locations[key].id + '">' + locations[key].name + '</option>';
        }
        if(key) location.html(selectOpen+options+selectClose);
        //else if(parseInt(id) !== 0 && $("#street").length) location.html("<input type='text' reqiured id='address_"+level+"' class='form-control' name='address_text' placeholder='"+$("#street").html()+"' />");
        else location.html("");
        if(old_address){
            $(".address_"+level).val(old_address);
            prepareChildren(old_address, level+1);
        }
    });
}