@extends('layout.index')

@section('title')
    @if($tab->screenshot)
        Редактирование скрин TAB
    @else
        Подать скрин TAB
    @endif
    | @lang('general.vtc_evoque')
@endsection

@section('assets')
    <link rel="stylesheet" type="text/css" href="/css/jquery.datetimepicker.min.css">
    <script src="/js/jquery.datetimepicker.full.min.js"></script>
@endsection

@section('content')

    <div class="container pt-5 pb-5">
        @include('layout.alert')
        <h2 class="mt-3 text-primary text-center">Подать скрин TAB</h2>
        <form method="post" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="convoy_title">Название конвоя</label>
                <input type="text" class="form-control" id="convoy_title" name="convoy_title" value="{{ $tab->convoy_title }}" required>
                @if($errors->has('convoy_title'))
                    <small class="form-text">{{ $errors->first('convoy_title') }}</small>
                @endif
            </div>
            <div class="form-group">
                <label for="date">Дата конвоя</label>
                <input type="text" class="form-control" name="date" id="date" value="{{ $tab->date ? $tab->date->format('d.m.Y') : '' }}" autocomplete="off" required>
                @if($errors->has('date'))
                    <small class="form-text">{{ $errors->first('date') }}</small>
                @endif
            </div>
            <div class="form-group">
                <label for="lead_id">Ведущий</label>
                <select class="form-control" id="lead_id" name="lead_id" required>
                    @foreach($members as $member)
                        <option value="{{ $member->id }}" @if($member->nickname === \Illuminate\Support\Facades\Auth::user()->member->nickname) selected @endif >
                            {{ $member->nickname }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="description">Дополнительная информация</label>
                <textarea class="form-control" id="description" rows="2" name="description" placeholder="Кто не доехал? Кому минус бал? Кому не защитать?">{{ $tab->description }}</textarea>
                @if($errors->has('description'))
                    <small class="form-text">{{ $errors->first('description') }}</small>
                @endif
            </div>
            <div class="form-group pt-3">
                <div class="custom-file custom-file-dark mb-3">
                    <input type="file" class="custom-file-input uploader" id="screenshot" name="screenshot" accept="image/*">
                    <label class="custom-file-label" for="screenshot">Выберите скрин</label>
                </div>
                <img src="/images/convoys/tab/{{ $tab->screenshot ?? 'image-placeholder.jpg' }}" alt="Скрин TAB" class="w-100" id=" screenshot-preview">
            </div>
            <div class="row justify-content-center">
                <button class="btn btn-outline-warning btn-lg" type="submit"><i class="fas fa-save"></i> Сохранить</button>
            </div>
        </form>
    </div>

    <script>
        $('#date').datetimepicker({
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
            format: 'd.m.Y',
            theme: 'dark',
            dayOfWeekStart: '1',
            timepicker: false,
            scrollInput: false
        });
        $.datetimepicker.setLocale('ru');
    </script>

@endsection
