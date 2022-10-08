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
                $('.is-invalid').removeClass('is-invalid');
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


    $('.delete-img').click(function(){
        if(confirm('Удалить изображение?')){
            let button = $(this);
            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                url: button.data('action'),
                type : 'POST',
                data : {
                    'target' : button.data('target'),
                },
                beforeSend : function(){
                    button.attr('disabled', 'true');
                    $('.toast').remove();
                    $('.new-convoy').append(addToast('Секундочку...', 'Удаляем картинку', 'warning'));
                    $('.toast').toast('show');
                },
                success : function(response){
                    if(response.status !== 'OK') console.log(response);
                    else{
                        $('.new-convoy').append(addToast('Успех!', 'Картинка удалена', 'success'));
                        $('.toast').toast({'delay': 3000}).toast('show');
                        $('.toast-warning').toast('hide');
                        button.hide();
                        $('#'+button.data('target')).val('');
                        $('label[for='+button.data('target')+']').html('Изображение');
                        $('#'+button.data('target')+'-preview').attr('src', '/images/convoys/image-placeholder.jpg');
                    }
                },
                error: function(jqXHR){
                    $('.new-convoy').append(addToast('Ошибка!', jqXHR.responseJSON.status, 'danger'));
                    $('.toast').toast({'delay': 5000}).toast('show');
                    $('.toast-warning').toast('hide');
                    button.show().attr('disabled', false);
                },
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
                    select.parent().find('.delete-img').hide();
                    $('#'+target+'_image').val('').attr('disabled', true).hide();
                    $('.'+target+'_image-input').hide();
                    $('#'+target+'_image-preview').attr('data-replaces', $('#'+target+'_image-preview').attr('src')).attr('src', response.path);
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
            $('#'+target+'_image-preview').attr('src', $('#'+target+'_image-preview').data('replaces'));
            $('#'+target+'').val('').attr('readonly', false);
            $('#'+target+'_tuning').val('');
            $('#'+target+'_paint').val('').attr('readonly', false);
            $('#'+target+'_public').prop('checked', false).attr('disabled', false);
            if(target === 'trailer'){
                $('.alt_trailer-section').show();
            }
            if(select.parent().find('.delete-img').length > 0){
                select.parent().find('.delete-img').attr('style', false);
            }
        }
    });

    $('[name=truck_image], [name=trailer_image], [name=alt_trailer_image]').change(function(){
        if($(this)[0].files){
            let replaces = $('#'+$(this).attr('name')+'-preview').attr('src');
            $(this).parent().parent().find('.delete-img').hide();
            $('#'+$(this).attr('name')+'-preview').after('<button type="button" class="delete-local-img" data-replaces="'+replaces+'" data-target="'+$(this).attr('name')+'"><i class="fas fa-undo"></i></button>');
        }
    });

    $(document).on('click', '.delete-local-img', function(){
        $('#'+$(this).data('target')+'-preview').attr('src', $(this).data('replaces'));
        $('#'+$(this).data('target')).val('');
        $('#'+$(this).data('target')+' + label').html('Выберите изображение');
        if($(this).parent().find('.delete-img').length > 0){
            $(this).parent().find('.delete-img').attr('style', false);
        }
        $(this).remove();
    });

    $('input#public').change(function(){
        let $checkboxes = $('input#truck_public, input#trailer_public').parent();
        $(this).prop('checked') ? $checkboxes.show() : $checkboxes.hide();
    });

    $('#list-scrollspy a').click(function(e){
        e.preventDefault();
        $([document.documentElement, document.body]).animate({
            scrollTop: ($($(this).attr('href')).offset().top - 60)
        }, 300);
    });

});
