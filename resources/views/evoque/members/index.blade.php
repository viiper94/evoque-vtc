@extends('evoque.layout.index')

@section('content')
<div class="container-fluid pt-5 members">
    @include('layout.alert')
    <h2 class="pt-3 text-center">Сотрудники ВТК EVOQUE</h2>
    <table class="table table-dark">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Ник в игре</th>
            <th scope="col">Возраст</th>
            <th scope="col">Должность</th>
            <th scope="col">Баллы</th>
            <th scope="col">Эвики</th>
            <th scope="col">Конвоев за неделю</th>
            <th scope="col">Посетить конвой до</th>
            <th scope="col">В отпуске до</th>
            <th scope="col">Использовано отпусков</th>
            <th scope="col">ВКонтакте</th>
            <th scope="col">Номер</th>
            <th scope="col">Город / Страна</th>
            <th scope="col">Steam</th>
            <th scope="col">TruckersMP</th>
            <th scope="col">Дата вступления</th>
        </tr>
        </thead>
        <tbody>
        @foreach($roles as $role_group)
            @if(count($role_group[0]->members) < 1) @continue @endif
            <tr>
                <th colspan="16" class="text-center text-primary">{{ $role_group[0]->group }}</th>
            </tr>
            @foreach($role_group[0]->members as $member)
                <tr>
                    <td></td>
                    <td>{{ $member->nickname }}</td>
                    <td>{{ $member->user->birth_date->age }}</td>
                    <td>
                        @foreach($member->role as $item)
                            {{ $item->title }}@if(!$loop->last),@endif
                        @endforeach
                    </td>
                    <td>{{ $member->scores }}</td>
                    <td>{{ $member->money }}</td>
                    <td>{{ $member->convoys }}</td>
                    <td></td>
                    <td>{{ !isset($member->on_vacation_till) ? '' : $member->on_vacation_till->isoFormat('LL') }}</td>
                    <td>{{ $member->vacations }}</td>
                    <td><a href="{{ $member->user->vk_link }}" target="_blank">{{ $member->user->name }}</a></td>
                    <td></td>
                    <td>{{ $member->user->city }} {{ $member->user->country }}</td>
                    <td><a href="{{ $member->user->steam_link }}" target="_blank"><i class="fab fa-steam-square"></i></a></td>
                    <td><a href="{{ $member->user->tmp_link }}" target="_blank"><i class="fas fa-truck-pickup"></i></a></td>
                    <td>{{ !isset($member->join_date) ? '' : $member->join_date->isoFormat('LL') }}</td>
                </tr>
            @endforeach
        @endforeach
        </tbody>
    </table>
</div>
@endsection
