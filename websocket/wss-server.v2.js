/**
 * setup:
 * npm install --save express
 * npm install --save express-ws
 */
const https     = require('https');
const fs        = require('fs');
const express   = require('express');
const expressWs = require('express-ws');

const server_data = require('./conf');
console.log(server_data);

const serverOptions = {
    cert: fs.readFileSync(server_data.crt),
    key: fs.readFileSync(server_data.key)
}

const app       = express();
const server    = https.createServer(serverOptions, app);

let wsInstance = expressWs(app, server);

wsInstance.getWss()

app.ws('/broadcast', (ws, req) => {

    ws.on('message', msg => {
        let clients = wsInstance.getWss('/broadcast').clients;
        //console.log(wsInstance.getWss().clients);
        clients.forEach(function (client) {
            //if (client.readyState == WebSocket.OPEN) {
                client.send( msg );
            //}
        });
        //ws.send(msg)
    });

    ws.on('close', () => {
        console.log('WebSocket was closed')
    });

    ws.send(JSON.stringify({
        from: 'broadcast',
        message:'Successfully connected to websocket /broadcast'
    }));

});

app.ws('/chat', (ws, req) => {

    ws.on('message', msg => {
        let clients = wsInstance.getWss('/chat').clients;
        //console.log(wsInstance.getWss().clients);
        clients.forEach(function (client) {
            //if (client.readyState == WebSocket.OPEN) {
            client.send( msg );
            //}
        });
        //ws.send(msg)
    });

    ws.on('close', () => {
        console.log('WebSocket was closed')
    });

    ws.send(JSON.stringify({
        from: 'broadcast',
        message:'Successfully connected to websocket /chat'
    }));

});

server.listen(server_data.port);