let SlidesWebSocket;
let $wss_data = $('#wss-data');
let wss_user;

$(document).ready(function() {

    if ($wss_data.length && $wss_data[0].hasAttribute('data-wss-url') && $wss_data[0].hasAttribute('data-wss-user')) {
        //ws = new Ws('ws://echo.websocket.org', 5);

        wss_user = $wss_data.data('wss-user');

        /**/
        SlidesWebSocket = new Ws($wss_data.data('wss-url'), 5); // второй параметр если 0 то реконекта не будет, иначе реконект после заданного кол-ва секунд

        //alert(1);
        /**/
        SlidesWebSocket.onclose = function () {

            if (SlidesWebSocket.reconnect > 0) {
                setTimeout(function () {

                    console.log('Connection lost to ' + SlidesWebSocket._url);
                    console.log('Trying restore connection to "' + SlidesWebSocket._url + '" in ' + SlidesWebSocket.reconnect + ' seconds');

                    SlidesWebSocket.connect();

                }/*.bind(this)*/, SlidesWebSocket.reconnect * 1000);
            } else {
                console.log('Disconnected from ' + SlidesWebSocket._url);
            }
        };

        /**/
        SlidesWebSocket.onmessage = function (message) {
            //console.log(statuses);
            console.log(message);
            //return true;
            var data = JSON.parse(message.data);

            if ("message" in data && "from" in data) {

                if (/*data.from != wss_user && */data.from != 'broadcast') {
                    alert(`Received message from ${data.from} \n Message: ${data.message}`);
                }

            }
        };


        $(document).on('click', '#send-to-chat', function() {
            let $chat_message = $('#chat-message');

            SlidesWebSocket.send(JSON.stringify({
                from: wss_user,
                message: $chat_message.val()
            }));

            $chat_message.val('');
        });
    }

});