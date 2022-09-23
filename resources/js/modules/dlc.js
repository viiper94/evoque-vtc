// DLC page

import 'jquery-ui/ui/core';
import 'jquery-ui/ui/widgets/mouse';
import 'jquery-ui/ui/widgets/sortable';
import {spinner} from './_functions.js';

$(document).ready(function(){

    $('.dlc-list .sortable').sortable({
        handle: '.sort-handle',
        stop: function(event, ui){
            let data = {};
            $.map($(this).find('tr'), function(el){
                data[$(el).index()] = $(el).data('id');
            });
            $.ajax({
                type: 'POST',
                data: {
                    '_token': $('[name=_token]').val(),
                    'data': data
                },
                beforeSend: function(){
                    $('.sort-th').html('Сортировка...').append(spinner())
                },
                success: function(response){
                    $('.spinner-border').remove();
                    $('.sort-th').html('Пересортировано')
                },
            });
        }
    });

    $('.dlc-list .edit-dlc-list-btn').click(function(){
        if($(this).data('id')){
            $('#addDLCLabel').text('Редактирование DLC');
            $('#dlc-modal #id').val($(this).data('id'));
            $('#dlc-modal #title').val($(this).data('title'));
            $('#dlc-modal #steam_link').val($(this).data('steam'));
            $('#dlc-modal #game').val($(this).data('game'));
        }else{
            $('#addDLCLabel').text('Добавление DLC');
            $('#dlc-modal #id').val('');
            $('#dlc-modal #title').val('');
            $('#dlc-modal #steam_link').val('');
        }
        $('#dlc-modal').modal('show');
    });

});
