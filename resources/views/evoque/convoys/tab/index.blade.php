@extends('layout.index')

@section('title')
        Скрин TAB | @lang('general.vtc_evoque')
@endsection

@section('content')

    <div class="container-fluid pt-5">
        @include('layout.alert')
        @can('viewAny', \App\Tab::class)
            <h2 class="mt-3 text-primary text-center">Все скрин TAB</h2>
        @else
            <h2 class="mt-3 text-primary text-center">Мои скрин TAB</h2>
        @endcan
        @can('create', \App\Tab::class)
            <div class="row justify-content-center">
                <a href="{{ route('evoque.convoys.tab.add') }}" class="btn btn-outline-warning ml-3 mt-3 btn-lg"><i class="fas fa-plus"></i> Новый скрин TAB</a>
            </div>
        @endcan
        <div class="tabs mt-3 mb-5 row justify-content-around">
            @foreach($tabs as $tab)
                <div class="card card-dark text-shadow-m col-auto m-3 px-0 @if($tab->status == 1)border-success @elseif($tab->status == 2) border-danger @else border-primary @endif">
                    <div class="card-header row mx-0">
                        <div class="col tab-title px-0">
                            <h5 class="mb-0">
                                @if($tab->status == 0)
                                    <i class="fas fa-arrow-alt-circle-up text-warning"></i>
                                @elseif($tab->status == 1)
                                    <i class="fas fa-check-circle text-success"></i>
                                @elseif($tab->status == 2)
                                    <i class="fas fa-times-circle text-danger"></i>
                                @endif
                                @can('claim', $tab)
                                        <a href="{{ route('evoque.admin.convoys.tab.accept', $tab->id) }}">{{ $tab->convoy_title }}</a>
                                @elsecan('update', $tab)
                                    <a href="{{ route('evoque.convoys.tab.edit', $tab->id) }}">{{ $tab->convoy_title }}</a>
                                @else
                                    {{ $tab->convoy_title }}
                                @endcan
                            </h5>
                        </div>
                        @if(\Illuminate\Support\Facades\Auth::user()->can('update', $tab) ||
                                \Illuminate\Support\Facades\Auth::user()->can('claim', $tab) ||
                                \Illuminate\Support\Facades\Auth::user()->can('delete', \App\Tab::class))
                            <div class="dropdown dropdown-dark col-auto px-0 dropleft">
                                <button class="btn dropdown-toggle no-arrow py-0" type="button" id="dropdownMenuButton"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <div class="dropdown-menu text-shadow-m" aria-labelledby="dropdownMenuButton">
                                    @can('update', $tab)
                                        <a href="{{ route('evoque.convoys.tab.edit', $tab->id) }}" class="dropdown-item"><i class="fas fa-pen"></i> Редактировать</a>
                                    @endcan
                                    @can('claim', $tab)
                                        <a href="{{ route('evoque.admin.convoys.tab.accept', $tab->id) }}" class="dropdown-item"><i class="fas fa-eye"></i> Смотреть</a>
                                    @endcan
                                    @can('delete', \App\Tab::class)
                                        <a href="{{ route('evoque.admin.convoys.tab.delete', $tab->id) }}"
                                           class="dropdown-item" onclick="return confirm('Удалить этот скрин таба?')"><i class="fas fa-trash"></i> Удалить</a>
                                    @endcan
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="card-body">
                        @if($tab->comment)
                            <p class="mb-0 text-danger">Комментарий от администратора: </p>
                            <div class="markdown-content">
                                @markdown($tab->comment)
                            </div>
                        @endif
                        <h5>{{ $tab->date->isoFormat('LL') }}</h5>
                        <div class="card-text">
                            <p>
                                Ведущий: <b>{{ $tab->lead->nickname }}</b>
                            </p>
                            <p class="text-primary markdown-content">{!! nl2br($tab->description) !!}</p>
                        </div>
                    </div>
                    <a href="/images/convoys/tab/{{ $tab->screenshot }}" target="_blank"><img class="w-100 text-shadow-m" src="/images/convoys/tab/{{ $tab->screenshot }}"></a>
                </div>
            @endforeach
        </div>
    </div>

@endsection
