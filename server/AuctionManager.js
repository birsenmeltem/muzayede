
const SQL = require('./SQL');
const Auction = require('./Auction');

class AuctionManager {

    constructor(){
        this.items = {};
        this.users = [];
    }

    get liveAuctions(){
        return Object.values(this.items).filter((item)=>{
            return item.isLive;
        })
    }

    load() {
        console.log("load auctions");
        let start = new Date();
        start.setHours(0,0,0,0);
        start.setDate(start.getDate()-2)

        let end = new Date(start.getTime());
        end.setDate(start.getDate()+3)
        end.setHours(23,59,59,0);

        SQL.query("SELECT * FROM auctions WHERE live_start_time BETWEEN ? AND ?",[start/1000, end/1000], (err,results) => {
            this.updateAuctions(results);
        });
    }

    updateAuctions(result){
        let itemIds = result.map(item => item.id);

        //Eski kayıtları temizliyoruz
        Object.values(this.items).forEach(item => {
            if (itemIds.indexOf(item.id) !== -1) {
                //Eğer canlı aktifse silmiyoruz
                if (! this.items[item.id].isLive) {
                    delete this.items[item.id];
                }
            }
        })

        result.forEach((item) => {

            let auction = this.items[item.id] || (this.items[item.id] = new Auction(item));

            auction.update(item);
        })
    }

    auction(id){
        return this.items[id] || new Auction({});
    }

    updateLiveAuctions(){
        return this.liveAuctions.forEach(auction => auction.updateLots() );
    }

    start(){
        console.log("manager starting");
        this.load();

        setInterval(() => {this.load()},60000);
        setInterval(() => {this.updateLiveAuctions()},1000);
    }
}

let Manager = new AuctionManager;

module.exports = Manager
