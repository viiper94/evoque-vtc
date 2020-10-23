@extends('layout.index')

@section('title')
    Заявка на сброс статистики рейтинговых перевозок | @lang('general.vtc_evoque')
@endsection

@section('content')

    <div class="container pt-5">
        @include('layout.alert')
        <h2 class="mt-3 text-primary">Заявка на смену уровня в рейтинговых перевозках</h2>
        <form method="post" class="mb-5">
            @csrf
            <div class="custom-control custom-radio">
                <input type="radio" id="game-ets2" name="game" class="custom-control-input" value="ets2" required>
                <label class="custom-control-label" for="game-ets2">Euro Truck Simulator 2</label>
            </div>
            <div class="custom-control custom-radio">
                <input type="radio" id="game-ats" name="game" class="custom-control-input" value="ats" required>
                <label class="custom-control-label" for="game-ats">American Truck Simulator</label>
            </div>
            <div class="form-group">
                <label for="new_rp_profile">Уровень в игре</label>
                <input type="text" class="form-control" id="new_rp_profile" name="new_rp_profile" value="{{ old('new_rp_profile') }}" required>
                @error('new_plate_number')
                <small class="form-text">{{ $message }}</small>
                @enderror
            </div>
            <div class="form-group">
                <label for="reason">Причина</label>
                <textarea class="form-control" id="reason" rows="3" name="reason" placeholder="Не обязательно">{{ old('reason') }}</textarea>
                @error('reason')
                    <small class="form-text">{{ $message }}</small>
                @enderror
            </div>
            <button type="submit" class="btn btn-outline-warning"><i class="fas fa-check"></i> Отправить</button>
        </form>
    </div>

@endsection
