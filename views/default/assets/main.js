jQuery(document).ready(function($) {
    'use strict';

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


/*
    var w = $(window).width(), h = $(window).height();

    var ha=0;
    if(w<991){
        $('.Mobile-account-button').click(function() {
            ha=ha + 1;
            if(ha==2){
                $('.Mobile-account .header-dropdown ').addClass('passive');
                ha=0;
            }
            if(ha==1){
                $('.Mobile-account .header-dropdown').removeClass('passive');
            }
        });
    }
*/



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

    // Mobile Menu Toggle - Show & Hide
    $('.mobile-menu-toggler').on('click', function (e) {
        $('body').toggleClass('mmenu-active');
        $(this).toggleClass('active');
        e.preventDefault();
    });


    $('.demo-filter a').on('click', function(e) {
        e.preventDefault();
        var filter = $(this).attr('href').replace('#', '');
        $('.demos').isotope({ filter: '.' + filter });
        $(this).addClass('active').siblings().removeClass('active');
    });

    $('.molla-lz').lazyload({
        effect: 'fadeIn',
        effect_speed: 400,
        appearEffect: '',
        appear: function(elements_left, settings) {

        },
        load: function(elements_left, settings) {
            $(this).removeClass('molla-lz').css('padding-top', '');
        }
    });

    $('.mobile-menu-overlay, .mobile-menu-close').on('click', function (e) {
        $('body').removeClass('mmenu-active');
        $('.menu-toggler').removeClass('active');
        e.preventDefault();
    });

    $('.goto-demos').on('click', function(e) {
        e.preventDefault();
        $('html, body').animate({scrollTop: $('.row.demos').offset().top}, 600);
    });

    $('.goto-features').on('click', function(e) {
        e.preventDefault();
        $('html, body').animate({scrollTop: $('.section-features').offset().top}, 800);
    });

    $('.goto-elements').on('click', function(e) {
        e.preventDefault();
        $('html, body').animate({scrollTop: $('.section-elements').offset().top}, 1000);
    });

    $('.goto-support').on('click', function(e) {
        e.preventDefault();
        $('html, body').animate({scrollTop: $('.section-support').offset().top}, 1200);
    });
});

jQuery(window).on('load', function() {
    jQuery('.demos').isotope({
        filter: '.homepages',
        initLayout: true,
        itemSelector: '.iso-item',
        layoutMode: 'masonry'
    }).on('layoutComplete', function(e) {
        jQuery(window).trigger('scroll');
    });
});