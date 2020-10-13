@extends('layout.index')

@section('title')
    Отчёт рейтинговых перевозок | @lang('general.vtc_evoque')
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
                <div class="card card-dark text-shadow-m m-3 p-0 @if(!$report->status)border-primary @endif">
                    <h5 class="card-header">От {{ $report->member->nickname }} ({{ $report->created_at->isoFormat('LLL') }})</h5>
                    <div class="card-body">
                        <p class="card-text"><b>{{ $report->game === 'ets2' ? 'Euro Truck Simulator 2' : 'American Truck Simulator' }}</b></p>
                        <div class="row">
                            @foreach($report->images as $image)
                                <a href="/images/rp/{{ $image }}" class="col-xs-12 col-md-{{ 12/count($report->images) }}" target="_blank">
                                    <img src="/images/rp/{{ $image }}" style="max-width: 100%" class="text-shadow-m">
                                </a>
                            @endforeach
                        </div>
                        @if($report->note)
                            <p>Дополнительная информация:<br>
                            <b>{{ $report->note }}</b></p>
                        @endif
                    </div>
                    @if(!$report->status)
                        <div class="card-actions">
                            @if(\Illuminate\Support\Facades\Auth::user()->member->id === $report->member_id)
                                <a href="{{ route('evoque.rp.reports.edit', $report->id) }}" class="btn btn-outline-warning"><i class="fas fa-edit"></i> Редактировать</a>
                            @endif
                            @can('manage_rp')
                                <a href="{{ route('evoque.rp.reports.accept', $report->id) }}" class="btn btn-outline-success"><i class="fas fa-edit"></i> Принять</a>
                                <a href="{{ route('evoque.rp.reports.delete', $report->id) }}" class="btn btn-outline-danger"
                                    onclick="return confirm('Удалить этот отчёт?')"><i class="fas fa-trash"></i> Отклонить и удалить</a>
                            @endcan
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
        {{ $reports->links('layout.pagination') }}
    </div>

@endsection
