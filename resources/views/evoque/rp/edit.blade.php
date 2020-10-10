@extends('layout.index')

@section('title')
    Отчёт рейтинговой перевозки | @lang('general.vtc_evoque')
@endsection

@section('content')

    <div class="container rp-report pt-5 pb-5">
        @include('layout.alert')
        <form method="post" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <div class="row justify-content-center mt-3">
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="game-ets2" name="game" class="custom-control-input" value="ets2" required>
                        <label class="custom-control-label" for="game-ets2">Euro Truck Simulator 2</label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="game-ats" name="game" class="custom-control-input" value="ats" required>
                        <label class="custom-control-label" for="game-ats">American Truck Simulator</label>
                    </div>
                </div>
                <h4>Скриншоты</h4>
                <div class="custom-file custom-file-dark mt-3 mb-3">
                    <input type="file" class="custom-file-input" id="start-screen" name="start-screen" required>
                    <label class="custom-file-label" for="start-screen">Скрин со старта*</label>
                </div>
                <div class="custom-file custom-file-dark mt-3 mb-3">
                    <input type="file" class="custom-file-input" id="finish-screen" name="finish-screen" required>
                    <label class="custom-file-label" for="finish-screen">Скрин с финиша*</label>
                </div>
                <div class="custom-file custom-file-dark mt-3 mb-3">
                    <input type="file" class="custom-file-input" id="new-id-screen" name="new-id-screen">
                    <label class="custom-file-label" for="new-id-screen">Скрин с новым ID</label>
                </div>
            </div>
            <div class="form-group">
                <label for="referral">Дополнительная информация</label>
                <textarea class="form-control" id="note" rows="3" name="note">{{ old('note') }}</textarea>
                @if($errors->has('note'))
                    <small class="form-text">{{ $errors->first('note') }}</small>
                @endif
            </div>
            <div class="row justify-content-center">
                <button type="submit" class="btn btn-outline-warning btn-lg"><i class="fas fa-fire-alt"></i> Огонь!</button>
            </div>
        </form>
        <div class="row">
            <section class="convoy-note pb-5 pt-5 m-auto">
                <hr class="m-auto">
                <blockquote class="blockquote text-center mb-5 mt-5">
                    <h2 class="mb-0 text-primary">Правила рейтинговых перевозок:</h2>
                    <ol class="text-left">
                        <li>Грузы разрешено брать <b>только в онлайне</b>*</li>
                        <li>Перед тем как взять груз делаете скрин: открытый TAB + маршрут (строго на старте)**</li>
                        <li>При сдаче груза: делаете скриншот: TAB + полный отчет о грузе***</li>
                        <li>Сданные грузы с повреждением более <b>10%</b> учитываться не будут.</li>
                        <li>Сданные грузы с маршрутом превышающим на обоих скринах <b>+/- 100 км</b> не учитываются.</li>
                        <li>Грузы протяженностью <b>менее 500</b> километров не будут засчитываться.</li>
                        <li>Выложенные грузы с Уровнем в игре ниже, чем указано в таблице засчитаны не будут.</li>
                        <li>Лидером недели становится тот, кто в сумме (отвезенный километраж + бонус)<br>
                            отвезет больше всех километров</li>
                        <li>За перевозки тяжелых грузов <b>на серверах симуляции</b> будет <br>
                            начисляться дополнительный бонус в виде дополнительных километров:</li>
                    </ol>
                    <ul class="text-left">
                        <li>груз от 0 т и до 14 т - коэф 0.0</li>
                        <li>груз от 15 т и до 19 т - коэф 0.1</li>
                        <li>груз от 20 т и до 25 т - коэф 0.3</li>
                        <li>груз от 26 т и до 32 т - коэф 0.5</li>
                        <li>груз от 33 т и до 61 т - коэф 0.7</li>
                    </ul>
                    <ul class="text-left mt-5">
                        <li>Грузы с которыми Вы приезжаете на конвой - не учитываются</li>
                        <li>Увеличивать маршрут с помощью точек - запрещено</li>
                        <li>Ваш ID на обоих скрин TAB должен совпадать,<br>
                            либо должен присутствовать 1 дополнительный скрин,<br>
                            информирующий о новом ID: скрин при входе в игру с открытым чатом и TAB.</li>
                    </ul>
                </blockquote>
            </section>
        </div>
    </div>

@endsection
