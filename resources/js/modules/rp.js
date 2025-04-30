// RP page

$(document).ready(function(){

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

    $('.distance-btn').click(function(){
        let clicked = $(this);
        $('.distance-btn').addClass('text-secondary');
        clicked.removeClass('text-secondary');
        $('.distance-unit').val(clicked.data('unit'));
    });

});
