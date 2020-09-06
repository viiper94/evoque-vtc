@extends('layout.index')

@section('content')

<div class="container mt-5">
    <section class="apply-requirements row pt-5 pb-5">
        <div class="col mr-5 with-img"></div>
        <div class="col ml-5">
            <h1>Требования</h1>
            <ul>
                <li class="row mt-5">
                    <div class="icon-wrapper">
                        <i class="fas fa-address-book"></i>
                    </div>
                    <p>Ваш возраст должен быть не менее 17 лет</p>
                </li>
                <li class="row mt-5">
                    <div class="icon-wrapper">
                        <i class="fas fa-layer-group"></i>
                    </div>
                    <p>Вы не должны состоять в других ВТК</p>
                </li>
                <li class="row mt-5" style="flex-wrap: nowrap">
                    <div class="icon-wrapper">
                        <i class="fab fa-steam-symbol"></i>
                    </div>
                    <p>Ваш профиль в Steam не должен быть скрытым<br>
                        <button data-toggle="modal" data-target="#steam-privacy-modal" class="btn btn-outline-warning btn-sm">Как открыть?</button></p>
                </li>
                <li class="row mt-5" style="flex-wrap: nowrap">
                    <div class="icon-wrapper">
                        <i class="fas fa-truck-pickup"></i>
                    </div>
                    <p>Ваша история банов на сайте TruckersMP должна быть открыта<br>
                        <a data-toggle="modal" data-target="#ban-history-modal" class="btn btn-outline-warning btn-sm">Как открыть?</a></p>
                </li>
                <li class="row mt-5">
                    <div class="icon-wrapper">
                        <i class="fas fa-ban"></i>
                    </div>
                    <p>Не более 2-х наказаний в истории банов на сайте TruckersMP</p>
                </li>
            </ul>
        </div>
    </section>

    <section class="apply-call-to-action row flex-column m-auto justify-content-center text-center pt-5 pb-5">
        <h1>Подходишь по требованиям?<br> Заполняй анкету!</h1>
        <a href="#apply-form" class="mt-4 text-shadow-m align-self-center" id="apply-go-to-form"><i class="fas fa-arrow-circle-down"></i></a>

    </section>

    <section id="apply-form" class="pt-5 mb-5">
        <form action="#" method="post">
            <div class="row">
                <div class="form-group col-md-6 col-sm-12">
                    <label for="name">Имя и фамилия</label>
                    <input type="text" class="form-control" id="name" required>
                </div>
                <div class="form-group col-md-6 col-sm-12">
                    <label for="nickname">Игровой ник</label>
                    <input type="text" class="form-control" id="nickname" required>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-6 col-sm-12">
                    <label for="age">Ваш возраст</label>
                    <input type="number" class="form-control" id="age" min="10" required>
                </div>
                <div class="form-group col-md-6 col-sm-12">
                    <label for="hours_played">Сколько сыграно часов в ETS2 в Steam</label>
                    <input type="number" class="form-control" id="hours_played" min="0" required>
                </div>
            </div>
            <div class="form-group">
                <label for="vk_link">Ссылка на Ваш профиль ВКонтакте</label>
                <input type="url" class="form-control" id="vk_link" required>
            </div>
            <div class="form-group">
                <label for="steam_link">Аккаунт Steam</label>
                <input type="url" class="form-control" id="steam_link" required>
            </div>
            <div class="form-group">
                <label for="tmp_link">Аккаунт на TruckersMP</label>
                <input type="url" class="form-control" id="tmp_link" required>
            </div>
            <div class="custom-control custom-checkbox mb-2">
                <input type="checkbox" class="custom-control-input" id="have_mic">
                <label class="custom-control-label" for="have_mic">Микрофон</label>
            </div>
            <div class="custom-control custom-checkbox mb-2">
                <input type="checkbox" class="custom-control-input" id="have_ts3">
                <label class="custom-control-label" for="have_ts3">TeamSpeak 3</label>
            </div>
            <div class="custom-control custom-checkbox mb-2">
                <input type="checkbox" class="custom-control-input" id="have_ats">
                <label class="custom-control-label" for="have_ats">Наличие American Truck Simulator</label>
            </div>
            <div class="form-group">
                <label for="referral">Откуда Вы узнали о нас?</label>
                <textarea class="form-control" id="referral" rows="5"></textarea>
            </div>
            <div class="custom-control custom-checkbox mb-2">
                <input type="checkbox" class="custom-control-input" id="rules_agreed">
                <label class="custom-control-label" for="rules_agreed">Правила мультиплеера и основные правила ВТК обязуюсь соблюдать!</label>
            </div>
            <div class="custom-control custom-checkbox mb-2">
                <input type="checkbox" class="custom-control-input" id="requirements_agreed">
                <label class="custom-control-label" for="requirements_agreed">Сменить окраску грузовика на официальную, поставить префикс в Steam и поменять номерной знак на сайте World of Trucks по требованию компании готов.</label>
            </div>
            <div class="row justify-content-center">
                <button type="submit" class="btn btn-outline-warning btn-lg">Отправить</button>
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
                            <img src="/assets/img/modals/steam-privacy/2.jpg"></p>
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
<div class="modal fade" id="steam-privacy-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-xl">
        <div class="modal-content modal-content-dark">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Как открыть историю банов в TruckersMP?</h5>
                <button type="button" class="close text-shadow" data-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <ol class="pl-4">
                    <li>
                        <p>Зайдите в настройки своего профиля TruckersMP. <br>
                            <a href="https://truckersmp.com/profile/settings" target="_blank">https://truckersmp.com/profile/settings</a></p>
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
                            <img src="/assets/img/modals/steam-privacy/2.jpg"></p>
                    </li>
                    <li>
                        <p>Готово! Данные автоматически сохранятся.</p>
                    </li>
                </ol>
            </div>
        </div>
    </div>
</div>

@endsection
