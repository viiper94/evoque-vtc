@extends('layout.index')

@section('title')
    Роли | @lang('general.vtc_evoque')
@endsection

@section('content')

    <div class="container pt-5 pb-5 roles">
        @include('layout.alert')
        <h2 class="mt-3 mb-3 text-primary text-center">Управление должностями</h2>
        <div class="table-responsive mb-3">
            <table class="table table-dark table-hover roles-table">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Название</th>
                    <th scope="col">Группа</th>
                    <th scope="col" class="text-center">Участников</th>
                    <th scope="col"></th>
                    <th scope="col"></th>
                </tr>
                </thead>
                <tbody>
                @foreach($roles as $role)
                    <tr>
                        <th scope="row">{{ $loop->iteration }}</th>
                        <td class="role-title">{{ $role->title }}</td>
                        <td class="">{{ $role->group }}</td>
                        <td class="text-center">{{ count($role->members) }}</td>
                        <td><i @class(['fas', 'fa-eye' => $role->visible, 'fa-eye-slash' => !$role->visible])></i></td>
                        <td>
                            @can('update', \App\Role::class)
                                <a href="{{ route('evoque.admin.roles.edit', $role->id) }}"><i class="fas fa-pen"></i></a>
                            @endcan
                            @can('delete', \App\Role::class)
                                <a href="{{ route('evoque.admin.roles.delete', $role->id) }}"
                                   onclick="return confirm('Все участники потеряют эту роль. Точно хотите удалить?')">
                                    <i class="fas fa-trash-alt"></i>
                                </a>
                            @endcan
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        @can('create', \App\Role::class)
            <div class="row justify-content-center">
                <a data-toggle="modal" data-target="#role-modal" class="btn btn-outline-warning"><i class="fas fa-plus-square"></i> Новая роль</a>
            </div>
        @endcan
    </div>

    @can('create', \App\Role::class)
        <!-- Add role modal -->
        <div class="modal fade" id="role-modal" tabindex="-1" aria-labelledby="Create role" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
                <div class="modal-content modal-content-dark">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Новая должность</h5>
                        <button type="button" class="close text-shadow" data-dismiss="modal" aria-label="Close">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form method="post" action="{{ route('evoque.admin.roles.add') }}">
                            @csrf
                            <div class="form-group">
                                <label for="title">Название должности</label>
                                <input type="text" class="form-control" id="title" name="title" required>
                            </div>
                            <div class="form-group">
                                <label for="group">Группа должности</label>
                                <input type="text" class="form-control" id="group" name="group" required>
                            </div>
                            <div class="form-group">
                                <label for="description">Описание</label>
                                <input type="text" class="form-control" id="description" name="description">
                            </div>
                            <div class="custom-control custom-checkbox mb-3">
                                <input type="checkbox" class="custom-control-input" id="visible" name="visible" checked>
                                <label class="custom-control-label" for="visible">Показывать эту роль в таблице</label>
                            </div>
                            <button type="submit" class="btn btn-outline-warning">Сохранить</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endcan

@endsection
