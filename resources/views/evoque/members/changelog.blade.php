@extends('layout.index')

@section('title')
    История изменения сотрудника | @lang('general.vtc_evoque')
@endsection

@section('content')

    <div class="container pt-5">
        @include('layout.changelog', ['items' => $member->audits])
    </div>

@endsection
