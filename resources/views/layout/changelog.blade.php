@if(count($items) > 0)
    <div class="member-changelog mb-3">
        <div class="changelog-item mb-3 table-responsive" data-fl-scrolls>
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
                @foreach($items as $item)
                    @if($item->event !== 'deleted' && $item->new_values)
                        @foreach($item->getModified() as $key => $values)
                            @php
                                $diff = new cogpowered\FineDiff\Diff($granularity ?? null);
                                $old_value = '';
                                if(isset($values['old']) && is_array($values['old'])){
                                    foreach($values['old'] as $k => $v){
                                        $old_value .= trans('changelog.'.$k) .': '. (is_array($v) ? $v['title'] : $v) ."; \n\r";
                                    }
                                }else if(preg_match('/^(\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}\.\d{6})Z$/', ($values['old'] ?? ''))){
                                    $date = \Carbon\Carbon::parse($values['old']);
                                    $old_value .= $date->format('H:i:s') === '00:00:00' ? $date->isoFormat('LL') : $date->isoFormat('LLL');
                                }else{
                                    $old_value .= $values['old'] ?? '';
                                }

                                $new_value = '';
                                if(isset($values['new']) && is_array($values['new'])){
                                    foreach($values['new'] as $k => $v){
                                        $new_value .= trans('changelog.'.$k) .': '. (is_array($v) ? $v['title'] : $v) ."; \n\r";
                                    }
                                }else if(preg_match('/^(\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}\.\d{6})Z$/', ($values['new'] ?? ''))){
                                    $date = \Carbon\Carbon::parse($values['new']);
                                    $new_value .= $date->format('H:i:s') === '00:00:00' ? $date->isoFormat('LL') : $date->isoFormat('LLL');
                                }else{
                                    $new_value .= $values['new'] ?? '';
                                }
                            @endphp
                            <tr>
                                @if($loop->index === 0)
                                    <td class="nowrap" rowspan="{{ count($item->getModified()) }}">
                                        @if($item->user)
                                            <span class="text-primary font-weight-bold">
                                                {{ $item->user->member?->nickname ?? $item->user->name }}
                                            </span>
                                        @elseif(isset($item->user_id))
                                            <i>Уволенный пользователь</i>
                                        @else
                                            <i>Сайт</i>
                                        @endif
                                        @lang('audits.'.$item->event) <br>
                                        {{ $item->created_at->format('d.m.Y в H:i') }}
                                    </td>
                                @endif
                                <td class="nowrap">{{ trans('attributes.'.$key) }}</td>
                                <td class="font-weight-bold no-ins">
                                    {!! nl2br($diff->render($old_value, $new_value)) !!}
                                </td>
                                <td class="font-weight-bold no-del">
                                    {!! nl2br($diff->render($old_value, $new_value)) !!}
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
@else
    <p class="text-center">Изменений не найдено</p>
@endif
