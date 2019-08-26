define([
    'jquery',
    'isotope'
], function($, isotope) {
    'use strict';

    return function(config) {

        var loading = false;

        //Load more product
        $(config.btnLoadMoreProduct).on('click', function() {
            if (loading) {
                return false;
            }
            loading = true;
            // console.log('view-more', $grid);
            $.ajax({
                type: 'POST',
                url: config.action + "&p=" + $(config.pageCurrent).val(),
                data: {},
                success: function(res) {
                    // $('.wrap-history-load-more').append(res.html);

                    $('.wrap-history-load-more').append(res.html)

                    setTimeout(() => {
                        var $grid = new isotope('.masonry', {
                            itemSelector: '.it',
                            percentPosition: true,
                            masonry: {
                                columnWidth: '.it'
                            },
                            animationEngine: 'jquery',
                            animationEngine: 'css',
                            animationOptions: {
                                duration: 1000,
                                easing: 'linear',
                                queue: false
                            }
                        });

                        // $grid.layout();
                    }, 100);


                    var pageCurrent = parseInt(res.pageCurrent);
                    var lastPageNumber = parseInt($('#last-page-number').val());

                    if (lastPageNumber <= pageCurrent) {
                        $(config.btnLoadMoreProduct).parent().hide();
                    } else {
                        $(config.pageCurrent).val(parseInt(res.pageCurrent) + 1);
                    }

                    loading = false;
                }
            });
        });

    }

})