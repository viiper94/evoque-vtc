<!-- Vacation application modal -->
<div class="modal fade" id="vacationModal" tabindex="-1" aria-labelledby="rpModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content modal-content-dark">
            <div class="modal-header">
                <h5 class="modal-title">Заявка на отпуск</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" action="{{ route('evoque.applications.vacation') }}">
                <div class="modal-body">
                    @csrf
                    <div class="form-group">
                        <label for="vacation_till">Выберите период отпуска (от и до)</label><br>
                        <input type="hidden" class="form-control" id="vacation_till" name="vacation_till" value="{{ old('vacation_till') }}" readonly required>
                        @error('vacation_till')
                            <small class="form-text">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="vacation-reason">Причина</label>
                        <textarea class="form-control" id="vacation-reason" rows="3" name="reason" placeholder="Не обязательно">{{ old('reason') }}</textarea>
                        @error('reason')
                            <small class="form-text">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-check"></i> Отправить</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    var simplemde = new SimpleMDE({
        element: $('#vacation-reason')[0],
        promptURLs: true
    });
</script>

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
