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
        @can('manage_rp')
            <h2 class="mt-3 text-primary ml-3 text-center">Все отчёты рейтинговых перевозок</h2>
        @else
            <h2 class="mt-3 text-primary ml-3 text-center">Мои отчёты рейтинговых перевозок</h2>
        @endcan
        @can('do_rp')
            <div class="row justify-content-center">
                <a href="{{ route('evoque.rp.reports.add') }}" class="btn btn-outline-warning ml-3 mt-3 btn-lg"><i class="fas fa-plus"></i> Новый отчет</a>
            </div>
        @endcan
        <div class="rp-reports pt-3 pb-5">
            @foreach($reports as $report)
                <div class="card card-dark text-shadow-m m-3 p-0
                        @if($report->status === 0) border-primary
                        @elseif($report->status === 1) border-success
                        @else border-danger @endif">
                    <div class="card-header row">
                        <h5 class="col-md-10 mb-0">
                            От {{ $report->member->nickname }} ({{ $report->created_at->isoFormat('LLL') }})
                            @if($report->status === 0)
                                <span class="badge badge-warning">Рассматривается</span>
                            @elseif($report->status === 1)
                                <span class="badge badge-success">Принят</span>
                            @else
                                <span class="badge badge-danger">Отклонён</span>
                            @endif
                        </h5>
                        <h5 class="col-md-2 text-md-right text-muted mb-0">{{ $report->game === 'ets2' ? 'ETS2' : 'ATS' }}</h5>
                    </div>
                    <div class="card-body">
                        @if($report->note)
                            <p class="font-weight-bold text-primary mt-2 mb-0">Дополнительная информация:</p>
                            @markdown($report->note)
                        @endif
                        @if($report->comment)
                            <p class="font-weight-bold text-primary mt-2 mb-0">Комментарий от {{ $report->comment_by }}:</p>
                            @markdown($report->comment)
                        @endif
                        <div class="row">
                            <div class="fotorama" data-allowfullscreen="true" data-nav="thumbs" data-maxheight="600">
                                @foreach($report->images as $image)
                                    <img src="/images/rp/{{ $image }}">
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @if(!$report->status)
                        <div class="card-actions">
                            @can('manage_rp')
                                <a href="{{ route('evoque.rp.reports.view', $report->id) }}" class="my-1 btn btn-outline-warning"><i class="fas fa-edit"></i> Смотреть</a>
                            @endcan
                                @if(\Illuminate\Support\Facades\Auth::user()->member->id == $report->member_id || \Illuminate\Support\Facades\Gate::allows('manage_rp'))
                                    <a href="{{ route('evoque.rp.reports.delete', $report->id) }}" class="my-1 btn btn-outline-danger"
                                       onclick="return confirm('Удалить этот отчёт?')"><i class="fas fa-trash"></i> Удалить</a>
                                @endif
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
        {{ $reports->links('layout.pagination') }}
    </div>

@endsection
