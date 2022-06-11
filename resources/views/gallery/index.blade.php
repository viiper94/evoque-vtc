@extends('layout.index')

@section('title')
    Галерея | @lang('general.vtc_evoque')
@endsection

@section('assets')
    <link rel="stylesheet" type="text/css" href="/js/fotorama-4.6.4/fotorama.css">
    <script src="/js/fotorama-4.6.4/fotorama.js"></script>
@endsection

@section('meta')
    <meta name="description" content="ВТК EVOQUE - Опытная, динамично развивающаяся виртуальная транспортная компания,
        которая занимается грузоперевозками в мире TruckersMP, проводит регулярные открытые конвои по мультиплееру ETS2 и ATS.">
    <meta name="keywords" content="втк, конвой, открытые конвои, открытый конвой, совместные поездки, покатушки,
        перевозки, грузоперевозки, виртуальная транспортная компания, truckersmp, truckers mp, ets2mp, atsmp, ets2 mp,
        euro truck simulator 2, american truck simulator, ets2, ats, multiplayer, мультиплеер, симулятор дальнобойщика,
        вступить в втк, втупить в компанию">
@endsection

@section('content')
    <section class="head-section">
        <div class="row h-100 justify-content-center align-items-center flex-column mx-0">
            <h1 class="text-primary display-4 text-shadow">Галерея</h1>
            @can('create', \App\Gallery::class)
                <a href="{{ route('gallery.add') }}" class="btn btn-outline-warning"><i class="fas fa-plus"></i> Загрузить фото</a>
            @endcan
        </div>
    </section>
    <div class="mt-3">
        @include('layout.alert')
    </div>
    <div class="row my-5 mx-0 gallery">
        @foreach($photos as $photo)
            <div @class(['gallery-image col-7 col-md-auto d-block p-0 m-2 text-shadow-m rounded',
                        'hidden-photo border-danger' => !$photo->visible
                    ]) style="background-image: url('/images/gallery/{{ $photo->image_thumb }}')" data-toggle="modal" data-target="#gallery-modal" data-frame="{{ $loop->index }}">
                @if(\Illuminate\Support\Facades\Auth::user()?->can('toggle', \App\Gallery::class) ||
                    \Illuminate\Support\Facades\Auth::user()?->can('delete', \App\Gallery::class))
                    <div class="dropdown dropdown-dark col-auto px-0 pt-2 dropleft text-right">
                        <button class="btn dropdown-toggle no-arrow py-0" type="button" id="dropdownMenuButton"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                        <div class="dropdown-menu text-shadow-m" aria-labelledby="dropdownMenuButton">
                            @can('toggle', \App\Gallery::class)
                                <a href="{{ route('gallery.toggle', $photo->id) }}" class="dropdown-item">
                                    @if($photo->visible)
                                        <i class="fas fa-eye-slash"></i> Скрыть
                                    @else
                                        <i class="fas fa-eye"></i> Опубликовать
                                    @endif
                                </a>
                            @endcan
                            @can('delete', \App\Gallery::class)
                                <a href="{{ route('gallery.delete', $photo->id) }}"
                                   class="dropdown-item" onclick="return confirm('Удалить этот скриншот?')"><i class="fas fa-trash"></i> Удалить</a>
                            @endcan
                        </div>
                    </div>
                @endif
            </div>
        @endforeach
    </div>

    <!-- Gallery modal -->
    <div class="modal fade" id="gallery-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" >
            <div class="modal-content modal-content-dark">
                <div class="modal-body p-0">
                    <div class="gallery-fotorama w-100" data-nav="thumbs" data-allowfullscreen="true" data-auto="false" data-fit="contain" data-ratio="16/9" data-width="100%">
                        @foreach($photos as $photo)
                            <a href="/images/gallery/{{ $photo->image_full }}" class="w-100" data-caption="Автор:
                                 @if($photo->user && !$photo->author)
                                    @if($photo->user->member)
                                        [EVOQUE] {{ $photo->user->member->nickname }}
                                    @else
                                         {{ $photo->user->readyname }}
                                    @endif
                                 @else
                                     {{ $photo->author ?? 'Не указан' }}
                                @endif" data-thumb="/images/gallery/{{ $photo->image_thumb }}"></a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>

        $(document).ready(function(){

            let fotorama = $('.gallery-fotorama').fotorama();
            let galleryIndex;

            $('#gallery-modal').on('shown.bs.modal', function (e){
                let fotoramaApi = fotorama.data('fotorama');
                console.log(fotorama);
                fotoramaApi.show({
                    index: galleryIndex,
                    time: 0
                });
            })

            $('.gallery-image').click(function(){
                let $image = $(this);
                galleryIndex = $image.data('frame');
            });

        });

    </script>

@endsection
