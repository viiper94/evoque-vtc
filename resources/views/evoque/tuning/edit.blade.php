@extends('layout.index')

@section('title')
    @if($tuning->id) Редактировать @else Добавить @endif официальный тюнинг | @lang('general.vtc_evoque')
@endsection

@section('content')

    <div class="container pt-5">
        @include('layout.alert')
        <h2 class="mt-3 text-primary text-center">@if($tuning->id) Редактировать @else Добавить @endif официальный тюнинг</h2>
        <ul class="nav nav-pills text-center justify-content-center" role="tablist">
            <li class="nav-item">
                <a class="nav-link @if($tuning->id && $type === ['truck'] || !$tuning->id) active @else disabled @endif" id="truck-tab" data-toggle="tab"
                   href="#truck" role="tab" aria-controls="truck" aria-selected="true">Для тягача</a>
            </li>
            <li class="nav-item">
                <a class="nav-link @if($tuning->id && $type === ['trailer']) active @elseif($tuning->id) disabled @endif" id="trailer-tab" data-toggle="tab"
                   href="#trailer" role="tab" aria-controls="trailer" aria-selected="false">Для прицепа</a>
            </li>
        </ul>
        <div class="tab-content">
            @foreach($type as $item)
                <div class="tab-pane fade show {{ $loop->first || old('type') === $item ? 'active' : '' }}" id="{{ $item }}" role="tabpanel" aria-labelledby="{{ $item }}-tab">
                    <form method="post" class="mb-5" enctype="multipart/form-data">
                        @csrf
                        <div class="custom-control custom-radio">
                            <input type="radio" id="{{ $item }}-game-ets2" name="game" class="custom-control-input" value="ets2"
                                   @if(old('type') === $item && ($tuning->game === 'ets2' || old('game') === 'ets2')) checked @endif required>
                            <label class="custom-control-label" for="{{ $item }}-game-ets2">@lang('general.ets2')</label>
                        </div>
                        <div class="custom-control custom-radio">
                            <input type="radio" id="{{ $item }}-game-ats" name="game" class="custom-control-input" value="ats"
                                   @if(old('type') === $item && ($tuning->game === 'ats' || old('game') === 'ats')) checked @endif required>
                            <label class="custom-control-label" for="{{ $item }}-game-ats">@lang('general.ats')</label>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="{{ $item }}-vendor">Марка</label>
                                    <input type="text" class="form-control" id="{{ $item }}-vendor" name="vendor" value="{{ old('type') === $item ? (old('vendor') ?? $tuning->vendor) : '' }}"
                                    @if($item === 'truck') required
                                    @else placeholder="Оставить пустым если прицеп не из ДЛС"
                                    @endif>
                                    @if(old('type') === $item && $errors->has('vendor'))
                                        <small class="form-text">{{ $errors->first('vendor') }}</small>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="{{ $item }}-model">@if($item === 'truck') Модель @else Тип @endif</label>
                                    <input type="text" class="form-control" id="{{ $item }}-model" name="model" value="{{ old('type') === $item ? (old('model') ?? $tuning->model) : '' }}" required>
                                    @if(old('type') === $item && $errors->has('model'))
                                        <small class="form-text">{{ $errors->first('model') }}</small>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="{{ $item }}-description">Описание</label>
                                    <textarea class="form-control" id="{{ $item }}-description" rows="2" name="description"
                                              placeholder="Не обязательно">{{ old('type') === $item ? (old('description') ?? $tuning->description) : '' }}</textarea>
                                    @if(old('type') === $item && $errors->has('description'))
                                        <small class="form-text">{{ $errors->first('description') }}</small>
                                    @endif
                                </div>
                                <div class="custom-control custom-checkbox mb-2">
                                    <input type="checkbox" class="custom-control-input" id="{{ $item }}-visible" name="visible" @if(old('type') !== $item && ($tuning->visible || old('visible') === 'on')) checked @endif>
                                    <label class="custom-control-label" for="{{ $item }}-visible">@lang('attributes.visible')</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group tuning_image">
                                    <label for="{{ $item }}-file">Загрузите изображение тюнинга</label>
                                    <div class="custom-file custom-file-dark mb-3">
                                        <input type="file" class="custom-file-input uploader" id="{{ $item }}-image" name="{{ $item }}-image" accept="image/*" required>
                                        <label class="custom-file-label" for="image">Выберите изображение</label>
                                        <small class="text-primary"><b>Макс. размер файла:</b> 3 Мб</small>
                                    </div>
                                    <img src="/images/tuning/{{ $tuning->image ?? "image-placeholder.jpg" }}" class="w-100" id="{{ $item }}-image-preview">
                                    @if(old('type') === $item && $errors->has('image'))
                                        <small class="form-text">{{ $errors->first('image') }}</small>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="type" value="{{ $item }}">
                        <button type="submit" class="btn btn-outline-warning"><i class="fas fa-check"></i> Сохранить</button>
                    </form>
                </div>
            @endforeach
        </div>

    </div>

@endsection
