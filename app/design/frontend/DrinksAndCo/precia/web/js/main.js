require([
    'jquery',
    'fancybox',
    'isotope',
    'swiper'
], function($, fancybox, isotope, swiper) {
    var $grid
    var h_p
    $(document).ready(function() {


        $(window).on('load', function() {
            main();
            responsive();
        });

        $(window).on('resize', function() {
            responsive();
        });

        $(window).scroll(function() {
            var height_header = $('header').height();
            if ($(window).scrollTop() >= height_header) {
                $('header .menu-scroll').addClass('show');
            } else {
                $('header .menu-scroll').removeClass('show');
            }
        });

        function responsive() {
            var body = document.body,
                html = document.documentElement,
                height = Math.max(body.scrollHeight, body.offsetHeight,
                    html.clientHeight, html.scrollHeight, html.offsetHeight),
                screen_height = $(window).height(),
                img_product = 0;
            if (height <= screen_height) {
                $('footer').addClass('fixed');
            } else {
                $('footer').removeClass('fixed');
            }

            if ($('.home-page').length > 0) {
                $('.wrap-partner').attr('style', '');
                var h_p = $('.wrap-partner .it:first-child').height() + 30;
                $('.wrap-partner').css({ 'height': h_p });
            }

            // if ($('.wrap-product .it').length > 0) {
            //     $('.wrap-product .it .img').attr('style', '');
            //     $('.wrap-product .it .img').each(function() {
            //         var $that = $(this),
            //             img_now = $that.height();
            //         if (img_product <= img_now) {
            //             img_product = img_now;
            //         }
            //     });
            //     $('.wrap-product .it .img').css({ 'height': img_product });
            // }

            $('.attribute-product h4').removeClass('active');
            $('.attribute-product h4, .attribute-product ul').attr('style', '');
        }

        function main() {
            $('.wrap-partner').height();
            $('.wrap-partner').height();

            $(document).on('click', '.scroll-top', function() {
                $('html,body').animate({ scrollTop: 0 }, 1000);
            });

            $(document).on('click', '.attribute-product h4', function() {
                var w_width = $(window).width(),
                    $that = $(this);
                if (w_width <= 640) {
                    if (!$that.hasClass('active')) {
                        $that.addClass('active');
                        $that.parent().find('ul').stop().slideDown();
                    } else {
                        $that.removeClass('active');
                        $that.parent().find('ul').stop().slideUp();
                    }
                } else {

                }
            });

            // if ($('.login-page').length > 0) {
            //     $('.login-page').attr('style', '');
            //     var window_now = $(window).height(),
            //         height_login = $('.login-page').outerHeight(),
            //         height_head = $('.head-page').outerHeight();
            //     if ((height_login + height_head) >= window_now) {
            //         tyle = window_now / (height_login + 100 + height_head);
            //         console.log(tyle);
            //         $('.login-page').css({ 'transform': 'scale(' + tyle + ')' });
            //         $('.login-parent').addClass('m-t-20');
            //         $('.login-parent').removeClass('m-t-60');
            //     }
            // }

            if ($('.slider-relate').length > 0) {
                var swiperRelate = new swiper('.slider-relate .swiper-container', {
                    slidesPerView: 4,
                    loop: false,
                    spaceBetween: 30,
                    autoplay: {
                        delay: 2500,
                        disableOnInteraction: false,
                    },
                    navigation: {
                        nextEl: '.slider-relate .swiper-button-next',
                        prevEl: '.slider-relate .swiper-button-prev',
                    },
                    breakpoints: {
                        1024: {
                            slidesPerView: 4,
                        },
                        768: {
                            slidesPerView: 3,
                        },
                        500: {
                            slidesPerView: 2,
                        },
                    }
                });
            }

            if ($('.slider-partner').length > 0) {
                var swiperPartner = new swiper('.slider-partner .swiper-container', {
                    slidesPerView: 7,
                    loop: true,
                    autoplay: {
                        delay: 2500,
                        disableOnInteraction: false,
                    },
                    spaceBetween: 0,
                    navigation: {
                        nextEl: '.slider-partner .swiper-button-next',
                        prevEl: '.slider-partner .swiper-button-prev',
                    },
                    breakpoints: {
                        1024: {
                            slidesPerView: 5,
                        },
                        768: {
                            slidesPerView: 3,
                        },
                        500: {
                            slidesPerView: 2,
                        },
                    }
                });
            }

            if ($('.banner-home').length > 0) {
                console.log('swiper home banner');
                var swiperBanner = new swiper('.banner-home .swiper-container', {
                    pagination: {
                        clickable: true,
                        el: '.banner-home .swiper-pagination',
                    },
                    autoplay: {
                        delay: 5000,
                    },
                });
            }
            if ($('.slider-bot-home').length > 0) {
                var swiperBotHome = new swiper('.slider-bot-home .swiper-container', {
                    loop: true,
                    spaceBetween: 10,
                    centeredSlides: true,
                    slidesPerView: 12,
                    autoplay: {
                        delay: 2500,
                        disableOnInteraction: false,
                    },
                    breakpoints: {
                        1024: {
                            slidesPerView: 7,
                        },
                        768: {
                            slidesPerView: 4,
                        },
                        480: {
                            slidesPerView: 3,
                        },
                    }
                });
            }

            if ($('.masonry').length > 0) {
                setTimeout(() => {
                    $grid = new isotope('.masonry', {
                        itemSelector: '.it',
                        percentPosition: true,
                        masonry: {
                            columnWidth: '.it'
                        }
                    });
                }, 500);
            }

            $("[data-fancybox]").fancybox({
                afterLoad: function(instance, slide) {
                    //var h_img = $('.wrap-popup .top .img-top').height();
                    // $('.wrap-popup').css({ 'padding-top': h_img / 2 + 20 });
                    //$('.wrap-popup .top .img-top').css({ 'margin-top': '-' + h_img / 2 + 'px' });
                }
            });

            $(document).on('click', '.btn-fancybox-invite', function() {
                $('#invitation-form')[0].reset();
                $('#invitation-form').removeClass('hide');
                $('#max-invite-form, #success-invite-form').addClass('hide');
                $('#trigger-popup-invite').trigger('click');
            });

            $(document).on('click', '.up-down .minus', function() {
                var $that = $(this),
                    number = parseInt($that.parent().find('.number').html());
                number = number - 1;
                if (number <= 0) {
                    number = '01';
                    $that.parent().find('.number').html(number);
                } else if (number < 10) {
                    number = '0' + number.toString();
                    $that.parent().find('.number').html(number);
                } else {
                    $that.parent().find('.number').html(number);
                }

                updateQty($that.parent(), number);

                return false;
            });

            $(document).on('click', '.hamburger', function() {
                if (!$(this).hasClass('open')) {
                    $(this).addClass('open');
                    $('header .menu-head.mobile-show').stop().slideDown();
                } else {
                    $(this).removeClass('open');
                    $('.wrap-menu').removeClass('open');
                    $('header .menu-head.mobile-show').stop().slideUp();
                }
            });

            $(document).on('click', '.profile-list .it .top div', function() {
                var $that = $(this);
                console.log($that);
                if (!$that.hasClass('active')) {
                    $that.addClass('active');
                    $that.closest('.it').addClass('active');
                } else {
                    $that.removeClass('active');
                    $that.closest('.it').removeClass('active');
                }
            });

            $(document).on('click', '.up-down .plus', function() {
                var $that = $(this),
                    number = parseInt($that.parent().find('.number').html());
                number = number + 1;
                if (number < 10) {
                    number = '0' + number.toString();
                    $that.parent().find('.number').html(number);
                } else {
                    $that.parent().find('.number').html(number);
                }

                updateQty($that.parent(), number);

                return false;
            });

            function updateQty(parentSelector, qty) {
                parentSelector.find('.qty').val(parseInt(qty));
            }

            function up() {
                if (g < 30) {
                    g++;
                    document.getElementById('up').innerHTML = g;
                }
            }

            function down() {
                if (g > 17) {
                    g--;
                    document.getElementById('up').innerHTML = g;
                }
            }

            $(document).on('click', '.news-cate a', function() {
                if (!$(this).hasClass('is-checked')) {
                    $('.news-cate a').removeClass('is-checked');
                    $(this).addClass('is-checked');
                    // var filterValue = $(this).attr('data-filter');
                    // $grid.isotope({ filter: filterValue });
                }
            });

            if ($('.home-page').length > 0) {
                $('.wrap-partner').hover(function() {
                    $('.wrap-partner').css({ 'height': h_p });
                }, function() {
                    var h_p = $('.wrap-partner .it:first-child').height() + 30;
                    $('.wrap-partner').css({ 'height': h_p });
                });
            }

            $(document).on('click', '.product-detail .info-detail .list-img a', function() {
                var $that = $(this),
                    data_img = $that.find('img').attr('src'),
                    data_old = $('.product-detail .img-detail').attr('data-old');
                if (!$that.hasClass('active')) {
                    $('.product-detail .info-detail .list-img a').removeClass('active');
                    $that.addClass('active');
                    $('.product-detail .img-detail > img').attr('src', data_img);
                } else {
                    $('.product-detail .info-detail .list-img a').removeClass('active');
                    $that.removeClass('active');
                    $('.product-detail .img-detail > img').attr('src', data_old);
                }

                return false;
            });
        }
    })
})