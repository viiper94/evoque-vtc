@extends('layout.index')

@section('title')
    EVOQUE - Виртуальная Транспортная Компания в TruckersMP
@endsection

@section('meta')
    <meta name="description" content="ВТК EVOQUE - Опытная, динамично развивающаяся виртуальная транспортная компания,
        которая занимается грузоперевозками в мире TruckersMP, проводит регулярные открытые конвои по мультиплееру ETS2 и ATS.">
    <meta name="keywords" content="втк, конвой, открытые конвои, открытый конвой, совместные поездки, покатушки,
        перевозки, грузоперевозки, виртуальная транспортная компания, truckersmp, truckers mp, ets2mp, atsmp, ets2 mp,
        euro truck simulator 2, american truck simulator, ets2, ats, multiplayer, мультиплеер, симулятор дальнобойщика,
        вступить в втк, втупить в компанию">
@endsection

@section('content')

<div id="carousel" class="carousel slide text-shadow-m" data-ride="carousel">
    <ol class="carousel-indicators">
        <li data-target="#carousel" data-slide-to="0" class="active"></li>
        <li data-target="#carousel" data-slide-to="1"></li>
        <li data-target="#carousel" data-slide-to="2"></li>
        <li data-target="#carousel" data-slide-to="3"></li>
        <li data-target="#carousel" data-slide-to="4"></li>
        <li data-target="#carousel" data-slide-to="5"></li>
        <li data-target="#carousel" data-slide-to="6"></li>
        @if($public_convoy)
            <li data-target="#carousel" data-slide-to="7"></li>
        @endif
    </ol>
    <div class="carousel-inner">
        @if($public_convoy)
            <div class="carousel-item convoy-slide active">
                <img src="/images/convoys/{{ $public_convoy->route[0] }}" class="d-block w-100">
                <div class="carousel-caption center-center">
                    <h1 class="display-4 font-weight-bold">Приглашаем тебя<br> на наш конвой!</h1>
                    <p>Мы проводим открытые мероприятия по вторникам</p>
                    <a class="btn btn-outline-primary btn-lg" href="{{ route('convoy.public') }}">Регламент</a>
                </div>
            </div>
        @endif
        <div class="carousel-item @if(!$public_convoy) active @endif">
            <img src="/assets/img/carousel/0.jpg" class="d-block">
            <div class="carousel-caption text-shadow">
                <h1 class="display-3">Мы - EVOQUE</h1>
                <p>Опытная, динамично развивающаяся виртуальная транспортная компания,<br>которая занимается грузоперевозками в мире TruckersMP.</p>
            </div>
        </div>
        <div class="carousel-item">
            <img src="/assets/img/carousel/1.jpg" class="d-block">
            <div class="carousel-caption text-shadow">
                <h1 class="display-3">Мы - EVOQUE</h1>
                <p>Опытная, динамично развивающаяся виртуальная транспортная компания,<br>которая занимается грузоперевозками в мире TruckersMP.</p>
            </div>
        </div>
        <div class="carousel-item">
            <img src="/assets/img/carousel/2.jpg" class="d-block">
            <div class="carousel-caption text-shadow">
                <h1 class="display-3">Мы - EVOQUE</h1>
                <p>Опытная, динамично развивающаяся виртуальная транспортная компания,<br>которая занимается грузоперевозками в мире TruckersMP.</p>
            </div>
        </div>
        <div class="carousel-item">
            <img src="/assets/img/carousel/3.jpg" class="d-block">
            <div class="carousel-caption top-center text-shadow">
                <h1 class="display-3">Мы - EVOQUE</h1>
                <p>Опытная, динамично развивающаяся виртуальная транспортная компания,<br>которая занимается грузоперевозками в мире TruckersMP.</p>
            </div>
        </div>
        <div class="carousel-item">
            <img src="/assets/img/carousel/4.jpg" class="d-block">
            <div class="carousel-caption top-center text-shadow">
                <h1 class="display-3">Мы - EVOQUE</h1>
                <p>Опытная, динамично развивающаяся виртуальная транспортная компания,<br>которая занимается грузоперевозками в мире TruckersMP.</p>
            </div>
        </div>
        <div class="carousel-item">
            <img src="/assets/img/carousel/5.jpg" class="d-block">
            <div class="carousel-caption top-center text-shadow">
                <h1 class="display-3">Мы - EVOQUE</h1>
                <p>Опытная, динамично развивающаяся виртуальная транспортная компания,<br>которая занимается грузоперевозками в мире TruckersMP.</p>
            </div>
        </div>
        <div class="carousel-item">
            <img src="/assets/img/carousel/6.png" class="d-block">
            <div class="carousel-caption top-center text-shadow">
                <h1 class="display-3">Мы - EVOQUE</h1>
                <p>Опытная, динамично развивающаяся виртуальная транспортная компания,<br>которая занимается грузоперевозками в мире TruckersMP.</p>
            </div>
        </div>
    </div>
    <a class="carousel-control-prev text-shadow" href="#carousel" role="button" data-slide="prev">
        <i class="fas fa-chevron-left"></i>
        <span class="sr-only">Вперед</span>
    </a>
    <a class="carousel-control-next text-shadow" href="#carousel" role="button" data-slide="next">
        <i class="fas fa-chevron-right"></i>
        <span class="sr-only">Назад</span>
    </a>
</div>
<div class="container">
    <section class="features text-center row pt-5 pb-5 mt-5 mb-5">
        @php $diff = \Carbon\Carbon::create(2016, 11)->diffInYears(\Carbon\Carbon::now()) @endphp
        <div class="feature col-md-4 col-sm-12">
            <h1 class="display-2 font-weight-bold">{{ $diff }}</h1>
            <hr class="m-auto pb-3">
            <p> {{ trans_choice('год|года|лет', $diff) }} на дорогах</p>
        </div>
        <div class="feature col-md-4 col-sm-12">
            <div class="row justify-content-center">
                <p class="col align-self-end col-auto pl-0">до </p>
                <h1 class="col display-2 col-auto pr-0 pl-0 font-weight-bold">3</h1>
                <p class="col align-self-end col-auto pl-0">-ёх</p>
            </div>
            <hr class="m-auto pb-3">
            <p>конвоев ежедневно</p>
        </div>
        <div class="feature col-md-4 col-sm-12">
            <h1 class="display-2 font-weight-bold">{{ $members_count }}</h1>
            <hr class="m-auto pb-3">
            <p>{{ trans_choice('опытный водитель|опытных водителя|опытных водителей', $members_count) }}</p>
        </div>
    </section>

    <section class="features text-center row pt-5 pb-5 mt-5 mb-5">
        <div class="feature col-md-4 col-sm-12">
            <h1 class="display-4"><i class="fas fa-money-bill-alt"></i></h1>
            <hr class="m-auto pb-3">
            <p>Своя игровая валюта - <strong>Эвик</strong>.<br>
                Проводя конвои можно заработать <br>
                на любимую игру или DLC</p>
        </div>
        <div class="feature col-md-4 col-sm-12">
            <h1 class="display-4"><i class="fas fa-chart-line"></i></h1>
            <hr class="m-auto pb-3">
            <p>Система Рейтинговых перевозок.<br>
                Даже вне конвоя можно с пользой<br>
                провести время играя в ETS2 или ATS</p>
        </div>
        <div class="feature col-md-4 col-sm-12">
            <h1 class="display-4"><i class="fas fa-cart-plus"></i></h1>
            <hr class="m-auto pb-3">
            <p>Различные розыгрыши игр и DLC.</p>
        </div>
    </section>

    <section class="apply mt-5 mb-5 pt-5 pb-5">
        <div class="apply-wrapper text-center">
            <h1 class="mb-5 ">Заинтересован? Присоединяйся!</h1>
            <a class="btn btn-outline-warning btn-lg" href="{{ route('apply') }}"><i class="fas fa-user-plus"></i> Вступить</a>
        </div>
    </section>
</div>

@endsection
