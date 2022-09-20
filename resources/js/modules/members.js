// Members page

import {spinner} from './_functions.js';

$(document).ready(function(){

    // Table
    $('input#scores').keyup(function(){
        if($('input#scores').val() === ''){
            $('input#sort').prop('checked', true);
        }else{
            $('input#sort').prop('checked', false);
        }
    });

    $(document).on('click', 'td .add-btn:not(.disabled)', function(){
        let button = $(this);
        if(confirm('Добавить '+ button.data('amount') + ' ' + button.data('target') +' для '+ button.data('nickname') +'?')){
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
                    button.parent().find('.number').html(spinner());
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

    $('.reset-btn').click(function(){
        let button = $(this);
        if(confirm('Обнулить посещаемость за 10 дней?')) {
            $.ajax({
                cache: false,
                dataType: 'json',
                data: {
                    '_token': button.data('token'),
                },
                url: 'evoque/reset',
                beforeSend: function () {
                    $('.member-convoys .number:not(.trainee)').html(spinner());
                    button.addClass('disabled');
                },
                success: function (response) {
                    $('.member-convoys .number:not(.trainee)').html('0');
                },
                complete: function () {
                    button.removeClass('disabled');
                }
            });
        }
    });

    $('a.reset-permission').click(function(e){
        let $checkbox = $(this).parent().find('input');
        $checkbox.prop('disabled', 'disabled');
        e.stopPropagation();
    });

    $('.member-permissions .custom-checkbox').click(function(e){
        let $checkbox = $(this).find('input');
        if($checkbox.prop('disabled') !== false){
            e.preventDefault();
            $checkbox.prop('disabled', false);
        }
    });

});
