@extends('layout.index')

@section('title')
    @if($convoy->id)Редактирование конвоя@elseНовый конвой@endif
    | @lang('general.vtc_evoque')
@endsection

@section('assets')
    <link rel="stylesheet" type="text/css" href="/js/simplemde/dist/simplemde-dark.min.css">
    <script src="/js/simplemde/dist/simplemde.min.js"></script>
@endsection

@section('content')

    <div id="list-scrollspy" class="list-group">
        <a class="list-group-item list-group-item-action" href="#info">Общая информация</a>
        <a class="list-group-item list-group-item-action" href="#route">Маршрут</a>
        <a class="list-group-item list-group-item-action" href="#dlc_info">DLC</a>
        <a class="list-group-item list-group-item-action" href="#communication-info">Связь</a>
        <a class="list-group-item list-group-item-action" href="#truck_info">Тягач</a>
        <a class="list-group-item list-group-item-action" href="#trailer_info">Прицеп</a>
    </div>
    <div class="container new-convoy pt-5" >
        @include('layout.alert')
        <form method="post" enctype="multipart/form-data" class="mb-5">
            @csrf
            <div class="mt-3 row justify-content-between">
                <h2 class="text-primary col-md-6" id="info">@if($convoy->id)Редактирование конвоя@elseНовый конвой@endif</h2>
                <div class="btn-wrapper col-md-6 text-md-right">
                    <button class="btn btn-outline-warning mx-1" type="submit"><i class="fas fa-save"></i> Сохранить конвой</button>
                </div>
            </div>
            @if(!$booking)
                <div class="custom-control custom-checkbox mb-2">
                    <input type="checkbox" class="custom-control-input" id="public" name="public" @checked($convoy->public || old('public') === 'on')>
                    <label class="custom-control-label" for="public">Наш открытый конвой (виден всем)</label>
                </div>
                <div class="custom-control custom-checkbox mb-2">
                    <input type="checkbox" class="custom-control-input" id="visible" name="visible" @checked($convoy->visible || old('visible') === 'on')>
                    <label class="custom-control-label" for="visible">Опубликовать для сотрудников</label>
                </div>
            @endif
            <div class="row">
                <div class="form-group col-md col-12">
                    <label for="title">@lang('attributes.title')</label>
                    <input type="text" class="form-control" id="title" name="title" value="{{ old('title') ?? $convoy->title }}" required>
                    @if($errors->has('title'))
                        <small class="form-text">{{ $errors->first('title') }}</small>
                    @endif
                </div>
                <div class="form-group col-md col-12">
                    <label for="lead">@lang('attributes.lead')</label>
                    <select class="form-control" id="lead" name="lead">
                        @if(!$booking)
                            <option value="На месте разберёмся" @selected((old('lead') ?? $convoy->lead) === 'На месте разберёмся')>На месте разберёмся</option>
                        @endif
                        @foreach($members as $member)
                                @if($member->topRole() < 13)
                                    <option value="{{ $member->nickname }}" @selected($member->nickname === (old('lead') ?? $convoy->lead))>{{ $member->nickname }}</option>
                                @endif
                        @endforeach
                    </select>
                </div>
{{--                @if(!$booking)--}}
{{--                    <div class="form-group col-md col-12">--}}
{{--                        <label for="cargoman">@lang('attributes.cargoman')</label>--}}
{{--                        <input type="text" class="form-control" id="cargoman" name="cargoman" value="{{ old('cargoman') ?? $convoy->cargoman }}">--}}
{{--                        @if($errors->has('cargoman'))--}}
{{--                            <small class="form-text">{{ $errors->first('cargoman') }}</small>--}}
{{--                        @endif--}}
{{--                    </div>--}}
{{--                @endif--}}
            </div>
            <div class="row">
                <div class="form-group col-xs-12 col-md-6">
                    <label for="server">@lang('attributes.server')</label>
                    <select class="form-control" id="server" name="server" required>
                        @foreach($servers as $server => $game)
                            <option value="{{ $server }}" @selected($server === (old('server') ?? $convoy->server))>[{{ strtoupper($game) }}] {{ $server }}</option>
                        @endforeach
                        <option value="Выделенный ивент сервер" @selected((old('server') ?? $convoy->server) === 'Выделенный ивент сервер')>Выделенный ивент сервер</option>
                        <option value="Определимся позже" @selected((old('server') ?? $convoy->server) === 'Определимся позже')>Определимся позже</option>
                    </select>
                </div>
                <div class="form-group col-md-3 col-sm-6">
                    <label for="start_date">@lang('attributes.start_date')</label>
                    <input type="text" class="form-control" id="start_date" name="start_date" value="{{ old('start_date') ?? $convoy->start_time->format('d.m.Y') }}" autocomplete="off" @if($booking) readonly @endif required>
                </div>
                <div class="form-group col-md-3 col-sm-6">
                    <label for="start_time">@lang('attributes.start_time')</label>
                    <select name="start_time" id="start_time" class="form-control" required>
                        @foreach($types as $type => $time)
                            <optgroup label="{{ \App\Convoy::getTypeByNum($type) }}">
                                @foreach($time as $item)
                                    <option value="{{ $item }}" @selected($item === (old('start_time') ?? $convoy->start_time->format('H:i')))>{{ $item }}</option>
                                @endforeach
                                    <option disabled></option>
                            </optgroup>
                        @endforeach
                    </select>
                    @if($errors->has('start_time'))
                        <small class="form-text">{{ $errors->first('start_time') }}</small>
                    @endif
                </div>
            </div>
            <h3 class="text-primary mt-3" id="route">@lang('attributes.route')</h3>
            <h6>Изображения</h6>
            <small class="text-primary">Макс. размер файла: <b>7 Мб</b></small>
            <div class="convoy-images-uploader d-flex align-items-center mb-3">
                @if($convoy->route)
                    <div class="convoy-images d-flex flex-grow-1">
                        @foreach($convoy->route as $item)
                            <div class="convoy-image-preview m-2">
                                <div class="delete-route-img"></div>
                                <img src="/images/convoys/{{ $item }}" data-name="{{ $item }}">
                            </div>
                        @endforeach
                    </div>
                    @if(count($convoy->route) < 6)
                            <div class="custom-file add-convoy-image mx-sm-2 my-2">
                                <input type="file" class="custom-file-input" accept="image/*" data-allowed="{{ 6 - count($convoy->route) }}" multiple>
                                <label class="custom-file-label"><i class="fas fa-plus"></i></label>
                            </div>
                    @endif
                @else
                    <div class="convoy-images d-flex flex-grow-1"></div>
                    <div class="custom-file add-convoy-image mx-sm-2 my-2">
                        <input type="file" class="custom-file-input" accept="image/*" data-allowed="6" multiple>
                        <label class="custom-file-label"><i class="fas fa-plus"></i></label>
                    </div>
                @endif
            </div>
            <div class="row">
                <div class="col-md-7">
                    <div class="form-row">
                        <div class="form-group col-7">
                            <label for="start_city">@lang('attributes.start_city')</label>
                            <input type="text" class="form-control" id="start_city" name="start_city" value="{{ old('start_city') ?? $convoy->start_city }}" required placeholder="Город">
                            @if($errors->has('start_city'))
                                <small class="form-text">{{ $errors->first('start_city') }}</small>
                            @endif
                        </div>
                        <div class="form-group col-5">
                            <label for="start_company">@lang('attributes.start_company')</label>
                            <input type="text" class="form-control" id="start_company" name="start_company" value="{{ old('start_company') ?? $convoy->start_company }}" required placeholder="Место">
                            @if($errors->has('start_company'))
                                <small class="form-text">{{ $errors->first('start_company') }}</small>
                            @endif
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-7">
                            <label for="rest_city">@lang('attributes.rest_city')</label>
                            <input type="text" class="form-control" id="rest_city" name="rest_city" value="{{ old('rest_city') ?? $convoy->rest_city }}" placeholder="Город">
                            @if($errors->has('rest_city'))
                                <small class="form-text">{{ $errors->first('rest_city') }}</small>
                            @endif
                        </div>
                        <div class="form-group col-5">
                            <label for="rest_company">@lang('attributes.rest_company')</label>
                            <input type="text" class="form-control" id="rest_company" name="rest_company" value="{{ old('rest_company') ?? $convoy->rest_company }}" placeholder="Место">
                            @if($errors->has('rest_company'))
                                <small class="form-text">{{ $errors->first('rest_company') }}</small>
                            @endif
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-7">
                            <label for="finish_city">@lang('attributes.finish_city')</label>
                            <input type="text" class="form-control" id="finish_city" name="finish_city" value="{{ old('finish_city') ?? $convoy->finish_city }}" required placeholder="Город">
                            @if($errors->has('finish_city'))
                                <small class="form-text">{{ $errors->first('finish_city') }}</small>
                            @endif
                        </div>
                        <div class="form-group col-5">
                            <label for="finish_company">@lang('attributes.finish_company')</label>
                            <input type="text" class="form-control" id="finish_company" name="finish_company" value="{{ old('finish_company') ?? $convoy->finish_company }}" required placeholder="Место">
                            @if($errors->has('finish_company'))
                                <small class="form-text">{{ $errors->first('finish_company') }}</small>
                            @endif
                        </div>
                    </div>
                    <h3 class="mt-5 text-primary" id="communication-info">@lang('attributes.communication')</h3>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="communication-ts3" name="communication" class="custom-control-input" value="TeamSpeak 3"
                               @checked((old('communication') ?? $convoy->communication) === 'TeamSpeak 3') @if($booking) disabled @endif>
                        <label class="custom-control-label" for="communication-ts3">TeamSpeak 3</label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="communication-discord" name="communication" class="custom-control-input" value="Discord"
                               @checked((old('communication') ?? $convoy->communication) === 'Discord' || (old('communication') ?? $convoy->communication) == '') @if($booking) disabled @endif>
                        <label class="custom-control-label" for="communication-discord">Discord</label>
                        @if($booking)
                            <input type="hidden" name="communication" value="{{ $convoy->communication }}">
                        @endif
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label for="communication_program">@lang('attributes.communication_link')</label>
                            <input type="text" class="form-control" id="communication_link" name="communication_link" value="{{ old('communication_link') ?? $convoy->communication_link }}" required @if($booking) readonly @endif>
                            @if($errors->has('communication_link'))
                                <small class="form-text">{{ $errors->first('communication_link') }}</small>
                            @endif
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="communication_channel">@lang('attributes.communication_channel')</label>
                            <input type="text" class="form-control" id="communication_channel" name="communication_channel" value="{{ old('communication_channel') ?? $convoy->communication_channel }}" @if($booking) readonly @endif>
                            @if($errors->has('communication_channel'))
                                <small class="form-text">{{ $errors->first('communication_channel') }}</small>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-5 form-group" id="dlc_info">
                    <label for="dlc">
                        @lang('attributes.dlc')
                        @can('editDLCList', \App\Convoy::class)
                            <a href="{{ route('evoque.convoys.dlc') }}">
                                <i class="fas fa-pen"></i>
                            </a>
                        @endcan
                    </label>
                    <select class="form-control" size="19" name="dlc[]" id="dlc" multiple>
                        @foreach($dlc as $game => $list)
                            <optgroup label="{{ strtoupper($game) }}">
                                @foreach($list as $item)
                                    <option value="{{ $item->id }}" @selected((is_array(old('dlc')) && in_array($item->id, old('dlc'))) || $convoy->DLC->contains($item->id))>{{ $item->title }}</option>
                                @endforeach
                                <option disabled></option>
                            </optgroup>
                        @endforeach
                    </select>
                    @if($errors->has('dlc'))
                        <small class="form-text">{{ $errors->first('dlc') }}</small>
                    @endif
                    <small class="text-muted">удерживать Ctrl для выбора нескольких или снятия выбора</small>
                </div>
            </div>
            <div class="row truck-section" id="truck_info">
                <h3 class="text-primary mt-3 col-12">@lang('attributes.truck')</h3>
                <div class="col-md-5">
                    <h6>Изображение</h6>
                    <div class="form-group truck_image">
                        <div class="custom-file custom-file-dark mb-3 truck_image-input" @if($convoy->officialTruckTuning) style="display: none" @endif>
                            <input type="file" class="custom-file-input uploader" id="truck_image" name="truck_image" accept="image/*" @if($convoy->officialTruckTuning) disabled @endif>
                            <label class="custom-file-label" for="truck_image">Выберите изображение</label>
                            <small class="text-primary">Макс. размер файла: <b>7 Мб</b></small>
                        </div>
                        <div class="vehicle-image-wrap">
                            <img src="@if($convoy->officialTruckTuning) /images/tuning/{{ $convoy->officialTruckTuning->image }} @else /images/convoys/{{ $convoy->truck_image ?? "image-placeholder.jpg" }} @endif"
                                 class="w-100" id="truck_image-preview">
                            @if($convoy->truck_image)
                                <button type="button" class="delete-img" data-target="truck_image" data-action="{{ route('evoque.convoy.deleteImg', $convoy->id) }}"><i class="fas fa-trash"></i></button>
                            @endif
                        </div>
                        @if($errors->has('truck_image'))
                            <small class="form-text">{{ $errors->first('truck_image') }}</small>
                        @endif
                        <label for="truck_with_tuning" class="my-1 text-muted">Или выберите тягач с официальным тюнингом</label>
                        <select id="truck_with_tuning" name="truck_with_tuning" class="custom-select custom-select-dark" data-target="truck">
                            <option value="">Официальный тюнинг не обязательный</option>
                            @foreach($trucks_tuning as $vendor => $tunings)
                                <optgroup label="{{ $vendor }}">
                                    @foreach($tunings as $item)
                                        <option value="{{ $item->id }}" @selected($convoy->officialTruckTuning?->id === $item->id)>{{ $item->vendor }} {{ $item->model }}</option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-7">
                    <h6>Описание</h6>
                    @if(!$booking)
                        <div class="custom-control custom-checkbox mb-2" @if(!$convoy->public) style="display: none" @endif>
                            <input type="checkbox" class="custom-control-input" id="truck_public" name="truck_public"
                                   @if(!$convoy->officialTruckTuning && ($convoy->truck_public || old('truck_public') === 'on')) checked @endif
                                   @if($convoy->officialTruckTuning) disabled @endif>
                            <label class="custom-control-label" for="truck_public">@lang('attributes.truck_public')</label>
                        </div>
                    @endif
                    <div class="form-group">
                        <input type="text" class="form-control" id="truck" name="truck"
                               value="{{ old('truck') ?? $convoy->truck }}"
                               placeholder="Марка" @if($convoy->officialTruckTuning) readonly @endif>
                        @if($errors->has('truck'))
                            <small class="form-text">{{ $errors->first('truck') }}</small>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="truck_tuning">@lang('attributes.truck_tuning')</label>
                        <input type="text" class="form-control" id="truck_tuning" name="truck_tuning"
                               value="{{ old('truck_tuning') ?? $convoy->truck_tuning }}"
                               placeholder="Не обязательно">
                        @if($errors->has('truck_tuning'))
                            <small class="form-text">{{ $errors->first('truck_tuning') }}</small>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="truck_paint">@lang('attributes.truck_paint')</label>
                        <input type="text" class="form-control" id="truck_paint" name="truck_paint"
                               value="{{ old('truck_paint') ?? $convoy->truck_paint }}"
                               placeholder="Не обязательно"  @if($convoy->officialTruckTuning) readonly @endif>
                        @if($errors->has('truck_paint'))
                            <small class="form-text">{{ $errors->first('truck_paint') }}</small>
                        @endif
                    </div>
                </div>
            </div>
            <div class="row trailer-section" id="trailer_info">
                <h3 class="text-primary mt-5 col-12">@lang('attributes.trailer')</h3>
                <div class="col-md-5">
                    <h6>Изображение</h6>
                    <div class="form-group trailer_image">
                        <div class="custom-file custom-file-dark mb-3 trailer_image-input" @if($convoy->officialTrailerTuning) style="display: none" @endif>
                            <input type="file" class="custom-file-input uploader" id="trailer_image" name="trailer_image" accept="image/*" @if($convoy->officialTrailerTuning) disabled @endif>
                            <label class="custom-file-label" for="trailer_image">Выберите изображение</label>
                            <small class="text-primary">Макс. размер файла: <b>7 Мб</b></small>
                        </div>
                        <div class="vehicle-image-wrap">
                            <img src="@if($convoy->officialTrailerTuning) /images/tuning/{{ $convoy->officialTrailerTuning->image }} @else /images/convoys/{{ $convoy->trailer_image ?? "image-placeholder.jpg" }} @endif"
                                class="w-100" id="trailer_image-preview">
                            @if($convoy->trailer_image)
                                <button type="button" class="delete-img" data-target="trailer_image" data-action="{{ route('evoque.convoy.deleteImg', $convoy->id) }}"><i class="fas fa-trash"></i></button>
                            @endif
                        </div>
                        @if($errors->has('trailer_image'))
                            <small class="form-text">{{ $errors->first('trailer_image') }}</small>
                        @endif
                        <label for="trailer_with_tuning" class="my-1 text-muted">Или выберите прицеп с официальным тюнингом</label>
                        <select id="trailer_with_tuning" name="trailer_with_tuning" class="custom-select custom-select-dark" data-target="trailer">
                            <option value="">Официальный тюнинг не обязательный</option>
                            @foreach($trailers_tuning as $vendor => $tunings)
                                <optgroup label="{{ $vendor }}">
                                    @foreach($tunings as $item)
                                        <option value="{{ $item->id }}" @selected($convoy->officialTrailerTuning?->id === $item->id)>{{ $item->model }} @if($item->vendor){{ $item->vendor }}@endif</option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-7">
                    <h6>Описание</h6>
                    @if(!$booking)
                        <div class="custom-control custom-checkbox mb-2" @if(!$convoy->public) style="display: none" @endif>
                            <input type="checkbox" class="custom-control-input" id="trailer_public" name="trailer_public" @if($convoy->trailer_public || old('trailer_public') === 'on') checked @endif>
                            <label class="custom-control-label" for="trailer_public">@lang('attributes.trailer_public')</label>
                        </div>
                    @endif
                    <div class="form-group">
                        <input type="text" class="form-control" id="trailer" name="trailer"
                               value="{{ old('trailer') ?? $convoy->trailer }}" placeholder="Тип"
                               @if($convoy->officialTruckTuning) readonly @endif>
                        @if($errors->has('trailer'))
                            <small class="form-text">{{ $errors->first('trailer') }}</small>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="trailer_tuning">@lang('attributes.trailer_tuning')</label>
                        <input type="text" class="form-control" id="trailer_tuning" name="trailer_tuning" value="{{ old('trailer_tuning') ?? $convoy->trailer_tuning }}" placeholder="Не обязательно">
                        @if($errors->has('trailer_tuning'))
                            <small class="form-text">{{ $errors->first('trailer_tuning') }}</small>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="trailer_paint">@lang('attributes.trailer_paint')</label>
                        <input type="text" class="form-control" id="trailer_paint" name="trailer_paint"
                               value="{{ old('trailer_paint') ?? $convoy->trailer_paint }}"
                               @if($convoy->officialTruckTuning) readonly @endif
                               placeholder="Не обязательно">
                        @if($errors->has('trailer_paint'))
                            <small class="form-text">{{ $errors->first('trailer_paint') }}</small>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="cargo">@lang('attributes.cargo')</label>
                        <input type="text" class="form-control" id="cargo" name="cargo" value="{{ old('cargo') ?? $convoy->cargo }}" placeholder="Не обязательно">
                        @if($errors->has('cargo'))
                            <small class="form-text">{{ $errors->first('cargo') }}</small>
                        @endif
                    </div>
                </div>
            </div>
            <div class="row alt_trailer-section" style="display: @if($convoy->officialTrailerTuning)none @else flex @endif ;" id="alt_trailer_info">
                <h3 class="text-primary mt-5 col-12">@lang('attributes.alt_trailer')</h3>
                <div class="col-md-5">
                    <h6>Изображение</h6>
                    <div class="form-group alt_trailer_image">
                        <div class="custom-file custom-file-dark mb-3">
                            <input type="file" class="custom-file-input uploader" id="alt_trailer_image" name="alt_trailer_image" accept="image/*">
                            <label class="custom-file-label" for="alt_trailer_image">Выберите изображение</label>
                            <small class="text-primary">Макс. размер файла: <b>7 Мб</b></small>
                        </div>
                        <div class="vehicle-image-wrap">
                            <img src="/images/convoys/{{ $convoy->alt_trailer_image ?? "image-placeholder.jpg" }}" class="w-100" id="alt_trailer_image-preview">
                            @if($convoy->alt_trailer_image)
                                <button type="button" class="delete-img" data-target="alt_trailer_image" data-action="{{ route('evoque.convoy.deleteImg', $convoy->id) }}"><i class="fas fa-trash"></i></button>
                            @endif
                        </div>
                        @if($errors->has('alt_trailer_image'))
                            <small class="form-text">{{ $errors->first('alt_trailer_image') }}</small>
                        @endif
                    </div>
                </div>
                <div class="col-md-7">
                    <h6>Описание</h6>
                    <div class="form-group">
                        <input type="text" class="form-control" id="alt_trailer" name="alt_trailer" value="{{ old('alt_trailer') ?? $convoy->alt_trailer }}" placeholder="Тип, не обязательно">
                        @if($errors->has('alt_trailer'))
                            <small class="form-text">{{ $errors->first('alt_trailer') }}</small>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="alt_trailer_tuning">@lang('attributes.alt_trailer_tuning')</label>
                        <input type="text" class="form-control" id="alt_trailer_tuning" name="alt_trailer_tuning" value="{{ old('alt_trailer_tuning') ?? $convoy->alt_trailer_tuning }}" placeholder="Не обязательно">
                        @if($errors->has('alt_trailer_tuning'))
                            <small class="form-text">{{ $errors->first('alt_trailer_tuning') }}</small>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="alt_trailer_paint">@lang('attributes.alt_trailer_paint')</label>
                        <input type="text" class="form-control" id="alt_trailer_paint" name="alt_trailer_paint" value="{{ old('alt_trailer_paint') ?? $convoy->alt_trailer_paint }}" placeholder="Не обязательно">
                        @if($errors->has('alt_trailer_paint'))
                            <small class="form-text">{{ $errors->first('alt_trailer_paint') }}</small>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="alt_cargo">@lang('attributes.alt_cargo')</label>
                        <input type="text" class="form-control" id="alt_cargo" name="alt_cargo" value="{{ old('alt_cargo') ?? $convoy->alt_cargo }}" placeholder="Не обязательно">
                        @if($errors->has('alt_cargo'))
                            <small class="form-text">{{ $errors->first('alt_cargo') }}</small>
                        @endif
                    </div>
                </div>
            </div>
            <div class="row pt-3">
                <div class="form-group col-12">
                    <label for="comment">@lang('attributes.comment')</label>
                    <textarea class="form-control simple-mde" id="comment" name="comment">{{ old('comment') ?? $convoy->comment }}</textarea>
                    @if($errors->has('comment'))
                        <small class="form-text">{{ $errors->first('comment') }}</small>
                    @endif
                </div>
            </div>
            <div class="row justify-content-center">
                <button class="btn btn-outline-warning mx-1" type="submit"><i class="fas fa-save"></i> Сохранить конвой</button>
            </div>
        </form>

        @if(count($convoy->audits) > 0)
            @can('update', \App\Convoy::class)
                <h3 class="text-primary mt-3">История изменений</h3>
                @include('layout.changelog', ['items' => $convoy->audits, 'granularity' => new cogpowered\FineDiff\Granularity\Word])
            @endcan
        @endif

    </div>

    <script>
        @if(!$booking)
            const picker = new Litepicker({
                element: document.getElementById('start_date'),
                plugins: ['mobilefriendly'],
                lang: 'ru-RU',
                format: 'DD.MM.YYYY'
            });
        @endif

        var simplemde = new SimpleMDE({
            element: $('#comment')[0],
            promptURLs: true,
        });
    </script>

@endsection
