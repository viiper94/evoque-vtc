import {readURL} from './_functions.js';

$(document).ready(function(){

    $('[data-toggle="tooltip"]').tooltip();

    $(document).on('change', '.uploader', function(){
        if(this.files[0].size > 7200000){
            alert('Превышен лимит размера файла. Максимум 7 МБ!');
            $(this).val('');
            $(this).parent().find('label').html('Виберите изображение');
            $(this).parent().parent().find('img').removeAttr('src');
            return false;
        }
        readURL(this, '#' + $(this).attr('id') + '-preview');
    });

});
