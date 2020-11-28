@extends('layout.index')

@section('title')
    Правила @lang('general.vtc_evoque')
@endsection

@section('content')

<div class="container pt-5">
    @include('layout.alert')
    <ul class="rules pt-5">
        @foreach($rules as $paragraph)
            <li class="p-1 row">
                <h1 class="paragraph-number display-2 pr-5 pl-sm-1">§{{ $paragraph->paragraph }} </h1>
                <h1 class="paragraph-title pl-md-5 pl-sm-1">{{ $paragraph->title }}
                </h1>
                <blockquote class="blockquote pl-md-5">
                    @markdown($paragraph->text)
                    <p>
                        @can('update', \App\Rules::class)
                            <a href="{{ route('evoque.rules.edit', $paragraph->id) }}" class="btn btn-outline-primary"><i class="fas fa-edit"></i> Редактировать</a>
                        @endcan
                        @can('viewChangelog', \App\Rules::class)
                            @if(count($paragraph->audits) > 0)
                                <a href="{{ route('evoque.rules.changelog', $paragraph->id) }}" class="btn btn-outline-info"><i class="fas fa-history"></i></a>
                            @endif
                        @endcan
                    </p>
                </blockquote>
            </li>
        @endforeach
        @if($public)
            <li class="p-end row mb-5">
                <h1 class="paragraph-number display-2 pr-5 pl-sm-1"></h1>
                <p class="ml-md-5">
                    Данные правила предназначены только для ознакомления и не несут никакой правовой или юридической формы. <br>
                    Любое копирование только с разрешения руководства Виртуальной Транспортной Компании EVOQUE. <br>
                    Данные правила обязаны соблюдать все сотрудники ВТК EVOQUE и лица намеривающие вступить в ВТК. <br>
                    Полная версия правил будет доступна после вступления в ВТК и авторизации на сайте.
                </p>
            </li>
        @endif
    </ul>
    @can('admin')
        <div class="row text-center justify-content-center">
            <a href="{{ route('evoque.rules.add') }}" class="mb-3 btn btn-lg btn-outline-warning"><i class="fas fa-plus"></i> Добавить параграф правил</a>
        </div>
    @endcan
</div>

@endsection
