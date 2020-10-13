import $ from "jquery"
import bsCustomFileInput from 'bs-custom-file-input'

window.$ = window.jQuery = require('jquery');

import "bootstrap/dist/js/bootstrap.min";

$(document).ready(function(){

    bsCustomFileInput.init();
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

    $('.report-accept #distance, .report-accept #weight').keyup(function(){
        let distance = $('.report-accept #distance').val();
        let weight = $('.report-accept #weight').val();
        if(distance !== '' && weight !== ''){
            let k = 0;
            if(weight <= 19 && weight >= 15) k = 0.1;
            if(weight <= 25 && weight >= 20) k = 0.3;
            if(weight <= 32 && weight >= 26) k = 0.5;
            if(weight >= 33) k = 0.7;
            let bonus = distance * k;
            $('.report-accept #bonus').val(bonus);
        }
    });

    $(document).on('change', '.uploader', function(){
        readURL(this, '#' + $(this).attr('id') + '-preview');
    });

    $('#add-convoy-img').click(function(){
        if($('input[id^=route-]').length >= 4){
            $(this).html('<i class="fas fa-times"></i> Угомонись уже, хватит!');
            return false;
        }
        let index = $(this).data('index')+1;
        $(this).data('index', index);
        let template = $('#'+$(this).data('target')+'_template').html().replace(/%i%/g, index);
        $(this).before(template);
        bsCustomFileInput.init();
        return true;
    });

    $('#delete-convoy-img').click(function(){
        $('.route-images .form-group').remove();
        let index = 0;
        $('#add-convoy-img').data('index', index).html('<i class="fas fa-plus"></i> Еще картинку');
        let template = $('#'+$(this).data('target')+'_template').html().replace(/%i%/g, index).replace('Еще одно ', '');
        $('#add-convoy-img').before(template);
        bsCustomFileInput.init();
        return true;
    });

});

function getPreloaderHtml(){
    return "<div class=\"spinner-border spinner-border-sm text-warning\" role=\"status\">\n" +
        "  <span class=\"sr-only\">Loading...</span>\n" +
        "</div>";
}

function readURL(input, selector){
    if(selector === undefined) selector = '#preview';
    if (input.files && input.files[0]){
        let reader = new FileReader();
        reader.onload = function (e) {
            $(selector).attr('src', e.target.result);
        };
        reader.readAsDataURL(input.files[0]);
    }
}
