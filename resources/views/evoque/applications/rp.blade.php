@extends('layout.index')

@section('title')
    Заявка на сброс статистики рейтинговых перевозок | @lang('general.vtc_evoque')
@endsection

@section('content')

    <div class="container pt-5">
        @include('layout.alert')
        <h2 class="mt-3 text-primary">Заявка на сброс статистики рейтинговых перевозок</h2>
        <form method="post" class="mb-5">
            @csrf
            <div class="custom-control custom-checkbox mt-3 mb-3">
                <input type="checkbox" class="custom-control-input" id="reset" name="reset" required>
                <label class="custom-control-label" for="reset">Подтверждаю, что желаю сбросить свою статистику в рейтинговых перевозках в ETS2 и ATS</label>
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
