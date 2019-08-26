define([
    'jquery',
 ], function($) {
        'use strict';
 
        return function(config){

            var loading = false;

            //Load more exp
            $(config.btnLoadMoreExp).on('click', function() {
                if(loading){
                    console.log('Loading....');
                    return false;
                }
                $('.loading-loadmore').removeClass('hide');
                loading = true;

                $.ajax({
                    type : 'POST',
                    url : config.action + "&p=" + $(config.pageCurrent).val(),
                    data : {},
                    success: function(res){
                        $('.wrap-exp-load-more').append(res.html);
                        var pageCurrent = parseInt(res.pageCurrent);
                        var lastPageNumber = parseInt($('#last-page-number').val());

                        if(lastPageNumber <= pageCurrent){
                            $(config.btnLoadMoreExp).parent().hide();
                        }else{
                            $(config.pageCurrent).val( parseInt(res.pageCurrent) + 1 );
                        }

                        loading = false;
                        $('.loading-loadmore').addClass('hide');
                    }
                });
            });

            $(document).on('click', '.add-exp-to-cart', function() {
                let id = $(this).data('product_id');

                if(loading){
                    console.log('Loading....');
                    return false;
                }
                $('.loading-addtocart').removeClass('hide');
                loading = true;

                $.ajax({
                    type : 'POST',
                    url : config.actionAddToCart,
                    data : {
                        product_id: id,
                        qty: 1
                    },
                    success: function(res){
                        $('.btn-popup-get-exp').trigger('click');

                        loading = false;
                        $('.loading-addtocart').addClass('hide');
                    }

                });
            });
        }
 
     }
 )