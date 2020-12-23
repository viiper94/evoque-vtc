@extends('layout.index')

@section('title')
    Мой профиль | @lang('general.vtc_evoque')
@endsection

@section('content')

    <div class="container pt-5 pb-5 profile">
        @include('layout.alert')
        <div class="row mt-5">
            <div class="col-sm-6 avatar text-md-right text-center pr-sm-5 pt-3 pb-3">
                <img src="{{ $user->image }}" alt="{{ $user->name }}" class="text-shadow-m">
                @if(\Illuminate\Support\Facades\Auth::user()->id === $user->id)
                    <div class="mt-2">
                        <a href="{{ route('evoque.profile.edit') }}" class="btn btn-sm btn-outline-info"><i class="fas fa-edit"></i> Редактировать профиль</a>
                    </div>
                @endif
                @can('setAsMember')
                    @if(!$user->member)
                        <div class="mt-2">
                            <a href="{{ route('evoque.admin.users.setAsMember', $user->id) }}" class="btn btn-sm btn-outline-danger"
                               onclick="return confirm('Назначить этого юзера сотрудником Эвок?')"><i class="fas fa-user-plus"></i> Сделать сотрудником</a>
                        </div>
                    @endif
                @endcan
            </div>
            <div class="col-sm-6 info pl-sm-5 pt-3 pb-3 text-center text-sm-left">
                <h1 class="text-primary">{{ $user->name }}</h1>
                @if($user->member)
                    <h3>[EVOQUE] {{ $user->member->nickname }}</h3>
                @endif
                @if($user->city)
                    <h6 class="pt-4">Город:</h6>
                    <h5><b>{{ $user->city }}</b></h5>
                @endif
                @if($user->country)
                    <h6>Страна:</h6>
                    <h5><b>{{ $user->country }}</b></h5>
                @endif
                @if($user->birth_date)
                    <h6 class="pt-4">Дата рождения:</h6>
                    <h5><b>{{ $user->birth_date->isoFormat('LL') }}</b></h5>
                    <h5><b>{{ $user->birth_date->age }} {{ trans_choice('год|года|лет', $user->birth_date->age) }}</b></h5>
                @endif
                <div class="user-links pt-4">
                    <a href="{{ $user->vk }}" target="_blank"><i class="fab fa-vk"></i></a>
                    <a href="https://steamcommunity.com/profiles/{{ $user->steamid64 }}" target="_blank" class="ml-2"><i class="fab fa-steam-square"></i></a>
                    <a href="https://truckersmp.com/user/{{ $user->truckersmp_id }}" target="_blank" class="ml-2"><i class="fas fa-truck-pickup"></i></a>
                </div>
            </div>
        </div>
    </div>

@endsection
