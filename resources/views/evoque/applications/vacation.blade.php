@extends('layout.index')

@section('title')
    Заявка на отпуск | @lang('general.vtc_evoque')
@endsection

@section('assets')
    <link rel="stylesheet" type="text/css" href="/js/simplemde/dist/simplemde-dark.min.css">
    <script src="/js/simplemde/dist/simplemde.min.js"></script>
@endsection


@section('content')

    <div class="container pt-5">
        @include('layout.alert')
        <h2 class="mt-3 text-primary">Заявка на отпуск</h2>
        <form method="post" class="mb-5">
            @csrf
            <div class="form-group">
                <label for="vacation_till">Выберите период отпуска (от и до)</label><br>
                <input type="hidden" class="form-control" id="vacation_till" name="vacation_till" value="{{ old('vacation_till') }}" readonly required>
                @error('vacation_till')
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

    <script>
        const picker = new Litepicker({
            element: document.getElementById('vacation_till'),
            plugins: ['mobilefriendly'],
            inlineMode: true,
            lang: 'ru-RU',
            maxDays: 14,
            minDate: Date.now(),
            singleMode: false,
            showTooltip: false,
            numberOfMonths: 2,
            numberOfColumns: 2
        });
    </script>

    <script>
        var simplemde = new SimpleMDE({
            element: $('#reason')[0],
            promptURLs: true
        });
    </script>

@endsection
