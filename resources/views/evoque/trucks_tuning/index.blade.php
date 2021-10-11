@extends('layout.index')

@section('title')
    Официальные тюнинги тягачей | @lang('general.vtc_evoque')
@endsection

@section('content')

    <div class="container pt-5">
        @include('layout.alert')
        <h2 class="mt-3 text-primary ml-3 text-center">Официальные тюнинги тягачей</h2>
        @can('create', \App\RpReport::class)
            <div class="row justify-content-center">
                <a href="{{ route('evoque.tuning.add') }}" class="btn btn-outline-warning mt-3 btn-lg"><i class="fas fa-plus"></i> Добавить</a>
            </div>
        @endcan
        <form method="get">
            <div class="row justify-content-center py-3">
                <div class="input-group col-md-6 col-lg-4">
                    <div class="input-group-prepend">
                        <button type="submit" class="btn btn-primary box-shadow-none"><i class="fas fa-search"></i></button>
                    </div>
                    <input type="text" class="form-control box-shadow-none" name="q" placeholder="Поиск" value="{{ Request::input('q') }}">
                    @if(Request::input('q'))
                        <div class="input-group-append">
                            <a href="{{ route('evoque.tuning') }}" class="btn btn-outline-primary box-shadow-none"><i class="fas fa-times"></i></a>
                        </div>
                    @endif
                </div>
            </div>

        </form>
        <div class="tunings pt-3 pb-5 row justify-content-around align-items-baseline">
            @if(count($tunings) > 0)
                @foreach($tunings as $tuning)
                    <div class="card card-dark col-12 col-md-4 text-shadow-m m-3 p-0">
                        <div class="card-header row mx-0">
                            <div class="col row rp-title">
                                <h5 class="mb-0">{{ $tuning->vendor }} {{ $tuning->model }}</h5>
                            </div>
                            <h5 class="col-auto text-md-right text-muted mb-0">{{ strtoupper($tuning->game) }}</h5>
{{--                            @if(\Illuminate\Support\Facades\Auth::user()->can('decline', $report) ||--}}
{{--                                        \Illuminate\Support\Facades\Auth::user()->can('delete', $report))--}}
                                <div class="dropdown dropdown-dark col-auto px-0 dropleft">
                                    <button class="btn dropdown-toggle no-arrow py-0" type="button" id="dropdownMenuButton"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <div class="dropdown-menu text-shadow-m" aria-labelledby="dropdownMenuButton">
{{--                                        @can('decline', $report)--}}
                                            <a href="{{ route('evoque.tuning.edit', $tuning->id) }}" class="dropdown-item"><i class="fas fa-pen"></i> Редактировать</a>
{{--                                        @endcan--}}
{{--                                        @can('delete', $report)--}}
                                            <a href="{{ route('evoque.tuning.delete', $tuning->id) }}"
                                               class="dropdown-item" onclick="return confirm('Удалить тюнинг?')"><i class="fas fa-trash"></i> Удалить</a>
{{--                                        @endcan--}}
                                    </div>
                                </div>
{{--                            @endif--}}
                        </div>
                        <div class="card-body p-0">
                            <a href="/images/tuning/{{ $tuning->image }}" target="_blank">
                                <img src="/images/tuning/{{ $tuning->image }}" alt="{{ $tuning->vendor }} {{ $tuning->model }}" class="w-100">
                            </a>
                        </div>
                        @if($tuning->description)
                            <div class="card-body">
                                {{ $tuning->description }}
                            </div>
                        @endif
                        <div class="card-footer text-muted">
                            {{ $tuning->created_at->isoFormat('LLL') }}
                        </div>
                    </div>
                @endforeach
            @else
                <h5 class="text-center">
                    Еще не создано тюнингов
                </h5>
            @endif
        </div>
    </div>

@endsection
