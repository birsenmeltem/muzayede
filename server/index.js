
if (! Date.timestamp) {
    Date.timestamp = () => +new Date/1000|0;
}
const io = require('./io');
const SQL = require('./SQL');
const Manager = require('./AuctionManager');
const getUser = require('./users');

SQL.connect();

Manager.start();

io.sockets.on("connection", socket => {

    console.log("User connection", socket.client.id);

    socket.on('joinlive', (data) => {
        socket.join(data.id);
        let auction = Manager.auction(data.id);
        let lots = auction.lots;
        if (auction.active && lots && lots.active)
        {
            lots.broadcast(socket);
        }
    });

    socket.on("sendPey", (arg) => {
        console.log("sendpay",arg);
        if(arg.product_id && arg.price && arg.id) {

            let auction = Manager.auction(arg.id);

            let lots = auction.lots;
            console.log(auction.id, auction.active, lots && lots.id);
            if (auction.active && lots && lots.active) {

                if (lots.isSeller(arg.username))
                {
                    return socket.emit('same seller');
                }

                if (lots.id !== arg.product_id)
                {
                    return; //Sonraki ürüne geçildi
                }

                if(arg.price <= lots.price) {
                    //socket.emit('owner',{'user' : lots.lastOffer.username});
                    return
                }

                if(lots.lastOffer && (arg.username === lots.lastOffer.username))  {
                    socket.emit('owner',{'user' : arg.username});
                    return
                }

                getUser(arg.username, user => {
                    if (user)
                    {
                        lots.makeOffer(arg.username, arg.price);
                    }
                });
            }

        }
    })
})
