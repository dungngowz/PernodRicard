define([
    'jquery',
 ], function($) {
        'use strict';
 
        return function(config){

            var loading = false;

            //Load more product
            $(config.btnLoadMoreProduct).on('click', function() {
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
                        $('.wrap-product-load-more').append(res.html);
                        var pageCurrent = parseInt(res.pageCurrent);
                        var lastPageNumber = parseInt($('#last-page-number').val());

                        if(lastPageNumber <= pageCurrent){
                            $(config.btnLoadMoreProduct).parent().hide();
                        }else{
                            $(config.pageCurrent).val( parseInt(res.pageCurrent) + 1 );
                        }

                        loading = false;
                        $('.loading-loadmore').addClass('hide');
                    }
                });
            });

            $(document).on('change', '#filter_prod_by_cat', function() {
                location.href = $(this).val();
            });
            
            $(document).on('click', '.add-to-cart', function() {
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
                        $('.btn-popup-get-consulted').trigger('click');
                        loading = false;
                        $('.loading-addtocart').addClass('hide');
                    }

                });
            });

            //Auto submit form
            $(".auto-submit-item").change(function() {

                var levels = [];
                $('.alcohol-levels:checked').each(function(i){
                    levels[i] = $(this).val();
                });

                var sizes = [];
                $('.size:checked').each(function(i){
                    sizes[i] = $(this).val();
                });

                $('input[name=alcohol-level]').val(levels.join(','));
                $('input[name=sizes]').val(sizes.join(','));

                $("form.auto-submit").submit();
            });
        }
 
     }
 )