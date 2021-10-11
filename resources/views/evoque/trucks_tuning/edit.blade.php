@extends('layout.index')

@section('title')
    Новый тюнинг | @lang('general.vtc_evoque')
@endsection

@section('content')

    <div class="container pt-5">
        @include('layout.alert')
        <h2 class="mt-3 text-primary">Новый официальный тюнинг</h2>
        <form method="post" class="mb-5" enctype="multipart/form-data">
            @csrf
            <div class="custom-control custom-radio">
                <input type="radio" id="game-ets2" name="game" class="custom-control-input" value="ets2" @if($tuning->game === 'ets2' || old('game') === 'ets2') checked @endif required>
                <label class="custom-control-label" for="game-ets2">Euro Truck Simulator 2</label>
            </div>
            <div class="custom-control custom-radio">
                <input type="radio" id="game-ats" name="game" class="custom-control-input" value="ats" @if($tuning->game === 'ats' || old('game') === 'ats') checked @endif required>
                <label class="custom-control-label" for="game-ats">American Truck Simulator</label>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="vendor">Марка</label>
                        <input type="text" class="form-control" id="vendor" name="vendor" value="{{ old('vendor') ?? $tuning->vendor }}" required>
                        @error('vendor')
                            <small class="form-text">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="model">Модель</label>
                        <input type="text" class="form-control" id="model" name="model" value="{{ old('model') ?? $tuning->model }}" required>
                        @error('model')
                            <small class="form-text">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="description">Описание</label>
                        <textarea class="form-control" id="description" rows="3" name="description" placeholder="Не обязательно">{{ old('description') ?? $tuning->description }}</textarea>
                        @error('description')
                            <small class="form-text">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="custom-control custom-checkbox mb-2">
                        <input type="checkbox" class="custom-control-input" id="visible" name="visible" @if($tuning->visible || old('visible') === 'on') checked @endif>
                        <label class="custom-control-label" for="visible">@lang('attributes.visible')</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group tuning_image">
                        <label for="">Загрузите изображение тюнинга</label>
                        <div class="custom-file custom-file-dark mb-3">
                            <input type="file" class="custom-file-input uploader" id="image" name="image" accept="image/*">
                            <label class="custom-file-label" for="image">Выберите изображение</label>
                            <small class="text-primary"><b>Макс. размер файла:</b> 5 Мб, 3000x3000px</small>
                        </div>
                        <img src="/images/tuning/{{ $tuning->image ?? "image-placeholder.jpg" }}" class="w-100" id="image-preview">
                        @if($errors->has('image'))
                            <small class="form-text">{{ $errors->first('image') }}</small>
                        @endif
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-outline-warning"><i class="fas fa-check"></i> Сохранить</button>
        </form>
    </div>

@endsection
