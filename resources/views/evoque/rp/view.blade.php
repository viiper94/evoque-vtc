@extends('layout.index')

@section('title')
    Прием отчёта рейтинговых перевозок | @lang('general.vtc_evoque')
@endsection

@section('assets')
    <link rel="stylesheet" type="text/css" href="/js/fotorama-4.6.4/fotorama.css">
    <script src="/js/fotorama-4.6.4/fotorama.js"></script>
    <link rel="stylesheet" type="text/css" href="/js/simplemde/dist/simplemde-dark.min.css">
    <script src="/js/simplemde/dist/simplemde.min.js"></script>
@endsection

@section('content')

    <div class="report-accept container pt-5 pb-5">
        @include('layout.alert')
        @if($stat)
            <section class="features lead-rules row pt-3 justify-content-around text-center">
                <h2 class="col-12 mb-3 text-primary text-left">Статистика {{ $report->member->nickname ?? 'уволенного сотрудника' }} по {{ strtoupper($stat->game) }}</h2>
                <div class="feature col-lg-3 mb-md-3 mb-5">
                    <h3>Дистанция<br>всего</h3>
                    <hr class="m-auto pb-3">
                    <p class="text-primary font-weight-bold">{{ $stat->distance_total }} км</p>
                </div>
                <div class="feature col-lg-3 col-sm-2 mb-md-3 mb-5">
                    <h3>Вес<br>всего</h3>
                    <hr class="m-auto pb-3">
                    <p class="text-primary font-weight-bold">{{ $stat->weight_total }} т</p>
                </div>
                <div class="feature col-lg-3 col-sm-2 mb-md-3 mb-5">
                    <h3>Грузов<br>всего</h3>
                    <hr class="m-auto pb-3">
                    <p class="text-primary font-weight-bold">{{ $stat->quantity_total }}</p>
                </div>
                <div class="feature col-lg-3 col-sm-2 mb-md-3 mb-5">
                    <h2>Уровень</h2>
                    <hr class="m-auto pb-3">
                    <h3 class="text-danger">{{ $stat->level }}</h3>
                </div>
                <div class="feature col-lg-3 col-sm-2 mb-md-0 mb-5">
                    <h3>Дистанция<br>за неделю</h3>
                    <hr class="m-auto pb-3">
                    <p class="text-primary font-weight-bold">{{ $stat->distance }} км</p>
                </div>
                <div class="feature col-lg-3 col-sm-2 mb-md-0 mb-5">
                    <h3>Вес<br>за неделю</h3>
                    <hr class="m-auto pb-3">
                    <p class="text-primary font-weight-bold">{{ $stat->weight }} т</p>
                </div>
                <div class="feature col-lg-3 col-sm-2 mb-md-0 mb-5">
                    <h3>Грузов<br>за неделю</h3>
                    <hr class="m-auto pb-3">
                    <p class="text-primary font-weight-bold">{{ $stat->quantity }}</p>
                </div>
                <div class="feature col-lg-3 col-sm-2 mb-md-0 mb-5">
                    <h3>Бонус<br>за неделю</h3>
                    <hr class="m-auto pb-3">
                    <p class="text-primary font-weight-bold">{{ $stat->bonus }} км</p>
                </div>
            </section>
        @endif
        <h2 class="mt-3 text-primary">Прием отчёта от {{ $report->member->nickname ?? 'уволенного сотрудника' }}</h2>
        <form method="post">
            @csrf
            @if($report->note)
                <div class="row">
                    <p class="mb-0 text-primary font-weight-bold col-12">Дополнительная информация</p>
                    <div class="col-12 markdown-content">
                        @markdown($report->note)
                    </div>
                </div>
            @endif
            <div class="row">
                <div class="col-md-9">
                    <div class="fotorama w-100" data-allowfullscreen="true" data-nav="thumbs">
                        @foreach($report->images as $image)
                            <img src="/images/rp/{{ $image }}">
                        @endforeach
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="distance">Пройденая дистанция, км</label>
                        <input type="number" class="form-control" id="distance" name="distance" value="{{ old('distance') }}">
                        @if($errors->has('distance'))
                            <small class="form-text">{{ $errors->first('distance') }}</small>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="weight">Перевезённый вес</label>
                        <input type="number" class="form-control" id="weight" name="weight" placeholder="В тоннах!" value="{{ old('weight') }}">
                        @if($errors->has('weight'))
                            <small class="form-text">{{ $errors->first('weight') }}</small>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="bonus">Бонус</label>
                        <input type="number" class="form-control" id="bonus" name="bonus" value="{{ old('bonus') }}" readonly>
                    </div>
                    <div class="form-group">
                        <label for="level">Уровень после перевозки</label>
                        <input type="number" class="form-control" id="level" name="level" min="{{ $stat->level }}" value="{{ old('level') }}" required>
                        @if($errors->has('level'))
                            <small class="form-text">{{ $errors->first('level') }}</small>
                        @endif
                    </div>
                </div>
            </div>
            <hr class="border-primary">
            <div class="row">
                <div class="form-group col-12">
                    <label for="comment">Комментарий</label>
                    <textarea class="form-control simple-mde" id="comment" name="comment">{{ old('comment') ?? $report->comment }}</textarea>
                    @if($errors->has('comment'))
                        <small class="form-text">{{ $errors->first('comment') }}</small>
                    @endif
                </div>
            </div>
            <div class="row justify-content-center">
                @can('accept', $report)
                    <button type="submit" name="accept" value="1" class="btn btn-outline-success btn-lg m-1">Принять</button>
                @endcan
                @can('decline', $report)
                        <button type="submit" name="decline" value="1" class="btn btn-outline-danger btn-lg m-1"
                                onclick="return confirm('Отклонить отчёт?')">Отклонить</button>
                @endcan
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
