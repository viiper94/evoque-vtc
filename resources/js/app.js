import $ from "jquery"
window.$ = window.jQuery = require('jquery');

import "bootstrap/dist/js/bootstrap.min";

$(document).ready(function(){

    $('#rules_agreed, #requirements_agreed').change(function(){
        if($('#rules_agreed').prop('checked') && $('#requirements_agreed').prop('checked')){
            $('#submit_btn').prop('disabled', false).removeClass('disabled');
        }else{
            $('#submit_btn').prop('disabled', true).addClass('disabled');
        }
    });

    $('input#scores').keyup(function(){
        if($('input#scores').val() === ''){
            $('input#sort').prop('checked', true);
        }else{
            $('input#sort').prop('checked', false);
        }
    });

});
