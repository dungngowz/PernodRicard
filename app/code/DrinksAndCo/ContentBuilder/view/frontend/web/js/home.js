define([
    'jquery',
 ], function($) {
        'use strict';
 
        return function(config){
            if( $('.btn-popup-wellcome').length > 0 ){
                $('.btn-popup-wellcome').trigger('click');
            }
        }
 
     }
 )