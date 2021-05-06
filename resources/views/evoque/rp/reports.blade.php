@extends('layout.index')

@section('title')
    Отчёт рейтинговых перевозок | @lang('general.vtc_evoque')
@endsection

@section('assets')
    <link rel="stylesheet" type="text/css" href="/js/fotorama-4.6.4/fotorama.css">
    <script src="/js/fotorama-4.6.4/fotorama.js"></script>
@endsection

@section('content')

    <div class="container pt-5">
        @include('layout.alert')
        @can('viewAll', \App\RpReport::class)
            <h2 class="mt-3 text-primary ml-3 text-center">Все отчёты рейтинговых перевозок</h2>
        @else
            <h2 class="mt-3 text-primary ml-3 text-center">Мои отчёты рейтинговых перевозок</h2>
        @endcan
        @can('create', \App\RpReport::class)
            <div class="row justify-content-center">
                <a href="{{ route('evoque.rp.reports.add') }}" class="btn btn-outline-warning ml-3 mt-3 btn-lg"><i class="fas fa-plus"></i> Новый отчет</a>
            </div>
        @endcan
    </div>
    <div class="container-fluid">
        <div class="rp-reports pt-3 pb-5 row justify-content-around align-items-baseline">
            @if(count($reports) > 0)
                @foreach($reports as $report)
                    <div class="card card-dark col-12 col-md-auto text-shadow-m m-3 p-0
                        @if($report->status === 2) border-danger
                        @elseif($report->status === 1) border-success
                        @else border-primary @endif">
                        <div class="card-header row mx-0">
                            <div class="col row rp-title">
                                <h5 class="mb-0">
                                    @if($report->status == '0')
                                        <i class="fas fa-arrow-alt-circle-up text-warning"></i>
                                    @elseif($report->status == '1')
                                        <i class="fas fa-check-circle text-success"></i>
                                    @elseif($report->status == '2')
                                        <i class="fas fa-times-circle text-danger"></i>
                                    @endif
                                    @can('decline', $report)
                                        <a href="{{ route('evoque.rp.reports.view', $report->id) }}">
                                            @endcan
                                            @if($report->member)
                                                От {{ $report->member->nickname }}
                                            @else
                                                <span class="no-member">Отчёт уволенного сотрудника</span>
                                            @endif
                                            @can('decline', $report)
                                        </a>
                                    @endcan
                                </h5>
                            </div>
                            <h5 class="col-auto text-md-right text-muted mb-0">{{ strtoupper($report->game) }}</h5>
                            @if(\Illuminate\Support\Facades\Auth::user()->can('decline', $report) ||
                                        \Illuminate\Support\Facades\Auth::user()->can('delete', $report))
                                <div class="dropdown dropdown-dark col-auto px-0 dropleft">
                                    <button class="btn dropdown-toggle no-arrow py-0" type="button" id="dropdownMenuButton"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <div class="dropdown-menu text-shadow-m" aria-labelledby="dropdownMenuButton">
                                        @can('decline', $report)
                                            <a href="{{ route('evoque.rp.reports.view', $report->id) }}" class="dropdown-item"><i class="fas fa-eye"></i> Смотреть</a>
                                        @endcan
                                        @can('delete', $report)
                                            <a href="{{ route('evoque.rp.reports.delete', $report->id) }}"
                                               class="dropdown-item" onclick="return confirm('Удалить этот отчёт?')"><i class="fas fa-trash"></i> Удалить</a>
                                        @endcan
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="card-body">
                            @if($report->note)
                                <p class="font-weight-bold text-primary mb-0">Дополнительная информация:</p>
                                <div class="markdown-content">
                                    @markdown($report->note)
                                </div>
                            @endif
                            @if($report->comment)
                                <p class="text-danger mb-0">Комментарий от {{ $report->comment_by }}:</p>
                                <div class="markdown-content">
                                    @markdown($report->comment)
                                </div>
                            @endif
                        </div>
                        <div class="row mx-0">
                            <div class="fotorama" data-allowfullscreen="true" data-maxheight="600">
                                @foreach($report->images as $image)
                                    <img src="/images/rp/{{ $image }}" style="max-width: 100%">
                                @endforeach
                            </div>
                        </div>
                        <div class="card-footer text-muted">
                            {{ $report->created_at->isoFormat('LLL') }}
                        </div>
                    </div>
                @endforeach
            @else
                <h5 class="text-center">
                    Еще не создано отчётов
                </h5>
            @endif
        </div>
        {{ $reports->links('layout.pagination') }}
        <div class="row">
            <section class="convoy-note pb-5 pt-5 m-auto">
                <hr class="m-auto">
                <blockquote class="blockquote text-center mb-5 mt-5">
                    <h2 class="mb-0 text-primary">Правила рейтинговых перевозок:</h2>
                    <ol class="text-left ml-5 mr-1">
                        <li>Грузы разрешено брать <b>только в онлайне</b>*</li>
                        <li>Перед тем как взять груз делаете скрин: открытый TAB + маршрут (строго на старте)**</li>
                        <li>При сдаче груза: делаете скриншот: TAB + полный отчет о грузе***</li>
                        <li>Сданные грузы с повреждением более <b>10%</b> учитываться не будут.</li>
                        <li>Сданные грузы с маршрутом превышающим на обоих скринах <b>+/- 100 км</b> не учитываются.</li>
                        <li>Грузы протяженностью <b>менее 500</b> километров не будут засчитываться.</li>
                        <li>Выложенные грузы с Уровнем в игре ниже, чем указано в таблице засчитаны не будут.</li>
                        <li>Лидером недели становится тот, кто в сумме (отвезенный километраж + бонус)<br>
                            отвезет больше всех километров</li>
                        <li>За перевозки тяжелых грузов <b>на серверах симуляции</b> будет <br>
                            начисляться дополнительный бонус в виде дополнительных километров:</li>
                    </ol>
                    <ul class="text-left ml-5 mr-1">
                        <li>груз от 0 т и до 14 т - коэф 0.0</li>
                        <li>груз от 15 т и до 19 т - коэф 0.1</li>
                        <li>груз от 20 т и до 25 т - коэф 0.3</li>
                        <li>груз от 26 т и до 32 т - коэф 0.5</li>
                        <li>груз от 33 т и до 61 т - коэф 0.7</li>
                    </ul>
                    <ul class="text-left ml-5 mr-1 mt-5 list-style-none">
                        <li>* Грузы с которыми Вы приезжаете на конвой - не учитываются</li>
                        <li>** Увеличивать маршрут с помощью точек - запрещено</li>
                        <li>*** Ваш ID на обоих скрин TAB должен совпадать,<br>
                            либо должен присутствовать 1 дополнительный скрин,<br>
                            информирующий о новом ID: скрин при входе в игру с открытым чатом и TAB.</li>
                    </ul>
                </blockquote>
            </section>
        </div>
    </div>

@endsection
