// Applications page

import {spinner} from './_functions.js';

$(document).ready(function(){

    $('.application').click(function(){
        let $icon = $(this).find('i');
        let id = $(this).data('id');
        $.ajax({
            data: {
                'id': id
            },
            beforeSend : function(){
                $icon.parent().prepend(spinner());
                $icon.hide();
                $('#appModal').modal().on('hidden.bs.modal', function(e){
                    $('#appModal .modal-content').html('');
                });
            },
            success : function(response){
                $('#appModal .modal-content').html(response.html);
            },
            complete: function(){
                $('.spinner-border').remove();
                $icon.show();
            }
        });
    });

});
