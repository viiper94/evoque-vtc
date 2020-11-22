@extends('layout.index')

@section('title')
        Скрин TAB | @lang('general.vtc_evoque')
@endsection

@section('content')

    <div class="container pt-5">
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
                <div class="card card-dark text-shadow-m col-md-5 m-3 px-0 @if($tab->status == 1)border-success @elseif($tab->status == 1) border-danger @else border-primary @endif">
                    <h5 class="card-header">
                        {{ $tab->convoy_title }}
                        @if($tab->status == 0)
                            <span class="badge badge-warning">Рассматривается</span>
                        @elseif($tab->status == 1)
                            <span class="badge badge-success">Принят</span>
                        @elseif($tab->status == 2)
                            <span class="badge badge-danger">Отклонён</span>
                        @endif
                    </h5>
                    <div class="card-body">
                        <h5>{{ $tab->date->isoFormat('LL') }}</h5>
                        <div class="card-text">
                            <p>
                                Ведущий: <b>{{ $tab->lead->nickname }}</b>
                            </p>
                            <p>{!! nl2br($tab->description) !!}</p>
                            <a href="/images/convoys/tab/{{ $tab->screenshot }}" target="_blank"><img class="w-100 text-shadow-m" src="/images/convoys/tab/{{ $tab->screenshot }}"></a>
                        </div>
                    </div>
                    <div class="card-actions">
                        @can('update', $tab)
                            <a href="{{ route('evoque.convoys.tab.edit', $tab->id) }}" class="btn btn-outline-warning my-1">Редактировать</a>
                        @endcan
                        @can('claim', $tab)
                            <a href="{{ route('evoque.admin.convoys.tab.accept', $tab->id) }}" class="btn btn-outline-warning my-1">Смотреть</a>
                        @endcan
                        @can('delete', \App\Tab::class)
                            <a href="{{ route('evoque.admin.convoys.tab.delete', $tab->id) }}" class="btn btn-outline-danger my-1"
                                onclick="return confirm('Удалить этот скрин TAB?')">Удалить</a>
                        @endcan
                    </div>
                </div>
            @endforeach
        </div>
    </div>

@endsection
