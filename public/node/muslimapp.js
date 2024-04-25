//var app = require('express')();
//var http = require('http').Server(app);   
////var io = require('socket.io')(http);
//var db = require('./db.js');
//const cors = require('cors');
//var mydb = new db();
//app.use(cors());
//app.get('/', function (req, res) {
//    res.send('Working fine New');
//});   
//var sockets = {};
//var arr = [];
//const io = require('socket.io')(http, {
//  cors: {
//    origin: '*'
//  }
//});
//io.on('connection', function (socket) {
//    socket.on('message_get', function (data) {
//        io.emit('message_send', {'user_id': data.user_id, 'other_name': data.other_name, 'photo': data.photo, 'message': data.message, 'other_id': data.other_id,'chat_id':data.chat_id});
//    });
//    socket.on('disconnect', function () {
//        if (sockets[socket.id] != undefined) {
//            mydb.releaseRequest(sockets[socket.id].user_id).then(function (result) {
//                console.log('disconected: ' + sockets[socket.id].request_id);
//                io.emit('request-released', {
//                    'request_id': sockets[socket.id].request_id
//                });
//                delete sockets[socket.id];
//            });
//        }
//    });
//});
//
//http.listen(5002, function () {
//    console.log('listening on *:5002');
//});
//
//
////// HTTPS SERVER
var https = require('https');
var fs = require('fs');
var express = require('express');
var options = {
    key: fs.readFileSync('/etc/letsencrypt/live/sockets.skylinxtech.com/privkey.pem'),
    cert: fs.readFileSync('/etc/letsencrypt/live/sockets.skylinxtech.com/fullchain.pem'),
    requestCert: false,
    rejectUnauthorized: false
};
const app = express();
app.use(function (req, res, next) {
    res.setHeader('Access-Control-Allow-Origin', '*');
    res.setHeader('Access-Control-Allow-Methods', 'GET, POST, OPTIONS, PUT, PATCH, DELETE');
    res.setHeader('Access-Control-Allow-Headers', 'X-Requested-With,content-type');
    res.setHeader('Access-Control-Allow-Credentials', true);
    next();
});
//const socket = require('socket.io');

const cors = require('cors');
//const app = express();
let port = 3003;

//app.use(express.static('public'));
app.use(cors());
var server = https.createServer(options, app);
const io = require('socket.io')(server, {
  cors: {
    origin: '*'
  }
});
let clients = 0
//const io = socket(server);
var sockets = {};
var arr = [];
io.on('connection', function (socket) {
    socket.on('message_get', function (data) {
        io.emit('message_send', data);
    });
    socket.on('disconnect', function () {
        if (sockets[socket.id] != undefined) {
            mydb.releaseRequest(sockets[socket.id].user_id).then(function (result) {
                console.log('disconected: ' + sockets[socket.id].request_id);
                io.emit('request-released', {
                    'request_id': sockets[socket.id].request_id
                });
                delete sockets[socket.id];
            });
        }
    });
});
server.listen(port, function () {
    console.log('Express server listening on port ' + server.address().port);
});