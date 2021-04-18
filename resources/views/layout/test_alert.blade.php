@if(session()->get('success'))
    <div class="toast toast-dark toast-success" data-delay="3000">
        <div class="toast-header">
            <strong class="mr-auto">Успех!</strong>
            <button type="button" class="close text-shadow" data-dismiss="toast" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="toast-body">{{ session()->get('success') }}</div>
    </div>
@elseif($errors->any())
    <div class="toast toast-dark toast-danger" data-delay="3000">
        <div class="toast-header">
            <strong class="mr-auto">Ошибка =(</strong>
            <button type="button" class="close text-shadow" data-dismiss="toast" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="toast-body">{{ $errors->first() }}</div>
    </div>
@endif
<script>
    $('.toast').toast('show')
</script>
