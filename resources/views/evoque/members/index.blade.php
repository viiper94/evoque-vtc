@extends('evoque.layout.index')

@section('content')
<div class="container-fluid pt-5 members-table">
    @include('layout.alert')
    <h2 class="pt-3 text-center text-primary">Сотрудники ВТК EVOQUE</h2>
    <div class="table-responsive mb-5">
        <table class="table table-dark table-bordered table-hover">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Ник в игре</th>
                <th scope="col">Возраст</th>
                <th scope="col">Должность</th>
                <th scope="col">Баллы</th>
                <th scope="col">Эвики</th>
                <th scope="col">Конвоев<br>за<br>неделю</th>
                <th scope="col">В отпуске до</th>
                <th scope="col">Использовано<br>отпусков</th>
                <th scope="col">Номер</th>
                <th scope="col">Имя</th>
                <th scope="col">Город/Страна</th>
                <th scope="col">Ссылки</th>
                <th scope="col">Дата<br>вступления</th>
            </tr>
            </thead>
            <tbody>
            @php $i = 1; @endphp
            @foreach($roles as $role_group)
                @if(count($role_group[0]->members) < 1) @continue @endif
                <tr>
                    <th colspan="14" class="text-center text-primary">{{ $role_group[0]->group }}</th>
                </tr>
                @foreach($role_group[0]->members as $member)
                    <tr>
                        <td>{{ $i++ }}</td>
                        <td>{{ $member->nickname }}</td>
                        <td>{{ $member->user->birth_date ? $member->user->birth_date->age : '–' }}</td>
                        <td>
                            @foreach($member->role as $item)
                                {{ $item->title }}@if(!$loop->last),@endif
                            @endforeach
                        </td>
                        <td> @cannot('admin'){{ $member->scores }} @else {{ '∞' }} @endcannot </td>
                        <td>{{ $member->isOwner() ? '∞' : $member->money }}</td>
                        <td>{{ $member->convoys }}</td>
                        <td>{{ !isset($member->on_vacation_till) ? '–' : $member->on_vacation_till->isoFormat('LL') }}</td>
                        <td>{{ $member->vacations }}</td>
                        <td class="plate-img p-0"><img src="{{ $member->plate }}"></td>
                        <td><a href="{{ route('profile') }}/{{ $member->user->id }}" target="_blank">{{ $member->user->name }}</a></td>
                        <td>{{ $member->getPlace() }}</td>
                        <td class="icon-link">
                            @isset($member->user->vk_link)
                                <a href="{{ $member->user->vk_link }}" target="_blank" class="mr-3"><i class="fab fa-vk"></i></a>
                            @endisset
                            @isset($member->user->steam_link)
                                <a href="{{ $member->user->steam_link }}" target="_blank" class="mr-3"><i class="fab fa-steam-square"></i></a>
                            @endisset
                            @isset($member->user->tmp_link)
                                <a href="{{ $member->user->tmp_link }}" target="_blank"><i class="fas fa-truck-pickup"></i></a>
                            @endisset
                            @can('manage_members')
                                <a href="{{ route('evoque.admin.members.edit', $member->id) }}" class="ml-3"><i class="fas fa-user-edit"></i></a>
                            @endcan
                        </td>
                        <td>{{ !isset($member->join_date) ? '–' : $member->join_date->isoFormat('LL') }}</td>
                    </tr>
                @endforeach
            @endforeach
            </tbody>
        </table>
    </div>

</div>
@endsection