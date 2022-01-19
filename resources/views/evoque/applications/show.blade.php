@extends('layout.index')

@section('title')
    Заявка от {{ !in_array($app->category, [4, 5]) && $app->member ? $app->member?->nickname : $app->old_nickname }} | @lang('general.vtc_evoque')
@endsection

@section('assets')
    <link rel="stylesheet" type="text/css" href="/js/simplemde/dist/simplemde-dark.min.css">
    <script src="/js/simplemde/dist/simplemde.min.js"></script>
@endsection

@section('content')

    <div class="report-accept container pt-5 pb-5">
        @include('layout.alert')
        <h2 class="mt-3 text-primary text-center">Заявка на {{ $app->getCategory() }} от {{ !in_array($app->category, [4, 5]) && $app->member ? $app->member?->nickname : $app->old_nickname }}</h2>
        @can('claim', $app)
            <div class="row justify-content-center">
                <form method="post" action="{{ route('evoque.applications.accept', $app->id) }}">
                    @csrf
                    <button type="submit" name="accept" value="1" class="btn btn-outline-success btn-lg m-1"
                            onclick="return confirm('Принять заявку?')">Принять</button>
                    <button type="submit" name="accept" value="2" class="btn btn-outline-danger btn-lg m-1"
                            onclick="return confirm('Отклонить заявку?')">Отклонить</button>
                </form>
            </div>
        @endcan
        <div class="row justify-content-between text-center mt-5">
            @switch($app->category)
                @case(1)
                    <div class="col-12">
                        <h4 class="mb-0">Отпуск</h4>
                        <h1 class="text-primary">с {{ \Carbon\Carbon::parse($app->vacation_till['from'])->isoFormat('LL') }}</h1>
                        <h1 class="text-primary">по {{ \Carbon\Carbon::parse($app->vacation_till['to'])->isoFormat('LL') }}</h1>
                    </div>
                    @break
                @case(2)
                    <div class="col-12">
                        <h4 class="mb-0">Желаемый номер</h4>
                        <h1 class="text-primary">EVOQUE {{ $app->new_plate_number }}
                            <a href="https://worldoftrucks.com/api/license_plate/eut2/germany/rear/evoque%20{{ $app->new_plate_number }}" target="_blank">
                                <i class="fas fa-cogs"></i>
                            </a>
                        </h1>
                    </div>
                    @break
                @case(3)
                    <div class="col-md-4">
                        <h4 class="mb-0">Игра</h4>
                        <h1 class="text-primary">{{ strtoupper($app->new_rp_profile[0]) }}</h1>
                    </div>
                    <div class="col-md-4">
                        <h4 class="mb-0 text-center">Новый уровень</h4>
                        <h1 class="text-primary text-center">{{ $app->new_rp_profile[1] }}</h1>
                    </div>
                    <div class="col-md-4">
                        <h4 class="mb-0 text-center">Текущий уровень</h4>
                        <h1 class="text-primary text-center">{{ $rp->level }}</h1>
                    </div>
                    @break
                @case(4)
                    <div class="col-md-6">
                        <h4 class="mb-0">Новый никнейм</h4>
                        <h1 class="text-primary">{{ $app->new_nickname }}</h1>
                    </div>
                    <div class="col-md-6">
                        <h4 class="mb-0">Текущий никнейм</h4>
                        <h1 class="text-primary">{{ $app->member->nickname }}</h1>
                    </div>
                    @break
                @case(5) @break
            @endswitch
        </div>

        @if($app->reason)
            <div class="row flex-column text-center">
                <h4 class="mb-0 pt-3">Причина: </h4>
                <div class="markdown-content">
                    @markdown($app->reason)
                </div>
            </div>
        @endif
        @if($app->status === 1)
            <h3 class="text-success text-center">Заявка одобрена!</h3>
        @elseif($app->status === 2)
            <h3 class="text-danger text-center">Заявка отклонена!</h3>
        @endif
        @if($app->isClosed() && count($app->comments) > 0 || !$app->isClosed())
            <div class="comments border-top border-primary pt-5 mt-5">
                <form method="post" action="{{ route('evoque.applications.comment', $app->id) }}">
                    @csrf
                    <h4 class="text-center mb-3">@if(count($app->comments) === 0)Нет комментариев @else Комментарии @endif</h4>
                    @foreach($app->comments as $comment)
                        <div class="card card-dark text-shadow-m mb-2">
                            <div class="card-header row mx-0 pr-2">
                                <div class="col px-0 app-title">
                                    @if($comment->author || $comment->author?->member)
                                        <b class="text-primary">{{ $comment->author->member?->nickname ?? $comment->author?->name }}</b>
                                    @else
                                        <span class="font-italic">Уволенный сотрудник</span>
                                    @endif
                                    <span class="text-muted"> написал:</span>
                                </div>
                                <span class="col-auto text-muted">{{ $comment->created_at->isoFormat('LLL') }}</span>
                                @can('deleteComment', [$app, $comment])
                                    <div class="dropdown dropdown-dark col-auto px-0 dropleft">
                                        <button class="btn dropdown-toggle no-arrow py-0" type="button" id="dropdownMenuButton"
                                                data-toggle="dropdown" aria-haspopup="tr?ue" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <div class="dropdown-menu text-shadow-m" aria-labelledby="dropdownMenuButton">
                                            <a href="{{ route('evoque.applications.comment.delete', $comment->id) }}"
                                               class="dropdown-item" onclick="return confirm('Удалить эту заявку?')"><i class="fas fa-trash"></i> Удалить</a>
                                        </div>
                                    </div>
                                @endcan
                            </div>
                            <div class="card-body"><p class="mb-0">
                                <div class="markdown-content">
                                    @markdown($comment->text)
                                </div>
                            </div>
                        </div>
                    @endforeach
                    @can('addComment', $app)
                        <div class="new-comment mt-5">
                            <textarea class="form-control simple-mde" id="comment" name="comment">{{ $app->comment }}</textarea>
                            @if($errors->has('comment'))
                                <small class="form-text">{{ $errors->first('comment') }}</small>
                            @endif
                            <button type="submit" name="accept" value="3" class="btn btn-outline-info m-1">Сохранить коментарий</button>
                        </div>
                    @endcan
                </form>
            </div>
        @endif
    </div>

    <script>
        var simplemde = new SimpleMDE({
            element: $('#comment')[0],
            promptURLs: true
        });
    </script>

@endsection
