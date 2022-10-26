const md5 = require("blueimp-md5");
const io = require('./io');
const SQL = require('./SQL');
const Lot = require('./Lots');

class Auction {

    constructor(item)
    {
        this.id = item.id;
        this.status = item.status;
        this.live_start_time = item.live_start_time;
        this.live_end_time = item.live_end_time;
        this.active = false;
        this.lots = null;
        this.remaining =item.remaining;
        this.products = [];
        this.productsLoaded = false;
        this.lotStartCountdown = 0;
    }

    get isLive(){
        return this.status == 2;
    }

    isTerminated(){
        return this.status == 3;
    }

    get activeProducts(){
        let timestamp = Date.timestamp();

        return this.products.filter((product) => {
            return !product.remaining || product.remaining > timestamp;
        })
    }

    updateLots(){
        if (this.isLive)
        {
            if (! this.productsLoaded) {
                this.loadProducts();
            }

            if (this.lots) {
                if (this.lots.dueDate() < Date.timestamp())
                {
                    let lots = this.lots;
                    this.lots.terminate();

                    this.lotStartCountdown = lots.isSold ? 3 : 3;

                    this.lots = lots = null;
                }
            }

            if (!this.lots){

                if (this.lotStartCountdown>0) {
                    this.lotStartCountdown--;
                    return;
                }

                let product = this.activeProducts[0];

                if (product) {
                    this.lots = new Lot(product, this);
                    this.active = true;
                } else if (this.productsLoaded) {
                    this.terminate();
                }
            }
        }
    }

    loadProducts(){
        SQL.query("SELECT * FROM products WHERE auction_id = ? AND sold=0 AND status=1 ORDER BY sku ASC", [this.id],(e,result) => {
            if (! result.length) {
                return this.terminate();
            }
            this.products = result;
            this.productsLoaded = true;
        })
    }

    start(){
        console.log("auction starting",this.id);
        SQL.query("UPDATE auctions SET status=2 WHERE id = ? ",[this.id]);
        this.status = 2;
        io.sockets.in(this.id).emit("start");
    }

    terminate(){
        console.log("auction terminating",this.id);
        this.active = false;
        let time = Date.timestamp();
        SQL.query("UPDATE auctions SET live_end_time= ?,status = ? WHERE id=?", [time,3,this.id]);
        this.status = 3;
        this.live_end_time = time;

        io.sockets.in(this.id).emit('end muzayede');
    }

    update(item) {
        console.log("auctions update",item.id);
        this.status = item.status;
        this.live_end_time = item.live_end_time;

        console.log("current-timestamp",Date.timestamp())
        if (parseInt(item.status) === 1 && (Date.timestamp() >= item.live_start_time)) {
            this.start();
        }
        //this.start();
    }
}

module.exports = Auction;
