if(typeof live_id  != 'undefined') {

    const socket = io.connect("https://phebusmuzayede.com:2021", {
        autoConnect : true
    });

    socket.on('disconnect', (reason) => {
        if (reason === 'io server disconnect') {
            socket.connect();
        }
        console.log(reason);
    });

    socket.on('same seller', () => {
        alert(lang.kendiurunpey);
    });

    socket.on('sendwait', (product_id) => {
         $.post(baseURL + 'crons/checklot', {'id':product_id});
    });

    socket.on('connect', () => {
        console.log('bağlandı');
        socket.emit('joinlive', {'id': live_id , 'username' : live_username });
    });

    socket.on('start',(data) => {
        if(data.first) $('[data-live-messages]').html('<li>'+ lang.start_muzayede + '</li>');
        else {
            $('#btnOffer').removeClass('disabled green').removeAttr('disabled');
            var message = lang.pey_bekleniyor.replace('{LOT}',data.sku);
            $('[data-live-messages]').html('<li>'+ message + '</li>');
        }
    });

    $(document).ready(function() {
        var checkOnline = setInterval('checkLive',170000);
        if(typeof live_username  == 'undefined') live_username = null;

        if(live_username) {
            $('#btnOffer:not(.disabled)').on('click', function() {
                console.log('tıklandı');
                socket.emit('sendPey', {'id' : live_id , 'price' : $('.live-bid-btn').data('btn-offer'), 'product_id':product_id , 'sku':$('[data-lotno]').html()});
            });
        }

        socket.on('end muzayede', () => {
            $('[data-live-messages]').html('<li><strong>'+lang.muzayedetamamlandi+'</strong></li>');
            $(".time-left-bar").width("0");
            $('#btnOffer').removeClass('disabled green').attr('disabled','disabled');
            $('[data-next-price-container]').show();
            $('[data-next-price]').html('-');
        });

        socket.on('past product', (data) => {
            $('#allLots li').removeClass('active');
            if(data.id) {
                var parent = $('#product-'+data.id);
                if(data.sold) {
                    $('[data-live-messages]').prepend('<li>'+ lang.livelotsatildi.replace('{PRICE}',data.price).replace('{LOT}',data.sku) + '</li>');
                    $('[data-sold-price]',parent).html(data.price);
                    $(parent).addClass('sold');
                }
                if(data.user == live_username) {
                    var saleProduct = $(parent).html();
                    $('#allWon ul').append('<li class="sold">'+saleProduct+'</li>');
                }
                $('.live-bid-btn').removeClass('green');
                $('#btnOffer').addClass('disabled').attr('disabled','disabled');
                $('[data-next-price-container]').show();
                $('[data-next-price]').html('-');
            }
        });

        socket.on('new message',(data) => {
            var message = '';
            if(data.same) {
                message = lang.livepeymasada.replace('{PRICE}',data.price);
                $('[data-live-messages]').prepend('<li>'+ message + '</li>');
            } else {
                message = lang.livepeytl.replace('{PRICE}',data.price);
                $('[data-live-messages]').prepend('<li>'+ lang.livepeytl.replace('{PRICE}',data.price) + '</li>');
                timeBarFunction(data.remaining);
            }

            if(data.user == live_username) {
                $('#btnOffer').addClass('disabled green').attr('disabled','disabled');
                $('[data-next-price-container]').hide();
            } else {
                $('#btnOffer').removeClass('disabled green').removeAttr('disabled');
                $('[data-next-price-container]').show();
            }
            $("[data-live-messages] li:first").addClass("new").delay(100).queue(function (next) {
                $(this).removeClass("new");
                next();
            });
            socket.emit('save message', {'product_id' : data.product_id , 'message' : data.price, 'sku': data.sku});
        });

        socket.on('owner', (data) => {
            if(data.user == live_username) {
                $('#btnOffer').addClass('disabled green').attr('disabled','disabled');
                $('[data-next-price-container]').hide();
            } else {
                $('#btnOffer').removeClass('disabled green').removeAttr('disabled');
                $('[data-next-price-container]').show();
            }
        })

        socket.on('messages', (data) => {
            if(data[0]) {
                var message = lang.pey_bekleniyor.replace('{LOT}',data[0].sku);
                $('[data-live-messages]').html('<li>'+ message + '</li>');

                $.each(data , function(index, val) {
                    var message = lang.livepeytl.replace('{PRICE}',val.price);
                    $('[data-live-messages]').prepend('<li>'+ lang.livepeytl.replace('{PRICE}',val.price) + '</li>');
                });

                $("[data-live-messages] li:first").addClass("new").delay(100).queue(function (next) {
                    $(this).removeClass("new");
                    next();
                });
            }

        })

        socket.on('next product',(data) => {
            if(data.id) {
                if(product_id != data.id) {
                    $('[data-product-images]').remove();
                    $('.image .helper').append('<div class="owl-carousel owl-theme owl-light" data-toggle="owl" data-product-images data-owl-options=\'{"dots": true,"nav": true,"loop": true,"responsive": {"1200": {"nav": true,"dots": true}}}\'></div>');

                    $.post(baseURL + 'products/picture', {'id': data.id}, function(json) {
                        $.each(json, function(i,d) {
                            var img = '<div class="item"><img class="pinch" src="data/products/'+ d.product_id + '/' + d.picture +'"></div>';
                            $('[data-product-images]').append(img);
                        });
                        owlCarousels();
                    });
                }
                $('#allLots li').removeClass('active');
                product_id = data.id;
                $('#product-'+data.id).addClass('active');
                $('[data-lotno]').html(data.sku);
                if(data.price == '0.00') $('[data-sale-price-container]').hide();
                else $('[data-sale-price-container]').show();
                $('[data-sale-price]').html(data.price);
                $('[data-open-price]').html(data.old_price);
                $('[data-next-price]').html(data.nextprice);
                $('.estimate.convert').data('price',data.nextprice);
                if(live_username) {
                    $('.live-bid-btn').data('btn-offer',data.nextprice).removeAttr('disabled').removeClass('disabled').removeClass('green');
                }
                $('[data-product-name] strong').html(data.name);
                $('[data-product-info]').html(data.shortdetail);

                if(data.user == live_username) {
                    $('#btnOffer').addClass('disabled green').attr('disabled','disabled');
                    $('[data-next-price-container]').hide();
                }

                var left = $('#product-'+data.id).position().left;
                var currScroll = $("#allLots").scrollLeft();
                var contWidth = $('#allLots').width() / 2;
                var activeOuterWidth = $('#product-'+data.id).outerWidth() / 2;
                left = left + currScroll - contWidth + activeOuterWidth;

                convert(localStorage.getItem("selected-exchange"));
                $('#allLots').scrollLeft(left);
                timeBarFunction(data.remaining);
            }
        });

    });

    function timeBarFunction(remaining) {
        if (remaining === 0 || remaining === waiting || remaining <= fairWaiting) {
            var startWidth = 100 - ((remaining * 100) / waiting);
            $(".time-left-bar").width(startWidth + "%").stop();

            remaining -= 0.3;

            var beforeInt = false;
            $(".time-left-bar").animate({ 'width': '100%' }, {
                duration   : remaining * 1000,
                easing    : 'linear',
                progress :  function(animation, progress, remainingMs ) {

                    if(!beforeInt && parseInt(remainingMs / 1000) == 3) {
                        beforeInt = true;
                        $('[data-live-messages]').prepend('<li>'+ lang.sonrakilotagec + '</li>');
                    }
                },
                complete : function () {
                    $(".time-left-bar").width("100%");
                }
            });
        }
    }

    function checkLive() {
        $.get(baseURL + 'checkonline');
    }

    function startMuzayede() {
        if(live_id) {
            setTimeout(function() {
                console.log("get messages yapıldı - product_id : ", product_id);
                socket.emit('get messages', {'id':live_id , 'product_id':product_id});
                },500);
        }
    }
}