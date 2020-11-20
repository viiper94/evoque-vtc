@extends('layout.index')

@section('title')
    Заявка на увольнение | @lang('general.vtc_evoque')
@endsection

@section('assets')
    <link rel="stylesheet" type="text/css" href="/js/simplemde/dist/simplemde-dark.min.css">
    <script src="/js/simplemde/dist/simplemde.min.js"></script>
@endsection

@section('content')

    <div class="container pt-5">
        @include('layout.alert')
        <h2 class="mt-3 text-primary">Заявка на увольнение</h2>
        <form method="post" class="mb-5">
            @csrf
            <div class="custom-control custom-checkbox mt-3 mb-3">
                <input type="checkbox" class="custom-control-input" id="fire" name="fire" required>
                <label class="custom-control-label" for="fire">Подтверждаю, что желаю уволиться из компании</label>
            </div>
            <div class="form-group">
                <label for="reason">Причина</label>
                <textarea class="form-control" id="reason" rows="3" name="reason" placeholder="Не обязательно">{{ old('reason') }}</textarea>
                @error('reason')
                    <small class="form-text">{{ $message }}</small>
                @enderror
            </div>
            <button type="submit" class="btn btn-outline-warning"><i class="fas fa-check"></i> Отправить</button>
        </form>
    </div>

    <script>
        var simplemde = new SimpleMDE({
            element: $('#reason')[0],
            promptURLs: true
        });
    </script>

@endsection
