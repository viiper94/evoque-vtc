@extends('layout.index')

@section('title')
    Уволенные сотрудники | @lang('general.vtc_evoque')
@endsection

@section('content')

    <div class="container-fluid pt-5">
        @include('layout.alert')
        <h2 class="mt-3 text-center text-primary">Уволенные сотрудники</h2>
        <div class="table-responsive mb-5">
            <table class="users table table-dark table-hover text-center"><thead>
                <tr>
                    <th scope="col">Ник</th>
                    <th scope="col">Имя</th>
                    <th scope="col">VK</th>
                    <th scope="col">SteamID64</th>
                    <th scope="col">TruckersMP ID</th>
                    <th scope="col">В компании с</th>
                    <th scope="col">На сайте с</th>
                    <th scope="col">Уволен</th>
                    <th scope="col">#</th>
                </tr>
                </thead>
                <tbody>
                @foreach($members as $user)
                    <tr>
                        <td class="user-image"><img src="{{ $user->image }}"></td>
                        <td><a href="{{ route('evoque.profile', $user->id) }}">{{ $user->name }}</td>
                        <td><a href="{{ $user->vk }}" target="_blank">{{ $user->vk }}</a></td>
                        <td><a href="https://steamcommunity.com/profiles/{{ $user->steamid64 }}" target="_blank">{{ $user->steamid64 }}</a></td>
                        <td><a href="https://truckersmp.com/user/{{ $user->truckersmp_id }}" target="_blank">{{ $user->truckersmp_id }}</a></td>
                        <td>
                            @if($user->member)
                                @if($user->member->visible)
                                    @can('update', \App\Member::class)
                                        <a href="{{ route('evoque.admin.members.edit', $user->member->id) }}"><i class="fontello icon-evoque text-primary"></i></a>
                                    @else
                                        <i class="fas fa-check"></i>
                                    @endcan
                                @else
                                    @can('update', \App\Member::class)
                                        <a href="{{ route('evoque.admin.members.edit', $user->member->id) }}"><i class="fontello icon-evoque text-danger"></i></a>
                                    @else
                                        <i class="fas fa-times text-danger"></i>
                                    @endcan
                                @endif
                            @else
                                <i class="fas fa-times text-danger"></i>
                            @endif
                        </td>
                        <td>{{ $user->created_at->isoFormat('DD.MM.Y HH:mm') }}</td>
                        <td>{{ !$user->fired_at ? '-' : $user->fired_at->isoFormat('DD.MM.Y HH:mm') }}</td>
                        <td>
                            @can('setAsMember', $user)
                                <a href="{{ route('evoque.admin.users.setAsMember', $user->id) }}" onclick="return confirm('Восстановить сотрудника?')"><i class="fas fa-undo"></i></a>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

@endsection
