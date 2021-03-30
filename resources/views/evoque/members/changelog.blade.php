@extends('layout.index')

@section('title')
    История изменения сотрудника | @lang('general.vtc_evoque')
@endsection

@section('content')

    <div class="container pt-5 pb-3">
        @if(count($member->audits) > 0)
            <div class="member-changelog mb-5">
                <h3 class="text-primary mt-3">История изменений сотрудника {{ $member->nickname }}</h3>
                <div class="changelog-item mb-3 table-responsive">
                    <table class="table table-sm table-dark table-bordered table-hover">
                        <thead>
                        <tr>
                            <th scope="col">Кто</th>
                            <th scope="col">Когда</th>
                            <th scope="col">Что</th>
                            <th scope="col">Было</th>
                            <th scope="col">Стало</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($member->audits as $item)
                            @if($item->event !== 'deleted' && $item->new_values)
                                @foreach($item->getModified() as $key => $values)
                                    <tr>
                                        @if($loop->index === 0)
                                            <td class="nowrap" rowspan="{{ count($item->getModified()) }}">
                                                    <span class="text-primary font-weight-bold">
                                                        {{ $item->user ? $item->user->member->nickname : '' }}
                                                    </span> @lang('audits.'.$item->event)
                                            </td>
                                            <td class="nowrap" rowspan="{{ count($item->getModified()) }}">{{ $item->created_at->format('d.m.Y в H:i') }}</td>
                                        @endif
                                        <td class="nowrap">{{ trans('attributes.'.$key) }}</td>
                                        <td class="text-danger font-weight-bold">
                                            @if(isset($values['old']) && is_array($values['old']))
                                                @foreach($values['old'] as $k => $v)
                                                    {{ $k }}: {{ $v }}<br>
                                                @endforeach
                                            @else
                                                {!! $values['old'] ?? null !!}
                                            @endif
                                        </td>
                                        <td class="text-success font-weight-bold">
                                            @if(isset($values['new']) && is_array($values['new']))
                                                @foreach($values['new'] as $k => $v)
                                                    {{ $k }}: {{ $v }}<br>
                                                @endforeach
                                            @else
                                                {!! $values['new'] ?? null !!}
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td><span class="text-primary font-weight-bold">{{ $item->user->member->nickname }}</span> @lang('audits.'.$item->event)</td>
                                    <td colspan="4">{{ $item->created_at->format('d.m.Y в H:i') }}</td>
                                </tr>
                            @endif
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>

@endsection
