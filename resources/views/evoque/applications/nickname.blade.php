@extends('layout.index')

@section('title')
    Заявка на смену никнейма | @lang('general.vtc_evoque')
@endsection

@section('content')

    <div class="container pt-5">
        @include('layout.alert')
        <h2 class="mt-3 text-primary">Заявка на смену никнейма</h2>
        <form method="post" class="mb-5">
            @csrf
            <div class="form-group">
                <label for="new_nickname">Новый никнейм</label>
                <input type="text" class="form-control" id="new_nickname" name="new_nickname" placeholder="Без тэга компании!" value="{{ old('new_nickname') }}" required>
                @error('new_nickname')
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
