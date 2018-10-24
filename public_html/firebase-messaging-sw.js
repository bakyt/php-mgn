// firebase-messaging-sw.js
importScripts('https://www.gstatic.com/firebasejs/5.3.1/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/5.3.1/firebase-messaging.js');

firebase.initializeApp({
    messagingSenderId: '187464181855'
});

const messaging = firebase.messaging();

messaging.setBackgroundMessageHandler(function(payload) {
    var notificate = payload.message
    var notificationTitle = notificate.title;
    var notificationOptions = {
        body:  notificate.body,
        icon: notificate.icon
    };

    return self.registration.showNotification(notificationTitle,
        notificationOptions);
});