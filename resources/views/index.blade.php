@extends('layout.index')

@section('title')
    ВТК EVOQUE 😔
@endsection

@section('content')

<div id="carousel" class="carousel slide text-shadow-m" data-ride="carousel">
    <ol class="carousel-indicators">
        <li data-target="#carousel" data-slide-to="0" class="active"></li>
    </ol>
    <div class="carousel-inner">
        <div @class(['carousel-item', 'active' => true])>
            {{-- <img src="/assets/img/carousel/0.jpg" class="d-block"> --}}

            
            <div class="carousel-caption center-center text-shadow">
                <h1 class="display-3">ВТК EVOQUE закрыта! 😔</h1>
                <p>Cпасибо, что были с нами всё это время.<br>Увидимся на дорогах!</p>
            </div>
        </div>
    </div>
</div>

@endsection
