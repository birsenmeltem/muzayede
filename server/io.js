const fs = require("fs");
const https = require("https");
const socket = require("socket.io");

//SSL Sertifikaları
var privateKey = fs.readFileSync('/etc/letsencrypt/live/phebusmuzayede.com/privkey.pem').toString();
var certificate = fs.readFileSync('/etc/letsencrypt/live/phebusmuzayede.com/fullchain.pem').toString();
var ca = fs.readFileSync('/etc/letsencrypt/live/phebusmuzayede.com/fullchain.pem').toString();

let server = https.createServer({key:privateKey,cert:certificate,ca:ca },(request, response) => {
    response.end("Server is online !");
});
server.listen(2021);

let io = socket.listen(server, {key:privateKey,cert:certificate,ca:ca, 'pingTimeout': 180000, 'pingInterval': 25000} );


module.exports = io;
