module.exports = {
    NextPey: function(price) {
        var NewPrice = (price+1);
        if(NewPrice < 20) return NewPrice;
        if(NewPrice >= 20 && NewPrice < 50) return (NewPrice % 2 == 0) ? NewPrice : ++NewPrice;
        if(NewPrice >= 50 && NewPrice < 100) return (NewPrice % 5 == 0) ? NewPrice : (NewPrice + (5 - (NewPrice % 5)));
        if(NewPrice >= 100 && NewPrice < 200) return (NewPrice % 10 == 0) ? NewPrice : (NewPrice + (10 - (NewPrice % 10)));
        if(NewPrice >= 200 && NewPrice < 500) return (NewPrice % 25 == 0) ? NewPrice : (NewPrice + (25 - (NewPrice % 25)));
        if(NewPrice >= 500 && NewPrice < 1000) return (NewPrice % 50 == 0) ? NewPrice : (NewPrice + (50 - (NewPrice % 50)));
        if(NewPrice >= 1000 && NewPrice < 2000) return (NewPrice % 100 == 0) ? NewPrice : (NewPrice + (100 - (NewPrice % 100)));
        if(NewPrice >= 2000 && NewPrice < 5000) return (NewPrice % 250 == 0) ? NewPrice : (NewPrice + (250 - (NewPrice % 250)));
        if(NewPrice >= 5000 && NewPrice < 10000) return (NewPrice % 500 == 0) ? NewPrice : (NewPrice + (500 - (NewPrice % 500)));
        if(NewPrice >= 10000 && NewPrice < 20000) return (NewPrice % 1000 == 0) ? NewPrice : (NewPrice + (1000 - (NewPrice % 1000)));
        if(NewPrice >= 20000 && NewPrice < 50000) return (NewPrice % 2500 == 0) ? NewPrice : (NewPrice + (2500 - (NewPrice % 2500)));
        if(NewPrice >= 50000 && NewPrice < 100000) return (NewPrice % 5000 == 0) ? NewPrice : (NewPrice + (5000 - (NewPrice % 5000)));
        if(NewPrice >= 100000) return (NewPrice % 10000 == 0) ? NewPrice : (NewPrice + (10000 - (NewPrice % 10000)));
    },
    PeyCheck : function(NewPrice) {
        if(NewPrice < 20) return NewPrice;
        if(NewPrice >= 20 && NewPrice < 50) return (NewPrice % 2 == 0) ? NewPrice : ++NewPrice;
        if(NewPrice >= 50 && NewPrice < 100) return (NewPrice % 5 == 0) ? NewPrice : (NewPrice + (5 - (NewPrice % 5)));
        if(NewPrice >= 100 && NewPrice < 200) return (NewPrice % 10 == 0) ? NewPrice : (NewPrice + (10 - (NewPrice % 10)));
        if(NewPrice >= 200 && NewPrice < 500) return (NewPrice % 25 == 0) ? NewPrice : (NewPrice + (25 - (NewPrice % 25)));
        if(NewPrice >= 500 && NewPrice < 1000) return (NewPrice % 50 == 0) ? NewPrice : (NewPrice + (50 - (NewPrice % 50)));
        if(NewPrice >= 1000 && NewPrice < 2000) return (NewPrice % 100 == 0) ? NewPrice : (NewPrice + (100 - (NewPrice % 100)));
        if(NewPrice >= 2000 && NewPrice < 5000) return (NewPrice % 250 == 0) ? NewPrice : (NewPrice + (250 - (NewPrice % 250)));
        if(NewPrice >= 5000 && NewPrice < 10000) return (NewPrice % 500 == 0) ? NewPrice : (NewPrice + (500 - (NewPrice % 500)));
        if(NewPrice >= 10000 && NewPrice < 20000) return (NewPrice % 1000 == 0) ? NewPrice : (NewPrice + (1000 - (NewPrice % 1000)));
        if(NewPrice >= 20000 && NewPrice < 50000) return (NewPrice % 2500 == 0) ? NewPrice : (NewPrice + (2500 - (NewPrice % 2500)));
        if(NewPrice >= 50000 && NewPrice < 100000) return (NewPrice % 5000 == 0) ? NewPrice : (NewPrice + (5000 - (NewPrice % 5000)));
        if(NewPrice >= 100000) return (NewPrice % 10000 == 0) ? NewPrice : (NewPrice + (10000 - (NewPrice % 10000)));
    }
}