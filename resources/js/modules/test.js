// Test page

import $ from "jquery";

$(document).ready(function(){

    // Edit test page
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

});
