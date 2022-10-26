const mysql = require("mysql");

class MysqlConnection {

    constructor(){
        this.sql = null;
    }

    log(detail, line){
        this.sql.query("INSERT INTO logs SET detail = ?, line = ?", [detail, line])
    }

    connect()
    {
        let dbConfig = {
            host: 'localhost',
            user: 'pheb_db',
            password: 'Tj6e6z_5',
            database: 'pheb_db'
        }


        this.sql = mysql.createConnection(dbConfig);

        this.sql.connect( function onConnect(err) {
            if (err) {
                console.log('error when connecting to db:', err);
                setTimeout(this.connect, 10000);
            }
            console.log('MySQL bağlantısı başarıyla gerçekleştirildi.');
        });

        this.sql.on('error', function onError(err) {
            console.log('db error', err);
            if (err.code == 'PROTOCOL_CONNECTION_LOST') {
                setTimeout(this.connect, 1000);
            } else {
                throw err;
            }
        });
    }

    query(){
        console.log(arguments[0],arguments[1]);
        return this.sql.query(...arguments);
    }
}

let SQL = new MysqlConnection();

module.exports = SQL;
