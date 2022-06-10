<div class="modal-header">
    <h5 class="modal-title" id="appModalLabel">Заявка на {{ $app->getCategory() }} от {{ !in_array($app->category, [4, 5]) && $app->member ? $app->member?->nickname : $app->old_nickname }}</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Закрыть">
        <i class="fas fa-times"></i>
    </button>
</div>
<div class="modal-body row justify-content-between">
    @switch($app->category)
        @case(1)
            <div class="col-12">
                <h4 class="mb-0">Отпуск</h4>
                <h3 class="text-primary">с {{ \Carbon\Carbon::parse($app->vacation_till['from'])->isoFormat('LL') }}</h3>
                <h3 class="text-primary">по {{ \Carbon\Carbon::parse($app->vacation_till['to'])->isoFormat('LL') }}</h3>
            </div>
            @break
        @case(3)
            <div class="col-md-4">
                <h4 class="mb-0">Игра</h4>
                <h1 class="text-primary">{{ strtoupper($app->new_rp_profile[0]) }}</h1>
            </div>
            <div class="col-md-4 text-center">
                <h4 class="mb-0">Новый уровень</h4>
                <h1 class="text-primary">{{ $app->new_rp_profile[1] }}</h1>
            </div>
            <div class="col-md-4 text-right">
                <h4 class="mb-0">Текущий уровень</h4>
                <h1 class="text-primary">{{ $rp->level }}</h1>
            </div>
            @break
        @case(4)
            <div class="col-12">
                <p class="mb-0">Старый никнейм</p>
                <h2 class="text-primary">{{ $app->old_nickname }}</h2>
            </div>
            <div class="col-12">
                <p class="mb-0 mt-3">Новый никнейм</p>
                <h2 class="text-primary">{{ $app->new_nickname }}</h2>
            </div>
            @break
        @case(5) @break
    @endswitch
    @if($app->reason)
        <div class="col-12">
            <h4 class="mb-0 mt-3">Причина: </h4>
            <div class="markdown-content">
                @markdown($app->reason)
            </div>
        </div>
    @endif
    @if($app->status !== 0)
        <div class="col-12">
            <h3 class="{{ \App\Enums\Status::from($app->status)->colorClass('text-') }}">Заявка {{ $app->getStatus() }}!</h3>
        </div>
    @endif
</div>

@if($app->isClosed() && count($app->comments) > 0 || !$app->isClosed())
    @if(count($app->comments) > 0)
        <div class="modal-header">
            <h5 class="modal-title">Комментарии</h5>
        </div>
    @endif
    <div class="modal-body">
        <div class="comments">
            @foreach($app->comments as $comment)
                <div class="card card-dark mb-2">
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
                    <div class="card-body py-1">
                        <div class="markdown-content">
                            @markdown($comment->text)
                        </div>
                    </div>
                </div>
            @endforeach
            @can('addComment', $app)
                <form method="post" action="{{ route('evoque.applications.comment', $app->id) }}">
                    @csrf
                    <div class="new-comment mt-5">
                        <textarea class="form-control simple-mde" id="comment" name="comment">{{ $app->comment }}</textarea>
                        @if($errors->has('comment'))
                            <small class="form-text">{{ $errors->first('comment') }}</small>
                        @endif
                        <button type="submit" name="accept" value="3" class="btn btn-outline-info m-1">Сохранить коментарий</button>
                    </div>
                </form>
                <script>
                    var comment = new SimpleMDE({
                        element: $('#comment')[0],
                        promptURLs: true
                    });
                </script>
            @endcan
        </div>
    @endif

</div>
@can('claim', $app)
    <div class="modal-footer justify-content-start">
        <form method="post" action="{{ route('evoque.applications.accept', $app->id) }}">
            @csrf
            <button type="submit" name="accept" value="1" class="btn btn-outline-success"
                    onclick="return confirm('Принять заявку?')">Принять</button>
            <button type="submit" name="accept" value="2" class="btn btn-outline-danger"
                    onclick="return confirm('Отклонить заявку?')">Отклонить</button>
            @can('delete', $app)
                <a href="{{ route('evoque.applications.delete', $app->id) }}" class="btn btn-outline-secondary"
                   onclick="return confirm('Удалить заявку?')">Удалить</a>
            @endcan
        </form>
    </div>
@endcan
