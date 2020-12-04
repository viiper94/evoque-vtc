@extends('layout.index')

@section('title')
    База знаний | @lang('general.vtc_evoque')
@endsection

@section('content')

    <div class="kb container py-5">
        @include('layout.alert')
        <h1 class="text-center text-primary mt-3">
            База знаний
            @can('create', \App\Kb::class)
                <a href="{{ route('kb.add') }}" class="btn btn-outline-warning"><i class="fas fa-plus"></i></a>
            @endcan
        </h1>
        <div class="row articles justify-content-center">
            @foreach($categories as $name => $category)
                <div class="category col-auto m-3">
                    <div class="card card-dark text-shadow-m">
                        <h5 class="card-header text-primary">
                            {{ $name }}
                        </h5>
                        <div class="card-body">
                            @foreach($category as $article)
                                <p><a href="{{ route('kb.view', $article->id) }}">{{ $article->title }}</a></p>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

@endsection
