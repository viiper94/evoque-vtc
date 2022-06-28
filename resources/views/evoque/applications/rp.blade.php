<!-- RP application modal -->
<div class="modal fade" id="rpModal" tabindex="-1" aria-labelledby="rpModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content modal-content-dark">
            <div class="modal-header">
                <h5 class="modal-title">Заявка на смену уровня в рейтинговых перевозках</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" action="{{ route('evoque.applications.rp') }}">
                <div class="modal-body">
                    @csrf
                    <div class="custom-control custom-radio">
                        <input type="radio" id="game-ets2" name="game" class="custom-control-input" value="ets2" required>
                        <label class="custom-control-label" for="game-ets2">Euro Truck Simulator 2</label>
                    </div>
                    <div class="custom-control custom-radio">
                        <input type="radio" id="game-ets2_promods" name="game" class="custom-control-input" value="ets2_promods" required>
                        <label class="custom-control-label" for="game-ets2_promods">ProMods ETS2</label>
                    </div>
                    <div class="custom-control custom-radio">
                        <input type="radio" id="game-ats" name="game" class="custom-control-input" value="ats" required>
                        <label class="custom-control-label" for="game-ats">American Truck Simulator</label>
                    </div>
                    <div class="form-group">
                        <label for="new_rp_profile">Уровень в игре</label>
                        <input type="text" class="form-control" id="new_rp_profile" name="new_rp_profile" value="{{ old('new_rp_profile') }}" required>
                        @error('new_plate_number')
                        <small class="form-text">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="rp-reason">Причина</label>
                        <textarea class="form-control" id="rp-reason" rows="3" name="reason" placeholder="Не обязательно">{{ old('reason') }}</textarea>
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
        element: $('#rp-reason')[0],
        promptURLs: true
    });
</script>
