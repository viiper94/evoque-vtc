import $ from "jquery"
import bsCustomFileInput from 'bs-custom-file-input'
import Litepicker from 'litepicker'
import 'litepicker/dist/plugins/mobilefriendly';

window.$ = window.jQuery = require('jquery');

import "bootstrap/dist/js/bootstrap.min";
import "./cookie.notice";

$(document).ready(function(){

    $('.toast').toast();
    $('[data-toggle="tooltip"]').tooltip();
    bsCustomFileInput.init();

    $('#rules_agreed, #requirements_agreed, #terms_agreed').change(function(){
        if($('#rules_agreed').prop('checked') && $('#terms_agreed').prop('checked')
            && $('#requirements_agreed').prop('checked') && $('.is-invalid').length === 0){
            $('#submit_btn').prop('disabled', false).removeClass('disabled');
        }else{
            $('#submit_btn').prop('disabled', true).addClass('disabled');
        }
    });

    $('#vk_link, #tmp_link, #discord_name').keyup(function(){
        let value = $(this).val();
        let regex = {
            'vk_link': /((http|https):\/\/)?vk\.com\/([0-9a-zA-Z_\.-]+)/,
            'tmp_link': /((http|https):\/\/)?truckersmp\.com\/user\/([0-9]+)/,
            'discord_name': /^.+#[0-9]{4}$/,
        }
        let result = value.match(regex[this.id]);
        if(result){
            $(this).addClass('is-valid').removeClass('is-invalid');
            if($('#rules_agreed').prop('checked') && $('#terms_agreed').prop('checked') && $('#requirements_agreed').prop('checked')){
                $('#submit_btn').prop('disabled', false).removeClass('disabled');
            }
        }else{
            $(this).addClass('is-invalid').removeClass('is-valid');
            $('#submit_btn').prop('disabled', true).addClass('disabled');
        }
    });

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
                    $('#plate-img').after(getPreloaderHtml());
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

    $('.reset-btn').click(function(){
        let button = $(this);
        if(confirm('Обнулить посещаемость за неделю?')) {
            $.ajax({
                cache: false,
                dataType: 'json',
                data: {
                    '_token': button.data('token'),
                },
                url: 'evoque/reset',
                beforeSend: function () {
                    $('.member-convoys .number:not(.trainee)').html(getPreloaderHtml());
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
            img.onload = function () {
                if(this.width > 3000 || this.height > 3000){
                    alert('Превышен лимит размера файла. Максимум 3000x3000px!');
                    $(this).val('');
                    $(this).parent().find('label').html('Виберите изображение');
                    $(this).parent().parent().find('img').removeAttr('src');
                    return false;
                }
            };
        }
        readURL(this, '#' + $(this).attr('id') + '-preview');
    });

    $(document).on('change', '.uploader', function(){
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
                    button.after(getPreloaderHtml());
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

    $('.member-set-scores [id^=lead-]').change(function(){
        let id = $(this).data('member');
        $('#scores-'+id+'-2').prop('checked', true);
    });

    $('a.uncheck-scores').click(function(){
        let target = $(this).data('target');
        let row = $(this).parent().parent();
        console.log($(this).parent());
        $(row).find('[id^='+target+'-]').prop('checked', false);
    });

    $('.book-convoy').click(function(){
        let date = $(this).data('date');
        $('.modal #date').val(date)
    });

    $('#generate-data-dump').click(function(){
        let button = $(this);
        if(!button.attr('href')){
            $.ajax('/evoque/profile/dump',{
                data: {
                    '_token' : button.data('token'),
                },
                beforeSend : function(){
                    button.html(getPreloaderHtml());
                },
                success : function(response){
                    button.addClass('btn-success').removeClass('btn-outline-info').html('<i class="fas fa-download"></i> Скачать свои данные').attr('href', '/dumps/'+response).prop('download', response);
                },
            });
        }
    });

    $('#remove-discord').click(function(){
        $('#discord').prop('disabled', false).prop('readonly', true).attr('name', 'discord_id').attr('value', '');
    });

    $('.add-cargoman').click(function(){
        $('#cargomanModal form #id').val($(this).data('id'));
    });

    $('#add_answer').click(function(){
        if($('input[name=correct]').length >= 4){
            return false;
        }
        let index = $(this).data('index')+1;
        $(this).data('index', index);
        let template = $('#'+$(this).data('target')+'_template').html().replace(/%i%/g, index);
        $('.'+$(this).data('target')).append(template);
        return true;
    });

    $(document).on('click', 'a.remove_answer', function(){
        console.log($(this));
        $('#answer-'+$(this).data('index')).remove();
    });

    $('#truck_with_tuning').change(function(){
        let select = $(this);
        let id = select.find(':selected').val();
        console.log(id);
        if(id){
            $.ajax('/evoque/tuning/load',{
                data: {
                    '_token' : select.data('token'),
                    'id' : id
                },
                beforeSend : function(){
                    select.after(getPreloaderHtml());
                },
                success : function(response){
                    $('#truck_image').val('').attr('disabled', true).hide();
                    $('.truck_image-input').hide();
                    $('#truck_image-preview').attr('src', response.path);
                    $('#truck').val(select.find(':selected').text()).attr('readonly', true);
                    $('#truck_tuning').val('Официальный из мода, стекло чистое');
                    $('#truck_paint').val('Официальный').attr('readonly', true);
                    $('#truck_public').attr('checked', false).attr('disabled', true);
                },
                complete : function(){
                    $('.spinner-border').remove();
                },
            });
        }else{
            $('#truck_image').val('').attr('disabled', false).show();
            $('.truck_image-input').show();
            $('#truck_image-preview').attr('src', '/images/tuning/image-placeholder.jpg');
            $('#truck').val('').attr('readonly', false);
            $('#truck_tuning').val('');
            $('#truck_paint').val('').attr('readonly', false);
            $('#truck_public').attr('checked', false).attr('disabled', false);
        }
    });

    $('.member-permissions .custom-checkbox').click(function(e){
        let $checkbox = $(this).find('input');
        if($checkbox.prop('disabled') !== false){
            e.preventDefault();
            $checkbox.prop('disabled', false);
        }
    });

    $('a.reset-permission').click(function(e){
        let $checkbox = $(this).parent().find('input');
        console.log($checkbox);
        $checkbox.prop('disabled', 'disabled');
        e.stopPropagation();
    });

    $('.application').click(function(){
        let $icon = $(this).find('i');
        let id = $(this).data('id');
        $.ajax({
            data: {
                'id': id
            },
            beforeSend : function(){
                $icon.parent().prepend(getPreloaderHtml());
                $icon.hide();
                $('#appModal').modal().on('hidden.bs.modal', function(e){
                    $('#appModal .modal-content').html('');
                });
            },
            success : function(response){
                $('#appModal .modal-content').html(response.html);
                var simplemde = new SimpleMDE({
                    element: $('#comment')[0],
                    promptURLs: true
                });
            },
            complete: function(){
                $('.spinner-border').remove();
                $icon.show();
            }
        });
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
