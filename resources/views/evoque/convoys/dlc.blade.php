@extends('layout.index')

@section('title')
    Список DLC | @lang('general.vtc_evoque')
@endsection

@section('content')

    <div class="container pt-5 pb-5 dlc-list">
        @include('layout.alert')
        @csrf
        <h2 class="mt-3 mb-3 text-primary text-center">Списком DLC для конвоев</h2>
        <div class="table-responsive mb-3">
            <table class="table table-dark table-hover">
                <thead>
                <tr>
                    <th scope="col">Название</th>
                    <th scope="col">Игра</th>
                    <th scope="col" class="text-center">
                        @can('editDLCList', \App\Convoy::class)
                            <a class="btn btn-sm btn-outline-primary edit-dlc-list-btn"><i class="fas fa-plus"></i> Новое DLC</a>
                        @endcan
                    </th>
                    <th scope="col" class="text-center sort-th">Сортировка</th>
                </tr>
                </thead>
                <tbody class="sortable">
                @foreach($dlc_list as $dlc)
                    <tr data-id="{{ $dlc->id }}">
                        <td><b>{{ $dlc->title }}</b></td>
                        <td>@lang('general.'.$dlc->game)</td>
                        <td class="text-center">
                            @if($dlc->steam_link) <a href="{{ $dlc->steam_link }}" target="_blank"><i class="fab fa-steam"></i></a> @endif
                            @can('editDLCList', \App\Convoy::class)
                                <a class="edit-dlc-list-btn"
                                   data-id="{{ $dlc->id }}" data-title="{{ $dlc->title }}"
                                   data-steam="{{ $dlc->steam_link }}" data-game="{{ $dlc->game }}">
                                    <i class="fas fa-pen"></i>
                                </a>
                                <a href="{{ route('evoque.convoys.dlc.delete', $dlc->id) }}" onclick="return confirm('Удалить DLC?')"><i class="fas fa-trash"></i></a>
                            @endcan
                        </td>
                        <td class="text-center"><i class="sort-handle fas fa-grip-vertical"></i></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" id="dlc-modal" tabindex="-1" aria-labelledby="addDLCLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
            <div class="modal-content modal-content-dark">
                <div class="modal-header">
                    <h5 class="modal-title" id="addDLCLabel">Новое DLC</h5>
                    <button type="button" class="close text-shadow" data-dismiss="modal" aria-label="Close">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <form method="post" action="{{ route('evoque.convoys.dlc.edit') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="title">Название</label>
                            <input type="text" class="form-control" id="title" name="title" required>
                        </div>
                        <div class="form-group">
                            <label for="game">Игра</label>
                            <select name="game" id="game" class="form-control" required>
                                <option value="ets2">Euro Truck Simulator 2</option>
                                <option value="ats">American Truck Simulator</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="steam_link">Ссылка в Steam</label>
                            <input type="url" class="form-control" id="steam_link" name="steam_link">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="id" id="id" value="">
                        <button class="btn btn-outline-info mx-1" type="submit"><i class="fas fa-save"></i> Сохранить</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
