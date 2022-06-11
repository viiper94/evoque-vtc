@extends('layout.index')

@section('title')
    @if($tuning->id) Редактировать @else Добавить @endif официальный тюнинг | @lang('general.vtc_evoque')
@endsection

@section('content')

    <div class="container pt-5">
        @include('layout.alert')
        <h2 class="mt-3 text-primary text-center">@if($tuning->id) Редактировать @else Добавить @endif официальный тюнинг</h2>
        @if(!$tuning->id)
            <div class="row justify-content-center">
                @if($tuning->type === 'truck')
                    <a class="btn btn-outline-info btn-sm" href="{{ route('evoque.tuning.add', 'trailer') }}">Добавить для прицепа</a>
                @else
                    <a class="btn btn-outline-primary btn-sm" href="{{ route('evoque.tuning.add', 'truck') }}">Добавить для тягача</a>
                @endif
            </div>
        @endif
        <form method="post" class="mb-5" enctype="multipart/form-data">
            @csrf
            <div class="custom-control custom-radio">
                <input type="radio" id="game-ets2" name="game" class="custom-control-input" value="ets2"
                       @checked($tuning->game === 'ets2' || old('game') === 'ets2') required>
                <label class="custom-control-label" for="game-ets2">@lang('general.ets2')</label>
            </div>
            <div class="custom-control custom-radio">
                <input type="radio" id="game-ats" name="game" class="custom-control-input" value="ats"
                       @checked($tuning->game === 'ats' || old('game') === 'ats') required>
                <label class="custom-control-label" for="game-ats">@lang('general.ats')</label>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="vendor">Марка</label>
                        <input type="text" class="form-control" id="vendor" name="vendor" value="{{ old('vendor') ?? $tuning->vendor }}"
                        @if($tuning->type === 'truck') required
                        @else placeholder="Оставить пустым если прицеп не из ДЛС"
                        @endif>
                        @error('vendor')
                            <small class="form-text">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="model">@if($tuning->type === 'truck')Модель@elseТип@endif</label>
                        <input type="text" class="form-control" id="model" name="model" value="{{ old('model') ?? $tuning->model }}" required>
                        @error('model')
                            <small class="form-text">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="description">Описание</label>
                        <textarea class="form-control" id="description" rows="2" name="description"
                                  placeholder="Не обязательно">{{ old('description') ?? $tuning->description }}</textarea>
                        @error('description')
                            <small class="form-text">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="custom-control custom-checkbox mb-2">
                        <input type="checkbox" class="custom-control-input" id="visible" name="visible" @checked($tuning->visible || old('visible') === 'on')>
                        <label class="custom-control-label" for="visible">@lang('attributes.visible')</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group tuning_image">
                        <label for="file">Загрузите изображение тюнинга</label>
                        <div class="custom-file custom-file-dark mb-3">
                            <input type="file" class="custom-file-input uploader" id="image" name="image" accept="image/*" @if(!$tuning->id) required @endif>
                            <label class="custom-file-label" for="image">Выберите изображение</label>
                            <small class="text-primary"><b>Макс. размер файла:</b> 3 Мб</small>
                        </div>
                        <img src="/images/tuning/{{ $tuning->image ?? "image-placeholder.jpg" }}" class="w-100" id="image-preview">
                        @error('image')
                            <small class="form-text">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
            </div>
            <input type="hidden" name="type" value="{{ $tuning->type }}">
            <button type="submit" class="btn btn-outline-warning"><i class="fas fa-check"></i> Сохранить</button>
        </form>
    </div>

@endsection
