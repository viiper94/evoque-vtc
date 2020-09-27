@extends('evoque.layout.index')

@section('assets')
    <link rel="stylesheet" type="text/css" href="/css/jquery.datetimepicker.min.css">
    <script src="/js/jquery.datetimepicker.full.min.js"></script>
@endsection

@section('content')

    <div class="container pt-5">
        <h2 class="text-primary">
            @if($convoy->title)
                Редактирование конвоя
            @else
                Новый конвой
            @endif
        </h2>
        <form method="post" class="mb-5">
            @csrf
            <div class="custom-control custom-checkbox mb-2">
                <input type="checkbox" class="custom-control-input" id="public" name="public" @if($convoy->public) checked @endif>
                <label class="custom-control-label" for="public">Открытый конвой</label>
            </div>
            <div class="custom-control custom-checkbox mb-2">
                <input type="checkbox" class="custom-control-input" id="visible" name="visible" @if($convoy->visible) checked @endif>
                <label class="custom-control-label" for="visible">Виден на сайте</label>
            </div>
            <div class="form-group">
                <label for="nickname">Название</label>
                <input type="text" class="form-control" id="title" name="title" value="{{ $convoy->title }}" required>
                @if($errors->has('title'))
                    <small class="form-text">{{ $errors->first('title') }}</small>
                @endif
            </div>
            <div class="form-group">
                <label for="server">Сервер</label>
                <select class="form-control" id="server" name="server" required>
                    @foreach($servers as $server)
                        <option value="{{ $server->getName() }}" @if($server->getName() === $convoy->server) selected @endif >
                            [{{ $server->getGame() }}] {{ $server->getName() }} ({{ $server->getPlayers() }}/{{ $server->getMaxPlayers() }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="datetimepicker">Время выезда</label>
                <input type="text" class="form-control" id="datetimepicker" name="start_time" value="{{ $convoy->start_time->format('d.m.Y H:i') }}" autocomplete="off" required>
                @if($errors->has('start_time'))
                    <small class="form-text">{{ $errors->first('start_time') }}</small>
                @endif
            </div>
            <h3 class="text-primary">Маршрут</h3>
            <div class="form-group">
                <label for="route">Изображение маршрута (ссылка)</label>
                <input type="url" class="form-control" id="route" name="route" value="{{ $convoy->route }}" required>
                @if($errors->has('route'))
                    <small class="form-text">{{ $errors->first('route') }}</small>
                @endif
            </div>
            <div class="form-group">
                <label for="start">Старт из</label>
                <input type="text" class="form-control" id="start" name="start" value="{{ $convoy->start }}" required>
                @if($errors->has('start'))
                    <small class="form-text">{{ $errors->first('start') }}</small>
                @endif
            </div>
            <div class="form-group">
                <label for="rest">Перерыв</label>
                <input type="text" class="form-control" id="rest" name="rest" value="{{ $convoy->rest }}" required>
                @if($errors->has('rest'))
                    <small class="form-text">{{ $errors->first('rest') }}</small>
                @endif
            </div>
            <div class="form-group">
                <label for="finish">Финиш</label>
                <input type="text" class="form-control" id="finish" name="finish" value="{{ $convoy->finish }}" required>
                @if($errors->has('finish'))
                    <small class="form-text">{{ $errors->first('finish') }}</small>
                @endif
            </div>
            <h3 class="text-primary">Связь</h3>
            <div class="custom-control custom-radio custom-control-inline">
                <input type="radio" id="communication-ts3" name="communication" class="custom-control-input" value="TeamSpeak 3" @if($convoy->communication == 'TeamSpeak 3' || $convoy->communication == '') checked @endif>
                <label class="custom-control-label" for="communication-ts3">TeamSpeak 3</label>
            </div>
            <div class="custom-control custom-radio custom-control-inline">
                <input type="radio" id="communication-discord" name="communication" class="custom-control-input" value="Discord" @if($convoy->communication == 'Discord') checked @endif>
                <label class="custom-control-label" for="communication-discord">Discord</label>
            </div>
            <div class="form-group">
                <label for="communication_program">Ссылка на сервер</label>
                <input type="text" class="form-control" id="communication_link" name="communication_link" value="{{ $convoy->communication_link }}" required>
                @if($errors->has('communication_link'))
                    <small class="form-text">{{ $errors->first('communication_link') }}</small>
                @endif
            </div>
            <div class="form-group">
                <label for="communication_channel">Канал на сервере</label>
                <input type="text" class="form-control" id="communication_channel" name="communication_channel" value="{{ $convoy->communication_channel }}" required>
                @if($errors->has('communication_channel'))
                    <small class="form-text">{{ $errors->first('communication_channel') }}</small>
                @endif
            </div>
            <div class="form-group">
                <label for="lead">Ведущий</label>
                <select class="form-control" id="lead" name="lead">
                    <option value="0" @if($convoy->lead === '0') selected @endif >На месте разберёмся</option>
                    @foreach($members as $member)
                        <option value="{{ $member->id }}" @if($member->id === $convoy->lead) selected @endif >[EVOQUE] {{ $member->nickname }}</option>
                    @endforeach
                </select>
            </div>
            <h3 class="text-primary">Тягач</h3>
            <div class="form-group">
                <label for="truck_image">Изображение тягача (ссылка)</label>
                <input type="text" class="form-control" id="truck_image" name="truck_image" value="{{ $convoy->truck_image }}">
                @if($errors->has('truck_image'))
                    <small class="form-text">{{ $errors->first('truck_image') }}</small>
                @endif
            </div>
            <div class="form-group">
                <label for="truck">Марка</label>
                <input type="text" class="form-control" id="truck" name="truck" value="{{ $convoy->truck }}">
                @if($errors->has('truck'))
                    <small class="form-text">{{ $errors->first('truck') }}</small>
                @endif
            </div>
            <div class="form-group">
                <label for="truck_tuning">Тюнинг</label>
                <input type="text" class="form-control" id="truck_tuning" name="truck_tuning" value="{{ $convoy->truck_tuning }}">
                @if($errors->has('truck_tuning'))
                    <small class="form-text">{{ $errors->first('truck_tuning') }}</small>
                @endif
            </div>
            <div class="form-group">
                <label for="truck_paint">Окрас</label>
                <input type="text" class="form-control" id="truck_paint" name="truck_paint" value="{{ $convoy->truck_paint }}">
                @if($errors->has('truck_paint'))
                    <small class="form-text">{{ $errors->first('truck_paint') }}</small>
                @endif
            </div>
            <h3 class="text-primary">Прицеп</h3>
            <div class="form-group">
                <label for="trailer_paint">Изображение прицепа (ссылка)</label>
                <input type="text" class="form-control" id="trailer_image" name="trailer_image" value="{{ $convoy->trailer_image }}">
                @if($errors->has('trailer_image'))
                    <small class="form-text">{{ $errors->first('trailer_image') }}</small>
                @endif
            </div>
            <div class="form-group">
                <label for="trailer">Тип</label>
                <input type="text" class="form-control" id="trailer" name="trailer" value="{{ $convoy->trailer }}">
                @if($errors->has('trailer'))
                    <small class="form-text">{{ $errors->first('trailer') }}</small>
                @endif
            </div>
            <div class="form-group">
                <label for="trailer_tuning">Тюнинг</label>
                <input type="text" class="form-control" id="trailer_tuning" name="trailer_tuning" value="{{ $convoy->trailer_tuning }}">
                @if($errors->has('trailer_tuning'))
                    <small class="form-text">{{ $errors->first('trailer_tuning') }}</small>
                @endif
            </div>
            <div class="form-group">
                <label for="trailer_paint">Окрас</label>
                <input type="text" class="form-control" id="trailer_paint" name="trailer_paint" value="{{ $convoy->trailer_paint }}">
                @if($errors->has('trailer_paint'))
                    <small class="form-text">{{ $errors->first('trailer_paint') }}</small>
                @endif
            </div>
            <div class="form-group">
                <label for="cargo">Груз</label>
                <input type="text" class="form-control" id="cargo" name="cargo" value="{{ $convoy->cargo }}">
                @if($errors->has('cargo'))
                    <small class="form-text">{{ $errors->first('cargo') }}</small>
                @endif
            </div>
            <button class="btn btn-outline-warning" type="submit"><i class="fas fa-save"></i> Сохранить конвой</button>
            <a href="{{ route('evoque.admin.convoy.delete', $convoy->id) }}" class="btn btn-outline-danger"
               onclick="return confirm('Удалить этот конвой?')"><i class="fas fa-trash"></i> Удалить</a>
        </form>
    </div>

    <script>
        $('#datetimepicker').datetimepicker({
            i18n:{
                ru:{
                    months:[
                        'Январь','Февраль','Март','Апрель',
                        'Май','Июнь','Июль','Август',
                        'Сентябрь','Октябрь','Ноябрь','Декабрь',
                    ],
                    dayOfWeek:[
                        "Вс", "Пн", "Вт", "Ср",
                        "Чт", "Пт", "Сб",
                    ]
                }
            },
            format: 'd.m.Y H:i',
            lang: 'ru',
            minDate: '0',
            step: 30,
            theme: 'dark',
            dayOfWeekStart: '1',
            defaultTime: '19:30',
            scrollInput: false
        });
        $.datetimepicker.setLocale('ru');
    </script>

@endsection
