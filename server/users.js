
const SQL = require('./SQL');

let users = [];

module.exports = function (username, callback)
{
    let user = users.find(i => i.username === username);

    if (user) {
        user.time = Date.timestamp();
        callback(user);
        return;
    }

    SQL.query("SELECT id FROM users WHERE md5(id)= ?",[username], (error,result) => {
        let user = null
        if (result[0])
        {
            user = {
                id : result[0].id,
                username : username,
                time : Date.timestamp()
            };

            users.push(user);
        }
        callback(user);
    })
}

