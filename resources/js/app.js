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

    $(document).on('click', 'td .add-btn:not(.disabled)', function(){
        let button = $(this);
        if(confirm('Добавить '+ (button.data('target') === 'бал' ? '1 ' : '0.5 ') + button.data('target') +' для '+ button.data('nickname') +'?')){
            $.ajax({
                cache: false,
                dataType : 'json',
                type : 'POST',
                data : {
                    '_token' : button.data('token'),
                    'target' : button.data('target'),
                    'member' : button.data('id')
                },
                url : 'evoque/add',
                beforeSend : function(){
                    button.parent().find('.number').html(getPreloaderHtml());
                    button.addClass('disabled');
                },
                success : function(response){
                    button.parent().find('.number').html(response.scores);
                },
                complete : function(){
                    button.removeClass('disabled');
                }
            });
        }
    });

});

function getPreloaderHtml(){
    return "<div class=\"spinner-border spinner-border-sm text-warning\" role=\"status\">\n" +
        "  <span class=\"sr-only\">Loading...</span>\n" +
        "</div>";
}
