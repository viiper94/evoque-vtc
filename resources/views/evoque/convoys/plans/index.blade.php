@extends('layout.index')

@section('title')
    Планы по конвоям | @lang('general.vtc_evoque')
@endsection

@section('content')

<div class="container plans py-5">
    <h1 class="mt-3 text-primary text-center">Планы по конвоям</h1>
    <div class="table-responsive">
        <table class="table table-dark table-hover text-center">
            <thead>
            <tr>
                <th class="text-right">День</th>
                <th>Дата</th>
                <th class="text-left">Конвои</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @foreach($days as $day)
                <tr>
                    <td class="text-right">{{ $day['date']->isoFormat('dddd') }}</td>
                    <td>{{ $day['date']->format('d.m') }}</td>
                    <td class="w-50 text-left">
                            @if(count($day['convoys']) > 0)
                                @foreach($day['convoys'] as $convoy)
                                    <p class="mb-0">{{ $convoy->start_time->format('H:i') }} - {{ $convoy->title }} @if($convoy->lead !== 'На месте разберёмся') (ведёт <b>{{ $convoy->lead }}</b>) @endif</p>
                                @endforeach
                            @else
                                <p class="mb-0">Свободно</p>
                            @endif
                    </td>
                    <td>
                        <a href="#" class="btn btn-outline-warning btn-sm @if($day['allowedToBook'] === 0) disabled @endif">Забронировать конвой</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection
