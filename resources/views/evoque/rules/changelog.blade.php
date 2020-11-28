@extends('layout.index')

@section('title')
    История изменения параграфа | @lang('general.vtc_evoque')
@endsection

@section('content')

    <div class="container pt-5">
        @foreach($changelog as $item)
            <div class="changelog-item mt-5 mb-5">
                <h3 class="text-primary">{{ $item->created_at->format('d.m.Y H:i') }}</h3>
                @can('update', \App\Rules::class)
                    <h4>Отредактировал {{ $item->user->member->nickname }} </h4>
                @endcan
                <div class="table-responsive">
                    <table class="table table-dark table-bordered table-hover">
                        <thead>
                        <tr>
                            <th scope="col">Параметр</th>
                            <th scope="col">Было</th>
                            <th scope="col">Стало</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($item->old_values as $key => $value)
                            <tr>
                                <td>{{ $key }}</td>
                                <td>{!! $value !!}</td>
                                <td>{!! $item->new_values[$key] !!}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endforeach
    </div>

@endsection
