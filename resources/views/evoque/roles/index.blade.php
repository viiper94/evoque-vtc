@extends('layout.index')

@section('title')
    Роли | @lang('general.vtc_evoque')
@endsection

@section('content')

    <div class="container-fliud pt-5 pb-5 roles">
        <h2 class="mt-3 mb-3 text-primary text-center">Управление ролями</h2>
        <div class="table-responsive mb-3">
            <table class="table table-dark table-hover roles-table">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Название</th>
                    <th scope="col">Группа</th>
                    <th scope="col">Участников</th>
                    <th scope="col">Права</th>
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
                        <td>{{ count($role->members) }}</td>
                        <td>
                            <i class="icon-evoque @if($role->admin)active @endif"></i>
                            <i class="fas fa-users-cog @if($role->manage_members)active @endif"></i>
                            <i class="fas fa-route @if($role->manage_convoys)active @endif"></i>
                            <i class="fas fa-table @if($role->manage_table)active @endif"></i>
                            <i class="fas fa-tasks @if($role->manage_rp)active @endif"></i>
                            <i class="fas fa-truck-moving @if($role->do_rp)active @endif"></i>
                        </td>
                        <td>
                            @if($role->visible)
                                <i class="fas fa-eye"></i>
                            @else
                                <i class="fas fa-eye-slash"></i>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('evoque.admin.roles.edit', $role->id) }}"><i class="fas fa-pen-square"></i></a>
                            <a href="{{ route('evoque.admin.roles.delete', $role->id) }}" onclick="return confirm('Все участники потеряют эту роль. Точно хотите удалить?')"><i class="fas fa-trash-alt"></i></a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="row justify-content-center">
            <a href="{{ route('evoque.admin.roles.add') }}" class="btn btn-outline-warning btn-lg"><i class="fas fa-plus-square"></i> Новая роль</a>
        </div>
    </div>

@endsection
