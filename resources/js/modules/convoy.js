// Convoy page

import {readURLFile, addToast, spinner} from './_functions.js';
import bsCustomFileInput from "bs-custom-file-input";
import 'jquery-ui/ui/core';
import 'jquery-ui/ui/widgets/mouse';
import 'jquery-ui/ui/widgets/sortable';

$(document).ready(function(){

    $('.add-cargoman').click(function(){
        $('#cargomanModal form #id').val($(this).data('id'));
    });

    // Edit page
    var formFiles = [];

    bsCustomFileInput.init();
    $('.convoy-images').sortable();
    $('body').scrollspy({
        target: '#list-scrollspy',
        offset: 120
    });

    $(document).on('change', '.convoy-images-uploader .custom-file-input', function(){
        // handling adding image to list
        let files = $(this)[0].files;
        let nextId = $('.convoy-image-preview').length;
        let maxFiles = $(this).data('allowed') > files.length ? files.length : $(this).data('allowed');
        if($(this).data('allowed') < files.length) alert('Чёт перебор... Оставил только '+ maxFiles + ' шт.');
        for(let i = 0; i < maxFiles; i++){
            if(files[i].size > 3200000){
                alert('Превышен лимит размера одного из файлов. Максимум 3 МБ!');
                return false;
            }
        }
        for(let i = 0; i < maxFiles; i++){
            formFiles.push(files[i]);
            $('.convoy-images').append('<div class="convoy-image-preview m-2"><div class="delete-route-img"></div><img data-name="'+files[i].name+'"></div>');
            readURLFile(files[i], '.convoy-image-preview:nth-child('+(i+nextId+1)+') img');
        }
        let allowed = 6-$('.convoy-image-preview').length;
        $('.new-convoy .custom-file-input').attr('data-allowed', allowed).data('allowed', allowed);
        if($('.convoy-image-preview').length >= 6){
            $('.add-convoy-image').hide();
        }
        $(this).val('');
    });

    $(document).on('click', '.convoy-image-preview .delete-route-img', function(){
        // deleting image from list
        if(confirm('Удалить изображение?')){
            let deletedName = $(this).parent().find('img').data('name');
            $(formFiles).each(function(index){
                if(this.name === deletedName) formFiles.splice(index, 1);
            });
            $(this).parent().remove();
        }
        if($('.convoy-image-preview').length >= 6){
            $('.add-convoy-image').hide()
        }else{
            $('.add-convoy-image').show();
            let allowed = 6-$('.convoy-image-preview').length;
            $('.new-convoy .custom-file-input').attr('data-allowed', allowed).data('allowed', allowed);
        }
    });

    $('.new-convoy form').submit(function(e){
        e.preventDefault();
        let data = new FormData();
        $('form input[id]:not([type=radio]), form textarea[id], form select[id], form input[id][type=radio]:checked').each(function(){
            if($(this).attr('name') === 'dlc[]'){
                for(let i = 0; i < $(this).val().length; i++){
                    data.append('dlc[]', $(this).val()[i]);
                }
            }else if($(this).attr('type') === 'file' && this.files[0] !== undefined){
                data.append($(this).attr('name'), this.files[0]);
            }else if($(this).attr('type') === 'checkbox'){
                data.append($(this).attr('name'), ($(this).prop('checked') ? '1' : '0'));
            }else{
                data.append($(this).attr('name'), $(this).val());
            }
        });
        let convoyImagesList = [];
        $('.convoy-image-preview img').each(function(){
            convoyImagesList.push($(this).data('name'));
        });
        if(convoyImagesList.length  === 0){
            $('.new-convoy').append(addToast('Ошибка!', 'Нужны скриншоты маршрута', 'danger', 3000));
            $('.toast').toast('show');
            $('.toast-warning').toast('hide');
            $('[type=submit]').attr('disabled', false);
            return false;
        }
        data.append('imageList', convoyImagesList);
        $(formFiles).each(function(){
            data.append('route['+this.name+']', this);
        });
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            method : 'POST',
            dataType : 'json',
            contentType: false,
            processData: false,
            data: data,
            beforeSend: function(){
                $('.toast').remove();
                $('[type=submit]').attr('disabled', 'true');
                $('.new-convoy').append(addToast('Секундочку...', 'Проверяем и сохраняем данные', 'warning'));
                $('.toast').toast({'delay': 999999}).toast('show');
            },
            success: function(response){
                if(response.redirect){
                    $('.new-convoy').append(addToast('Успех!', response.message));
                    $('.toast').toast({'delay': 999999}).toast('show');
                    $('.toast-warning').toast('hide');
                    setTimeout(function(){
                        window.location = response.redirect;
                    }, 2000);
                }
            },
            error: function(jqXHR){
                if(jqXHR.responseJSON.errors){
                    $('.new-convoy').append(addToast('Ошибка!', jqXHR.responseJSON.message, 'danger'));
                    $('.toast').toast({'delay': 5000}).toast('show');
                    $('.toast-warning').toast('hide');
                    $('[type=submit]').attr('disabled', false);
                    $.each(jqXHR.responseJSON.errors, function(key){
                        $('#'+key).addClass('is-invalid');
                    });
                }
            },
        });
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
