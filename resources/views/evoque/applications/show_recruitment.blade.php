@extends('layout.index')

@section('title')
    Заявка от {{ $app->name }} | @lang('general.vtc_evoque')
@endsection

@section('assets')
    <link rel="stylesheet" type="text/css" href="/js/simplemde/dist/simplemde-dark.min.css">
    <script src="/js/simplemde/dist/simplemde.min.js"></script>
@endsection

@section('content')

    <div class="report-accept container pt-5 pb-5">
        @include('layout.alert')
        <h2 class="mt-3 text-primary text-center">Заявка на вступление</h2>
        <div class="row justify-content-between text-center mt-5">
            <div class="col-md-4">
                <h4 class="mb-0">Имя</h4>
                <h1 class="text-primary">{{ $app->name }}</h1>
            </div>
            <div class="col-md-4">
                <h4 class="mb-0 text-center">Возраст</h4>
                <h1 class="text-primary text-center">{{ $app->age }} {{ trans_choice('год|года|лет', $app->age) }}</h1>
            </div>
            <div class="col-md-4">
                <h4 class="mb-0 text-center">Ник в игре</h4>
                <h1 class="text-primary text-center">{{ $app->nickname }}</h1>
            </div>
        </div>
        <div class="row justify-content-between text-center mt-5">
            <div class="col-md-4">
                <h4 class="mb-0">Часов в ETS2</h4>
                <h1 class="text-primary">{{ $app->hours_played }} {{ trans_choice('час|часа|часов', $app->hours_played) }}</h1>
            </div>
            <div class="col-md-4">
                @if($app->have_mic)
                    <h4 class="mb-0 text-center">Микрофон: @if($app->have_mic) <i class="fas fa-check text-success"></i> @else <i class="fas fa-times text-danger"></i> @endif</h4>
                @endif
                <h4 class="mb-0 text-center">Discord: @if($app->have_ts3) <i class="fas fa-check text-success"></i> @else <i class="fas fa-times text-danger"></i> @endif</h4>
                <h4 class="mb-0 text-center">Наличие ATS: @if($app->have_ats) <i class="fas fa-check text-success"></i> @else <i class="fas fa-times text-danger"></i> @endif</h4>
            </div>
            <div class="col-md-4">
                <h4 class="mb-0 text-center">Ссылки</h4>
                <h1 class="py-2">
                    <a class="mx-2" href="{{ $app->vk_link }}" target="_blank"><i class="fab fa-vk"></i></a>
                    <a class="mx-2" href="{{ $app->steam_link }}" target="_blank"><i class="fab fa-steam"></i></a>
                    <a class="mx-2" href="{{ $app->tmp_link }}" target="_blank"><i class="fas fa-truck-pickup"></i></a>
                </h1>
            </div>
        </div>

        @if($app->referral)
            <div class="row flex-column text-center mb-3">
                <h4 class="mb-0 pt-3">Откуда узнал</h4>
                <h2 class="text-primary">{!! nl2br($app->referral) !!}</h2>
            </div>
        @endif
        @can('claim', $app)
            <form method="post" action="{{ route('evoque.applications.accept.recruitment', $app->id) }}">
                @csrf
                <h4 class="text-center mb-0">Комментарий</h4>
                <textarea class="form-control simple-mde" id="comment" name="comment">{{ $app->comment }}</textarea>
                @if($errors->has('comment'))
                    <small class="form-text">{{ $errors->first('comment') }}</small>
                @endif
                <div class="row justify-content-center">
                    <button type="submit" name="accept" value="1" class="btn btn-outline-success btn-lg m-1"
                            onclick="return confirm('Принять заявку?')">Принять</button>
                    <button type="submit" name="accept" value="2" class="btn btn-outline-danger btn-lg m-1"
                            onclick="return confirm('Отклонить заявку?')">Отклонить</button>
                </div>
            </form>
        @endcan
    </div>

    <script>
        var simplemde = new SimpleMDE({
            element: $('#comment')[0],
            promptURLs: true
        });
    </script>

@endsection
