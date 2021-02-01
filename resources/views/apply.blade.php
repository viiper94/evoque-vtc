@extends('layout.index')

@section('title')
    Вступить в @lang('general.vtc_evoque')
@endsection

@section('meta')
    <meta name="description" content="ВТК EVOQUE - Опытная, динамично развивающаяся виртуальная транспортная компания,
        которая занимается грузоперевозками в мире TruckersMP, проводит регулярные открытые конвои по мультиплееру ETS2 и ATS.">
    <meta name="keywords" content="втк, конвой, открытые конвои, открытый конвой, совместные поездки, покатушки,
        перевозки, грузоперевозки, виртуальная транспортная компания, truckersmp, truckers mp, ets2mp, atsmp, ets2 mp,
        euro truck simulator 2, american truck simulator, ets2, ats, multiplayer, мультиплеер, симулятор дальнобойщика,
        вступить в втк, втупить в компанию">
    <meta property="og:title" content="Вступить в ВТК EVOQUE">
    <meta property="og:type" content="article">
    <meta property="og:image" content="{{ \Illuminate\Support\Facades\URL::to('/') }}/assets/img/evoque.jpg">
    <meta property="og:image:type" content="image/jpeg">
    <meta property="og:site_name" content="ВТК EVOQUE">
@endsection

@section('content')

<div class="container mt-5">
    @include('layout.alert')
    <section class="apply-requirements row pt-5 pb-5">
        <div class="col mr-5 with-img"></div>
        <div class="col ml-md-5">
            <h1>Требования</h1>
            <ul>
                <li class="row mt-5 ml-1 ml-md-0">
                    <div class="icon-wrapper">
                        <i class="fas fa-address-book"></i>
                    </div>
                    <p>Ваш возраст должен быть не менее 17 лет</p>
                </li>
                <li class="row mt-5 ml-1 ml-md-0" style="flex-wrap: nowrap">
                    <div class="icon-wrapper">
                        <i class="fab fa-steam-symbol"></i>
                    </div>
                    <p>Ваш профиль в Steam не должен быть скрытым<br>
                        <button data-toggle="modal" data-target="#steam-privacy-modal" class="btn btn-outline-warning btn-sm">Как открыть?</button></p>
                </li>
                <li class="row mt-5 ml-1 ml-md-0" style="flex-wrap: nowrap">
                    <div class="icon-wrapper">
                        <i class="fas fa-ban"></i>
                    </div>
                    <p>Ваша история банов на сайте TruckersMP должна быть открыта<br>
                        <a data-toggle="modal" data-target="#ban-history-modal" class="btn btn-outline-warning btn-sm">Как открыть?</a></p>
                </li>
                <li class="row mt-5 ml-1 ml-md-0">
                    <div class="icon-wrapper">
                        <i class="fab fa-vk"></i>
                    </div>
                    <p>Ваши личные сообщения ВК должны быть открытыми</p>
                </li>
                <li class="row mt-5 ml-1 ml-md-0">
                    <div class="icon-wrapper">
                        <i class="fas fa-map"></i>
                    </div>
                    <p>Наличие DLC Going East!, Scandinavia, Vive la France! и Italia<br>
                        <button class="btn btn-outline-warning btn-sm"
                                data-toggle="popover"
                                data-content="Отсутствие вышеупомянутых DLC не будет поводом в отказе при приеме,
                                            но большая часть конвоев в ВТК проходит именно по этим расширениям!"
                                data-trigger="focus">
                            А что если нет?
                        </button>
                    </p>
                </li>
            </ul>
        </div>
    </section>

    <section class="apply-call-to-action row flex-column m-auto justify-content-center text-center pt-5 pb-5">
        <h1>Подходишь по требованиям?<br> Заполняй анкету!</h1>
        <a href="#apply-form" class="mt-4 text-shadow-m align-self-center" id="apply-go-to-form"><i class="fas fa-arrow-circle-down"></i></a>

    </section>

    <section id="apply-form" class="pt-5 mb-5">
        <form action="#" method="post" class="application-form">
            @csrf
            <div class="row">
                <div class="form-group col-md-6 col-sm-12">
                    <label for="name">Имя и фамилия*</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                    @if($errors->has('name'))
                        <small class="form-text">{{ $errors->first('name') }}</small>
                    @endif
                </div>
                <div class="form-group col-md-6 col-sm-12">
                    <label for="age">Ваш возраст*</label>
                    <input type="number" class="form-control" name="age" id="age" min="10" value="{{ old('age') }}" required>
                    @if($errors->has('age'))
                        <small class="form-text">{{ $errors->first('age') }}</small>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-6 col-sm-12">
                    <label for="vk_link">Ссылка на Ваш профиль ВКонтакте*</label>
                    <input type="url" class="form-control" id="vk_link" name="vk_link" value="{{ old('vk_link') }}" required>
                    @if($errors->has('vk_link'))
                        <small class="form-text">{{ $errors->first('vk_link') }}</small>
                    @endif
                </div>
                <div class="form-group col-md-6 col-sm-12">
                    <label for="tmp_link">Ссылка на Ваш аккаунт TruckersMP*</label>
                    <input type="url" class="form-control" id="tmp_link" name="tmp_link" value="{{ old('tmp_link') }}" required>
                    @if($errors->has('tmp_link'))
                        <small class="form-text">{{ $errors->first('tmp_link') }}</small>
                    @endif
                </div>
                <div class="form-group col-md-6 col-sm-12">
                    <label for="discord_name">
                        Ваше имя в Дискорд
                    </label>
                    <a tabindex="0" data-toggle="popover" data-trigger="focus" data-content="Найти своё имя можно в
                        клиенте или веб-версии Дискорда в самом низу слева. Возле вашего аватара будет ваш ник и дискриминатор (4 цифры).
                        Чтобы их скопировать, достаточно нажать на них один раз." class="text-light">
                        <i class="fas fa-question-circle"></i>
                    </a>
                    <input type="text" class="form-control" id="discord_name" name="discord_name" value="{{ old('discord_name') }}" placeholder="Имя#0001">
                    @if($errors->has('discord_name'))
                        <small class="form-text">{{ $errors->first('discord_name') }}</small>
                    @endif
                </div>
            </div>
            <div class="custom-control custom-checkbox mb-4">
                <input type="checkbox" class="custom-control-input" id="have_mic" name="have_mic" @if(old('have_mic')) checked @endif>
                <label class="custom-control-label" for="have_mic">Есть микрофон</label>
            </div>
            <div class="form-group">
                <label for="referral">Откуда Вы узнали о нас?</label>
                <textarea class="form-control" id="referral" rows="5" name="referral">{{ old('referral') }}</textarea>
                @if($errors->has('referral'))
                    <small class="form-text">{{ $errors->first('referral') }}</small>
                @endif
            </div>
            <div class="custom-control custom-checkbox mb-2">
                <input type="checkbox" class="custom-control-input" id="rules_agreed" name="rules_agreed">
                <label class="custom-control-label" for="rules_agreed">Правила мультиплеера и основные правила ВТК обязуюсь соблюдать!
                    @if($errors->has('rules_agreed'))
                        <small class="form-text">{{ $errors->first('rules_agreed') }}</small>
                @endif
            </div>
            <div class="custom-control custom-checkbox mb-2">
                <input type="checkbox" class="custom-control-input" id="requirements_agreed" name="requirements_agreed">
                <label class="custom-control-label" for="requirements_agreed">Сменить окрас грузовика на официальный,
                    поставить префикс в профиле на сайте TruckersMP и поменять номерной знак на сайте World of Trucks по требованию компании готов.</label>
                @if($errors->has('requirements_agreed'))
                    <small class="form-text">{{ $errors->first('requirements_agreed') }}</small>
                @endif
            </div>
            <div class="custom-control custom-checkbox mb-2">
                <input type="checkbox" class="custom-control-input" id="terms_agreed" name="terms_agreed">
                <label class="custom-control-label" for="terms_agreed">С <a href="{{ route('terms') }}" target="_blank" class="text-primary">Правилами использования</a> и
                    <a href="{{ route('privacy') }}" target="_blank" class="text-primary">Политикой конфиденциальности</a> ознакомился и принимаю их!
                    @if($errors->has('terms_agreed'))
                        <small class="form-text">{{ $errors->first('terms_agreed') }}</small>
                @endif
            </div>
            <div class="row justify-content-center">
                <button type="submit" id="submit_btn" class="btn btn-outline-warning btn-lg disabled" disabled>Отправить</button>
            </div>
        </form>
    </section>
</div>

<!-- Steam privacy modal -->
<div class="modal fade" id="steam-privacy-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-xl">
        <div class="modal-content modal-content-dark">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Как открыть профиль в Steam?</h5>
                <button type="button" class="close text-shadow" data-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <ol class="pl-4">
                    <li>
                        <p>Зайдите в свой Steam профиль через браузер или клиент. <br>
                            Зайдите в раздел <a href="https://steamcommunity.com/id/14/edit/settings">редактирвания профиля</a>.</p>
                    </li>
                    <li>
                        <p>Перейдите в раздел <b>Приватность</b>.</p>
                    </li>
                    <li>
                        <p>Установите параметр <b>Мой профиль</b> и <b>Доступ к игровой информации</b> в значение <b>Открытый</b>.</p>
                    </li>
                    <li>
                        <p><b>Снимите</b> голочку с пункта <b>Скрывать общее время в игре, даже если видны другие данные об играх</b>.<br>
                            На скриншоте снизу показан финальный результат.<br>
                            <img src="/assets/img/modals/steam-privacy2.jpg"></p>
                    </li>
                    <li>
                        <p>Готово! Данные автоматически сохранятся.</p>
                    </li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- Ban history modal -->
<div class="modal fade" id="ban-history-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg">
        <div class="modal-content modal-content-dark">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Как открыть историю банов в TruckersMP?</h5>
                <button type="button" class="close text-shadow" data-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <p>По умолчанию список банов на TruckersMP не виден для других пользователей.<br>
                    Чтобы открыть список банов — нужно:</p>
                <ol class="pl-4">
                    <li>
                        <p>Зайдите под своими данными в свой профиль на TruckersMP.</p>
                    </li>
                    <li>
                        <p>Перейдите по пунктам меню <b>Account > Settings</b><br>
                            или по ссылке - <a href="https://truckersmp.com/profile/settings" target="_blank">https://truckersmp.com/profile/settings</a></p>
                    </li>
                    <li>
                        <p>В открывшемся меню настроек, промотайте страницу вниз и<br>
                            установите галочку напротив пункта <b>Display your bans on your profile and API</b>.<br>
                            <img src="/assets/img/modals/ban-history.jpg"></p>
                    </li>
                    <li>
                        <p>Нажмите <b>Save</b> для сохранения настроек профиля.</p>
                    </li>
                    <li>
                        <p>Готово!</p>
                    </li>
                </ol>
            </div>
        </div>
    </div>
</div>

@endsection
