// Apply page

$(document).ready(function(){

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

});
