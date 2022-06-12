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
                    <th scope="col">
                        @can('create', \App\Role::class)
                            <a href="{{ route('evoque.admin.roles.add') }}"><i class="fas fa-plus"></i></a>
                        @endcan
                    </th>
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
    </div>

@endsection
