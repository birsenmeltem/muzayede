// Main Js File

function rebuildhtml(product_id,type,div) {
    $.post(baseURL + 'auctions/getshow', {'product_id':product_id, 'type' : type}, function(data) {
        $(div).html(data);
        InitPey();
        showPey(product_id);
        convert(localStorage.getItem("selected-exchange"));
    })
}
function showPey(product_id) {
    $.post(baseURL + 'auctions/getpey', {'product_id':product_id}, function(data) {
        $('#pey'+product_id).html(data);
    });
}
function InitPey() {
    $('.peysend').unbind('click');
    $('.peysend').on('click', function() {
        var btn = $(this);
        var product_id = $(this).parents('.product').data('id');
        var type = $(this).parents('.product').data('type');
        var input = btn.parents('.product').find('.number-spinner').find('input');

        $.post(baseURL + 'auctions/pey', {'price':input.val(), 'product_id':product_id}, function(json) {
            if(json.status == 'success') {
                swal.fire({
                    title: json.header,
                    html: json.html,
                    type: "success",
                    showCancelButton: true,
                    reverseButtons : true,
                    confirmButtonColor: '#d33',
                    confirmButtonClass: 'btn-success',
                    confirmButtonText: "ONAYLA",
                    cancelButtonText: "İptal Et",
                    closeOnConfirm: false
                }).then(function (result) {
                    if(result.value) {
                        $.post(baseURL + 'auctions/save', {'product_id':product_id, 'price':json.price } , function(data) {
                            if(data.status != 'success') {
                                swal.fire(data.header,data.message , 'error');
                            } else rebuildhtml(product_id,type,btn.parents('.product').find('.result'));

                        })
                    }
                });
            } else {
                if(json.login) {
                    Swal.fire({
                        icon : 'warning',
                        html : json.html,
                        title : json.header,
                        showCancelButton: true,
                        showDenyButton: true,
                        denyButtonText : json.loginbtn,
                        cancelButtonText : json.regbtn,
                    }).then((result) => {
                        if (result.isDismissed) {
                            top.location.href = baseURL + 'user/register?returnUrl='+top.location.href;
                        } else if (result.isDenied) {
                            top.location.href = baseURL + 'user/login?returnUrl='+top.location.href;
                        }
                    })
                } else {
                    swal.fire(json.header,json.html , 'error');
                }

            }
        })

    });

    $('.number-spinner button').unbind('click');
    $('.number-spinner button').on('click', function() {
        var btn = $(this);
        var input = btn.closest('.number-spinner').find('input');
        var product_id = $(this).parents('.product').data('id');

        $.post(baseURL + 'auctions/price', {'price':input.val(), 'product_id':product_id}, function(data) {
            input.val(data);
        })
    });

    $('[data-addwish]').unbind('click');
    $('[data-addwish]').on('click', function() {
        var id = $(this).data('addwish');
        var element = $(this);
        if(id) {
            $.post(baseURL + 'user/addwish/',{'id':id},function(json) {
                if(json.cls == 'success') {
                    if(json.add) $(element).addClass('active');
                    else $(element).removeClass('active');
                } else {
                    alert(json.message);
                }
                $('#takip'+id).html(json.adet);
            })
        }
    });

    $('[data-addlive]').unbind('click');
    $('[data-addlive]').on('click', function() {
        var id = $(this).data('addlive');
        var element = $(this);
        if(id) {
            $.post(baseURL + 'user/addlive/',{'id':id},function(json) {
                if(json.cls == 'success') {
                    if(json.add) $(element).addClass('active');
                    else $(element).removeClass('active');
                } else {
                    alert(json.message);
                }
            })
        }
    });
}

function owlCarousels($wrap, options) {
    if ( $.fn.owlCarousel ) {
        var owlSettings = {
            items: 1,
            loop: true,
            margin: 0,
            responsiveClass: true,
            nav: true,
            navText: ['<i class="icon-angle-left">', '<i class="icon-angle-right">'],
            dots: true,
            smartSpeed: 400,
            autoplay: false,
            autoplayTimeout: 15000
        };
        if (typeof $wrap == 'undefined') {
            $wrap = $('body');
        }
        if (options) {
            owlSettings = $.extend({}, owlSettings, options);
        }

        // Init all carousel
        $wrap.find('[data-toggle="owl"]').each(function () {
            var $this = $(this),
            newOwlSettings = $.extend({}, owlSettings, $this.data('owl-options'));

            $this.owlCarousel(newOwlSettings);

        });
    }
}
$(document).ready(function () {

    if ($(window).width() > 770) {
        $('.product-media').css({
            'height':'250px',
            'overflow':'inherit',
        });
        $(document).on({
            mouseenter: function () {
                var obj = $(this);
                var owlContainer = $('.owl-carousel', obj);

                $(this).addClass('hover');
                setTimeout(function () {
                    owlContainer.trigger('refresh.owl.carousel');
                }, 10);
            },
            mouseleave: function () {
                var obj = $(this);
                var owlContainer = $('.owl-carousel', obj);

                $(this).removeClass('hover');
                setTimeout(function () {
                    owlContainer.trigger('refresh.owl.carousel');
                }, 10);
            },
        }, ".product-media");
    }

    $(".mobile-menu-toggler").click(function () {
        $(".menu-header").toggleClass('menu-header-active');
        $("body").toggleClass('over');
        $(this).toggleClass('mobile-menu-toggler-active');

    });
    $(".mobile-menu-search").click(function () {
        $(".header-search").toggleClass('header-search-mobile_active');
        $(this).toggleClass('mobile-menu-search-active');

    });
    $(".Filter-open").click(function () {
        $(".Filter").toggleClass('Filter-active');
        $("body").toggleClass('over');
    });
    $(".Filter-close").click(function () {
        $(".Filter").toggleClass('Filter-active');
        $("body").toggleClass('over');
    });
    $(".Account-open").click(function () {
        $(".Account-menu").toggleClass('Account-menu-active');
        $("body").toggleClass('over');
    });
    $(".Account-close").click(function () {
        $(".Account-menu").toggleClass('Account-menu-active');
        $("body").toggleClass('over');
    });
    var $file = $("#upload");
    $("#upload").change(function(e){

        var name = e.target.files[0].name;
        $("#ts-fileupload-container .name").html("<span class='name'> " + name + "</span>");
        $("#ts-fileupload-container .text").html("<span>Seçildi</span>");

    });
    owlCarousels();

    setTimeout(function() {
        $('.owls').owlCarousel({
            items: 1,
            loop: true,
            margin: 0,
            responsiveClass: true,
            nav: true,
            navText: ['<i class="icon-angle-left">', '<i class="icon-angle-right">'],
            dots: true,
            smartSpeed: 400,
            autoplay: false,
            autoplayTimeout: 15000
        });
        },1000);
    /*
    $(".peys").on("change", function (event) {
    var value = $(this).val();
    var element = $(this);
    $.post(baseURL + 'auctions/price', {'price':value}, function(data) {
    $(element).val(data)
    })
    });

    $(".peys").inputSpinner({
    decrementButton: '',
    incrementButton: '<i class="icon-plus"></i>',
    groupClass: 'input-spinner',
    buttonsClass: 'btn-spinner',
    buttonsWidth: '26px'
    });
    */

    // Header Search Toggle

    $.each($('.cdown .remaining'), function(index,val) {
        $(val).cdown({
            id: $(this).data('id'),
            liveUrl: $(this).data('live'),
            milliseconds: $(this).data('date'),
            liveStatus: $(this).data('status'),
            liveHas: parseInt($(this).data('livehas'))
        });
    });

    $('.header-search').on('click', function (e) {
        e.stopPropagation();
    });

    // Sticky header
    var catDropdown = $('.category-dropdown'),
    catInitVal = catDropdown.data('visible');

    if ( $('.sheader').length && $(window).width() >= 992 ) {
        var sticky = new Waypoint.Sticky({
            element: $('.sheader')[0],
            stuckClass: 'fixed',
            offset: -300,
            handler: function ( direction ) {
                // Show category dropdown
                if ( catInitVal &&  direction == 'up') {
                    catDropdown.addClass('show').find('.dropdown-menu').addClass('show');
                    catDropdown.find('.dropdown-toggle').attr('aria-expanded', 'true');
                    return false;
                }

                // Hide category dropdown on fixed header
                if ( catDropdown.hasClass('show') ) {
                    catDropdown.removeClass('show').find('.dropdown-menu').removeClass('show');
                    catDropdown.find('.dropdown-toggle').attr('aria-expanded', 'false');
                }
            }
        });
    }

    // Menu init with superfish plugin
    if ( $.fn.superfish ) {
        $('.menu, .menu-vertical').superfish({
            popUpSelector: 'ul, .megamenu',
            hoverClass: 'show',
            delay: 0,
            speed: 80,
            speedOut: 80 ,
            autoArrows: true
        });
    }

    // Mobile Menu Toggle - Show & Hide
    $('.mobile-menu-toggler').on('click', function (e) {
        $body.toggleClass('mmenu-active');
        $(this).toggleClass('active');
        e.preventDefault();
    });

    $('.mobile-menu-overlay, .mobile-menu-close').on('click', function (e) {
        $body.removeClass('mmenu-active');
        $('.menu-toggler').removeClass('active');
        e.preventDefault();
    });

    // Add Mobile menu icon arrows to items with children
    $('.mobile-menu').find('li').each(function () {
        var $this = $(this);

        if ( $this.find('ul').length ) {
            $('<span/>', {
                'class': 'mmenu-btn'
            }).appendTo($this.children('a'));
        }
    });

    // Mobile Menu toggle children menu
    $('.mmenu-btn').on('click', function (e) {
        var $parent = $(this).closest('li'),
        $targetUl = $parent.find('ul').eq(0);

        if ( !$parent.hasClass('open') ) {
            $targetUl.slideDown(300, function () {
                $parent.addClass('open');
            });
        } else {
            $targetUl.slideUp(300, function () {
                $parent.removeClass('open');
            });
        }

        e.stopPropagation();
        e.preventDefault();
    });

    // Sidebar Filter - Show & Hide
    var $sidebarToggler = $('.sidebar-toggler');
    $sidebarToggler.on('click', function (e) {
        $body.toggleClass('sidebar-filter-active');
        $(this).toggleClass('active');
        e.preventDefault();
    });

    $('.sidebar-filter-overlay').on('click', function (e) {
        $body.removeClass('sidebar-filter-active');
        $sidebarToggler.removeClass('active');
        e.preventDefault();
    });

    // Clear All checkbox/remove filters in sidebar filter
    $('.sidebar-filter-clear').on('click', function (e) {
        $('.sidebar-shop').find('input').prop('checked', false);

        e.preventDefault();
    });

    // Popup - Iframe Video - Map etc.
    if ( $.fn.magnificPopup ) {
        $('.btn-iframe').magnificPopup({
            type: 'iframe',
            removalDelay: 600,
            preloader: false,
            fixedContentPos: false,
            closeBtnInside: false
        });
    }

    // Product hover
    if ( $.fn.hoverIntent ) {
        $('.product-3').hoverIntent(function () {
            var $this = $(this),
            animDiff = ( $this.outerHeight() - ( $this.find('.product-body').outerHeight() + $this.find('.product-media').outerHeight()) ),
            animDistance = ( $this.find('.product-footer').outerHeight() - animDiff );

            $this.find('.product-footer').css({ 'visibility': 'visible', 'transform': 'translateY(0)' });
            $this.find('.product-body').css('transform', 'translateY('+ -animDistance +'px)');

            }, function () {
                var $this = $(this);

                $this.find('.product-footer').css({ 'visibility': 'hidden', 'transform': 'translateY(100%)' });
                $this.find('.product-body').css('transform', 'translateY(0)');
        });
    }

    // Product countdown
    if ( $.fn.countdown ) {
        $('.product-countdown').each(function () {
            var $this = $(this),
            untilDate = $this.data('until'),
            compact = $this.data('compact'),
            dateFormat = ( !$this.data('format') ) ? 'DHMS' : $this.data('format'),
            newLabels = ( !$this.data('labels-short') ) ?
            ['Years', 'Months', 'Weeks', 'Days', 'Hours', 'Minutes', 'Seconds'] :
            ['Years', 'Months', 'Weeks', 'Days', 'Hours', 'Mins', 'Secs'],
            newLabels1 = ( !$this.data('labels-short') ) ?
            ['Year', 'Month', 'Week', 'Day', 'Hour', 'Minute', 'Second'] :
            ['Year', 'Month', 'Week', 'Day', 'Hour', 'Min', 'Sec'];

            var newDate;

            // Split and created again for ie and edge
            if ( !$this.data('relative') ) {
                var untilDateArr = untilDate.split(", "), // data-until 2019, 10, 8 - yy,mm,dd
                newDate = new Date(untilDateArr[0], untilDateArr[1] - 1, untilDateArr[2]);
            } else {
                newDate = untilDate;
            }

            $this.countdown({
                until: newDate,
                format: dateFormat,
                padZeroes: true,
                compact: compact,
                compactLabels: ['y', 'm', 'w', ' days,'],
                timeSeparator: ' : ',
                labels: newLabels,
                labels1: newLabels1

            });
        });

        // Pause
        // $('.product-countdown').countdown('pause');
    }

    // Quantity Input - Cart page - Product Details pages
    function quantityInputs() {
        if ( $.fn.inputSpinner ) {

        }
    }

    // Sticky Content - Sidebar - Social Icons etc..
    // Wrap elements with <div class="sticky-content"></div> if you want to make it sticky
    if ( $.fn.stick_in_parent && $(window).width() >= 992 ) {
        $('.sticky-content').stick_in_parent({
            offset_top: 80,
            inner_scrolling: false
        });
    }



    // Product Image Zoom plugin - product pages
    if ( $.fn.elevateZoom ) {
        $('#product-zoom').elevateZoom({
            gallery:'product-zoom-gallery',
            galleryActiveClass: 'active',
            zoomType: "inner",
            cursor: "crosshair",
            zoomWindowFadeIn: 400,
            zoomWindowFadeOut: 400,
            responsive: true
        });

        // On click change thumbs active item
        $('.product-gallery-item').on('click', function (e) {
            $('#product-zoom-gallery').find('a').removeClass('active');
            $(this).addClass('active');

            e.preventDefault();
        });

        var ez = $('#product-zoom').data('elevateZoom');

        // Open popup - product images
        $('#btn-product-gallery').on('click', function (e) {
            if ( $.fn.magnificPopup ) {
                $.magnificPopup.open({
                    items: ez.getGalleryList(),
                    type: 'image',
                    gallery:{
                        enabled:true
                    },
                    fixedContentPos: false,
                    removalDelay: 600,
                    closeBtnInside: false
                    }, 0);

                e.preventDefault();
            }
        });
    }

    // Product Gallery - product-gallery.html
    if ( $.fn.owlCarousel && $.fn.elevateZoom ) {
        var owlProductGallery = $('.product-gallery-carousel');

        owlProductGallery.on('initialized.owl.carousel', function () {
            owlProductGallery.find('.active img').elevateZoom({
                zoomType: "inner",
                cursor: "crosshair",
                zoomWindowFadeIn: 400,
                zoomWindowFadeOut: 400,
                responsive: true
            });
        });

        owlProductGallery.owlCarousel({
            loop: false,
            margin: 0,
            responsiveClass: true,
            nav: true,
            navText: ['<i class="icon-angle-left">', '<i class="icon-angle-right">'],
            dots: false,
            smartSpeed: 400,
            autoplay: false,
            autoplayTimeout: 15000,
            responsive: {
                0: {
                    items: 1
                },
                560: {
                    items: 2
                },
                992: {
                    items: 3
                },
                1200: {
                    items: 3
                }
            }
        });

        owlProductGallery.on('change.owl.carousel', function () {
            $('.zoomContainer').remove();
        });

        owlProductGallery.on('translated.owl.carousel', function () {
            owlProductGallery.find('.active img').elevateZoom({
                zoomType: "inner",
                cursor: "crosshair",
                zoomWindowFadeIn: 400,
                zoomWindowFadeOut: 400,
                responsive: true
            });
        });
    }

    // Product Gallery Separeted- product-sticky.html
    if ( $.fn.elevateZoom ) {
        $('.product-separated-item').find('img').elevateZoom({
            zoomType: "inner",
            cursor: "crosshair",
            zoomWindowFadeIn: 400,
            zoomWindowFadeOut: 400,
            responsive: true
        });

        // Create Array for gallery popup
        var galleryArr = [];
        $('.product-gallery-separated').find('img').each(function () {
            var $this = $(this),
            imgSrc = $this.attr('src'),
            imgTitle= $this.attr('alt'),
            obj = {'src': imgSrc, 'title': imgTitle };

            galleryArr.push(obj);
        })

        $('#btn-separated-gallery').on('click', function (e) {
            if ( $.fn.magnificPopup ) {
                $.magnificPopup.open({
                    items: galleryArr,
                    type: 'image',
                    gallery:{
                        enabled:true
                    },
                    fixedContentPos: false,
                    removalDelay: 600,
                    closeBtnInside: false
                    }, 0);

                e.preventDefault();
            }
        });
    }

    // Checkout discount input - toggle label if input is empty etc...
    $('#checkout-discount-input').on('focus', function () {
        // Hide label on focus
        $(this).parent('form').find('label').css('opacity', 0);
    }).on('blur', function () {
        // Check if input is empty / toggle label
        var $this = $(this);

        if( $this.val().length !== 0 ) {
            $this.parent('form').find('label').css('opacity', 0);
        } else {
            $this.parent('form').find('label').css('opacity', 1);
        }
    });

    // Dashboard Page Tab Trigger
    $('.tab-trigger-link').on('click', function (e) {
        var targetHref = $(this).attr('href');

        $('.nav-dashboard').find('a[href="'+targetHref+'"]').trigger('click');

        e.preventDefault();
    });

    // Masonry / Grid layout fnction
    function layoutInit( container, selector) {
        $(container).each(function () {
            var $this = $(this);

            $this.isotope({
                itemSelector: selector,
                layoutMode: ( $this.data('layout') ? $this.data('layout'): 'masonry' )
            });
        });
    }

    function isotopeFilter ( filterNav, container) {
        $(filterNav).find('a').on('click', function(e) {
            var $this = $(this),
            filter = $this.attr('data-filter');

            // Remove active class
            $(filterNav).find('.active').removeClass('active');

            // Init filter
            $(container).isotope({
                filter: filter,
                transitionDuration: '0.7s'
            });

            // Add active class
            $this.closest('li').addClass('active');
            e.preventDefault();
        });
    }

    /* Masonry / Grid Layout & Isotope Filter for blog/portfolio etc... */
    if ( typeof imagesLoaded === 'function' && $.fn.isotope) {
        // Portfolio
        $('.portfolio-container').imagesLoaded(function () {
            // Portfolio Grid/Masonry
            layoutInit( '.portfolio-container', '.portfolio-item' ); // container - selector
            // Portfolio Filter
            isotopeFilter( '.portfolio-filter',  '.portfolio-container'); //filterNav - .container
        });

        // Blog
        $('.entry-container').imagesLoaded(function () {
            // Blog Grid/Masonry
            layoutInit( '.entry-container', '.entry-item' ); // container - selector
            // Blog Filter
            isotopeFilter( '.entry-filter',  '.entry-container'); //filterNav - .container
        });

        // Product masonry product-masonry.html
        $('.product-gallery-masonry').imagesLoaded(function () {
            // Products Grid/Masonry
            layoutInit( '.product-gallery-masonry', '.product-gallery-item' ); // container - selector
        });

        // Products - Demo 11
        $('.products-container').imagesLoaded(function () {
            // Products Grid/Masonry
            layoutInit( '.products-container', '.product-item' ); // container - selector
            // Product Filter
            isotopeFilter( '.product-filter',  '.products-container'); //filterNav - .container
        });
    }

    // Count
    var $countItem = $('.count');
    if ( $.fn.countTo ) {
        if ($.fn.waypoint) {
            $countItem.waypoint( function () {
                $(this.element).countTo();
                }, {
                    offset: '90%',
                    triggerOnce: true
            });
        } else {
            $countItem.countTo();
        }
    } else {
        // fallback
        // Get the data-to value and add it to element
        $countItem.each(function () {
            var $this = $(this),
            countValue = $this.data('to');
            $this.text(countValue);
        });
    }

    // Scroll To button
    var $scrollTo = $('.scroll-to');
    // If button scroll elements exists
    if ( $scrollTo.length ) {
        // Scroll to - Animate scroll
        $scrollTo.on('click', function(e) {
            var target = $(this).attr('href'),
            $target = $(target);
            if ($target.length) {
                // Add offset for sticky menu
                var scrolloffset = ( $(window).width() >= 992 ) ? ($target.offset().top - 52) : $target.offset().top
                $('html, body').animate({
                    'scrollTop': scrolloffset
                    }, 600);
                e.preventDefault();
            }
        });
    }

    // Review tab/collapse show + scroll to tab
    $('#review-link').on('click', function (e) {
        var target = $(this).attr('href'),
        $target = $(target);

        if ( $('#product-accordion-review').length ) {
            $('#product-accordion-review').collapse('show');
        }

        if ($target.length) {
            // Add offset for sticky menu
            var scrolloffset = ( $(window).width() >= 992 ) ? ($target.offset().top - 72) : ( $target.offset().top - 10 )
            $('html, body').animate({
                'scrollTop': scrolloffset
                }, 600);

            $target.tab('show');
        }

        e.preventDefault();
    });

    // Scroll Top Button - Show
    var $scrollTop = $('#scroll-top');

    $(window).on('load scroll', function() {
        if ( $(window).scrollTop() >= 400 ) {
            $scrollTop.addClass('show');
        } else {
            $scrollTop.removeClass('show');
        }
    });

    // On click animate to top
    $scrollTop.on('click', function (e) {
        $('html, body').animate({
            'scrollTop': 0
            }, 800);
        e.preventDefault();
    });

    // Google Map api v3 - Map for contact pages
    if ( document.getElementById("map") && typeof google === "object" ) {

        var content =   '<address>' +
        '88 Pine St,<br>' +
        'New York, NY 10005, USA<br>'+
        '<a href="#" class="direction-link" target="_blank">Get Directions <i class="icon-angle-right"></i></a>'+
        '</address>';

        var latLong = new google.maps.LatLng(40.8127911,-73.9624553);

        var map = new google.maps.Map(document.getElementById('map'), {
            zoom: 14,
            center: latLong, // Map Center coordinates
            scrollwheel: false,
            mapTypeId: google.maps.MapTypeId.ROADMAP

        });

        var infowindow = new google.maps.InfoWindow({
            content: content,
            maxWidth: 360
        });

        var marker;
        marker = new google.maps.Marker({
            position: latLong,
            map: map,
            animation: google.maps.Animation.DROP
        });

        google.maps.event.addListener(marker, 'click', (function (marker) {
            return function() {
                infowindow.open(map, marker);
            }
            })(marker));
    }

    var $viewAll = $('.view-all-demos');
    $viewAll.on('click', function (e) {
        e.preventDefault();
        $('.demo-item.hidden').addClass('show');
        $(this).addClass('disabled-hidden');
    })

    var $megamenu = $('.megamenu-container .sf-with-ul');
    $megamenu.hover(function() {
        $('.demo-item.show').addClass('hidden');
        $('.demo-item.show').removeClass('show');
        $viewAll.removeClass('disabled-hidden');
    });

    // Product quickView popup

    $('body').on('click', '.carousel-dot', function () {
        $(this).siblings().removeClass('active');
        $(this).addClass('active');
    });



    if(document.getElementById('newsletter-popup-form')) {
        setTimeout(function() {
            var mpInstance = $.magnificPopup.instance;
            if (mpInstance.isOpen) {
                mpInstance.close();
            }

            setTimeout(function() {
                $.magnificPopup.open({
                    items: {
                        src: '#newsletter-popup-form'
                    },
                    type: 'inline',
                    removalDelay: 350,
                    callbacks: {
                        open: function() {
                            $('body').css('overflow-x', 'visible');
                            $('.sheader.fixed').css('padding-right', '1.7rem');
                        },
                        close: function() {
                            $('body').css('overflow-x', 'hidden');
                            $('.sheader.fixed').css('padding-right', '0');
                        }
                    }
                });
                }, 500)
            }, 10000)
    }

    var input = document.querySelector("#phones");
    window.intlTelInput(input, {
        utilsScript: "views/default/assets/js/utils.js?20",
        initialCountry: "auto",
        geoIpLookup: function(success, failure) {
            success('TR');
        }
    });

    /*$(".losings-btn").on("click", function (e) {
        e.stopPropagation();

        var sticky = $(this).closest(".sticky-header");
        var detail = $(this).closest(".sticky-content").find(".sticky-detail");

        loadData(sticky, detail, "losing");
        stickyToggle(sticky,detail);
        detail.show();
        sticky.addClass("selected");
    });

    $(".vinings-btn").on("click", function (e) {
        e.stopPropagation();
        var sticky = $(this).closest(".sticky-header");
        var detail = $(this).closest(".sticky-content").find(".sticky-detail");

        loadData(sticky, detail, "winning");
        detail.show();
        sticky.addClass("selected");
    });*/

    $(".lost-btn").on("click", function (e) {
        e.stopPropagation();

        var detail = $(this).closest(".sticky-content").find(".sticky-detail");

        $('.product', detail).hide();
        $('.lost', detail).show();

    });
    $(".win-btn").on("click", function (e) {
        e.stopPropagation();

        var detail = $(this).closest(".sticky-content").find(".sticky-detail");

        $('.product', detail).hide();
        $('.win', detail).show();

    });

    $(".sheader").on("click", function (e) {
        var sticky = $(this);
        var detail = $(this).parents('.sticky-content').find(".sticky-detail");
        if (sticky.hasClass("selected") && sticky.position().top > 0) {
            if ($(window).width() > 770) {
                $(window).scrollTop(detail.offset().top - $(".sheader:first-child").offset().top - sticky.height());
            } else {
                $(window).scrollTop(detail.offset().top - $("header").height() - sticky.height());
            }
        }

        GetDetailSeller(sticky, detail, $(this).data('type'));
    });

});






function stickyToggle(sticky, detail) {
    sticky.toggleClass("selected");
    detail.toggle();
}

function GetDetailSeller(sticky, detail, fromWhere) {
    var loaded = sticky.data("loaded") == "true";
    var selected = sticky.hasClass("selected");


    stickyToggle(sticky, detail);

    if (!loaded) {
        if (!selected) {
            loadData(sticky, detail, fromWhere);
        }
    }
}

function loadData(sticky, detail, fromWhere) {
    var auctionId = parseInt(sticky.data("auction-id"), 10);

    $.ajax({
        type: "POST",
        url: baseURL + "user/auctiondetail/"+auctionId+'/'+fromWhere,
        success: function (msg) {
            sticky.data("loaded", "true");
            detail.html(msg);
            InitPey();
            owlCarousels();
        }
    });
}


(function ($) {
    $.fn.cdown = function (s) {
        var settings = $.extend({
            id: 0,
            milliseconds: '',

            days: "gün",
            hours: "saat",
            minutes: "dakika",
            seconds: "saniye",

            liveHas: false,
            liveStatus: 0,
            liveUrl: ""
            }, s);

        var obj = $(this);
        var cd = $("span[data-countdown]", obj);
        var auction = obj;

        if (settings.milliseconds) {
            $(".auction-countdown", obj).show();
            $(".auction-finish-date", obj).toggle(!Boolean(settings.liveHas));
            $(".auction-live-date", obj).toggle(Boolean(settings.liveHas));
        }

        cd.countdown(settings.milliseconds)
        .on("update.countdown", function (e) {
            if (e.offset.totalDays > 0) {
                cd.html(e.strftime("%D " + settings.days + " %H " + settings.hours));
            } else if (e.offset.hours > 0) {
                cd.html(e.strftime("%H " + settings.hours + " %M " + settings.minutes));
            } else if (e.offset.minutes > 0) {
                cd.html(e.strftime("%M " + settings.minutes + " %S " + settings.seconds));
            } else if (e.offset.seconds > 0) {
                cd.html(e.strftime("%S " + settings.seconds));
            }
        }).on("finish.countdown", function (e) {
            $("p", obj).hide();

            if (settings.liveHas) {
                if (settings.liveStatus === 3) {
                    $("p.auction-completed", obj).show();
                } else {
                    $("p.auction-live-now", obj).show();
                    $("a", auction).attr("href", settings.liveUrl);
                    $('.peyDiv').remove();
                    if(live_id) startMuzayede();
                }
            } else {
                $("p.auction-completed", obj).show();
            }
        });
    };
    }(jQuery));