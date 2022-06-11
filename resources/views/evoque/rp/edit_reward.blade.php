@extends('layout.index')

@section('title')
    @if($reward->id)Редактирование награды @else Новая награда @endif | @lang('general.vtc_evoque')
@endsection

@section('content')
    <div class="container rp-report pt-5 pb-5">
        @include('layout.alert')
        <h2 class="mt-3 text-primary text-center">@if($reward->id)Редактирование награды @else Новая награда @endif</h2>
        <form method="post">
            @csrf
            <div class="row">
                <div class="col-md-4 col-sm-12">
                    <h4>Выберите игру</h4>
                    <div class="custom-control custom-radio">
                        <input type="radio" id="game-ets2" name="game" class="custom-control-input" value="ets2" @checked($reward->game === 'ets2') required>
                        <label class="custom-control-label" for="game-ets2">Euro Truck Simulator 2</label>
                    </div>
                    <div class="custom-control custom-radio">
                        <input type="radio" id="game-ats" name="game" class="custom-control-input" value="ats" @checked($reward->game === 'ats') required>
                        <label class="custom-control-label" for="game-ats">American Truck Simulator</label>
                    </div>
                </div>
                <div class="form-group col-md-4 col-sm-12">
                    <h4>Ступень</h4>
                    <input type="text" class="form-control" id="stage" name="stage" value="{{ old('stage') ?? $reward->stage }}" required>
                    @if($errors->has('stage'))
                        <small class="form-text">{{ $errors->first('stage') }}</small>
                    @endif
                </div>
                <div class="form-group col-md-4 col-sm-12">
                    <h4>Дистанция</h4>
                    <input type="number" class="form-control" id="km" name="km" value="{{ old('km') ?? $reward->km }}" required>
                    @if($errors->has('km'))
                        <small class="form-text">{{ $errors->first('km') }}</small>
                    @endif
                </div>
            </div>
            <h4 class="mt-4">Награды</h4>
            <div class="form-group">
                <textarea class="form-control" id="reward" rows="3" name="reward" required>{{ old('reward') ?? $reward->reward }}</textarea>
                @if($errors->has('reward'))
                    <small class="form-text">{{ $errors->first('reward') }}</small>
                @endif
            </div>
            <div class="row justify-content-center">
                <button type="submit" class="btn btn-outline-success btn-lg m-1">Сохранить</button>
            </div>
        </form>
    </div>
@endsection
