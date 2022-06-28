@extends('layout.index')

@section('title')
    Редактирование роли | @lang('general.vtc_evoque')
@endsection

@section('content')

    <div class="container pt-5 pb-5">
        @include('layout.alert')
        @can($role->id !== false ? 'update' : 'create', \App\Role::class)
            <h3 class="mt-3 text-primary">Редактирование должности</h3>
            <form method="post">
                @csrf
                <div class="form-group">
                    <label for="title">Название должности</label>
                    <input type="text" class="form-control" id="title" name="title" value="{{ $role->title }}" required>
                </div>
                <div class="form-group">
                    <label for="group">Группа должности</label>
                    <input type="text" class="form-control" id="group" name="group" value="{{ $role->group }}" required>
                </div>
                <div class="form-group">
                    <label for="description">Описание</label>
                    <input type="text" class="form-control" id="description" name="description" value="{{ $role->description }}">
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="min_scores">Минимальное количество баллов (включительно)</label>
                        <input type="number" class="form-control" id="min_scores" name="min_scores" value="{{ $role->min_scores }}">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="max_scores">Максимальное количество баллов (включительно)</label>
                        <input type="number" class="form-control" id="max_scores" name="max_scores" value="{{ $role->max_scores }}">
                    </div>
                </div>
                <div class="custom-control custom-checkbox mb-3">
                    <input type="checkbox" class="custom-control-input" id="visible" name="visible" @if($role->visible) checked @endif>
                    <label class="custom-control-label" for="visible">Показывать эту роль в таблице</label>
                </div>
                <button type="submit" class="btn btn-outline-warning">Сохранить</button>
            </form>
        @endcan
        @if(!is_null($role->id))
            @can('updatePermissions', $role)
                <hr class="border-primary py-3">
                <form action="{{ route('evoque.admin.roles.editPermissions', $role->id) }}" method="post">
                    @csrf
                    <h1 class="text-primary mb-3">Права должности на сайте</h1>
                    <button type="submit" class="btn btn-outline-warning">Сохранить права</button>
                    <div class="row mt-3">
                        @foreach(\App\Role::$permission_list as $category => $perms)
                            <div class="col-md-6 mb-3">
                                <div class="card card-dark">
                                    <h5 class="card-header text-primary">@lang('roles.'.$category)</h5>
                                    <div class="card-body">
                                        @foreach($perms as $item)
                                            <div @class(['custom-control custom-checkbox', 'mb-4' => $loop->index === 0])>
                                                <input type="checkbox" class="custom-control-input" id="{{ $item }}" name="{{ $item }}" @checked($role->$item)>
                                                <label @class(['custom-control-label', 'text-danger' => $loop->index === 0]) for="{{ $item }}">@lang('roles.'.$item)</label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                            </div>
                        @endforeach
                    </div>
                    <button type="submit" class="btn btn-outline-warning">Сохранить права</button>
                </form>
            @endcan
        @endif
    </div>

@endsection
