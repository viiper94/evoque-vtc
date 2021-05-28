<div class="row my-5 py-5 flex-column align-items-center">
    <h2>Тест пройден!</h2>
    <p class="text-muted">Следующая попытка будет открыта через 1 месяц</p>
    <h3 class="my-5">Ваш результат {{ count($correct) }}/{{ $count }}</h3>
    <a href="{{ route('evoque.test', 1) }}" class="btn btn-sm btn-primary m-auto">Смотреть ответы</a>
</div>
