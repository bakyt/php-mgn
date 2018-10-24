// браузер поддерживает уведомления
// вообще, эту проверку должна делать библиотека Firebase, но она этого не делает
 var messaging;
$(function() {
    if ('Notification' in window) {
        messaging = firebase.messaging();
        // пользователь уже разрешил получение уведомлений
        // подписываем на уведомления если ещё не подписали
        if (Notification.permission === 'granted') {
            subscribe();
        }
        else if (Notification.permission === 'default') {
            if (GetCookie_N('notified') === null) {
                $.get('/users/subscribe', {}, function (data) {
                    $('body').append(data);
                    $('#subscribe').click();
                });
                var nDate = new Date();
                nDate.setHours(23);
                nDate.setMinutes(59);
                nDate.setSeconds(59);
                SetCookieN('notified', '1', nDate, "/");
            }
        }

        messaging.onMessage(function (payload) {
            navigator.serviceWorker.register('/firebase-messaging-sw.js');
            Notification.requestPermission(function (result) {
                if (result === 'granted') {
                    navigator.serviceWorker.ready.then(function (registration) {
                        console.log("registration success");
                    }).catch(function (error) {
                        console.log('ServiceWorker registration failed', error);
                    });
                }
            });
        });
        // Callback fired if Instance ID token is updated.
        messaging.onTokenRefresh(function() {
            messaging.getToken().then(function(refreshedToken) {
                console.log('Token refreshed.');
                // Indicate that the new Instance ID token has not yet been sent to the
                // app server.
                setTokenSentToServer(false);
                // Send Instance ID token to app server.
                sendTokenToServer(refreshedToken);
                // ...
            }).catch(function(err) {
                console.log('Unable to retrieve refreshed token ', err);
            });
        });


    }


});
function SetCookieN(name, value) {  
	var argv = SetCookieN.arguments;  
	var argc = SetCookieN.arguments.length;  
	var expires = (argc > 2) ? argv[2] : null;  
	var path = (argc > 3) ? argv[3] : null;  
	var domain = (argc > 4) ? argv[4] : null;  
	var secure = (argc > 5) ? argv[5] : false;  
	document.cookie = name + "=" + escape (value) +  
	((expires == null) ? "" : ("; expires=" + expires.toGMTString())) +  
	((path == null) ? "" : ("; path=" + path)) +  
	((domain == null) ? "" : ("; domain=" + domain)) +  
	((secure == true) ? "; secure" : "");
}

function GetCookie_N(name) {  
	var arg = name + "=";  
	var alen = arg.length;  
	var clen = document.cookie.length;  
	var i = 0;  
	while (i < clen){  
		var j = i + alen;  
		if (document.cookie.substring(i, j) == arg)  
			return getCookieVal_popupssu(j);  
		i = document.cookie.indexOf(" ", i) + 1;  
		if (i == 0) break;  
	}  
	return null; 
} 

function getCookieVal_popupssu(offset) {
	var endstr = document.cookie.indexOf (";", offset);
	if (endstr == -1)
		endstr = document.cookie.length;
	return unescape(document.cookie.substring(offset, endstr));
}

function subscribe() {
    // запрашиваем разрешение на получение уведомлений
    messaging.requestPermission()
        .then(function () {
            // получаем ID устройства
            messaging.getToken()
                .then(function (currentToken) {
                    if($.Nukura.auth && $.Nukura.auth !== "-1") subscribeTokenToTopic(currentToken, $.Nukura.auth);
                    else return false;
                })
                .catch(function (err) {
                    console.warn('При получении токена произошла ошибка.', err);
                    setTokenSentToServer(false);
                });
    })
    .catch(function (err) {
        console.warn('Не удалось получить разрешение на показ уведомлений.', err);
    });
}
function subscribeTokenToTopic(token, topic) {
    fetch('https://iid.googleapis.com/iid/v1/'+token+'/rel/topics/'+topic, {
        method: 'POST',
        headers: new Headers({
            'Authorization': 'key=AAAAK6W8aF8:APA91bFqVkvJ6aKWehI8mm0ONCNGdarpSEhmipxOhdrm8noepCArm2qcSMgglho6a8IA6g5JJwv9ZJVXb_p8A0KcoDPAQBcGPviIPrsgI-TVEdJL8yEv_0vYNP-llzw_8PD92hcx-sa1sPzI8kMyNJrGcGQ4Arj4QA'
        })
    }).then(response => {
        if (response.status < 200 || response.status >= 400) {
            throw 'Error subscribing to topic: '+response.status + ' - ' + response.text();
        }
    }).catch(error => {
        console.error(error);
    })
}
// отправка ID на сервер
function sendTokenToServer(currentToken) {
    console.log(currentToken);
    $.post('/users/subscribe', {key: currentToken});
}

// используем localStorage для отметки того,
// что пользователь уже подписался на уведомления
function isTokenSentToServer(currentToken) {
    return window.localStorage.getItem('sentFirebaseMessagingToken') == currentToken;
}

function setTokenSentToServer(currentToken) {
    window.localStorage.setItem(
        'sentFirebaseMessagingToken',
        currentToken ? currentToken : ''
    );
} 