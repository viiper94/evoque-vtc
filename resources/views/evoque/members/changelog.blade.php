@extends('layout.index')

@section('title')
    История изменения сотрудника | @lang('general.vtc_evoque')
@endsection

@section('content')

    @include('layout.changelog', ['items' => $member->audits])

@endsection
