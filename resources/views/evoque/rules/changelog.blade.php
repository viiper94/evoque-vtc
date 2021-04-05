@extends('layout.index')

@section('title')
    История изменения параграфа | @lang('general.vtc_evoque')
@endsection

@section('assets')
    <script src="/js/htmldiff.js"></script>
@endsection

@section('content')

    <div class="container pt-5">
        @if(count($paragraph->audits) > 0)
            <div class="member-changelog mb-5">
                <h3 class="text-primary mt-3">История изменений параграфа №{{ $paragraph->paragraph }}</h3>
                <div class="changelog-item mb-3 table-responsive">
                    <table class="table table-sm table-dark table-bordered table-hover">
                        <thead>
                        <tr>
                            <th scope="col">Кто</th>
                            <th scope="col">Что</th>
                            <th scope="col">Было</th>
                            <th scope="col">Стало</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($paragraph->audits as $item)
                            @if($item->event !== 'deleted' && $item->new_values)
                                @foreach($item->getModified() as $key => $values)
                                    @php
                                        $diff = new cogpowered\FineDiff\Diff(new cogpowered\FineDiff\Granularity\Word);
                                        $old_value = '';
                                        if(isset($values['old']) && is_array($values['old'])){
                                            foreach($values['old'] as $k => $v){
                                                $old_value .= $k .': '. $v .'<br>';
                                            }
                                        }else{
                                            $old_value .= $values['old'] ?? '';
                                        }$new_value = '';
                                        if(isset($values['new']) && is_array($values['new'])){
                                            foreach($values['new'] as $k => $v){
                                                $new_value .= $k .': '. $v .'<br>';
                                            }
                                        }else{
                                            $new_value .= $values['new'] ?? '';
                                        }
                                    @endphp
                                    <tr>
                                        @if($loop->index === 0)
                                            <td class="nowrap" rowspan="{{ count($item->getModified()) }}">
                                                <span class="text-primary font-weight-bold">
                                                    {{ $item->user ? $item->user->member->nickname : '' }}
                                                </span> @lang('audits.'.$item->event) <br>
                                                {{ $item->created_at->format('d.m.Y в H:i') }}
                                            </td>
                                        @endif
                                        <td class="nowrap">{{ trans('attributes.'.$key) }}</td>
                                        <td class="font-weight-bold no-ins">
                                            {!! $diff->render($old_value, $new_value) !!}
                                        </td>
                                        <td class="font-weight-bold no-del">
                                            {!! $diff->render($old_value, $new_value) !!}
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
