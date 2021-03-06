import $ from "jquery"
import bsCustomFileInput from 'bs-custom-file-input'
import Litepicker from 'litepicker'
import 'litepicker/dist/plugins/mobilefriendly';

window.$ = window.jQuery = require('jquery');

import "bootstrap/dist/js/bootstrap.min";
import "./cookie.notice";

$(document).ready(function(){

    $('[data-toggle="popover"]').popover()
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
        if(this.files[0].size > 3200000){
            alert('Превышен лимит размера файла. Максимум 3 МБ!');
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
