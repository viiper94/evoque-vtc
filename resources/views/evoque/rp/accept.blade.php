@extends('layout.index')

@section('title')
    Прием отчёта рейтинговых перевозок | @lang('general.vtc_evoque')
@endsection

@section('assets')
    <link rel="stylesheet" type="text/css" href="/js/fotorama-4.6.4/fotorama.css">
    <script src="/js/fotorama-4.6.4/fotorama.js"></script>
@endsection

@section('content')

    <div class="report-accept container pt-5 pb-5">
        @include('layout.alert')
        <h2 class="mt-3 text-primary">Прием отчёта от {{ $report->member->nickname }}</h2>
        <div class="row">
            <div class="col-md-9">
                <div class="fotorama w-100" data-allowfullscreen="true" data-fit="cover" data-nav="thumbs">
                    @foreach($report->images as $image)
                        <img src="/images/rp/{{ $image }}">
                    @endforeach
                </div>
            </div>
            <div class="col-md-3">
                <form method="post">
                    @csrf
                    <div class="form-group">
                        <label for="distance">Пройденая дистанция, км</label>
                        <input type="number" class="form-control" id="distance" name="distance">
                        @if($errors->has('distance'))
                            <small class="form-text">{{ $errors->first('distance') }}</small>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="weight">Перевезённый вес</label>
                        <input type="number" class="form-control" id="weight" name="weight" placeholder="В тоннах!">
                        @if($errors->has('weight'))
                            <small class="form-text">{{ $errors->first('weight') }}</small>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="bonus">Бонус</label>
                        <input type="number" class="form-control" id="bonus" name="bonus" readonly>
                    </div>
                    <div class="form-group">
                        <label for="level">Уровень после перевозки</label>
                        <input type="number" class="form-control" id="level" name="level">
                        @if($errors->has('level'))
                            <small class="form-text">{{ $errors->first('level') }}</small>
                        @endif
                    </div>
                    <div class="row justify-content-center">
                        <button type="submit" class="btn btn-outline-warning btn-lg">Принять отчёт</button>
                    </div>
                </form>
            </div>
        </div>

    </div>

@endsection
