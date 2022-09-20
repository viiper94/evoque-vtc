// Profile page

import {spinner} from './_functions.js';

$(document).ready(function(){

    $('#generate-data-dump').click(function(){
        let button = $(this);
        if(!button.attr('href')){
            $.ajax('/evoque/profile/dump',{
                data: {
                    '_token' : button.data('token'),
                },
                beforeSend : function(){
                    button.html(spinner());
                },
                success : function(response){
                    button.addClass('btn-success').removeClass('btn-outline-info').html('<i class="fas fa-download"></i> Скачать свои данные').attr('href', '/dumps/'+response).prop('download', response);
                },
            });
        }
    });

    //Edit profile
    $('#check-plate-btn').click(function(){
        let input = $('#plate');
        let btn = $(this);
        if(input.val().length === 0) return false;
        let oldVal = input.val().split('');
        let newVal = [];
        $.each(oldVal, function(index, char){
            if(char.match(/[0-9]/)){
                newVal.push(char);
            }
        });
        input.val(newVal.join(''));

        $.ajax({
            cache: false,
            dataType : 'json',
            type : 'post',
            data : {
                '_token' : input.data('token'),
                'value' : newVal.join('')
            },
            url : '/evoque/profile/checkPlate',
            beforeSend : function(){
                input.removeClass('is-invalid').removeClass('is-valid');
                if($('#plate-img ~ .spinner-border').length === 0){
                    $('#plate-img').after(spinner());
                }
            },
            success : function(response){
                if(response.data.isFree === true){
                    input.addClass('is-valid').removeClass('is-invalid');
                    $('#plate-img').attr('src', '/images/plates/'+response.data.value+'.png').show();
                    $('#plate-text').html('');
                }else{
                    $('#plate-text').html('Номер уже занят');
                    input.addClass('is-invalid').removeClass('is-valid');
                    $('#plate-img').attr('src', '/images/plates/empty.png');
                }
                input.val(response.data.value);
            },
            complete : function(){
                $('#plate-img ~ .spinner-border').remove();
            }
        });
    });

    $('#remove-discord').click(function(){
        $('#discord').prop('disabled', false).prop('readonly', true).attr('name', 'discord_id').attr('value', '');
    });

});
