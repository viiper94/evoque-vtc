@extends('layout.index')

@section('title')
    Планы по конвоям | @lang('general.vtc_evoque')
@endsection

@section('content')

<div class="container py-5">
    <h1 class="mt-3 text-primary text-center">Планы по конвоям</h1>
    <div class="table-responsive">
        <table class="table table-dark table-hover text-center">
            <thead>
            <tr>
                <th>День</th>
                <th>Дата</th>
                <th>Конвои</th>
            </tr>
            </thead>
            <tbody>
            @foreach($days as $day)
                <tr>
                    <td>{{ $day->isoFormat('dddd') }}</td>
                    <td>{{ $day->format('d.m') }}</td>
                    <td class="w-75"></td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection
