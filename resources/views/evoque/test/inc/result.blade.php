<div class="row my-5 py-5 flex-column align-items-center">
    @if($count > 0)
        <h2>Вы ответили на все вопросы!</h2>
        <p class="text-muted">Через {{ $first_answered->created_at->addDays(31)->diffInDays(\Carbon\Carbon::now()) }} дней ваши результаты будут обнулены.</p>
        <h3 class="my-5">Результат {{ count($correct) }}/{{ $count }}</h3>
        <a href="{{ route('evoque.test', 1) }}" class="btn btn-sm btn-primary m-auto">Смотреть ответы</a>
    @else
        <h2>Еще нет вопросов!</h2>
    @endif
</div>
