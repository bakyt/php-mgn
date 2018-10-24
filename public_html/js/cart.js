$(function () {
    var  truncate = $(".truncate"),temp, total=$(".total"), table=$("#order-table-abstract"), totalText = $('.total-price').html(), somText = $('.som-text').html(), ordersBox = $("#orders-box"), cartQuantity=$("#cart-quantity");
    if(!Boolean(sessionStorage.getItem("cart"))) {
        sessionStorage.setItem("cart", JSON.stringify({}));
    }
    else if(sessionStorage.getItem("cart")){
        var markets = JSON.parse(sessionStorage.getItem("cart"));
        if(!jQuery.isEmptyObject(markets) && cartQuantity.hasClass('hidden')){
            cartQuantity.removeClass('hidden');
            cartQuantity.html(0);
        }
        for (var key in markets) {
            if(markets.hasOwnProperty(key)) {
                table.children().attr('id', 'orders-table-' + key);
                ordersBox.append("<div class='order-box panel panel-default flat'><div class='panel-heading'><div data-quantity='0' data-price='0' data-id='"+key+"' class='remove-order-table cursor-pointer pull-right' ><i class='fa fa-close'></i> </div><a href='" + markets[key]['address'] + "'>" + markets[key]['name'] + "</a></div><div style='overflow: auto' class='panel-body no-padding'>" + table.html()+"</div><div class='panel-footer text-right'> "+totalText+": <div class='total-table-"+key+"' style='display: inline-block'></div>"+somText+"</div></div>");
                table.children().removeAttr('id');
                var orders = markets[key].orders, quantity=0, price=0;
                temp = $("#orders-table-" + key + " > tbody");
                for (var koy in orders) {
                    if(orders.hasOwnProperty(koy)) {
                        quantity+=orders[koy].quantity;
                        price+=orders[koy].price;
                        temp.append("<tr id='order-"+orders[koy].id+"'><td>"+orders[koy].quantity+"</td><td><a href='/"+orders[koy].address+"'>"+orders[koy].name+"</a></td><td>"+orders[koy].price+"</td><td><a href='/list/"+orders[koy].category.id+"'>"+orders[koy].category.name+"</a> <i class='fa fa-trash'></i> </td></tr>");
                        deleteItem($("#order-"+orders[koy].id).find('.fa-trash'));
                    }
                }
                $('.total-table-'+key).html(price);
                total.html(parseInt(total.html())+price);
                cartQuantity.html(parseInt(cartQuantity.html())+quantity);
                deleteBtnUpdate(temp.parent().parent().parent().find('.remove-order-table'));
            }
        }
    }
    cartQuantity.on("click", function () {
        this.style = "width:auto;height:auto;font-size:8pt;";
    });
    $("#cartFunctionLoader").on('click', function () {
        addToCartUpdate($(".add-to-cart-"+$("#currentElemForCart").val()));
    });
    $(".plus-quantity").on('click', function () {
        var field = $(this).parent().parent().find('input[name=quantity]');
        if(!field.val()) field.val(1);
        else field.val(parseInt(field.val())+1);
    });
    $(".minus-quantity").on('click', function () {
        var field = $(this).parent().parent().find('input[name=quantity]');
        if(!field.val()) field.val(1);
        else if(field.val() !== "1") field.val(parseInt(field.val())-1);
    });
    truncate.on('click', function () {
        sessionStorage.setItem("cart", "{}");
        ordersBox.html("");
        cartQuantity.html(0);
        cartQuantity.addClass('hidden');
        total.html(0)
    });
    if(window.location.hash === "#truncate") {
        truncate.click();
    }
    function addToCartUpdate($thus){
        $thus.on('click', function () {
            var $this = $(this), input, item, author, oldItem, user=true, has=false, marketTable=null, userTable=null;
            input = $this.parent().find('input[name=quantity]');
            if($this.data("marketSlug")) {
                user = false;
                marketTable = $("#orders-table-"+$this.data("marketSlug")+" > tbody");
                if(marketTable.length) has=true;
                else {
                    if($this.data('delivery') && !confirm($this.data('delivery'))) return false;
                    temp = JSON.parse(sessionStorage.getItem("cart"));
                    temp[$this.data("marketSlug")] = {
                        type:"market",
                        slug:$this.data("marketSlug"),
                        name:$this.data("marketName"),
                        address:"/"+$this.data("marketSlug"),
                        orders:{},
                        total:0
                    };
                    sessionStorage.setItem("cart", JSON.stringify(temp));
                }
                item = "<tr id='order-"+$this.data("id")+"'><td>"+(input.val()?parseInt(input.val()):1)+"</td><td><a href='/"+$this.data('marketSlug')+"/view/"+$this.data('id')+"'>"+$this.data("name")+"</a></td><td>"+(input.val()?parseInt($this.data("price"))*parseInt(input.val()):$this.data("price"))+"</td><td><a href='/"+$this.data('marketSlug')+"/list/"+$this.data('categoryId')+"'>"+$this.data('categoryName')+"</a> <i class='fa fa-trash'></i></td></tr>";
                author = "<a href='/"+$this.data("marketSlug")+"'>"+$this.data("marketName")+"</a>";
            }
            else{
                item = "<tr id='order-"+$this.data("id")+"'><td>"+(input.val()?parseInt(input.val()):1)+"</td><td><a href='/view/"+$this.data('id')+"'>"+$this.data("name")+"</a></td><td>"+$this.data("price")+"</td><td><a href='/list/"+$this.data('categoryId')+"'>"+$this.data('categoryName')+"</a> <i class='fa fa-trash'></i></td></tr>";
                author = "<a href='/users/"+$this.data("userId")+"'>"+$this.data("userName")+"</a>";
                userTable = $("#orders-table-"+$this.data("userId")+"---markets > tbody");
                if(userTable.length) has=true;
                else {
                    if(!confirm($this.data('delivery'))) return false;
                    temp = JSON.parse(sessionStorage.getItem("cart"));
                    temp[$this.data("userId")+"---markets"] = {
                        type:"user",
                        id:$this.data("userId"),
                        name:$this.data("userName"),
                        address:"/users/"+$this.data("userId"),
                        orders:{},
                        total:0
                    };
                    sessionStorage.setItem("cart", JSON.stringify(temp));
                }
            }
            if(!has) {
                var currentType = user?$this.data("userId")+"---markets":$this.data("marketSlug");
                table.children().attr('id', 'orders-table-'+currentType);
                ordersBox.append("<div class='order-box panel panel-default'><div class='panel-heading'><div data-id='"+currentType+"' class='remove-order-table cursor-pointer pull-right'><i class='fa fa-close'></i> </div>"+author+"</div><div style='overflow: auto' class='panel-body no-padding'>"+table.html()+"</div><div class='panel-footer text-right'> "+totalText+": <div class='total-table-"+currentType+"' style='display: inline-block'></div>"+somText+"</div></div>");
                table.children().removeAttr('id');
                marketTable = $("#orders-table-"+$this.data("marketSlug")+" > tbody");
                userTable = $("#orders-table-"+$this.data("userId")+"---markets > tbody");
                if(cartQuantity.hasClass('hidden')){
                    cartQuantity.removeClass('hidden');
                    cartQuantity.html(0);
                }
            }
            oldItem = $("#order-"+$this.data("id"));
            if(user) {
                if(oldItem.length) {
                    oldItem.find("td:eq(2)").html(parseInt(oldItem.find("td:eq(2)").html())+parseInt($this.data("price"))*(input.val()?parseInt(input.val()):1));
                    oldItem.children().first().html(parseInt(oldItem.children().first().html())+(input.val()?parseInt(input.val()):1));
                    temp = JSON.parse(sessionStorage.getItem("cart"));
                    temp[$this.data("userId")+"---markets"].orders[$this.data("id")].quantity+=(input.val()?parseInt(input.val()):1);
                    temp[$this.data("userId")+"---markets"].orders[$this.data("id")].price+=parseInt($this.data("price"))*(input.val()?parseInt(input.val()):1);
                }
                else {
                    userTable.append(item);
                    deleteBtnUpdate(userTable.parent().parent().parent().find('.remove-order-table'));
                    deleteItem($("#order-"+$this.data('id')).find('.fa-trash'));
                    temp = JSON.parse(sessionStorage.getItem("cart"));
                    temp[$this.data("userId")+"---markets"].orders[$this.data("id")] = {
                        id:$this.data("id"),
                        quantity:input.val()?parseInt(input.val()):1,
                        name:$this.data("name"),
                        price:parseInt($this.data("price"))*(input.val()?parseInt(input.val()):1),
                        address:"view/"+$this.data("id"),
                        category:{
                            id:$this.data('categoryId'),
                            name:$this.data('categoryName')
                        }
                    };
                }
                var currentPrice = parseInt($this.data("price"))*(input.val()?parseInt(input.val()):1);
                temp[$this.data("userId")+"---markets"].total+=currentPrice;
                $('.total-table-'+$this.data("userId")+"---markets").html(temp[$this.data("userId")+"---markets"].total);
                total.html(parseInt(total.html())+currentPrice);
                sessionStorage.setItem('cart', JSON.stringify(temp));
                cartQuantity.animate({height:100, fontSize:100}, 200);
                setTimeout(function () {
                    cartQuantity.html(parseInt(cartQuantity.html())+(input.val()?parseInt(input.val()):1));
                }, 500);
                setTimeout(function () {
                    cartQuantity.css("width","auto");
                    cartQuantity.css("height","auto");
                    cartQuantity.css("font-size","8pt");
                }, 800);

            }
            else {
                if(oldItem.length) {
                    oldItem.find("td:eq(2)").html(parseInt(oldItem.find("td:eq(2)").html())+(input.val()?parseInt($this.data('price'))*parseInt(input.val()):$this.data('price')));
                    oldItem.children().first().html(parseInt(oldItem.children().first().html())+(input.val()?parseInt(input.val()):1));
                    temp = JSON.parse(sessionStorage.getItem("cart"));
                    temp[$this.data("marketSlug")].orders[$this.data("id")].quantity+=(input.val()?parseInt(input.val()):1);
                    temp[$this.data("marketSlug")].orders[$this.data("id")].price+=parseInt($this.data("price"))*(input.val()?parseInt(input.val()):1);
                }
                else {
                    deleteBtnUpdate(marketTable.parent().parent().parent().find('.remove-order-table'));
                    marketTable.append(item);
                    deleteItem($("#order-"+$this.data('id')).find('.fa-trash'));
                    temp = JSON.parse(sessionStorage.getItem("cart"));
                    //alert();
                    temp[$this.data("marketSlug")].orders[$this.data("id")] = {
                        id:$this.data("id"),
                        quantity:input.val()?parseInt(input.val()):1,
                        name:$this.data("name"),
                        price:parseInt($this.data("price"))*(input.val()?parseInt(input.val()):1),
                        address:$this.data("marketSlug")+"/view/"+$this.data("id"),
                        category:{
                            id:$this.data('categoryId'),
                            name:$this.data('categoryName')
                        }
                    };
                }
                var curPrice = parseInt($this.data("price"))*(input.val()?parseInt(input.val()):1);
                temp[$this.data("marketSlug")].total+=curPrice;
                $('.total-table-'+$this.data("marketSlug")).html(temp[$this.data("marketSlug")].total);
                total.html(parseInt(total.html())+curPrice);
                sessionStorage.setItem('cart', JSON.stringify(temp));
                cartQuantity.animate({height:100, fontSize:100}, 200);
                setTimeout(function () {
                    cartQuantity.html(parseInt(cartQuantity.html())+(input.val()?parseInt(input.val()):1));
                }, 500);
                setTimeout(function () {
                    cartQuantity.css("width","auto");
                    cartQuantity.css("height","auto");
                    cartQuantity.css("font-size","8pt");
                }, 800);
            }
        });
    }
    addToCartUpdate($(".add-to-cart"));
    function deleteBtnUpdate(closeButton){
        closeButton.on('click', function () {
            var quantity = 0, check=false;
            temp = JSON.parse(sessionStorage.getItem("cart"));
            if(typeof temp[$(this).data('id')].orders !== 'undefined')
                for (var koy in temp[$(this).data('id')].orders) {
                check=true;
                if(temp[$(this).data('id')].orders.hasOwnProperty(koy)) {
                    quantity+=parseInt(temp[$(this).data('id')].orders[koy].quantity);
                }
            }
            if(!check) return false;
            if(parseInt(cartQuantity.html())-quantity){
                cartQuantity.html(parseInt(cartQuantity.html())-quantity);
                total.html(parseInt(total.html())-temp[$(this).data("id")].total);
            }
            else {
                total.html(0);
                cartQuantity.addClass('hidden');
            }
            delete temp[$(this).data("id")];
            sessionStorage.setItem('cart', JSON.stringify(temp));
            $(this).parent().parent().remove();
        });
    }
    function deleteItem(item){
        item.on('click', function () {
            var tr = item.parent().parent(), tbody = tr.parent(), table = tbody.parent(), tableId = table.attr('id').replace('orders-table-', '');
            temp = JSON.parse(sessionStorage.getItem('cart'));
            var temporary = tr.attr('id').replace('order-', '');
            total.html(parseInt(total.html())-temp[tableId].orders[temporary].price);
            cartQuantity.html(parseInt(cartQuantity.html())-temp[tableId].orders[temporary].quantity);
            if(cartQuantity.html() === '0') cartQuantity.addClass('hidden');
            tr.remove();
            if(!tbody.find('tr').length) {
                table.parent().parent().remove();
                delete temp[tableId];
            }
            else {
                var tot = $(".total-table-"+tableId);
                temp[tableId].total = parseInt(tot.html())-temp[tableId].orders[temporary].price;
                tot.html(temp[tableId].total);
                delete temp[tableId].orders[temporary];
            }
            sessionStorage.setItem('cart', JSON.stringify(temp));
        });
    }
    $("#client-form").on('click', function () {
        if(!total.html() || total.html() === "0"){
            new PNotify({
                text: document.getElementById('order_cant_be_empty').innerHTML,
                type: "error"
            });
        }
        else {
            $("#cart-items").val(sessionStorage.getItem('cart'));
            $("#cart-order").slideToggle();
            $(this).hide();
            $("#client-send-code").removeClass("hidden");
        }
    });
    $.Nukura.initializePhoneActivator('client-send-code');
    $.Nukura.initPhoneField("client-phone-code", "client-phone-number", true);
    var formcheck = false, sendCode = $('#client-send-code'), order = $("#client-order");
    $('#client-phone-number').on('input', function(){
        if(this.value === "0") this.value = "";
    });
    sendCode.on('click', function () {
        $("#loading").fadeIn();
        $.Nukura.sendActivationCode("+" + document.getElementById("client-phone-code").value + document.getElementById("client-phone-number").value, function () {
            sendCode.hide();
            order.removeClass('hidden');
            $("#code-resend").removeClass('hidden');
            $('#client-code-content').show();
            new PNotify({
                title: document.getElementById('success').innerHTML,
                text: document.getElementById('code-sent').innerHTML,
                type: "success",
                icon: "fa fa-check"
            });
            $("#loading").fadeOut();
        }, function (error) {
            //alert(error);
            $("#loading").fadeOut();
        });
    });
    $('#client-verify').on('click', function () {
        $.Nukura.confirmActivationCode('vercode', function () {
            formcheck = true;
            new PNotify({
                title: document.getElementById('success').innerHTML,
                text: document.getElementById('verified').innerHTML,
                type: "success",
                icon: "fa fa-check"
            });
            $('#client-confirm-content').slideToggle();
            order.removeAttr('disabled');
            order.click();
            order.attr('disabled', 'disabled');
        }, function (error) {
            new PNotify({
                //title: '{{ trans("app.success") }}',
                text: error,
                type: "error",
                icon: "fa fa-close"
            });
        });
    });
    $("#cart").on('submit', function (e) {
        if(!formcheck) {
            e.preventDefault();
            $("#client-send-code").click();
            $("#loading").fadeIn();
        }
        else {
            $("#loading").fadeIn();
        }
    })
});