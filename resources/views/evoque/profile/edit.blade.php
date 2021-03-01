@extends('layout.index')

@section('title')
    Редактирование профиля | @lang('general.vtc_evoque')
@endsection

@section('assets')
    <link rel="stylesheet" type="text/css" href="/css/jquery.datetimepicker.min.css">
    <script src="/js/jquery.datetimepicker.full.min.js"></script>
@endsection

@section('content')

    <div class="container pt-5 pb-5">
        @include('layout.alert')
        <h2 class="mt-3 text-primary">Редактирование профиля</h2>
        <form method="post">
            @csrf
            <div class="row">
                <div class="col-12 col-sm-auto text-center text-sm-left">
                    <label>Аватар</label>
                    <img src="{{ $user->image }}" alt="{{ $user->name }}" class="text-shadow-m d-block m-auto">
                    <a href="{{ route('evoque.profile.updateAvatar') }}" class="btn btn-sm btn-outline-warning my-1"><i class="fab fa-steam"></i> Загрузить Steam аватар</a>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label for="name">Имя и Фамилия</label>
                        <input type="text" class="form-control" name="name" id="name" value="{{ $user->name }}" required>
                        @if($errors->has('name'))
                            <small class="form-text">{{ $errors->first('name') }}</small>
                        @endif
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label for="city">Город</label>
                            <input type="text" class="form-control" name="city" id="city" value="{{ $user->city }}" required>
                            @if($errors->has('city'))
                                <small class="form-text">{{ $errors->first('city') }}</small>
                            @endif
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="country">Страна</label>
                            <input type="text" class="form-control" name="country" id="country" value="{{ $user->country }}" required>
                            @if($errors->has('country'))
                                <small class="form-text">{{ $errors->first('country') }}</small>
                            @endif
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="birth_date">Дата рождения</label>
                        <input type="text" class="form-control" name="birth_date" id="birth_date"
                               value="{{ $user->birth_date ? $user->birth_date->format('d.m.Y') : '' }}" autocomplete="off"
                               placeholder="Укажите в формате дд.мм.гггг или выберите в календаре" required>
                        @if($errors->has('birth_date'))
                            <small class="form-text">{{ $errors->first('birth_date') }}</small>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="email">E-Mail</label>
                        <input type="email" class="form-control" name="email" id="email" value="{{ $user->email }}">
                        @if($errors->has('email'))
                            <small class="form-text">{{ $errors->first('email') }}</small>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="vk">Ссылка ВК</label>
                        <input type="url" class="form-control" name="vk" id="vk_link" value="{{ $user->vk }}">
                        @if($errors->has('vk'))
                            <small class="form-text">{{ $errors->first('vk') }}</small>
                        @endif
                    </div>
                    <div class="form-group row">
                        @if($user->discord_id)
                            <label for="discord" class="col-12">Discord</label>
                            <div class="col">
                                <input type="text" class="form-control" id="discord" value="{{ $user->discord_name }}" disabled>
                            </div>
                            <div class="col-auto">
                                <a id="remove-discord" class="btn btn-outline-warning"><i class="fas fa-times"></i></a>
                            </div>
                        @else
                            <div class="col-auto">
                                <a href="{{ route('auth.discord') }}" class="btn btn-outline-info"><i class="fab fa-discord"></i> Прикрепить аккаунт Discord</a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            @if($user->member)
                <div class="row">
                    <div class="col-auto pr-0" style="width: 220px;">
                        <label for="plate" class="d-block">Превью</label>
                        <img src="/images/plates/{{ $user->member->plate ?? 'empty' }}.png" id="plate-img" style="height: 38px;">
                    </div>
                    <div class="col" style="min-width: 200px;">
                        <div class="form-group">
                            <label for="plate">Номерной знак</label>
                            <div class="row m-0">
                                <input type="text" class="form-control col" name="plate" id="plate" value="{{ $user->member->plate }}"
                                       maxlength="3" placeholder="Три цифры" data-token="{{ csrf_token() }}" @if(!$user->member->isTrainee()) required @endif>
                                <button class="btn btn-outline-warning ml-1" type="button" id="check-plate-btn">Проверить</button>
                            </div>
                            <small class="form-text" id="plate-text">@if($errors->has('plate')){{ $errors->first('plate') }}@endif</small>
                        </div>
                    </div>
                </div>
            @endif
            <div class="row text-center justify-content-center">
                <button type="submit" class="btn btn-outline-warning btn-lg">Сохранить</button>
            </div>
        </form>
    </div>

    <script>
        const picker = new Litepicker({
            element: document.getElementById('birth_date'),
            plugins: ['mobilefriendly'],
            lang: 'ru-RU',
            format: 'DD.MM.YYYY'
        });
    </script>

@endsection
