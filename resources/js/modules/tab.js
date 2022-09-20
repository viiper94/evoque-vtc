// Tab page

$(document).ready(function(){

    // Accept tab
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

});
