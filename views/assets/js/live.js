if(typeof live_id  != 'undefined') {

    function startMuzayede() {

    }
}



function checkLive() {
    $.get(baseURL + 'checkonline');
}

$(document).ready(function() {
    var checkOnline = setInterval(() => {
        checkLive();
    },170000);
});