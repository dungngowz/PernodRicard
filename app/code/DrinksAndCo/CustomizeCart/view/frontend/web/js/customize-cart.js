define([
    'jquery',
 ], function($) {
        'use strict';
 
        return function(config){
            var loading = false;

            $(config.btnSubmit).on('click', function() {
                if(loading){
                    console.log('Loading....');
                    return false;
                }
                $('.loading-addtocart').removeClass('hide');
                loading = true;

                $.ajax({
                    type : 'POST',
                    url : config.action,
                    data : $(config.selectorForm).serialize(),
                    success: function(res){
                        $('.btn-popup-get-consulted').trigger('click');

                        loading = false;
                        $('.loading-addtocart').addClass('hide');
                    }

                });
            });
 
        }
 
     }
 )