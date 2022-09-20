// Convoy page

import {readURL, spinner} from './_functions.js';
import bsCustomFileInput from "bs-custom-file-input";

$(document).ready(function(){

    $('.add-cargoman').click(function(){
        $('#cargomanModal form #id').val($(this).data('id'));
    });

    // Edit page
    bsCustomFileInput.init();

    $(document).on('change', '.new-convoy .custom-file-input', function(){
        let _URL = window.URL || window.webkitURL;
        console.log(this.files[0].size);
        if(this.files[0].size > 5200000){
            alert('Превышен лимит размера файла. Максимум 5 МБ!');
            $(this).val('');
            $(this).parent().find('label').html('Виберите изображение');
            $(this).parent().parent().find('img').removeAttr('src');
            return false;
        }
        let file, img;
        if ((file = this.files[0])) {
            img = new Image();
            img.src = _URL.createObjectURL(file);
        }
        readURL(this, '#' + $(this).attr('id') + '-preview');
    });

    $('#add-convoy-img').click(function(){
        if($('input[id^=route-]').length >= 6){
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
        if(confirm('Очистить все слоты для изображений?')){
            $('.route-images .form-group').remove();
            let index = 0;
            $('#add-convoy-img').data('index', index).html('<i class="fas fa-plus"></i> Еще картинку');
            let template = $('#' + $(this).data('target') + '_template').html().replace(/%i%/g, index).replace('Еще одно ', '');
            $('#add-convoy-img').before(template);
            bsCustomFileInput.init();
            return true;
        }
        return false;
    });

    $('.delete-img').click(function(){
        if(confirm('Удалить изображение?')){
            let button = $(this);
            $.ajax({
                cache: false,
                dataType : 'json',
                type : 'POST',
                data : {
                    '_token' : $('form [name=_token]').val(),
                    'target' : button.data('target'),
                    'action' : 'remove_img'
                },
                beforeSend : function(){
                    button.after(spinner());
                },
                success : function(response){
                    if(response.status != 'OK') console.log(response);
                    else{
                        $('#'+button.data('target')).val('');
                        $('label[for='+button.data('target')+']').html('Изображение');
                        $('#'+button.data('target')+'-preview').attr('src', '/images/convoys/image-placeholder.jpg');
                    }
                },
                complete : function(){
                    $('.spinner-border').remove();
                }
            });
        }
        return false;
    });

    $('[name=truck_with_tuning], [name=trailer_with_tuning]').change(function(){
        let select = $(this);
        let target = select.data('target');
        let id = select.find(':selected').val();
        if(id){
            $.ajax('/evoque/tuning/load',{
                data: {
                    '_token' : $('form [name=_token]').val(),
                    'id' : id
                },
                beforeSend : function(){
                    select.after(spinner());
                },
                success : function(response){
                    $('#'+target+'_image').val('').attr('disabled', true).hide();
                    $('.'+target+'_image-input').hide();
                    $('#'+target+'_image-preview').attr('src', response.path);
                    $('#'+target+'').val(select.find(':selected').text()).attr('readonly', true);
                    $('#'+target+'_tuning').val('Официальный из мода');
                    $('#'+target+'_paint').val('Официальный').attr('readonly', true);
                    $('#'+target+'_public').prop('checked', false).attr('disabled', true);
                    if(target === 'trailer'){
                        $('.alt_trailer-section').hide();
                    }
                },
                complete : function(){
                    $('.spinner-border').remove();
                },
            });
        }else{
            $('#'+target+'_image').val('').attr('disabled', false).show();
            $('.'+target+'_image-input').show();
            $('#'+target+'_image-preview').attr('src', '/images/tuning/image-placeholder.jpg');
            $('#'+target+'').val('').attr('readonly', false);
            $('#'+target+'_tuning').val('');
            $('#'+target+'_paint').val('').attr('readonly', false);
            $('#'+target+'_public').prop('checked', false).attr('disabled', false);
            if(target === 'trailer'){
                $('.alt_trailer-section').show();
            }
        }
    });

});
