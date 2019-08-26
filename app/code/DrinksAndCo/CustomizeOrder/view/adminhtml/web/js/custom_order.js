require([
    'jquery'
], function ($) {
    'use strict';

    $(document).ready(function(){
        let statusHistory = $('select#history_status').val();
        
        if(statusHistory == 'cb'){
            $('.wrap_history_agent').css('display', 'block');
        }else{
            $('.wrap_history_agent').css('display', 'none');
        }

    	$('select#history_status').on('change', function() {
            let statusHistory = $(this).val();
 
            if(statusHistory == 'cb'){
                $('.wrap_history_agent').css('display', 'block');
            }else{
                $('.wrap_history_agent').css('display', 'none');
            }
 
        });
    });

});