@extends('layout.index')

@section('title')
    Принять скрин TAB| @lang('general.vtc_evoque')
@endsection

@section('assets')
    <link rel="stylesheet" type="text/css" href="/js/simplemde/dist/simplemde-dark.min.css">
    <script src="/js/simplemde/dist/simplemde.min.js"></script>
@endsection

@section('content')

    <div class="container pt-5 pb-5">
        @include('layout.alert')
        <h2 class="mt-3 text-primary text-center">Принять скрин TAB</h2>
        <div class="row mt-5">
            <div class="attribute col-md-4 col-xs-12 text-center text-md-left">
                <p class="mb-0">Конвой</p>
                <h3 class="text-primary">{{ $tab->convoy_title }}</h3>
            </div>
            <div class="attribute col-md-4 col-xs-12 text-center">
                <p class="mb-0">Дата</p>
                <h3 class="text-primary">{{ $tab->date->isoFormat('LL') }}</h3>
            </div>
            <div class="attribute col-md-4 col-xs-12 text-md-right text-center">
                <p class="mb-0">Ведущий</p>
                <h3 class="text-primary">{{ $tab->lead->nickname }}</h3>
            </div>
        </div>
        @if($tab->description)
            <div class="attribute mt-3">
                <p class="mb-0">Дополнительная информация</p>
                <h3 class="text-primary">{!! nl2br($tab->description) !!}</h3>
            </div>
        @endif
        <div class="tab-screenshot">
            <a href="/images/convoys/tab/{{ $tab->screenshot }}" target="_blank"><img src="/images/convoys/tab/{{ $tab->screenshot }}" class="img-fluid"></a>
        </div>
        @can('claim', $tab)
            <form method="post">
            @csrf
                <div class="member-set-scores mt-5">
                    <div class="table-responsive">
                        <table class="table table-dark table-hover text-center">
                            <thead>
                            <tr>
                                <th class="border-right-5">Никнейм</th>
                                <th colspan="4" class="border-right-5">Баллы</th>
                                <th colspan="3">Ведущий</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($roles as $role_group)
                                <tr>
                                    <th colspan="8" class="text-center text-primary">{{ $role_group[0]->group }}</th>
                                </tr>
                                @foreach($role_group as $role)
                                    @foreach($role->members as $member)
                                        @if($member->topRole() == $role->id)
                                            <tr class="member-row">
                                                <th class="border-right-5">{{ $member->nickname }}</th>
                                                @if(isset($member->scores) && !$member->isTrainee())
                                                    <td>
                                                        <div class="custom-control custom-radio">
                                                            <input type="radio" class="custom-control-input" id="scores-{{ $member->id }}-0" name="scores[{{ $member->id }}]" value="0">
                                                            <label class="custom-control-label" for="scores-{{ $member->id }}-0">0 баллов</label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-radio">
                                                            <input type="radio" class="custom-control-input" id="scores-{{ $member->id }}-1" name="scores[{{ $member->id }}]" value="1">
                                                            <label class="custom-control-label" for="scores-{{ $member->id }}-1">1 балл</label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-radio">
                                                            <input type="radio" class="custom-control-input" id="scores-{{ $member->id }}-2" name="scores[{{ $member->id }}]" value="2" @if($tab->lead_id === $member->id) checked @endif>
                                                            <label class="custom-control-label" for="scores-{{ $member->id }}-2">2 балла</label>
                                                        </div>
                                                    </td>
                                                @else
                                                    <td colspan="3">
                                                        <div class="custom-control custom-radio">
                                                            <input type="radio" class="custom-control-input" id="scores-{{ $member->id }}-2" name="scores[{{ $member->id }}]" value="0" @if($tab->lead_id === $member->id) checked @endif>
                                                            <label class="custom-control-label" for="scores-{{ $member->id }}-2">Посещение</label>
                                                        </div>
                                                    </td>
                                                @endif
                                                <td class="border-right-5"><a class="uncheck-scores" data-target="scores"><i class="fas fa-times"></i></a></td>
                                                @if(isset($member->money) && !$member->isTrainee())
                                                    <td>
                                                        <div class="custom-control custom-radio">
                                                            <input type="radio" class="custom-control-input" id="lead-05-{{ $member->id }}" data-member="{{ $member->id }}" name="lead" value="{{ $member->id }},0.5">
                                                            <label class="custom-control-label" for="lead-05-{{ $member->id }}">0.5 эвика</label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-radio">
                                                            <input type="radio" class="custom-control-input" id="lead-1-{{ $member->id }}" data-member="{{ $member->id }}" name="lead" value="{{ $member->id }},1" @if($tab->lead_id === $member->id) checked @endif>
                                                            <label class="custom-control-label" for="lead-1-{{ $member->id }}">1 эвик</label>
                                                        </div>
                                                    </td>
                                                    <td><a class="uncheck-scores" data-target="lead"><i class="fas fa-times"></i></a></td>
                                                @elseif($member->isTrainee())
                                                    <td colspan="3"></td>
                                                @else
                                                    <td colspan="2">
                                                        <div class="custom-control custom-radio">
                                                            <input type="radio" class="custom-control-input" id="lead-{{ $member->id }}" data-member="{{ $member->id }}" name="lead" value="{{ $member->id }},0" @if($tab->lead_id === $member->id) checked @endif>
                                                            <label class="custom-control-label" for="lead-{{ $member->id }}">Ведущий</label>
                                                        </div>
                                                    </td>
                                                    <td><a class="uncheck-scores" data-target="lead"><i class="fas fa-times"></i></a></td>
                                                @endif
                                            </tr>
                                        @endif
                                    @endforeach
                                @endforeach
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-12">
                        <label for="comment">Комментарий</label>
                        <textarea class="form-control simple-mde" id="comment" name="comment">{{ $tab->comment }}</textarea>
                        @if($errors->has('comment'))
                            <small class="form-text">{{ $errors->first('comment') }}</small>
                        @endif
                    </div>
                </div>
                <div class="row justify-content-center">
                    <button type="submit" name="accept" value="1" class="btn btn-outline-success btn-lg m-1">Принять</button>
                    <button type="submit" name="accept" value="2" class="btn btn-outline-danger btn-lg m-1" onclick="return confirm('Отклонить этот скрин таба?')">Отклонить</button>
                </div>
            </form>
        @endcan
    </div>

    <script>
        var simplemde = new SimpleMDE({
            element: $('#comment')[0],
            promptURLs: true
        });
    </script>

@endsection
