define([
    'jquery',
 ], function($) {
        'use strict';
 
        return function(config){
            var loading = false;

            $(config.btnSubmit).on('click', function() {
                if ( $(config.selectorForm).validation('isValid') ) {
                    if(loading){
                        console.log('Loading....');
                        return false;
                    }
                    $('.loading-save-order').removeClass('hide');
                    loading = true;

                    $.ajax({
                       type : 'POST',
                       url : config.action,
                       data : $(config.selectorForm).serialize(),
                        success: function(res){
                            if(res.code == 500){
                                $('.btn-popup-consultant-fail').trigger('click');
                            }else{
                                $('span.your_order').html(res.yourOrder.increment_id);
                                $('.btn-popup-consultant-send').trigger('click');
                                $(config.selectorForm)[0].reset();
                                $('.wrapper-cart, .wrap-edit-profile').remove();
                                $('.nodata').removeClass('hide');
                            }

                            loading = false;
                            $('.loading-save-order').addClass('hide');
                        }
                    });
                }
            });

            $(document).on('click','.remove-confirm',function() {
                $('.btn-delete-item-cart').data('id', $(this).data('id'));
                $('.btn-popup-confirm-update-cart').trigger('click');
            });

            $(document).on('click','.btn-delete-item-cart',function() {
                updateCart($(this).data('id'), 0);
            });

            $(document).on('change','.item-qty-confirm-cart',function() {
                updateCart($(this).data('id'), $(this).val());
            });

            function updateCart(id, qty, stop = false){
                if(loading){
                    return false;
                }
                $('.loading-remove-item-cart').removeClass('hide');
                loading = true;

                $.ajax({
                    type : 'POST',
                    url : config.actionUpdateCart,
                    data : {
                        id: id,
                        qty: qty,
                        stop: stop
                    },
                     success: function(res){
                        loading = false;
                        if(!stop){
                            updateCart(id, qty, true);
                        }else{
                            $('.wrapper-cart').html(res.htmlCart);
                            $('#popup-get-confirm-update-cart .btn-close-fancybox').trigger('click');
                            $('.loading-remove-item-cart').addClass('hide');

                            //If cart empty
                            var totalQty = parseInt($('.total-qty').html());
                            if(totalQty < 1){
                                $('.wrapper-cart, .profile-edit').remove();
                                $('.nodata').removeClass('hide');
                            }
                        }
                     }
                 });
            }
 
        }
 
     }
 )