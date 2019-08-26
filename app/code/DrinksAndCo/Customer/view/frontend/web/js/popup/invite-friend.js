define([
   'jquery',
   'Magento_Ui/js/modal/modal'
], function($, modal) {
       'use strict';

       return function(config){

           let options = {
               type: 'popup',
               responsive: true,
               modalClass: 'invite-friend-modal-popup',
               buttons: [{
                   class: 'close-invite-friend-popup',
                   click: function() {
                       this.closeModal();
                   }
               }],
               modalCloseBtn: ['.actionclose'],
               opened: function() {
                   $('#search').focus();
               }
           };

           let popup = modal(options, $(config.contentPopup));

           $(config.selector).on('click', function() {
               popup.openModal();
           });

           $(config.closeBtnSkip).on('click', function() {
               popup.closeModal();
           });

           $(config.btnSubmit).on('click', function() {
               if ( $(config.selectorForm).validation('isValid') ) {
                   $.ajax({
                       type : 'POST',
                       url : config.action,
                       data : $(config.selectorForm).serialize(),
                       success: function(res){
                           console.log(res.msg);
                           alert(res.msg);
                           location.reload();
                       }

                   });
               }
           });

       }

    }
)