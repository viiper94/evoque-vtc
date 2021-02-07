@extends('layout.index')

@section('title')
    Заявка от {{ !in_array($app->category, [4, 5]) ? $app->member->nickname : $app->old_nickname }} | @lang('general.vtc_evoque')
@endsection

@section('assets')
    <link rel="stylesheet" type="text/css" href="/js/simplemde/dist/simplemde-dark.min.css">
    <script src="/js/simplemde/dist/simplemde.min.js"></script>
@endsection

@section('content')

    <div class="report-accept container pt-5 pb-5">
        @include('layout.alert')
        <h2 class="mt-3 text-primary text-center">Заявка на {{ $app->getCategory() }} от {{ !in_array($app->category, [4, 5]) ? $app->member->nickname : $app->old_nickname }}</h2>
        <div class="row justify-content-between text-center mt-5">
            @switch($app->category)
                @case(1)
                    <div class="col-12">
                        <h4 class="mb-0">Отпуск</h4>
                        <h1 class="text-primary">с {{ \Carbon\Carbon::parse($app->vacation_till['from'])->isoFormat('LL') }}</h1>
                        <h1 class="text-primary">по {{ \Carbon\Carbon::parse($app->vacation_till['to'])->isoFormat('LL') }}</h1>
                    </div>
                    @break
                @case(2)
                    <div class="col-12">
                        <h4 class="mb-0">Желаемый номер</h4>
                        <h1 class="text-primary">EVOQUE {{ $app->new_plate_number }}
                            <a href="https://worldoftrucks.com/api/license_plate/eut2/germany/rear/evoque%20{{ $app->new_plate_number }}" target="_blank">
                                <i class="fas fa-cogs"></i>
                            </a>
                        </h1>
                    </div>
                    @break
                @case(3)
                    <div class="col-md-4">
                        <h4 class="mb-0">Игра</h4>
                        <h1 class="text-primary">{{ strtoupper($app->new_rp_profile[0]) }}</h1>
                    </div>
                    <div class="col-md-4">
                        <h4 class="mb-0 text-center">Новый уровень</h4>
                        <h1 class="text-primary text-center">{{ $app->new_rp_profile[1] }}</h1>
                    </div>
                    <div class="col-md-4">
                        <h4 class="mb-0 text-center">Текущий уровень</h4>
                        <h1 class="text-primary text-center">{{ $rp->level }}</h1>
                    </div>
                    @break
                @case(4)
                    <div class="col-md-6">
                        <h4 class="mb-0">Новый никнейм</h4>
                        <h1 class="text-primary">{{ $app->new_nickname }}</h1>
                    </div>
                    <div class="col-md-6">
                        <h4 class="mb-0">Текущий никнейм</h4>
                        <h1 class="text-primary">{{ $app->member->nickname }}</h1>
                    </div>
                    @break
                @case(5) @break
            @endswitch
        </div>

        @if($app->reason)
            <div class="row flex-column text-center">
                <h4 class="mb-0 pt-3">Причина: </h4>
                <div class="markdown-content">
                    @markdown($app->reason)
                </div>
            </div>
        @endif
        <form method="post" action="{{ route('evoque.applications.accept', $app->id) }}">
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
    </div>

    <script>
        var simplemde = new SimpleMDE({
            element: $('#comment')[0],
            promptURLs: true
        });
    </script>

@endsection
