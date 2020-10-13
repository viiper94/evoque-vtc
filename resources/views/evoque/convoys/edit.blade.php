@extends('layout.index')

@section('title')
    @if($convoy->title)
        Редактирование конвоя
    @else
        Новый конвой
    @endif
    | @lang('general.vtc_evoque')
@endsection

@section('assets')
    <link rel="stylesheet" type="text/css" href="/css/jquery.datetimepicker.min.css">
    <script src="/js/jquery.datetimepicker.full.min.js"></script>
@endsection

@section('content')

    <div class="container pt-5">
        @include('layout.alert')
        <h2 class="mt-3 text-primary">
            @if($convoy->title)
                Редактирование конвоя
            @else
                Новый конвой
            @endif
        </h2>
        <form method="post" enctype="multipart/form-data" class="mb-5">
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
            <div class="row">
                <div class="col-md-5 route-images">
                    @foreach($convoy->route as $index => $image)
                        @if($loop->last)
                            @php $image_index = $index @endphp
                        @endif
                        <div class="form-group">
                            <div class="custom-file custom-file-dark mb-3">
                                <input type="file" class="custom-file-input uploader" id="route-{{ $index }}" name="route[]" accept="image/*">
                                <label class="custom-file-label" for="route-{{ $index }}">Изображение маршрута</label>
                            </div>
                            <img src="/images/convoys/{{ $image ?? "image-placeholder.jpg" }}" class="w-100" id="route-{{ $index }}-preview">
                        </div>
                    @endforeach
                    <button type="button" class="btn btn-sm btn-outline-warning" id="add-convoy-img" data-target="route_images" data-index="{{ $image_index ?? 0 }}"><i class="fas fa-plus"></i> Еще картинку</button>
                    <button type="button" class="btn btn-sm btn-outline-danger" id="delete-convoy-img" data-target="route_images" onclick="return confirm('Удалить все картинки?')"><i class="fas fa-trash"></i> Удалить все картинки</button>
                </div>
                <div class="col-md-7">
                    <div class="form-row">
                        <div class="form-group col-7">
                            <label for="start_city">Старт из</label>
                            <input type="text" class="form-control" id="start_city" name="start_city" value="{{ $convoy->start_city }}" required placeholder="Город">
                            @if($errors->has('start_city'))
                                <small class="form-text">{{ $errors->first('start_city') }}</small>
                            @endif
                        </div>
                        <div class="form-group col-5">
                            <label for="start_company">Место</label>
                            <input type="text" class="form-control" id="start_company" name="start_company" value="{{ $convoy->start_company }}" required placeholder="Место">
                            @if($errors->has('start_company'))
                                <small class="form-text">{{ $errors->first('start_company') }}</small>
                            @endif
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-7">
                            <label for="rest_city">Перерыв</label>
                            <input type="text" class="form-control" id="rest_city" name="rest_city" value="{{ $convoy->rest_city }}" required placeholder="Город">
                            @if($errors->has('rest_city'))
                                <small class="form-text">{{ $errors->first('rest_city') }}</small>
                            @endif
                        </div>
                        <div class="form-group col-5">
                            <label for="rest_company">Место</label>
                            <input type="text" class="form-control" id="rest_company" name="rest_company" value="{{ $convoy->rest_company }}" placeholder="Место">
                            @if($errors->has('rest_company'))
                                <small class="form-text">{{ $errors->first('rest_company') }}</small>
                            @endif
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-7">
                            <label for="finish_city">Финиш в:</label>
                            <input type="text" class="form-control" id="finish_city" name="finish_city" value="{{ $convoy->finish_city }}" required placeholder="Город">
                            @if($errors->has('finish_city'))
                                <small class="form-text">{{ $errors->first('finish_city') }}</small>
                            @endif
                        </div>
                        <div class="form-group col-5">
                            <label for="finish_company">Финиш</label>
                            <input type="text" class="form-control" id="finish_company" name="finish_company" value="{{ $convoy->finish_company }}" required placeholder="Место">
                            @if($errors->has('finish_company'))
                                <small class="form-text">{{ $errors->first('finish_company') }}</small>
                            @endif
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="dlc">Необходимые ДЛС (удерживать Ctrl для выбора нескольких)</label>
                        <select class="form-control" size="10" name="dlc[]" id="dlc" multiple>
                            @foreach($dlc as $game => $list)
                                <option disabled>{{ strtoupper($game) }}</option>
                                @foreach($list as $item)
                                    <option value="{{ $item }}" @if(is_array($convoy->dlc) && in_array($item, $convoy->dlc)) selected @endif>{{ $item }}</option>
                                @endforeach
                            @endforeach
                        </select>
                        @if($errors->has('dlc'))
                            <small class="form-text">{{ $errors->first('dlc') }}</small>
                        @endif
                    </div>
                </div>
            </div>
            <h3 class="mt-5 text-primary">Связь</h3>
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
                    <option value="На месте разберёмся" @if($convoy->lead === 'На месте разберёмся') selected @endif >На месте разберёмся</option>
                    @foreach($members as $member)
                        <option value="{{ $member->nickname }}" @if($member->nickname === $convoy->lead) selected @endif >{{ $member->nickname }}</option>
                    @endforeach
                </select>
            </div>
            <h3 class="text-primary mt-5">Тягач</h3>
            <div class="row">
                <div class="col-md-5">
                    <div class="form-group">
                        <div class="custom-file custom-file-dark mb-3">
                            <input type="file" class="custom-file-input uploader" id="truck_image" name="truck_image" accept="image/*">
                            <label class="custom-file-label" for="truck_image">Изображение тягача</label>
                        </div>
                        <img src="/images/convoys/{{ $convoy->truck_image ? $convoy->truck_image : "image-placeholder.jpg" }}" class="w-100" id="truck_image-preview">
                        @if($errors->has('truck_image'))
                            <small class="form-text">{{ $errors->first('truck_image') }}</small>
                        @endif
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="custom-control custom-checkbox mb-2">
                        <input type="checkbox" class="custom-control-input" id="truck_public" name="truck_public" @if($convoy->truck_public) checked @endif>
                        <label class="custom-control-label" for="truck_public">Показывать для всех</label>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" id="truck" name="truck" value="{{ $convoy->truck }}" placeholder="Марка" required>
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
                </div>
            </div>
            <h3 class="text-primary mt-5">Прицеп (основной)</h3>
            <div class="row">
                <div class="col-md-5">
                    <div class="form-group">
                        <div class="custom-file custom-file-dark mb-3">
                            <input type="file" class="custom-file-input uploader" id="trailer_image" name="trailer_image" accept="image/*">
                            <label class="custom-file-label" for="trailer_image">Изображение прицепа</label>
                        </div>
                        <img src="/images/convoys/{{ $convoy->trailer_image ? $convoy->trailer_image : "image-placeholder.jpg" }}" class="w-100" id="trailer_image-preview">
                        @if($errors->has('trailer_image'))
                            <small class="form-text">{{ $errors->first('trailer_image') }}</small>
                        @endif
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="custom-control custom-checkbox mb-2">
                        <input type="checkbox" class="custom-control-input" id="trailer_public" name="trailer_public" @if($convoy->trailer_public) checked @endif>
                        <label class="custom-control-label" for="trailer_public">Показывать для всех</label>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" id="trailer" name="trailer" value="{{ $convoy->trailer }}" placeholder="Тип" required>
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
                </div>
            </div>
            <h3 class="text-primary mt-5">Прицеп (без ДЛС)</h3>
            <div class="row">
                <div class="col-md-5">
                    <div class="form-group">
                        <div class="custom-file custom-file-dark mb-3">
                            <input type="file" class="custom-file-input uploader" id="alt_trailer_image" name="alt_trailer_image" accept="image/*">
                            <label class="custom-file-label" for="alt_trailer_image">Изображение прицепа</label>
                        </div>
                        <img src="/images/convoys/{{ $convoy->alt_trailer_image ? $convoy->alt_trailer_image : "image-placeholder.jpg" }}" class="w-100" id="alt_trailer_image-preview">
                        @if($errors->has('alt_trailer_image'))
                            <small class="form-text">{{ $errors->first('alt_trailer_image') }}</small>
                        @endif
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="form-group">
                        <input type="text" class="form-control" id="alt_trailer" name="alt_trailer" value="{{ $convoy->alt_trailer }}" placeholder="Тип">
                        @if($errors->has('alt_trailer'))
                            <small class="form-text">{{ $errors->first('alt_trailer') }}</small>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="alt_trailer_tuning">Тюнинг</label>
                        <input type="text" class="form-control" id="alt_trailer_tuning" name="alt_trailer_tuning" value="{{ $convoy->alt_trailer_tuning }}">
                        @if($errors->has('alt_trailer_tuning'))
                            <small class="form-text">{{ $errors->first('alt_trailer_tuning') }}</small>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="alt_trailer_paint">Окрас</label>
                        <input type="text" class="form-control" id="alt_trailer_paint" name="alt_trailer_paint" value="{{ $convoy->alt_trailer_paint }}">
                        @if($errors->has('alt_trailer_paint'))
                            <small class="form-text">{{ $errors->first('alt_trailer_paint') }}</small>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="alt_cargo">Груз</label>
                        <input type="text" class="form-control" id="alt_cargo" name="alt_cargo" value="{{ $convoy->alt_cargo }}">
                        @if($errors->has('alt_cargo'))
                            <small class="form-text">{{ $errors->first('alt_cargo') }}</small>
                        @endif
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <button class="btn btn-outline-warning mx-1" type="submit"><i class="fas fa-save"></i> Сохранить конвой</button>
            </div>

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

    <script type="text/html" id="route_images_template">
        <div class="form-group">
            <div class="custom-file custom-file-dark mb-3">
                <input type="file" class="custom-file-input uploader" id="route-%i%" name="route[]" accept="image/*">
                <label class="custom-file-label" for="route-%i%">Еще одно изображение маршрута</label>
            </div>
            <img src="/images/convoys/image-placeholder.jpg" class="w-100" id="route-%i%-preview">
        </div>
    </script>

@endsection
