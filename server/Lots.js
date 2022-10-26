const md5 = require("blueimp-md5");
const io = require('./io');
const SQL = require('./SQL');
const System = require('./functions');
const getUser = require('./users');
const axios = require('axios')
const baseUrl = "https://phebusmuzayede.com";

class Lots {

    constructor(product, auction)
    {
        this.product = product;
        this.id = product.id;
        this.seller = product.seller;
        this.active = false;
        this.startTime = null;
        this.offers = [];
        this.duration = auction.remaining;
        this.reserve = null;
        this.start()
        if (this.id) {

            axios({
                headers: {
                    'Content-Type' : 'application/x-www-form-urlencoded; charset=UTF-8',
                    "Access-Control-Allow-Origin": "*",
                },
                method: 'post',
                url: baseUrl + '/crons/checklot',
                data: {
                    id: this.id
                },
                transformRequest: [
                    function(data, headers) {
                        const serializedData = []

                        for (const k in data) {
                            if (data[k]) {
                                serializedData.push(`${k}=${encodeURIComponent(data[k])}`)
                            }
                        }

                        return serializedData.join('&')
                    }
                ]
            }).then(res=>{}).catch(res=>{});

            //axios.post(baseUrl + '/crons/checklot', {'id':this.id}).then(res=>{}).catch(res=>{});
        }
    }

    get lastOffer(){
        return this.offers[this.offers.length-1] || null;
    }

    get isSold(){
        return parseInt(this.product.status) === 2;
    }

    get price(){
        return this.lastOffer ? this.lastOffer.price : this.product.price;
    }

    dueDate(){
        if (this.lastOffer)
        {
            return this.lastOffer.time + this.duration;
        }

        return this.startTime + this.duration;
    }

    start(){

        SQL.query("SELECT * FROM offers WHERE product_id = ? ORDER BY price DESC, id ASC LIMIT 1", [this.id], (err, result) => {
            this.reserve = result[0];
        });
        this.startTime = Date.timestamp();

        this.active = true;

        console.log("start lot",this.id);
        this.broadcast();
    }

    updateProductInfo(){
        SQL.query("UPDATE products SET sale = ?, price = ?, remaining = ?, status=? WHERE id= ?", [
            this.product.sale,
            this.product.price,
            this.product.remaining,
            this.product.status,
            this.id
        ])

        this.offers.forEach((offer)=>{
            getUser(offer.username, user => {

                SQL.query("INSERT INTO offers SET user_id = ?, product_id = ?, price = ?, create_time = ?", [
                    user.id,
                    this.product.id,
                    offer.price,
                    offer.time,
                ]);
            });
        })
    }

    terminate(){

        let offer = this.lastOffer || null;

        if (!this.lastOffer && this.reserve) {
            offer = {
                username : md5(this.reserve.user_id),
                price : Math.min(this.product.price, this.reserve.price),
                time : Date.timestamp()-this.duration,
            };
            this.offers.push(offer)
        }

        this.product.remaining = this.dueDate();
        this.product.status = offer ? 2 : 3;
        this.product.price = offer ? offer.price : this.product.price;
        this.product.sale = this.product.sale;
        this.active = false;

        if (offer) {
            getUser(offer.username, user => {
                this.product.sale = user.id;
                this.updateProductInfo();
            });
        }
        else
        {
            this.updateProductInfo();
        }

        console.log("terminate lot",this.id);

        // this.broadcast();

        io.sockets.in(this.product.auction_id).emit("terminate lot", {
            id : this.id,
            price : this.price,
            sale : offer ? offer.username : md5(this.product.sale),
            status : this.product.status,
        });
    }

    isSeller(username) {
        return md5(this.seller) === username;
    }

    makeOffer(username, price){
        if (this.active)
        {
            if(this.lastOffer && (username === this.lastOffer.username)) return

            if(price <= this.price) return;

            let time = Date.timestamp();

            let offer = {username, price, time};

            this.offers.push(offer);

            if (this.reserve && (this.reserve.price >= offer.price)) {
                this.offers.push({
                    username : md5(this.reserve.user_id),
                    price : Math.min(System.NextPey(offer.price), this.reserve.price),
                    time : Date.timestamp()
                })
            }

            this.broadcast();

            return true;
        }

        return false
    }

    broadcast(socket){
        let lots = {
            id : this.id,
            price : this.price,
            nextPrice : this.price ? System.NextPey(this.lastOffer ? this.lastOffer.price : this.price) : this.product.old_price,
            status : this.product.status,
            sale : this.lastOffer ? this.lastOffer.username : md5(this.product.sale),
            active : this.active,
            remaining : this.dueDate(),
            countdown : this.dueDate()-Date.timestamp(),
            offers : this.offers,
            startTime : this.startTime,
            seller : this.seller,
            product : {
                id : this.product.id,
                sku : this.product.sku,
                name : this.product.name,
                shortdetail : this.product.shortdetail,
                old_price : this.product.old_price,
                pictures : [],
            }
        };
        console.log("broadcast",socket?socket.id:null,lots);
        if (socket){
            socket.emit('lots', lots);
        } else {
            io.sockets.in(this.product.auction_id).emit('lots', lots);
        }

    }
}

module.exports = Lots;
