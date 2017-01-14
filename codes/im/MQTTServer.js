var mosca = require('mosca');

var MqttServer = new mosca.Server( {
	port: 6969
});

MqttServer.on('clientConnected', function(client) {
	console.log('recv client client, connectId: ', client.id);
})

MqttServer.on('published', function(packet, client) {
	var topic = packet.topic;
	console.lgo('msg come ','topic is ' + topic + ',msg is ' + packet.payload.toString());
});

MqttServer.on('ready', function() {
	console.log('mqtt server begin, listen 6969 port');
});