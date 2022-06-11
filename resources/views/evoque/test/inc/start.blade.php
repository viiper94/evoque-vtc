<div class="row my-5 flex-column text-center">
    <h4>Пройдено вопросов: {{ count($results) }}/{{ $count }}</h4>
    <h6 class="text-muted mb-5">Для отображения результатов пройдите все вопросы</h6>
    <a href="{{ route('evoque.test', 1) }}" class="btn btn-lg btn-primary m-auto">@if(count($results) > 0)Продолжить@elseНачать@endif тест</a>
</div>
