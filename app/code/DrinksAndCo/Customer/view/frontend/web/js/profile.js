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
                    $('.loading-profile').removeClass('hide');
                    loading = true;

                    $.ajax({
                       type : 'POST',
                       url : config.action,
                       data : $(config.selectorForm).serialize(),
                        success: function(res){
                            $('.btn-popup-profile-edit').trigger('click');

                            loading = false;
                            $('.loading-profile').addClass('hide');
                        }
                    });
                }
            });
 
        }
 
     }
 )