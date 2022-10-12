@extends('layout.index')

@section('title')
    История изменения параграфа | @lang('general.vtc_evoque')
@endsection

@section('assets')
    <link rel="stylesheet" type="text/css" href="/css/jquery.floatingscroll.css">
    <script src="/js/jquery.floatingscroll.min.js"></script>
@endsection

@section('content')
    <div class="container-fluid pt-5">
        <h3 class="text-primary text-center mt-3">История изменений параграфа №{{ $paragraph->paragraph }}</h3>
        @include('layout.changelog', ['items' => $audits, 'granularity' => new cogpowered\FineDiff\Granularity\Word])
    </div>

@endsection
