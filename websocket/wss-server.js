const fs = require('fs');
const https = require('https');
const WebSocket = require('ws');
const server_data = require('./conf');

console.log(server_data);

const server = new https.createServer({
    cert: fs.readFileSync(server_data.crt),
    key: fs.readFileSync(server_data.key)
});

/**/
//const wss = new WebSocket.Server({ server });
const wss = new WebSocket.Server({server: server/*, path: "/broadcast"*/});

/**/
wss.on('connection', function (ws, req) {

    //let route = req.url;
    //if (route == '/broadcast')
    ws.routeUrl = req.url;

    /**/
    ws.on('message', function (msg) {
        //console.log(req.url);
        //console.log('received: %s', msg);
        let route = req.url;
        if (route == '/broadcast') {
            wss.clients.forEach(function (client) {
                if (client !== ws && client.readyState === WebSocket.OPEN) {
                    client.send( msg );
                }
            });
        } else if (route == '/broadcast-all') {
            wss.clients.forEach(function (client) {
                if (client.readyState == WebSocket.OPEN) {
                    client.send( msg );
                }
            });
        } else {
            wss.clients.forEach(function (client) {
                if (client !== ws && client.readyState == WebSocket.OPEN) {
                    if (typeof client.routeUrl != 'undefined' && client.routeUrl == req.url) {
                        client.send(msg);
                    }
                }
            });
        }
    });

    /**/
    ws.send(JSON.stringify({
        from: 'broadcast',
        message: `Successfully connected to websocket ${req.url}`
    }));
});

/**/
server.listen(server_data.port);
