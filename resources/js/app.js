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

    if($('.member-scores .add-score')){
        $('.member-scores .add-score').click(function(){
            let button = $(this);
            if(confirm('Добавить 1 бал для '+ button.data('nickname') +'?')){
                $.ajax({
                    cache: false,
                    dataType : 'json',
                    type : 'POST',
                    data : {
                        '_token' : button.data('token'),
                        'member' : button.data('id')
                    },
                    url : 'addscore',
                    beforeSend : function(){
                        button.parent().find('.scores-number').html(getPreloaderHtml());
                    },
                    success : function(response){

                    }
                });
            }
        });
    }

});

function getPreloaderHtml(){
    return "<div class=\"spinner-border spinner-border-sm text-warning\" role=\"status\">\n" +
        "  <span class=\"sr-only\">Loading...</span>\n" +
        "</div>";
}
