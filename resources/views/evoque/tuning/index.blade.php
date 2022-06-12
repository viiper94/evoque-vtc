@extends('layout.index')

@section('title')
    Официальный тюнинг | @lang('general.vtc_evoque')
@endsection

@section('content')

    <div class="container pt-5">
        @include('layout.alert')
        <h2 class="mt-3 text-primary ml-3 text-center">Официальный тюнинг</h2>
        <div class="row justify-content-center py-3">
            @foreach($vendors as $vendor => $tunings)
                <a href="#{{ $vendor }}-tuning" @class([
                    'btn mx-2',
                    'btn-outline-primary' => $tunings[0]->type === 'truck',
                    'btn-outline-info' => $tunings[0]->type === 'trailer'
                ])>{{ $tunings[0]->type === 'truck' ? $vendor : 'Прицеп' }}</a>
            @endforeach
            @can('add', \App\Tuning::class)
                <a href="{{ route('evoque.tuning.add') }}" class="btn btn-outline-secondary mx-2"><i class="fas fa-plus"></i> Добавить</a>
            @endcan
        </div>
        @if(count($vendors) > 0)
            @foreach($vendors as $vendor => $tunings)
                <div class="vendor row nowrap">
                    <div id="{{ $vendor }}-tuning" @class([
                            'tunings mb-5 col row justify-content-around align-items-baseline border-right',
                            'border-primary' => $tunings[0]->type === 'truck',
                            'border-info' => $tunings[0]->type === 'trailer',
                        ])>
                        @foreach($tunings as $tuning)
                            <div class="card-wrapper col-12 col-md-6 my-2">
                                <div class="card card-dark text-shadow-m p-0">
                                    <div class="card-header row mx-0">
                                        <div class="col row rp-title">
                                            <h5 class="mb-0">{{ $tuning->vendor }} {{ $tuning->model }}</h5>
                                        </div>
                                        <h5 class="col-auto text-md-right text-muted mb-0">{{ strtoupper($tuning->game) }}</h5>
                                        @if(\App\User::orCan(['edit', 'delete'], \App\Tuning::class))
                                            <div class="dropdown dropdown-dark col-auto px-0 dropleft">
                                                <button class="btn dropdown-toggle no-arrow py-0" type="button" id="dropdownMenuButton"
                                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </button>
                                                <div class="dropdown-menu text-shadow-m" aria-labelledby="dropdownMenuButton">
                                                    @can('edit', \App\Tuning::class)
                                                        <a href="{{ route('evoque.tuning.edit', [$tuning->type, $tuning->id]) }}" class="dropdown-item"><i class="fas fa-pen"></i> Редактировать</a>
                                                    @endcan
                                                    @can('delete', \App\Tuning::class)
                                                        <a href="{{ route('evoque.tuning.delete', $tuning->id) }}"
                                                           class="dropdown-item" onclick="return confirm('Удалить тюнинг?')"><i class="fas fa-trash"></i> Удалить</a>
                                                    @endcan
                                                </div>
                                            </div>
                                        @endif
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
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <h3 @class(['text-center col-auto',
                            'text-primary' => $tunings[0]->type === 'truck',
                            'text-info' => $tunings[0]->type === 'trailer',
                        ]) style="writing-mode: vertical-lr">{{ $tunings[0]->type === 'truck' ? $vendor : 'Прицеп' }}</h3>
                </div>
            @endforeach
        @else
            <div class="pt-3 pb-5 row justify-content-around align-items-baseline">
                <h5 class="text-center">
                    Еще не создано тюнингов
                </h5>
            </div>
        @endif
    </div>

@endsection
