define([
    'jquery',
 ], function($) {
        'use strict';
 
        return function(config){
            var loading = false;

            $(window).keydown(function(event){
                if(event.keyCode == 13) {
                    event.preventDefault();
                    return false;
                }
            });

            //Check string hash
            var hash = location.hash;
            if(hash){
                location.hash = '';
                hash = hash.replace("#token=", "");
                hash = hash.split('-');
                if(hash.length == 2){
                    var email = hash[1];
                    var pass = hash[0].slice(4);

                    $('#email-login').val(email);
                    $('#pass-login').val(pass);
                }
            }
            

            $(config.btnSubmit).on('click', function() {
                if ( $(config.selectorForm).validation('isValid') ) {
                    if(loading){
                        console.log('Loading....');
                        return false;
                    }
                    $('.loading').removeClass('hide');
                    loading = true;

                    $.ajax({
                        type : 'POST',
                        url : config.action,
                        data : $(config.selectorForm).serialize(),
                        success: function(res){
                            loading = false;
                            $('.loading').addClass('hide');

                            if(res.code == 500){
                                alert(res.msg);
                            }else{
                                location.href = "/";
                            }
                        }
                    });
                }

                return false;
            });

            $(config.btnSubmitB2B).on('click', function() {
                if ( $(config.selectorFormB2B).validation('isValid') ) {
                    if(loading){
                        console.log('Loading....');
                        return false;
                    }
                    $('.loading').removeClass('hide');
                    loading = true;

                    $.ajax({
                        type : 'POST',
                        url : config.action,
                        data : $(config.selectorFormB2B).serialize(),
                        success: function(res){

                            loading = false;
                            $('.loading').addClass('hide');

                            if(res.code == 500){
                                alert(res.msg);
                            }else{
                                location.href = "/";
                            }
                        }
                    });
                }

                return false;
            });
 
        }
 
     }
 )