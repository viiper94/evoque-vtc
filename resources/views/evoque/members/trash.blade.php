@extends('layout.index')

@section('title')
    Уволенные сотрудники | @lang('general.vtc_evoque')
@endsection

@section('content')

    <div class="container-fluid pt-5">
        @include('layout.alert')
        <h2 class="mt-3 text-center text-primary">Уволенные сотрудники</h2>
        <div class="row justify-content-between">
            <form method="get" class="col">
                <div class="input-group mb-3">
                    <input type="text" class="form-control" placeholder="Поиск по никнейму" name="q" style="max-width: 400px">
                </div>
            </form>
            <div class="col-auto mr-3">
                {{ $members->links('layout.pagination') }}
            </div>
        </div>

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
                    <th scope="col">С восстановлением</th>
                    <th scope="col">#</th>
                </tr>
                </thead>
                <tbody>
                @foreach($members as $member)
                    <tr>
                        <td><a href="{{ route('evoque.admin.members.edit', $member->id) }}">{{ $member->nickname }}</td>
                        <td><a href="{{ route('evoque.profile', $member->user->id) }}">{{ $member->user->name }}</td>
                        <td><a href="{{ $member->user->vk }}" target="_blank">{{ $member->user->vk }}</a></td>
                        <td><a href="https://steamcommunity.com/profiles/{{ $member->user->steamid64 }}" target="_blank">{{ $member->user->steamid64 }}</a></td>
                        <td><a href="https://truckersmp.com/user/{{ $member->user->truckersmp_id }}" target="_blank">{{ $member->user->truckersmp_id }}</a></td>
                        <td>{{ $member->join_date->isoFormat('DD.MM.Y') }}</td>
                        <td>{{ $member->user->created_at->isoFormat('DD.MM.Y HH:mm') }}</td>
                        <td>{{ $member->deleted_at->isoFormat('DD.MM.Y HH:mm') }}</td>
                        <td>@if($member->restore) <span class="text-success">С восстановлением</span> @endif</td>
                        <td><a href="{{ route('evoque.admin.members.restore', $member->id) }}" onclick="return confirm('Восстановить сотрудника?')"><i class="fas fa-undo"></i></a></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

@endsection
