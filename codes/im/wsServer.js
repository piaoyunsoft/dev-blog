var WebSocketServer = require('ws').Server,

wss = new WebSocketServer({port:6969});
wss.on('connection', function(ws) {
	console.log('client connected');
	ws.send('you are ' + wss.clients.lenght + 's');
	ws.on('message', function(message) {
		console.log(message);
		ws.send('recv: ' + message);
	});
	ws.on('close', function(close) {
		console.log('disConnect');
	});
});
console.log('begin to listen 6969');