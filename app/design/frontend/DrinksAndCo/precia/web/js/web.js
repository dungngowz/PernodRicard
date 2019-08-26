/* FIX Missing Account Links in Mobile */
require(
    ['jquery', 'domReady!'],
    function($) {
        $('.header-top .top-links').clone().appendTo('#store\\.links');
    });
/* ECG Panel*/
require([
    'jquery',
    'accordion'
], function($) {
    $(".ecg-panel").accordion();
});
require(
    ['jquery'],
    function($) {
        /* Scroll Top */
        $(document).ready(function() {
            $(window).scroll(function() {
                if ($(this).scrollTop() > 100) {
                    $('#back-top').addClass('zoomIn animated').removeClass('zoomOut');
                } else {
                    $('#back-top').addClass('zoomOut').removeClass('zoomIn');
                }
            });
            $('#back-top').on("click", function() {
                $('html, body').animate({ scrollTop: 0 }, 500);
            });
            /* Quick Link */
            $('.footer-quick-links').click(function() {
                $(this).toggleClass('active').parent().find('div.footer-top-content').stop().slideToggle('medium');
            });
            /*Category Side Bar */
            $('.category-filter-sidebar .parent  .fa').click(function() {
                $(this).closest('.parent').toggleClass('active');
                $(this).closest('.parent').children('.child').each(function() {
                    $(this).slideToggle('300').toggleClass('active');
                });
            });
            /* Block Search Top */
            $('#ecg-search .action.show').click(function() {
                $('#ecg-search').addClass('active');
            });
            $('#ecg-search .bt-close').click(function() {
                $('#ecg-search').removeClass('active');
            });
            /* Close MINI cart */
            $('.block-minicart:before').click(function() {
                console.log('test');
                $('.action.showcart').trigger('click');

            });
        })

        /* ====================== Sticky Header ============================= */
        headerTransitionEvents();

        function headerTransitionEvents() {
            var headerId = $('#header').attr('data-header');
            checkState(40);
        }

        function checkState(space) {
            var scrollHappened = false;
            var scrollDistance = $(document).scrollTop();
            if (scrollDistance > space && scrollHappened == false) {
                scrollHappened = true;
                transitionHeader(1);
            }
            /*On Scroll*/
            $(window).scroll(function() {
                var scrollDistance = $(document).scrollTop();
                if (scrollDistance > space && scrollHappened == false) {
                    scrollHappened = true;
                    transitionHeader(1);
                } else if (scrollDistance <= space && scrollHappened == true) {
                    scrollHappened = false;
                    transitionHeader(0);
                }
            });
        }

        function transitionHeader(state) {
            if (state == 1) {
                $('body').removeClass('sticky-false');
                $('body').addClass('sticky-true');
            } else {
                $('body').addClass('sticky-false');
                $('body').removeClass('sticky-true');
            }
        }

        /* Nav Toggle */
        $('.action.nav-toggle').click(function() {
                if ($('body').hasClass('nav-open')) {
                    $('body').removeClass('nav-open');
                } else {
                    $('body').addClass('nav-open');
                }
            })
            /* Mobile Menu */
        $('#megamenu .open').click(function() {
                var parent = $(this).parent();
                if (parent.hasClass('active')) {
                    parent.removeClass('active');
                } else {
                    parent.addClass('active');
                }
            })
            /* CountDown */
        $('.count-down').each(function() {
            var el = $(this);
            var time = $(this).attr('data-time');
            var countDownDate = new Date(time).getTime();
            // Update the count down every 1 second
            var x = setInterval(function() {
                // Get todays date and time
                var now = new Date().getTime();
                // Find the distance between now an the count down date
                var distance = countDownDate - now;
                // Time calculations for days, hours, minutes and seconds
                var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                var seconds = Math.floor((distance % (1000 * 60)) / 1000);
                var htmlDay = '<div>' + days + '<span>' + $.mage.__('days') + '</span>' + '</div>';
                var htmlHours = '<div>' + hours + '<span>' + $.mage.__('hours') + '</span>' + '</div>';
                var htmlMinutes = '<div>' + minutes + '<span>' + $.mage.__('minutes') + '</span>' + '</div>';
                var htmlSeconds = '<div>' + seconds + '<span>' + $.mage.__('seconds') + '</span>' + '</div>';
                el.html(htmlDay + htmlHours + htmlMinutes + htmlSeconds);
                // If the count down is finished, write some text
                if (distance < 0) {
                    clearInterval(x);
                    el.html($.mage.__('expired'));
                }
            }, 1000);
        });

        /* Custom Axon*/

    });

require([
    'jquery',
    'magnificPopup'
], function($, modal) {
    'use strict';
    $(document).ready(function() {
        /* Paints Our tools*/
        $('.open_video_popup').click(function() {
            ourToolPopup();
        })

        function ourToolPopup() {
            $.magnificPopup.open({
                items: {
                    src: '#iframe_video'
                },
                type: 'inline',
                removalDelay: 300,
                showCloseBtn: false,
                callbacks: {
                    beforeOpen: function() {
                        this.st.mainClass = 'modal_iframe_video';
                        $('#iframe_video').addClass('zoomIn animated').removeClass('zoomOut');
                    },
                    open: function() {
                        $.magnificPopup.instance.close = function() {
                            $('#iframe_video').addClass('zoomOut animated').removeClass('zoomIn');
                            $.magnificPopup.proto.close.call(this);
                        }
                    }
                }
            });
            $('.video_popup_close').on('click', function() {
                $.magnificPopup.close({
                    items: {
                        src: '#iframe_video'
                    }

                })
            });
        }

    })
})
require([
    'jquery',
    'DrinksAndCo_Base/library/isotope/isotope.pkgd.min',
], function($, Isotope) {
    $(document).ready(function() {
        var el = $('.packery-layout');
        if (el.length) {
            var iso = new Isotope('.packery-layout', {});
        }
    })
})