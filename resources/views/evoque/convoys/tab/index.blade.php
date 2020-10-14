@extends('layout.index')

@section('title')
        Скрин TAB | @lang('general.vtc_evoque')
@endsection

@section('assets')
    <link rel="stylesheet" type="text/css" href="/js/fotorama-4.6.4/fotorama.css">
    <script src="/js/fotorama-4.6.4/fotorama.js"></script>
@endsection

@section('content')

    <div class="container pt-5">
        @include('layout.alert')
        <h2 class="mt-3 text-primary text-center">Все скрин TAB</h2>
        @can('lead_convoys')
            <div class="row justify-content-center">
                <a href="{{ route('evoque.convoys.tab.add') }}" class="btn btn-outline-warning ml-3 mt-3 btn-lg"><i class="fas fa-plus"></i> Новый скрин TAB</a>
            </div>
        @endcan
        <div class="tabs mt-3 mb-5 row justify-content-around">
            @foreach($tabs as $tab)
                <div class="card card-dark text-shadow-m col-md-5 m-3 px-0 @if($tab->status == 0)border-primary @endif">
                    <h5 class="card-header">{{ $tab->convoy_title }}</h5>
                    <div class="card-body">
                        <h5>{{ $tab->date->isoFormat('LL') }}</h5>
                        <div class="card-text">
                            <p>
                                Ведущий: <b>{{ $tab->lead->nickname }}</b>
                            </p>
                            <p>{!! nl2br($tab->description) !!}</p>
                            <div class="fotorama w-100" data-allowfullscreen="true" data-fit="cover" data-nav="none">
                                <img src="/images/convoys/tab/{{ $tab->screenshot }}">
                            </div>
                        </div>
                    </div>
                    <div class="card-actions">
                        @if($tab->member_id === \Illuminate\Support\Facades\Auth::user()->member->id)
                            <a href="{{ route('evoque.convoys.tab.edit', $tab->id) }}" class="btn btn-outline-warning my-1"><i class="fas fa-edit"></i> Редактировать</a>
                        @endif
                        @can('manage_table')
                            <a href="{{ route('evoque.admin.convoys.tab.accept', $tab->id) }}" class="btn btn-outline-success my-1"><i class="fas fa-check"></i> Принять</a>
                        @endcan
                        <a href="{{ route('evoque.admin.convoys.tab.delete', $tab->id) }}" class="btn btn-outline-danger my-1"
                           onclick="return confirm('Удалить этот скрин TAB?')"><i class="fas fa-trash"></i> Удалить</a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

@endsection
