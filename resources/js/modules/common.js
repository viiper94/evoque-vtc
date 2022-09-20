import {readURL} from './_functions.js';

$(document).ready(function(){

    $('[data-toggle="tooltip"]').tooltip();

    $(document).on('change', '.uploader', function(){
        readURL(this, '#' + $(this).attr('id') + '-preview');
    });

});
