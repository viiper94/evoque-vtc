@extends('layout.index')

@section('title')
    Принять скрин TAB| @lang('general.vtc_evoque')
@endsection

@section('content')

    <div class="container pt-5 pb-5">
        @include('layout.alert')
        <h2 class="mt-3 text-primary text-center">Принять скрин TAB</h2>
        <dt></dt>
        <div class="row">
            <div class="attribute col-md-4 col-xs-12 text-center text-md-left">
                <p class="mb-0">Конвой</p>
                <h3 class="text-primary">{{ $tab->convoy_title }}</h3>
            </div>
            <div class="attribute col-md-4 col-xs-12 text-center">
                <p class="mb-0">Дата</p>
                <h3 class="text-primary">{{ $tab->date->isoFormat('LL') }}</h3>
            </div>
            <div class="attribute col-md-4 col-xs-12 text-md-right text-center">
                <p class="mb-0">Ведущий</p>
                <h3 class="text-primary">{{ $tab->lead->nickname }}</h3>
            </div>
        </div>
        @if($tab->description)
            <div class="attribute mt-3">
                <p class="mb-0">Дополнительная информация</p>
                <h3 class="text-primary">{!! nl2br($tab->description) !!}</h3>
            </div>
        @endif
        <div class="member-set-scores mt-5">

        </div>
    </div>

@endsection
