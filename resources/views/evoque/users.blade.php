@extends('evoque.layout.index')

@section('content')

    <div class="container-fluid pt-5">
        @include('layout.alert')
        <h2 class="pt-3 text-center text-primary">Все пользователи</h2>
        <table class="users table table-dark table-hover"><thead>
            <tr>
                <th scope="col">Ава</th>
                <th scope="col">Имя</th>
                <th scope="col">Город</th>
                <th scope="col">Страна</th>
                <th scope="col">Дата рождения</th>
                <th scope="col">SteamID64</th>
                <th scope="col">TruckersMP ID</th>
                <th scope="col">Эвок</th>
                <th scope="col">На сайте с</th>
                <th scope="col">#</th>
            </tr>
            </thead>
            <tbody>
            @foreach($users as $user)
                <tr>
                    <td class="user-image"><img src="{{ $user->image }}"></td>
                    <td><a href="{{ route('profile') }}/{{ $user->id }}">{{ $user->name }}</td>
                    <td>{{ $user->city }}</td>
                    <td>{{ $user->country }}</td>
                    <td>{{ !isset($user->birth_date) ? '' : $user->birth_date->isoFormat('LL') }}</td>
                    <td><a href="https://steamcommunity.com/profiles/{{ $user->steamid64 }}" target="_blank">{{ $user->steamid64 }}</a></td>
                    <td><a href="https://truckersmp.com/user/{{ $user->truckersmp_id }}" target="_blank">{{ $user->truckersmp_id }}</a></td>
                    <td><i class="fas fa-{{ $user->member ? 'check' : 'times' }}"></i></td>
                    <td>{{ $user->created_at->isoFormat('LLL') }}</td>
                    <td>
                        @if(!$user->member)
                            <a href="{{ route('evoque.admin.users.setAsMember', $user->id) }}" onclick="return confirm('НАзначить этого юзера сотрудником Эвок?')"><i class="fas fa-user-plus"></i></a>
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

@endsection