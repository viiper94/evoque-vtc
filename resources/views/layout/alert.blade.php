@if(session()->get('success'))
    <div class="container">
        <div class="alert alert-success" role="alert">{{ session()->get('success') }}</div>
    </div>
@endif
@if($errors->any())
    <div class="container">
        <div class="alert alert-danger" role="alert"><b>Ошибка:</b><br>{{ $errors->first() }}</div>
    </div>
@endif
