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
    <section class="apply-requirements row pt-3 pb-5">
        <h1 class="col-12 mb-5 text-center">Требования к сотрудникам</h1>
        <div class="col mr-5 with-img"></div>
        <div class="col ml-md-5">
            <ul>
                <li class="row mt-5 ml-1 ml-md-0">
                    <div class="icon-wrapper">
                        <i class="fas fa-user"></i>
                    </div>
                    <p>Ваш возраст должен <br> быть <b>не менее 17 лет</b></p>
                </li>
                <li class="row mt-5 ml-1 ml-md-0">
                    <div class="icon-wrapper">
                        <i class="fab fa-steam"></i>
                    </div>
                    <p>Ваш профиль в Steam <br> <b>не должен</b> быть скрытым<br>
                        <a href="{{ route('kb.view', 15) }}" target="_blank" class="btn btn-outline-warning btn-sm">Как открыть?</a></p>
                </li>
                <li class="row mt-5 ml-1 ml-md-0" style="flex-wrap: nowrap">
                    <div class="icon-wrapper">
                        <i class="fas fa-ban"></i>
                    </div>
                    <p>Ваша история банов на сайте TruckersMP должна быть открыта<br>
                        <a href="{{ route('kb.view', 16) }}" target="_blank" class="btn btn-outline-warning btn-sm">Как открыть?</a></p>
                </li>
                <li class="row mt-5 ml-1 ml-md-0">
                    <div class="icon-wrapper">
                        <i class="fab fa-vk"></i>
                    </div>
                    <p>Ваши личные сообщения ВК должны быть открытыми</p>
                </li>
                <li class="row mt-5 ml-1 ml-md-0">
                    <div class="icon-wrapper">
                        <i class="fas fa-headset"></i>
                    </div>
                    <p>Иметь <b>исправный микрофон</b>, быть готовым к голосовому общению в Discord</p>
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
                    <a tabindex="0" data-toggle="tooltip" data-title="Нужно зайти в ВК и перейти на вкладку Моя страница.
                        Затем скопировать ссылку из адресной строки." class="text-light">
                        <i class="fas fa-question-circle"></i>
                    </a>
                    <input type="url" class="form-control" id="vk_link" name="vk_link" value="{{ old('vk_link') }}" placeholder="https://vk.com/username" required>
                    @if($errors->has('vk_link'))
                        <small class="form-text">{{ $errors->first('vk_link') }}</small>
                    @endif
                </div>
                <div class="form-group col-md-6 col-sm-12">
                    <label for="tmp_link">Ссылка на Ваш аккаунт TruckersMP*</label>
                    <a tabindex="0" data-toggle="tooltip" data-title="Нужно залогиниться на сайте TruckersMP, сверху справа найти и
                        навести курсор мыши на свой ник. Из выпадающего меню перейти в раздел Profile. Затем скопировать ссылку из адресной строки." class="text-light">
                        <i class="fas fa-question-circle"></i>
                    </a>
                    <input type="url" class="form-control" id="tmp_link" name="tmp_link" value="{{ old('tmp_link') }}" placeholder="https://truckersmp.com/user/11122233" required>
                    @if($errors->has('tmp_link'))
                        <small class="form-text">{{ $errors->first('tmp_link') }}</small>
                    @endif
                </div>
                <div class="form-group col-md-6 col-sm-12">
                    <label for="discord_name">
                        Ваше имя в Дискорд
                    </label>
                    <a tabindex="0" data-toggle="tooltip" data-title="Найти своё имя можно в
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
            <div class="form-group">
                <label for="referral">Откуда Вы узнали о нас?</label>
                <textarea class="form-control" id="referral" rows="5" name="referral">{{ old('referral') }}</textarea>
                @if($errors->has('referral'))
                    <small class="form-text">{{ $errors->first('referral') }}</small>
                @endif
            </div>

            <div class="rules text-muted p-3">
                <ul>
                    <li>В вашей коллекции в Steam должны быть <a href="https://store.steampowered.com/bundle/5555/Euro_Truck_Simulator_2_Map_Booster/" target="_blank" class="text-primary">основные картовые DLC</a>
                        к ETS2. Отсутствие вышеупомянутых DLC не будет поводом в отказе при приеме, но большая часть конвоев в ВТК проходит именно по дополнениям!</li>
                    <li>Большинство конвоев начинаются в 19:00 по МСК, вы должны быть уверены что сможете посещать наши конвои в своем часовом поясе!</li>
                </ul>
            </div>

            <div class="custom-control custom-checkbox mb-2">
                <input type="checkbox" class="custom-control-input" id="rules_agreed" name="rules_agreed">
                <label class="custom-control-label" for="rules_agreed">
                    <a href="https://truckersmp.com/kb/744" target="_blank" class="text-primary">Правила мультиплеера</a>
                    и <a href="{{ route('rules') }}" target="_blank" class="text-primary">основные правила ВТК</a> обязуюсь соблюдать!
                </label>
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

@endsection
