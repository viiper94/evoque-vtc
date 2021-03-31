@extends('layout.index')

@section('title')
    Статистика посещений за неделю | @lang('general.vtc_evoque')
@endsection

@section('content')

    <div class="container-fluid pt-5 members-table">
        <h2 class="mt-3 text-center text-primary">Статистика посещений за неделю</h2>
        <div class="table-responsive mb-5" data-fl-scrolls>
            <table class="table table-sm table-dark table-bordered table-hover">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Ник в игре</th>
                    <th scope="col">Конвоев<br>за неделю</th>
                    <th scope="col">@lang('attributes.scores')</th>
                    <th scope="col">@lang('attributes.money')</th>
                    <th scope="col">В отпуске<br>до</th>
                    <th scope="col">Исп.<br>отпусков</th>
                    <th scope="col">Должность</th>
                    <th scope="col">Имя</th>
                    <th scope="col">Ссылки</th>
                    <th scope="col">Дата<br>вступления</th>
                </tr>
                </thead>
                <tbody>
                @php $counter = 0 @endphp
                @php $lastConvoys = null @endphp
                @foreach($members as $member)
                    <tr>
                        <td>
                            {{ $lastConvoys === $member->convoys ? $counter : ++$counter }}
                            @php $lastConvoys = $member->convoys @endphp
                        </td>
                        <td class="font-weight-bold">{{ $member->nickname }}</td>
                        <td @if($member->convoys === 0 && !$member->onVacation(true))class="text-danger font-weight-bold" @endif>{{ $member->convoys }}</td>
                        <td>{{ $member->scores ?? '∞' }}</td>
                        <td>{{ $member->money ?? '∞' }}</td>
                        <td>{{ $member->on_vacation_till && !\Carbon\Carbon::parse($member->on_vacation_till['from'])->isFuture() ? \Carbon\Carbon::parse($member->on_vacation_till['to'])->isoFormat('DD.MM.Y') : '–' }}</td>
                        <td>{{ $member->vacations }}</td>
                        <td>
                            @foreach($member->role as $item)
                                {{ $item->title }}@if(!$loop->last),@endif
                            @endforeach
                        </td>
                        <td>
                            @can('update', \App\Member::class)
                                <a href="{{ route('evoque.profile', $member->user->id) }}" target="_blank">{{ $member->user->name }}</a>
                            @else
                                {{ $member->user->name }}
                            @endcan
                        </td>
                        <td class="icon-link">
                            @isset($member->user->vk)
                                <a href="{{ $member->user->vk }}" target="_blank" class="mr-3"><i class="fab fa-vk"></i></a>
                            @endisset
                            @isset($member->user->discord_id)
                                <a href="https://discordapp.com/users/{{ $member->user->discord_id }}" target="_blank" class="mr-3"><i class="fab fa-discord"></i></a>
                            @endisset
                            @isset($member->user->steamid64)
                                <a href="https://steamcommunity.com/profiles/{{ $member->user->steamid64 }}" target="_blank" class="mr-3"><i class="fab fa-steam-square"></i></a>
                            @endisset
                            @isset($member->user->truckersmp_id)
                                @can('seeBans', \App\Member::class)
                                    @if($member->isBanned())
                                        <a href="https://truckersmp.com/user/{{ $member->user->truckersmp_id }}"
                                           target="_blank" class="text-danger" data-toggle="popover" data-trigger="hover"
                                           data-content="Забанен {{ isset($member->tmp_banned_until) ? 'до '.$member->tmp_banned_until->isoFormat('DD.MM.YYYY, HH:MM') : 'перманентно!' }}">
                                            <i class="fas fa-truck-pickup"></i>
                                        </a>
                                    @else
                                        <a href="https://truckersmp.com/user/{{ $member->user->truckersmp_id }}" target="_blank">
                                            <i class="fas fa-truck-pickup"></i>
                                        </a>
                                    @endif
                                @else
                                    <a href="https://truckersmp.com/user/{{ $member->user->truckersmp_id }}" target="_blank">
                                        <i class="fas fa-truck-pickup"></i>
                                    </a>
                                @endcan
                            @endisset
                            @if(\Illuminate\Support\Facades\Auth::user()->can('update', \App\Member::class) ||
                                    \Illuminate\Support\Facades\Auth::user()->can('updateRpStats', \App\Member::class))
                                <a href="{{ route('evoque.admin.members.edit', $member->id) }}" class="ml-3"><i class="fas fa-user-edit"></i></a>
                            @endcan
                        </td>
                        <td>{{ !isset($member->join_date) ? '–' : $member->join_date->isoFormat('DD.MM.Y') }}</td>
                    </tr>
                @endforeach
                </tbody>
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Ник в игре</th>
                    <th scope="col">Конвоев<br>за неделю</th>
                    <th scope="col">@lang('attributes.scores')</th>
                    <th scope="col">@lang('attributes.money')</th>
                    <th scope="col">В отпуске<br>до</th>
                    <th scope="col">Исп.<br>отпусков</th>
                    <th scope="col">Должность</th>
                    <th scope="col">Имя</th>
                    <th scope="col">Ссылки</th>
                    <th scope="col">Дата<br>вступления</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>

@endsection
