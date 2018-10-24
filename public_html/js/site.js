$(function () {
    $.get("/timezone/check", {}, function (data) {
        if(!data) $.Nukura.setTimezone();
    });
    if(!firebase.apps.length) {
        firebase.initializeApp({
            apiKey: "AIzaSyAZgqIUDL44Ll2J18NcUS5inMw3UwvabbA",
            authDomain: "megazon-kg.firebaseapp.com",
            databaseURL: "https://megazon-kg.firebaseio.com",
            projectId: "megazon-kg",
            storageBucket: "megazon-kg.appspot.com",
            messagingSenderId: "187464181855"
        });
    }
        // $('.category-on-mouse').on('mousemove', function () {
        //     if(!$(this).parent().hasClass('open') && !$.Nukura.isTouch()) this.click();
        // });
    $('.double-arrow').on('mouseover', function () {
        this.click();
    });
    $('a[data-toggle=modal]').on('click', function () {
       $('html body').css({
           margin:0,
           height:"100%",
           overflow:'hidden'
       });
       //alert('open');
    });
    $('.modal').on('hidden.bs.modal', function () {
        $('html body').css({
            margin:"auto",
            height:"auto",
            overflow:"auto"
        });
    });
    // var searching = $("#searching");
    // searching.on('input', function () {
    //     $.Nukura.searchCategory();
    //     $(this).click();
    // });
    $(function () {
        $('#phone-number-guest').on('input', function () {
            var text = $(this).val().replace(/[^0-9 ]/i, "").replace(" ", "");
            $(this).val(text==="0"?'':text);
        });
    });
    // searching.on('keydown', function (e) {
    //     if(e.keyCode === 13) $("#search-btn").click();
    //     $.Nukura.focusing(event);
    // });
    // searching.on('focus', function () {
    //     if(window.location.pathname === "/") {
    //         $.Nukura.searchCategory();
    //         $(this).click();
    //     }
    // });
    // searching.on('click', function () {
    //     $('#my-drop-parameter').show();
    // });
    // $("#search-btn").on('click', function () {
    //     event.preventDefault();
    //     searching.focus();
    //     searching.click();
    //     $("#search-0").parent().click();
    // });
    // $("#message-input").on('keydown', function (e) {
    //     if(e.keyCode === 13) {
    //         $("#message-sender").click();
    //     }
    // });
    var searchBtnOpen = $(".search-mobile-open-btn");
    if(location.href.search('query')>-1 && searchBtnOpen.is(':visible')) $('.search-mobile-container').show();
    $("#message-sender").on("click", function () {
        var input = $("#message-input");
        var text;
        var newContact = $("#contacts-list-new");
        var $this = this;
        var feedback = $("#feedback-link");
        if(Boolean(feedback.html())) {

            text = feedback.html()+"\n"+input.val();
            feedback.html("");
        }
        else text = input.val();
        input.val("");
        if(!$.Nukura.auth) $.Nukura.auth = $("#user-id").html();
        if(Boolean(text) && Boolean($.Nukura.currentId.html())) $.Nukura.query('/message/send',{"user_id":$.Nukura.auth, "to":this.value, 'message':text}, function(data){
            if(data) new PNotify({
                //title: '{{ trans("app.error") }}',
                text: data,
                type: "error",
                icon: "fa fa-close"
            });
            else {
                $.Nukura.contactsUpdate();
                if(Boolean(newContact.html()) && $.Nukura.currentId.html() === "#contact-"+$this.value) newContact.html("");
            }
        });
        $.Nukura.sent = true;
    });
    $("#phone-number-guest").on('input', function () {
        var $this = $(this);
        $.Nukura.checkPhoneNumber($this, $("#phone-code-guest").val(), function () {
            $("#guest-sign-in").removeAttr("disabled");
        }, function () {
            $("#guest-sign-in").attr("disabled", "disabled");
        });
    });
    $("#guest-verify").on("click", function () {
        var code = $("#guest-code"), phone = $("#phone-number-guest");
        $.Nukura.confirmActivationCode('guest-code', function () {
            var name = $(document.getElementsByName("name-guest")[0]);
            $.Nukura.query('/guest/create', {"phone": phone.val(), "name": name.val()}, function (data) {
                $("#user-id").html(phone.val());
                $.Nukura.contactsUpdate();
                window.location.href = window.location.href + (window.location.href.indexOf("?") > -1 ? "&" : "?") + "messenger=" + $("#redirect-id").val() + "#messenger";
            });
        }, function (error) {
            code.parent().addClass('has-error');
            new PNotify({
                text: "Wrong code",
                type: "error",
                icon: "fa fa-close"
            });
        });
    });
    $("#guest-form").on("submit", function (e) {
        e.preventDefault();
        var loading=$("#loading-messenger"), code = $("#guest-code"), $this=$(this), phone = $("#phone-number-guest"), phoneCode = $("#phone-code-guest"), verify = $("#guest-verify");
        $.Nukura.query("/guest/check", {phone:phoneCode.val()+phone.val()}, function (data) {
            if(data) {
                sessionStorage.setItem("success", data);
                window.location.href = "/login?redirect=" + window.location.href+(window.location.href.indexOf("?") > -1 ? "&" : "?") + "messenger=" + $("#redirect-id").val()+"*messenger";
            }
            else if(!Boolean(code.val())){
                loading.fadeIn();
                phone.on('input', function () {
                    code.hide();
                    code.attr("disabled", "disabled");
                    $this.show();
                });
                $this.attr("disabled", "disabled");
                $.Nukura.initializePhoneActivator("guest-sign-in");
                $.Nukura.sendActivationCode("+"+ phoneCode.val() + phone.val(), function () {
                    code.show();
                    code.removeAttr("disabled");
                    $("#guest-sign-in").hide();
                    verify.removeClass("hidden");
                    verify.removeAttr("disabled");
                    loading.fadeOut();
                    new PNotify({
                        //title: '{{ trans("app.success") }}',
                        text: $("#code-sent-text").html(),
                        type: "success",
                        icon: "fa fa-check"
                    });
                }, function (error) {
                    loading.fadeOut();
                    new PNotify({
                        //title: '{{ trans("app.success") }}',
                        text: $("#error_phone").html(),
                        type: "error",
                        icon: "fa fa-close"
                    });
                });
            }
        });

    });
    $.Nukura.userMenu();
    $.Nukura.updateUserMenuFunctions();
    if(Boolean($.Nukura.auth)) {
        $.Nukura.userMenuUpdate($.Nukura.auth);
        setInterval(function () {
            $.Nukura.userMenuUpdate($.Nukura.auth);
        }, 10000);
        setInterval(function () {
            if($("#modal-message").css("display") !== "none") $.Nukura.contactsUpdate()
        }, 5000);
        setInterval(function () {
            $.Nukura.query('/visit/update', {'auth':$.Nukura.auth});
        }, 30000);
    }
    $('#filter_more').on('click', function () {
        $('#filter_box').slideToggle();
    });
    $('#filter_box_close').on('click', function () {
        $('#filter_box').slideToggle();
    });
    var sub_cats =  document.getElementsByClassName('sub-searching');
    for(var i=0; i<sub_cats.length;i++){
        $(sub_cats[i]).on('input', function () {
            $.Nukura.searchSubCategory(this.id.replace(/[^\d.]/g, ''));
        })
    }
    $.Nukura.myBox.html($.Nukura.type);
    $("a[href='#feedback']").on("click", function () {
        if(!$.Nukura.auth) $.Nukura.authCont.html("-1");
        $.Nukura.newMessage(0, $("#feedback-text").html()+" - Megazon", "/storage/users/default.png");
        $("#feedback").click();
    });
    // $(window).scroll(function (e) {
    //     alert($(window).offset().top());
    // });
});
$(document).ready(function () {
    var ar_right = $(".double-arrow.right"), ar_left = $(".double-arrow.left"),sc_menu = $('#menu'), sc_menu_cont = $(".scrollmenu");
    if(sc_menu.width()>sc_menu_cont.width()) ar_right.show();
    ar_right.on('click', function () {
        sc_menu_cont.animate({scrollLeft:sc_menu_cont.scrollLeft()+150});
    });
    ar_left.on('click', function () {
        sc_menu_cont.animate({scrollLeft:sc_menu_cont.scrollLeft()-150});
    });
    sc_menu_cont.scroll(function() {
        if(sc_menu_cont.scrollLeft() + sc_menu_cont.width() === sc_menu.width()) {
            ar_right.fadeOut();
        }
        else ar_right.fadeIn();
        if(sc_menu_cont.scrollLeft() === 0) {
            ar_left.fadeOut();
        }
        else ar_left.fadeIn();
    });

        $(window).on("load", function () {
            if($.Nukura.findBootstrapEnvironment() !== "xs") $("#contact-toggle").removeAttr("data-widget");
            else $("#contact-toggle").click();
        });

    $(window).on("resize", function () {
        $.Nukura.userMenu();
        $.Nukura.blackBoxResize();
        if($.Nukura.findBootstrapEnvironment() !== "xs") {
            $.Nukura.mobcomp.addClass("direct-chat-contacts-open");
            $("#contact-toggle").removeAttr("data-widget");
        }
        else {
            $("#contact-toggle").attr("data-widget", "chat-pane-toggle");
            $('#modal-message').animate({
                scrollTop:$('#modal-message').offset().top
            });
        }
        if(sc_menu.width()>sc_menu_cont.width()) ar_right.show();
        else ar_right.hide();
    });
    $('a[class="sidebar-toggle"]').on('click', function(){
        $.Nukura.blackBoxResize();
    });
    $(document).click(function (e) {
        var target = $(e.target);
        var searching = $('#searching');
        var btn = $('#search-btn');
        if (!target.is($.Nukura.myBox) && target.closest($.Nukura.myBox).length === 0 && !target.is(searching) && !target.is(btn) && !target.is(btn.children()) && target.closest(searching).length === 0) $.Nukura.myBox.hide();
    });
    $("#back-btn").on("click", function () {
        $.Nukura.updater = false;
    });
});
$.Nukura = {
    timezone:-new Date().getTimezoneOffset()/60,
    currentId:$("#current-id"),
    updater:true,
    new:false,
    oldHref:document.location.href,
    sent:false,
    global_id:-1,
    old_box:-1,
    type:$('.type-what-to-serach').text(),
    nothing:$('.nothing-was-found').text(),
    myBox:$('#my-drop-parameter'),
    whendel:false,
    audio:[new Audio('/storage/messenger/sms.mp3'), new Audio('/storage/messenger/smsopen.mp3')],
    subCatTempId:-1,
    csrf:$('meta[name="csrf-token"]').attr("content"),
    authCont:$("#user-id"),
    auth:$("#user-id").html(),
    hasMessage:$('#has-message'),
    hasNotice:$('#has-notice'),
    hasModerate:$('#has-moderate'),
    userNotice:$('#user-notice'),
    mobcomp:$('#mobcomp'),
    messenger:$('#modal-message'),
    notifyCheck:{message:false,notice:false, moderate:false},
    isTouch:function (action) {
        try {
            document.createEvent("TouchEvent");
            if(typeof action !== 'undefined') action();
            return true;
        } catch (e) {
            return false;
        }
    },
    getPhoneNumber: function (id, phone){
        var allP = phone.split("~");
        var phoneAttr = allP[0].split(",");
        var whatAttr = allP[1]?allP[1].split(","):"";
        if(phoneAttr.length>1 || allP.length>1){
            var number1 = '<p class="dropdown-toggle" style="margin-bottom: 0" data-toggle="dropdown" aria-expanded="false"><b>'+phoneAttr[0]+'</b> <i style="cursor: pointer" class="fa fa-chevron-down"></i></p><ul class="dropdown-menu flat">';
            for (var i=0; i<phoneAttr.length; i++) number1+='<li><a target="_blank" href="tel:'+phoneAttr[i]+'"><i class="fa fa-phone"></i><b style="color:#000;font-size:12pt;padding:10px;">'+phoneAttr[i]+'</b></a></li>';
            for (var i=0; i<whatAttr.length; i++) number1+='<li><a target="_blank" href="whatsapp://send?text=https://ijara.kg&phone='+whatAttr[i]+'"><i class="fa fa-whatsapp"></i><b style="color:#000;font-size:12pt;padding:10px;">'+whatAttr[i]+'</b></a></li>';
            $("#item-phone-"+id).html(number1+"</ul>");
        }
        else $("#item-phone-"+id).html(phone);
    },
    checkPhoneNumber:function ($this, code, success, error) {
        var text = $this.val().replace(/[^0-9 ]/i, "").replace(" ", "");
        $this.val(text==="0"?'':text);
        if(text.length<parseInt($this.attr("maxlength"))) {
            error();
        }
        else {
            $.Nukura.query('/users/check',{"phone_number":code+text}, function(data){
                success(data);
            });
        }

    },
    initPhoneField:function (selectId, phoneId, autodetect) {
        var select = $("#"+selectId), phone = $("#"+phoneId);
        $.get('/phone/codes', {}, function (data) {
            if(data) {
                var option = "";

                $.get("https://ipapi.co/json/", function(response) {
                    $(".wrapper").first().after('<div id="phone-code-auto" style="display: none">'+response.country_calling_code.replace("+", "")+'</div>');
                });

                for(var key in data){
                    option+="<option value='"+data[key].code+"'>+"+data[key].code+"</option>"
                }
                select.html(option);
                select.on("change", function () {
                    $.get('/phone/property', {code:select.val()}, function (dat) {
                        phone.attr("maxlength", dat.size);
                        phone.attr("placeholder", phone.attr('src')+": "+dat.example);
                        phone.val("");
                    });
                });
            }
        });
        if(autodetect) {
            var autodet = $("#phone-code-auto");
            if(autodet.length) {
                select.val(autodet.html());
                select.click();
            }
        }
    },
    viewPlus:function (id, self) {
        if($(".custom-box-for-dropdown-"+self).css("margin-bottom") < "30px" || typeof self === 'undefined') $.get('/item/plusplus', {id:id})
    },
    setTimezone: function () {
        $.get("/timezone/set", {timezone:$.Nukura.timezone});
    },
    userMenuUpdate:function (id) {
        $.Nukura.query('/message/get_new',{"user_id":id}, function(data){
            if(data) {
                $.Nukura.hasMessage.html(data);
                if($.Nukura.userNotice.is(':visible')){
                    $.when($.Nukura.userNotice.fadeOut()).done(function () {
                        $.Nukura.userNotice.html(data);
                        $.Nukura.userNotice.fadeIn();
                    });
                }
                else {
                    $.Nukura.userNotice.html(data);
                    $.Nukura.userNotice.show(500);
                }
                if($.Nukura.new<data) {
                    if ($("#modal-message").css("display") !== "block") $.Nukura.audio[0].play();
                    else if($.Nukura.currentId.html() !== $("#open-id").val()) $.Nukura.audio[0].play();
                    else $.Nukura.audio[1].play();
                }
                $.Nukura.new=data;
                $.Nukura.hasMessage.show();
            }
            else if(Boolean($.Nukura.hasMessage.html())) {
                $.Nukura.hasMessage.html("");
                $.Nukura.new=0;
                $.Nukura.userNotice.hide(500);
            }
        });
        $.Nukura.query('/notice/get_new',{"user_id":id}, function(data){
            if(data) {
                $.Nukura.hasNotice.html(data);
                $.Nukura.hasNotice.show();
                $.Nukura.notifyCheck.notice = true;
                if($.Nukura.userNotice.is(':visible')) setTimeout(function(){
                    $.when($.Nukura.userNotice.fadeOut()).done(function () {
                        $.Nukura.userNotice.html(data);
                        $.Nukura.userNotice.fadeIn();
                    });
                }, 3000);
                else {
                    $.Nukura.userNotice.fadeIn();
                    $.Nukura.userNotice.html(data);
                }
            }
            else if($.Nukura.notifyCheck.notice){
                $.Nukura.userNotice.fadeOut();
                $.Nukura.userNotice.html("");
                $.Nukura.notifyCheck.notice = false;
            }
        });
        $.Nukura.query('/moderation/get_new',{"user_id":id}, function(data){
            if(data) {
                $.Nukura.hasModerate.html(data);
                $.Nukura.hasModerate.show();
                $.Nukura.notifyCheck.moderate = true;
                if($.Nukura.userNotice.is(':visible')) setTimeout(function(){
                    $.when($.Nukura.userNotice.fadeOut()).done(function () {
                        $.Nukura.userNotice.html(data);
                        $.Nukura.userNotice.fadeIn();
                    });
                }, 3000);
            }
            else if($.Nukura.notifyCheck.moderate){
                $.Nukura.userNotice.fadeOut();
                $.Nukura.userNotice.html("");
                $.Nukura.notifyCheck.moderate = false;
            }
        });

    },
    updateUserMenuFunctions:function () {
        $.Nukura.hasMessage = $('#has-message');
        $.Nukura.hasNotice = $('#has-notice');
        $.Nukura.hasModerate = $('#has-moderate');
        $("#timeline-btn").on('click', function () {
            redir(this);
        });
        $("#moderate-btn").on('click', function () {
            redir(this);
        });
        $("#settings-btn").on('click', function () {
            redir(this);
        });
        function redir($element){
            var elem = $('a[href="'+$element.hash+'"]');
            elem.click();
            $.Nukura.scrolling($($element.hash));
            event.preventDefault();
        }
        $("#message-btn").on('click', function () {
            if(!$.Nukura.auth) {
                $.Nukura.initPhoneField('phone-code-guest', 'phone-number-guest', true);
                $("#guest").show();
            }
            $.Nukura.contactsUpdate();
        });
    },
    contactSeparator:function (id) {
        $.Nukura.updater = true;
        var toTop = false;
        if($.Nukura.currentId.html()!== "#contact-"+id) {
            $("#loading-messenger").fadeIn();
            $($.Nukura.currentId.html()).removeClass("active");
            $.Nukura.currentId.html("#contact-"+id);
            $("#open-id").val("#contact-"+id);
            $("#message-input").val("").focus();
            $("#contact-"+id).addClass("active");
            this.contactSeparator(id);
            $.Nukura.userNotice.addClass("hidden");
            toTop = true;
        }
        $.Nukura.query('/message/get', {user_id:$.Nukura.auth, with_id:id}, function (data) {
            var message = "", newm = true, fromMessageContent=$("#from-message"), myMessageContent=$("#my-message"), messageBox = $("#message-box");
            for (var j = 0; j < data.messages.length; j++) {
                if(data.messages[j].id) {
                    if(!$.Nukura.whendel) $.Nukura.whendel = newm && data.messages[j].delivered === 0 && parseInt(data.messages[j].to_id) === parseInt($.Nukura.auth);
                    if($.Nukura.whendel) setTimeout(function(){$.Nukura.whendel = false}, 3000);
                    if(newm && data.messages[j].delivered === 0 && parseInt(data.messages[j].to_id) === parseInt($.Nukura.auth)) {
                        newm=false;
                        message+="<div id='new-message-text'>"+data.new_messages+"</div>";
                    }
                    if(data.messages[j].to_id === $.Nukura.auth+"") message += $.Nukura.replacerAll(fromMessageContent.html(), {
                        "{message}": data.messages[j].body,
                        "{date}": data.messages[j].created,
                        "{delete}":"onclick='$.Nukura.deleteMessage("+data.messages[j].id+", "+$.Nukura.auth+")'"
                    });
                    else message += $.Nukura.replacerAll(myMessageContent.html(), {
                        "{message}": data.messages[j].body,
                        "{date}": data.messages[j].created,
                        "{delivered}": data.messages[j].delivered?"fa fa-envelope-open":"fa fa-envelope",
                        "{delete}":(data.messages[j].to_id!=="0"?"":"style='display:none'")+"onclick='$.Nukura.deleteMessage("+data.messages[j].id+", "+$.Nukura.auth+")'"
                    });

                }
                else message+="<div class='messenger-date'><p class='text'>"+data.messages[j]+"</p></div>";

            }
            $("#sender-title").html($.Nukura.replacerAll($("#sender-title-proto").html(), {'{name}':data.user.name, '{visited}':data.user.visited, '{avatar}':"src = '/storage/"+data.user.avatar+"'"}));
            messageBox.html(message);

            if(toTop || $("#new-message-text").length) messageBox.scrollTop(messageBox.prop("scrollHeight"));
            if($.Nukura.sent) {
                $.Nukura.sent = false;
                messageBox.animate({scrollTop:messageBox.prop("scrollHeight")});
            }
            $("#message-sender").val(id);
            $("#loading-messenger").fadeOut();
        });
    },
    contactsUpdate:function () {
        $.Nukura.query('/message/update', {user_id:$.Nukura.auth}, function (data) {
            $("#loading-messenger").hide();
            if(!data.contacts.length && !Boolean($("#contacts-list-new").html())) $("#empty").show();
            else $("#empty").hide();
            var list="", contact = $("#contact-proto"), contactsList = $("#contacts-list"), span = $("#notice-proto").html();
            for(var i=0; i<data.contacts.length; i++) list+="<li "+($.Nukura.currentId.html()==="#contact-"+(data.contacts[i].user.id!==-2?data.contacts[i].user.id:data.contacts[i].user.phone_number)?"class='active'":"")+" id='contact-"+(data.contacts[i].user.id!==-2?data.contacts[i].user.id:data.contacts[i].user.phone_number)+"' style='position: relative' onclick='$.Nukura.contactSeparator(\""+(data.contacts[i].user.id!==-2?data.contacts[i].user.id:data.contacts[i].user.phone_number)+"\")'>"+$.Nukura.replacerAll(contact.html(), {"{name}":data.contacts[i].user.name, "{message}":data.contacts[i].message.replace(/<(?:.|\n)*?>/gm, '').substring(0,20)+"...", "{delete}":"onclick='$.Nukura.deleteMessagesWith("+$.Nukura.auth+","+(data.contacts[i].user.id!==-2?data.contacts[i].user.id:data.contacts[i].user.phone_number)+")'", "{avatar}":"src = '/storage/"+data.contacts[i].user.avatar+"'"})+(Boolean(data.newMessages[data.contacts[i].user.id])?span.replace("{number}", data.newMessages[(data.contacts[i].user.id!==-2?data.contacts[i].user.id:data.contacts[i].user.phone_number)]):"")+"</li>";
            contactsList.html(list);
            if(Boolean($.Nukura.currentId.html()) && $.Nukura.updater) $($.Nukura.currentId.html()).click();
            if($.Nukura.findBootstrapEnvironment() !== "xs"){
                if(!$.Nukura.mobcomp.hasClass("direct-chat-contacts-open")) {
                    $.Nukura.mobcomp.addClass("direct-chat-contacts-open");
                    $.Nukura.updater = true;
                }
            }
        });
    },
    newMessage:function (id, name, img, rent_id) {
        if(id !== this.auth) {
            $("#redirect-id").val(rent_id);
            if(!this.authCont.html() || this.authCont.html() === "-1" && id !== 0) {
                $.Nukura.initPhoneField('phone-code-guest', 'phone-number-guest', true);
                $("#guest").show();
            }
            else {
                $("#contacts-list-new").html("");
                $("#guest").hide();
                $("#message-input").val("");
            }
            this.contactsUpdate();
            setTimeout(function () {
                var existing = $("#contact-"+id);
                if(existing.length) {
                    if($.Nukura.currentId.html() !== "#contact-"+id) {
                        $.Nukura.currentId.html("#contact-"+id);
                        existing.click();
                    }
                }
                else {
                    var newContact = $("#contacts-list-new"), contact = $("#contact-proto"), contactId = $("#contact-"+id);
                    newContact.html("<li class='active' onclick='$.Nukura.contactSeparator("+id+")' id='contact-"+id+"' style='position: relative'>"+$.Nukura.replacerAll(contact.html(), {"{name}":name, "{message}":"", "{date}":"", "{avatar}":"src='"+img+"'"})+"</li><li style='margin: 0;padding: 0'></li>");
                    $("#sender-title").html($.Nukura.replacerAll($("#sender-title-proto").html(), {'{name}':name, '{visited}':"",'{avatar}':"src='"+img+"'"}));
                    $("#message-sender").val(id);
                    contactId.on('click', function () {
                        $("#sender-title").html($.Nukura.replacerAll($("#sender-title-proto").html(), {'{name}':name, '{avatar}':"src='"+img+"'"}));
                        $("#message-sender").val(id);
                        $("#message-box").html("");
                        contactId.addClass('active');
                    });
                    $("#message-box").html("");
                    $.Nukura.currentId.html("#contact-"+id);
                    $("#empty").hide();
                }
                $("#message-input").focus();
            }, 200)
        }
    },
    deleteMessagesWith:function (id, user_id) {
        this.query("/message/deletewith", {id:id, user_id:user_id});
    },
    deleteMessage:function (id, user_id) {
        this.query("/message/delete", {id:id, user_id:user_id});
    },
    replacerAll:function replaceAll(str,mapObj){
        return str.replace(new RegExp(Object.keys(mapObj).join("|"),"gi"), function(matched){
            return mapObj[matched.toLowerCase()];
        });
    },
    userMenu:function () {
        var userMenuContentSidebar = $("#user-menu-content-sidebar"), userMenuContent = $("#user-menu-content"), userMenu=$("#user-menu");
        if(this.findBootstrapEnvironment() === "xs") {
            userMenu.css({
                "position": "fixed",
                "left":0,
                "right":0,
                "bottom":0,
                "z-index":1000
            });
            if(userMenuContentSidebar.html()) {
                userMenuContent.html(userMenuContentSidebar.html());
                userMenuContentSidebar.html("");
                this.updateUserMenuFunctions();
            }
        }
        else {
            userMenu.css({
                "position": "relative",
                "left":0,
                "right":0,
                "bottom":0
            });

            if(userMenuContent.html()) {
                userMenuContentSidebar.html(userMenuContent.html());
                userMenuContent.html("");
                this.updateUserMenuFunctions();
            }
        }
    },
    scrolling:function (elem) {
        $('html, body').animate({
            scrollTop: elem.offset().top-150
        }, 500);
    },
    toggleDrop:function(id, val, sizeChanger) {
        var box = $('.custom-dropdown-' + id);
        var parent = $('.custom-box-for-dropdown-' + id);
        var pointer = $('.custom-self-' + id);
        var width_c = $('.content-header');
        var for_pointer = width_c.innerWidth() - 20;
        if(val) document.getElementById("searching").value = val;
        else if($("#searching").length) document.getElementById("searching").value = "";
        box.css('width', width_c.innerWidth());
        if (this.findBootstrapEnvironment() === 'xs') {
            box.css('left', -5);
            box.css('right', 0);
            pointer.css('left', for_pointer / 2);
            if(!sizeChanger) toggleBox(id + 1);
        } else if ($.Nukura.findBootstrapEnvironment() === 'sm') {
            if ((id % 2) === 0) {
                box.css('left', -1);
                box.css('right', -parent.innerWidth());
                pointer.css('left', for_pointer / 4);
                if(!sizeChanger) toggleBox((id + 2) / 2);
            }
            else {
                box.css('left', -(parent.innerWidth() + 30));
                box.css('right', 0);
                pointer.css('left', for_pointer / 4 * 3);
                if(!sizeChanger) toggleBox((id + 1) / 2);
            }
        }
        else {
            if ((id + 2) % 3 === 0) {
                box.css('left', -(parent.innerWidth() + 30));
                box.css('right', -parent.innerWidth()+2);
                pointer.css('left', for_pointer / 2);
                if(!sizeChanger) toggleBox((id + 2) / 3);

            }
            else if ((id + 1) % 3 === 0) {
                box.css('left', -(parent.innerWidth() * 2 + 59));
                box.css('right', 2);
                pointer.css('left', for_pointer / 6 * 5);
                if(!sizeChanger) toggleBox((id + 1) / 3);
            }
            else {
                box.css('left', -1);
                box.css('right', -(parent.innerWidth() * 2 + 4));
                pointer.css('left', for_pointer / 6);
                if(!sizeChanger) toggleBox(id / 3 + 1);
            }
        }
        function toggleBox(new_box) {
            if($.Nukura.subCatTempId !== -1){
                $('#searching-'+$.Nukura.subCatTempId).val("");
                $.Nukura.searchSubCategory($.Nukura.subCatTempId);
                $.Nukura.subCatTempId=-1;
            }
            if(val && box.css('display') === "block") scrolling();
            else {
                if (box.css('display') !== "block") {
                    scrolling();
                    function getRatio(width){
                        return ((375*width)/705).toFixed(2);

                    }
                    var sizeB = box.outerHeight() + 20, env = $.Nukura.findBootstrapEnvironment(), conWidth=$(".custom-row").css("width").replace(/[^\d.]/g, '');
                    if($("#gallery0").length && box.html().indexOf('ug-slider-wrapper') === -1) {if(env==="xs" || env==="sm")sizeB -=500-getRatio(conWidth);else if(env==="lg" || env ==="md") sizeB -=500-getRatio(conWidth*2/3);}
                    if (new_box !== $.Nukura.old_box) parent.animate({'marginBottom': sizeB + "px"});
                    else parent.css('margin-bottom', sizeB + "px");
                }
                else parent.animate({'marginBottom': '20px'});
                if (new_box === $.Nukura.old_box && id !== $.Nukura.global_id) box.toggle();
                else {
                    box.slideToggle("middle");
                }
                if ($.Nukura.global_id !== id && new_box !== $.Nukura.old_box) {
                    $.Nukura.hideDrop($.Nukura.global_id, 1);
                    $.Nukura.old_box = new_box;
                }
                else if ($.Nukura.global_id !== id) {
                    $.Nukura.hideDrop($.Nukura.global_id, 0);
                    $.Nukura.old_box = new_box;
                }
                else if (new_box !== $.Nukura.old_box) $.Nukura.old_box = new_box;
                else $.Nukura.old_box = -1;
                $.Nukura.global_id = id;
            }
            function scrolling(){
                var filter = 0, filterBox = $('#filter');
                if(filterBox.length) filter=filterBox.height();
                $('html, body, .content-header').animate({
                    scrollTop: ((parent.height() + 20) * new_box + ($('.content').offset().top - 100)+filter)
                }, 500);
            }
        }

    },
    blackBoxResize:function() {
        if($.Nukura.global_id !== -1) setTimeout(function(){$.Nukura.toggleDrop($.Nukura.global_id, false, true)}, 300);
    },
    hideDrop:function(id, effect) {
        var box = $('.custom-dropdown-' + id);
        var drop = $('.custom-box-for-dropdown-' + id);
        if (effect === 0) {
            box.hide();
            drop.css('margin-bottom', '20px');
        }
        else {
            box.slideUp("middle");
            drop.animate({'marginBottom': '20px'});
        }
    },
    searchCategory:function() {
        var query = $('#searching').val().toUpperCase();
        var sub_cats = document.getElementsByClassName("sub-cat");
        var cats = document.getElementsByClassName("rent-cat");
        var resultSubCats = "";
        var resultCats = "";
        var drop_box = $('#my-drop-parameter');
        var searching = 0;
        var url;
        for (i = 0; i < sub_cats.length; i++) {
            url = sub_cats[i].getElementsByTagName('a')[0].getAttribute("href");
            var sear = $(sub_cats[i].getElementsByTagName('span')).text();
            var title = $(sub_cats[i].getElementsByTagName('span')[0]).text();
            if (sear.toUpperCase().indexOf(query) > -1 || $.Nukura.transWord(sear.toUpperCase()).indexOf(query) > -1) {
                resultSubCats += '<div tabindex="-1" onclick="location.href=\''+url+'\'"><a id="search-' + searching + '" href="' + url + '">' + title + "</a></div>";
                searching++;
            }
        }
        var url1;
        for (i = 0; i < cats.length; i++) {
            url1 = cats[i].getElementsByTagName('a')[0].getAttribute("href");
            var titl = $(cats[i].getElementsByTagName("span")[0]).text();
            if ($(cats[i]).text().toUpperCase().indexOf(query) > -1 || $.Nukura.transWord($(cats[i]).text().toUpperCase()).indexOf(query) > -1) {
                resultCats += '<div tabindex="-1" onclick="location.href=\''+url1+'\'" class="result-list-design"><a class="lia" href="'+url1+'" id="search-' + searching + '">' + titl + "</a></div>";
                searching++;
            }
        }
        if (query === "") drop_box.html($.Nukura.type);
        else if (searching === 0) drop_box.html($.Nukura.nothing);
        else drop_box.html($(".search-results").html()+resultSubCats + resultCats);
    },
    searchSubCategory:function($id) {
        var query = $('#searching-'+$id).val().toUpperCase();
        var sub_cats = document.getElementById('sub-cat-box-'+$id).getElementsByClassName("sub-cats-"+$id);
        var resultSubCats = "";
        var drop_box = $('#sub-cat-result-box-'+$id);
        var all_box = $('#sub-cat-box-'+$id);
        var searching = 0;
        $.Nukura.subCatTempId=$id;
        for (i = 0; i < sub_cats.length; i++) {
            var url = sub_cats[i].getElementsByTagName('a')[0].getAttribute("href");
            var title = $(sub_cats[i].getElementsByTagName('span')[0]).text();
            if (title.toUpperCase().indexOf(query) > -1 || $.Nukura.transWord(title.toUpperCase()).indexOf(query) > -1) {
                resultSubCats += sub_cats[i].outerHTML;
                searching++;
            }
        }
        if (query === "") {
            all_box.show();
            drop_box.hide();
        }
        else if (searching === 0) {
            all_box.hide();
            drop_box.show();
            drop_box.html('<div class="nothing-found">'+$.Nukura.nothing+'</div>');
        }
        else {
            all_box.hide();
            drop_box.show();
            drop_box.html(resultSubCats);
        }
        return false;
    },
    focusing:function (ev) {
        if (ev.keyCode === 38 || ev.keyCode === 40) {
            $('#my-drop-parameter').on('focus', 'div', function () {
                var $this = $(this);
                $this.addClass('active').siblings().removeClass();
            }).on('keydown', 'div', function (e) {
                var $this = $(this);
                if (e.keyCode === 40) {
                    $this.next().focus();
                    return false;
                } else if (e.keyCode === 38) {
                    $this.prev().focus();
                    return false;
                }
                else if (e.keyCode === 13) {
                    $this.click();
                    return false;
                }
            }).find('div').first().focus();
        }
        else if (ev.keyCode === 13) {
            $('#search-btn').focus();
            return false;
        }
    },
    transWord:function(word, onlytolatynic) {
        var a = {
            "Ё": "YO",
            "Й": "I",
            "Ц": "TS",
            "У": "U",
            "К": "K",
            "Е": "E",
            "Н": "N",
            "Г": "G",
            "Ш": "SH",
            "Щ": "SCH",
            "З": "Z",
            "Х": "H",
            "Ъ": "'",
            "ё": "yo",
            "й": "i",
            "ц": "ts",
            "у": "u",
            "к": "k",
            "е": "e",
            "н": "n",
            "г": "g",
            "ш": "sh",
            "щ": "sch",
            "з": "z",
            "х": "h",
            "ъ": "'",
            "Ф": "F",
            "Ы": "I",
            "В": "V",
            "А": "A",
            "П": "P",
            "Р": "R",
            "О": "O",
            "Л": "L",
            "Д": "D",
            "Ж": "J",
            "Э": "E",
            "ф": "f",
            "ы": "i",
            "в": "v",
            "а": "a",
            "п": "p",
            "р": "r",
            "о": "o",
            "л": "l",
            "д": "d",
            "ж": "j",
            "э": "e",
            "Я": "Ya",
            "Ч": "CH",
            "С": "S",
            "М": "M",
            "И": "I",
            "Т": "T",
            "Ь": "'",
            "Б": "B",
            "Ю": "YU",
            "я": "ya",
            "ч": "ch",
            "с": "s",
            "м": "m",
            "и": "i",
            "т": "t",
            "ь": "'",
            "б": "b",
            "ю": "yu"
        };
        if(!onlytolatynic) for (var key in a) {
            a[a[key]] = key;
        }
        return word.split('').map(function (char) {
            return a[char] || char;
        }).join("");
    },
    findBootstrapEnvironment:function() {
        var body = parseInt($('body').css('width').replace(/[^\d.]/g, ''));
        if (body >= 1200) return "lg";
        else if (body >= 992) return "md";
        else if (body >= 768) return "sm";
        else if (body < 768) return "xs";
    },
    query:function(url, json_request, action, err){
        $.ajax({
            type: "POST",
            url: url,
            data:$.extend(json_request, {"_token":$.Nukura.csrf}),
            dataType:'json',
            success: function(msg){
                if(action) action(msg);
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                if(err) err(errorThrown);
            }
        });
    },
    initializePhoneActivator:function (sendButtonId) {
            window.recaptchaVerifier = new firebase.auth.RecaptchaVerifier(sendButtonId, {
                'size': 'invisible',
                'callback': function (response) {
                    //$("#"+sendButtonId).click();
                }
            });
    },
    sendActivationCode:function (phoneNumber, afterCodeSentAction, afterAbortAction) {
        firebase.auth().signInWithPhoneNumber(phoneNumber, window.recaptchaVerifier)
            .then(function (confirmationResult) {
                window.confirmationResult = confirmationResult;
                if(afterCodeSentAction) afterCodeSentAction();
            }, function (err) {
                if(afterAbortAction) afterAbortAction(err);
            });
    },
    confirmActivationCode:function(verificationFieldId, actionAfterSuccess, actionAfterAbort){
        window.confirmationResult.confirm(document.getElementById(verificationFieldId).value)
            .then(function (result) {
                if(actionAfterSuccess) actionAfterSuccess();
            }, function (error) {
                if(actionAfterAbort) actionAfterAbort(error);
            });
    },
    request:function (name){
        if(name=(new RegExp('[?&]'+encodeURIComponent(name)+'=([^&]*)')).exec(location.search))
            return decodeURIComponent(name[1]);
    },
    formSaver:function(url,form, success, error, withloader){
        event.preventDefault();
        if(withloader) $("#loading").fadeIn();
        $.ajax({
            type: "POST",
            url: url,
            processData: false, // important
            contentType: false, // important
            data:new FormData(form),
            dataType:'json',
            success: function(msg){
                if(success) success(msg);
                if(withloader) $("#loading").fadeOut();
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                if(error && textStatus === 'timeout') error();
                if(withloader) $("#loading").fadeOut();
            }
        });
    }

};