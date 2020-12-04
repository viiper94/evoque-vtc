@extends('layout.index')

@section('title')
    @if($kb->title) Редактирование @else Создание @endif статьи для базы знаний | @lang('general.vtc_evoque')
@endsection

@section('assets')
    <link rel="stylesheet" type="text/css" href="/js/simplemde/dist/simplemde-dark.min.css">
    <script src="/js/simplemde/dist/simplemde.min.js"></script>
@endsection

@section('content')

    <div class="container py-5">
        @include('layout.alert')
        <h2 class="text-primary mt-3">@if($kb->title) Редактирование @else Создание @endif статьи</h2>
        <form method="post">
            @csrf
            <div class="form-group">
                <label for="title">Заголовок статьи</label>
                <input type="text" class="form-control" id="title" name="title" value="{{ $kb->title }}" required>
                @if($errors->has('title'))
                    <small class="form-text">{{ $errors->first('title') }}</small>
                @endif
            </div>
            <div class="form-group">
                <label for="category">Категория</label>
                <input type="text" class="form-control" id="category" name="category" value="{{ $kb->category }}" required>
                @if($errors->has('category'))
                    <small class="form-text">{{ $errors->first('category') }}</small>
                @endif
            </div>
            <div class="custom-control custom-checkbox mb-2">
                <input type="checkbox" class="custom-control-input" id="visible" name="visible" @if($kb->visible) checked @endif>
                <label class="custom-control-label" for="visible">Опубликована</label>
            </div>
            <div class="custom-control custom-checkbox mb-2">
                <input type="checkbox" class="custom-control-input" id="public" name="public" @if($kb->public) checked @endif>
                <label class="custom-control-label" for="public">Публичная статья</label>
            </div>
            <textarea name="article" id="article">{{ $kb->article }}</textarea>
            <button type="submit" class="btn btn-outline-warning">Сохранить</button>
        </form>
    </div>

    <script>
        var simplemde = new SimpleMDE({
            element: $('#article')[0],
            promptURLs: true,
        });
    </script>

@endsection
