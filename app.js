'use strict';
var server_port = '3000';

var express = require('express');
var mysql = require('mysql');
var dbconfig   = require('./mysql.js');
var http = require('http');
var fs = require('fs');

var app = express();
var server = http.createServer(app);


var dbconn = mysql.createConnection(dbconfig);
dbconn.connect();
var sql = " mysql query ";
dbconn.query(sql, function(error, rows, fields){
	if (error) throw error;
	console.log('User info is: ', rows);
});
dbconn.end();

server.listen(server_port, function(){ 
	console.log('socket server running...'); 
});

app.get('/', function(req, res){
	res.sendfile(__dirname + '/');	
});

var io = require('socket.io')(server);

var usernames = {};

io.sockets.on('connection', function (socket) {

	console.log('server connect');

	socket.on('sendChat', function (data) {
		io.sockets.emit('updateChat', socket.username, data);
		console.log(socket.username);
	});

	socket.on('addUser', function(username){
		socket.username = username;
		usernames[username] = username;
		socket.emit('updateChat', 'SERVER', 'you have connected');
		socket.broadcast.emit('updateChat', 'SERVER', username + ' has connected');
		io.sockets.emit('updateUser', usernames);
	});

	socket.on('disconnect', function(){
		delete usernames[socket.username];
		io.sockets.emit('updateUser', usernames);
		socket.broadcast.emit('updateChat', 'SERVER', socket.username + ' has disconnected');
	});
});
