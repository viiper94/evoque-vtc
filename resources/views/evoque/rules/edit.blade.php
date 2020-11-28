@extends('layout.index')

@section('title')
    Редактирование правил | @lang('general.vtc_evoque')
@endsection

@section('assets')
    <link rel="stylesheet" type="text/css" href="/js/simplemde/dist/simplemde-dark.min.css">
    <script src="/js/simplemde/dist/simplemde.min.js"></script>
@endsection

@section('content')

    <div class="container pt-5 pb-5">
        @include('layout.alert')
        @if($rules->paragraph)
            <h3 class="text-primary mt-3">Редактирование параграфа {{ $rules->paragraph }} правил</h3>
        @else
            <h3 class="text-primary mt-3">Новый параграф правил</h3>
        @endif
        <form method="post">
            @csrf
            <div class="custom-control custom-radio">
                <input type="radio" id="private" name="public" value="true" class="custom-control-input" @if($rules->public) checked @endif>
                <label class="custom-control-label" for="private">Публичные правила</label>
            </div>
            <div class="custom-control custom-radio">
                <input type="radio" id="public" name="public" value="false" class="custom-control-input" @if(!$rules->public) checked @endif>
                <label class="custom-control-label" for="public">Закрытые правила</label>
            </div>
            <div class="row">
                <div class="form-group col-sm-3">
                    <label for="paragraph">Параграфа №</label>
                    <input type="number" class="form-control" name="paragraph" id="paragraph" value="{{ $rules->paragraph }}" required>
                    @if($errors->has('paragraph'))
                        <small class="form-text">{{ $errors->first('paragraph') }}</small>
                    @endif
                </div>
                <div class="form-group col-sm-9">
                    <label for="title">Заголовок параграфа</label>
                    <input type="text" class="form-control" name="title" id="title" value="{{ $rules->title }}" required>
                    @if($errors->has('title'))
                        <small class="form-text">{{ $errors->first('title') }}</small>
                    @endif
                </div>
            </div>
            <div class="form-group">
                <label for="text">Текст параграфа</label>
                <textarea name="text" id="text" required>{{ $rules->text }}</textarea>
                @if($errors->has('text'))
                    <small class="form-text">{{ $errors->first('text') }}</small>
                @endif
            </div>
            <button type="submit" class="btn btn-outline-warning">Сохранить</button>
            @can('delete', \App\Rules::class)
                @if($rules->paragraph)
                    <a href="{{ route('evoque.rules.delete', $rules->id) }}" class="btn btn-outline-danger"
                       onclick="return confirm('Удалить этот параграф правил?')"><i class="fas fa-trash"></i> Удалить</a>
                @endif
            @endcan
        </form>
        @if($rules->paragraph)
            @can('viewChangelog', \App\Rules::class)
                <h2 class="mt-5 text-primary">История изменений</h2>
                @foreach($changelog as $item)
                    <div class="changelog-item mt-3 mb-5">
                        <h3 class="text-primary">{{ $item->created_at->format('d.m.Y H:i') }}</h3>
                        <h4>Отредактировал {{ $item->user->member->nickname }} </h4>
                        <div class="table-responsive">
                            <table class="table table-dark table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th scope="col">Параметр</th>
                                    <th scope="col">Было</th>
                                    <th scope="col">Стало</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($item->old_values as $key => $value)
                                    <tr>
                                        <td>{{ $key }}</td>
                                        <td>{!! $value !!}</td>
                                        <td>{!! $item->new_values[$key] !!}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endforeach
            @endcan
        @endif
    </div>

    <script>
        var simplemde = new SimpleMDE({
            element: $('#text')[0],
            promptURLs: true
        });
    </script>

@endsection

