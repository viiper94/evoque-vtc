@extends('layout.index')

@section('title')
    404 | Страница не найдена
@endsection

@section('content')

    <div class="row pt-5 pb-5 align-items-center flex-column">
        <h1 class="display-1 text-primary font-weight-bold mt-5">404</h1>
        <h2 class="text-primary font-weight-bold mb-5">Страница не найдена</h2>
        <a href="{{ route('home') }}" class="btn btn-lg btn-outline-warning">На главную</a>
    </div>

@endsection
