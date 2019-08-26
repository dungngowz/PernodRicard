define([
   'jquery',
], function($) {
       'use strict';

       return function(config){
            let loadingInvite = false;

            //Submit Form
            $(config.btnSubmit).on('click', function() {
                $(config.selectorMsg).html('');

                if ( $(config.selectorForm).validation('isValid') ) {
                    if(loadingInvite){
                        return false;
                    }
                    $('.loading-invite').removeClass('hide');
                    loadingInvite = true;

                    $.ajax({
                       type : 'POST',
                       url : config.action,
                       data : $(config.selectorForm).serialize(),
                        success: function(res){
                            if(res.code == 'max-invite'){
                                $('#max-invite-form').removeClass('hide');
                                $('#invitation-form, #success-invite-form').addClass('hide');
                            }else if(res.code == 'success-invite'){
                                $('#success-invite-form').removeClass('hide');
                                $('#max-invite-form, #invitation-form').addClass('hide');

                                if(res.againInviteFriends < 1){
                                    $('.btn-fancybox-invite').hide();
                                }else{
                                    $('.btn-fancybox-invite span').html(res.againInviteFriends);
                                }
                            }else{
                                $(config.selectorMsg).html(res.msg);
                            }

                            $('.loading-invite').addClass('hide');
                            loadingInvite = false;
                        }

                    });
                }

               return false;
            });

            //Close popup
            $(config.selectorClosePopup).on('click', function() {
                $.fancybox.close();
            });

            //Close popup and reload page
            $(config.selectorClosePopupAdnReload).on('click', function() {
                $.fancybox.close();
                location.reload();
            });
       }

    }
)