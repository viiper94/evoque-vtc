@extends('layout.index')

@section('title')
    Сотрудники @lang('general.vtc_evoque')
@endsection

@section('content')

    <div class="container members mt-5">
        @foreach($roles as $role_group)
            @if(count($role_group[0]->members) < 1) @continue @endif
            <section class="pt-5 pb-5">
                <h2 class="text-center">{{ $role_group[0]->group }}</h2>
                <div class="row justify-content-center">
                    @foreach($role_group as $role)
                        @foreach($role->members as $member)
                            @if($member->topRole() == $role->id)
                                <div class="member card card-dark text-shadow-m mt-5 ml-5 mr-5 text-center">
                                    <img src="{{ $member->user->image }}" class="card-img-top" alt="{{ $member->nickname }}">
                                    <div class="card-body">
                                        <h5 class="card-title member-nickname">{{ $member->nickname}}</h5>
                                        <p class="card-text member-roles">
                                            @foreach($member->role as $item)
                                                {{ $item->title }}@if(!$loop->last),@endif
                                            @endforeach
                                        </p>
                                        <p class="card-text">
                                            @if(isset($member->join_date))
                                                В компании с:<br>{{ $member->join_date->isoFormat('LL') }}
                                            @endif
                                        </p>
                                        <div class="member-links text-center">
                                            <a href="https://steamcommunity.com/profiles/{{ $member->user->steamid64 }}" target="_blank"><i class="fab fa-steam-square"></i></a>
                                            <a href="https://truckersmp.com/user/{{ $member->user->truckersmp_id }}" target="_blank"><i class="fas fa-truck-pickup"></i></a>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    @endforeach
                </div>
            </section>
        @endforeach
    </div>

@endsection
