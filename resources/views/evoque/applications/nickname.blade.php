<!-- Nickname application modal -->
<div class="modal fade" id="nicknameModal" tabindex="-1" aria-labelledby="nicknameModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content modal-content-dark">
            <div class="modal-header">
                <h5 class="modal-title">Заявка на смену ника</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" action="{{ route('evoque.applications.nickname') }}">
                <div class="modal-body">
                    @csrf
                    <div class="form-group">
                        <label for="new_nickname">Новый никнейм</label>
                        <input type="text" class="form-control" id="new_nickname" name="new_nickname" placeholder="Без тэга компании!" value="{{ old('new_nickname') }}" required>
                        @error('new_nickname')
                            <small class="form-text">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="nickname-reason">Причина</label>
                        <textarea class="form-control" id="nickname-reason" rows="3" name="reason" placeholder="Не обязательно">{{ old('reason') }}</textarea>
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
        element: $('#nickname-reason')[0],
        promptURLs: true
    });
</script>
