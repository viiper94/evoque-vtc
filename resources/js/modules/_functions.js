export function readURL(input, selector){
    if(selector === undefined) selector = '#preview';
    if (input.files && input.files[0]){
        let reader = new FileReader();
        reader.onload = function (e) {
            $(selector).attr('src', e.target.result);
        };
        reader.readAsDataURL(input.files[0]);
    }
}

export function readURLFile(file, selector){
    if(selector === undefined) selector = '#preview';
    if(file){
        let reader = new FileReader();
        reader.onload = function (e) {
            $(selector).attr('src', e.target.result);
        };
        reader.readAsDataURL(file);
    }
}

export function spinner(){
    return "<div class=\"spinner-border spinner-border-sm text-warning\" role=\"status\">\n" +
        "  <span class=\"sr-only\">Loading...</span>\n" +
        "</div>";
}

export function addToast(header, text, color = 'success'){
    return "<div class=\"toast toast-dark toast-"+color+"\">\n" +
        "        <div class=\"toast-header\">\n" +
        "            <strong class=\"mr-auto\">"+header+"</strong>\n" +
        "            <button type=\"button\" class=\"close text-shadow\" data-dismiss=\"toast\" aria-label=\"Close\">\n" +
        "                <span aria-hidden=\"true\">&times;</span>\n" +
        "            </button>\n" +
        "        </div>\n" +
        "        <div class=\"toast-body\">"+text+"</div>\n" +
        "    </div>";
}
