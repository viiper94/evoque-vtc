@extends('layout.index')

@section('title')
    Забронировать конвой | @lang('general.vtc_evoque')
@endsection

@section('assets')
    <link rel="stylesheet" type="text/css" href="/css/jquery.datetimepicker.min.css">
    <script src="/js/jquery.datetimepicker.full.min.js"></script>
@endsection

@section('content')

    <div class="container py-5">
        @include('layout.alert')

    </div>

@endsection
