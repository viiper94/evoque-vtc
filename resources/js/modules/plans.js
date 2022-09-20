// Plans page

$(document).ready(function(){

    $('.book-convoy').click(function(){
        let date = $(this).data('date');
        $('.modal #date').val(date)
    });

});
