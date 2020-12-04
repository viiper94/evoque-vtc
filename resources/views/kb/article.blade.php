@extends('layout.index')

@section('title')
    База знаний | @lang('general.vtc_evoque')
@endsection

@section('content')

    <div class="container py-5">
        @include('layout.alert')
        <section class="article-author mt-3 row">
            <div class="col-auto">
                <img src="{{ $article->user->image }}" alt="{{ $article->user->name }}" style="max-width: 100px;" class="rounded">
            </div>
            <div class="col">
                <p class="mb-0 text-muted">Автор</p>
                <h4 class="text-primary">{{ $article->user->member ? $article->user->member->nickname : $article->user->name }}</h4>
                @if($article->user->member)
                    <h5>{{ $article->user->name }}</h5>
                @endif
            </div>
            <div class="col-auto text-right">
                <p class="text-muted mb-0">Статья создана: {{ $article->created_at->format('d.m.Y в H:i') }}</p>
                <p class="text-muted">Последнее изменение: {{ $article->updated_at->format('d.m.Y в H:i') }}</p>
            </div>
            <hr class="border-primary w-50 mx-auto my-4">
        </section>
        @can('viewAny', \App\Kb::class)
            @if(!$article->visible)
                <p class="text-muted">Статья не опубликована!</p>
            @endif
        @endcan
        <h1 class="text-primary my-3">{{ $article->title }}</h1>
        <section class="article-content">
            @markdown($article->article)
        </section>
        @can('update', $article)
            <a href="{{ route('kb.edit', $article->id) }}" class="btn btn-outline-warning">Редактировать</a>
        @endcan
        @can('delete', $article)
            <a href="{{ route('kb.delete', $article->id) }}" class="btn btn-outline-danger"
               onclick="return confirm('Удалить эту статью?')">Удалить</a>
        @endcan
    </div>

@endsection
