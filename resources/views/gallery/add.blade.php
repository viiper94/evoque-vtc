@extends('layout.index')

@section('title')
    Добавление скриншота в Галлерею | @lang('general.vtc_evoque')
@endsection

@section('content')

    <div class="container pt-5 pb-5">
        @include('layout.alert')
        <h2 class="mt-3 text-primary text-center">Добавление скриншота в Галлерею</h2>
        <form method="post" enctype="multipart/form-data">
            @csrf
            <div class="pt-3">
                @can('forceCreate', \App\Gallery::class)
                    <div class="custom-control custom-checkbox mb-2">
                        <input type="checkbox" class="custom-control-input" id="public" name="public" checked>
                        <label class="custom-control-label" for="public">Опубликовать сразу</label>
                    </div>
                @endcan
                <div class="form-group">
                    <label for="author">Автор</label>
                    <input type="text" class="form-control" id="author" name="author" value="{{ old('author') }}" placeholder="Укажите, если автор не вы">
                    @if($errors->has('author'))
                        <small class="form-text">{{ $errors->first('author') }}</small>
                    @endif
                </div>
                <div class="form-group">
                    <div class="custom-file custom-file-dark mb-3">
                        <input type="file" class="custom-file-input uploader" id="image" name="image" accept="image/*">
                        <label class="custom-file-label" for="image">Выберите скриншот</label>
                    </div>
                    <img class="w-100" id="image-preview">
                </div>
            </div>
            <div class="row justify-content-center">
                <button class="btn btn-outline-warning btn-lg" type="submit"><i class="fas fa-save"></i> Сохранить</button>
            </div>
        </form>
    </div>

@endsection
