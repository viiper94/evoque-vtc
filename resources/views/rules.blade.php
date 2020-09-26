@extends('layout.index')

@section('content')

<div class="container pt-5">
    @include('layout.alert')
    <ul class="rules pt-5">
        @foreach($rules as $paragraph)
            <li class="p-1 row">
                <h1 class="paragraph-number display-2 pr-5 pl-sm-1">§{{ $paragraph->paragraph }} </h1>
                <h1 class="paragraph-title pl-md-5 pl-sm-1">{{ $paragraph->title }}
                    @can('admin')
                        <a href="{{ route('evoque.rules.edit', $paragraph->id) }}" class="btn btn-sm text-primary"><i class="fas fa-edit"></i></a>
                    @endcan
                </h1>
                <blockquote class="blockquote">
                    <p class="ml-md-5">
                        {!! $paragraph->text !!}
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
                    Полная версия правил доступна после вступления в закрытой группе ВТК EVOQUE.
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
