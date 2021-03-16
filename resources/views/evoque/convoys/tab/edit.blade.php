@extends('layout.index')

@section('title')
    @if($tab->screenshot)
        Редактирование скрин TAB
    @else
        Подать скрин TAB
    @endif
    | @lang('general.vtc_evoque')
@endsection

@section('content')

    <div class="container pt-5 pb-5">
        @include('layout.alert')
        @if($tab->screenshot)
            <h2 class="mt-3 text-primary text-center">Редактирование скрин TAB</h2>
        @else
            <h2 class="mt-3 text-primary text-center">Подать скрин TAB</h2>
        @endif
        <form method="post" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label for="convoy_title">Название конвоя</label>
                        <input type="text" class="form-control" id="convoy_title" name="convoy_title" value="{{ $tab->convoy_title }}" required>
                        @if($errors->has('convoy_title'))
                            <small class="form-text">{{ $errors->first('convoy_title') }}</small>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="lead_id">Ведущий</label>
                        <select class="form-control" id="lead_id" name="lead_id" required>
                            @foreach($members as $member)
                                <option value="{{ $member->id }}" @if($member->nickname === \Illuminate\Support\Facades\Auth::user()->member->nickname) selected @endif >
                                    {{ $member->nickname }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="description">Дополнительная информация</label>
                        <textarea class="form-control" id="description" rows="2" name="description" placeholder="Кто не доехал? Кому минус бал? Кому не защитать?">{{ $tab->description }}</textarea>
                        @if($errors->has('description'))
                            <small class="form-text">{{ $errors->first('description') }}</small>
                        @endif
                    </div>
                </div>
                <div class="col-auto">
                    <div class="form-group">
                        <label for="date">Дата конвоя</label><br>
                        <input type="hidden" name="date" id="date" value="{{ $tab->date }}" required>
                        @if($errors->has('date'))
                            <small class="form-text">{{ $errors->first('date') }}</small>
                        @endif
                    </div>
                </div>
            </div>
            <div class="form-group pt-3">
                <div class="custom-file custom-file-dark mb-3">
                    <input type="file" class="custom-file-input uploader" id="screenshot" name="screenshot" accept="image/*">
                    <label class="custom-file-label" for="screenshot">Выберите скрин</label>
                </div>
                <img src="{{ $tab->screenshot ? '/images/convoys/tab/'.$tab->screenshot : '' }}" class="w-100" id="screenshot-preview">
            </div>
            <div class="row justify-content-center">
                <button class="btn btn-outline-warning btn-lg" type="submit"><i class="fas fa-save"></i> Сохранить</button>
            </div>
        </form>
    </div>

    <script>
        const picker = new Litepicker({
            element: document.getElementById('date'),
            plugins: ['mobilefriendly'],
            lang: 'ru-RU',
            inlineMode: true,
            maxDate: Date.now()
        });
    </script>

@endsection
