 <!-- Fire application modal -->
    <div class="modal fade" id="fireModal" tabindex="-1" aria-labelledby="fireModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content modal-content-dark">
                <div class="modal-header">
                    <h5 class="modal-title">Заявка на увольнение</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="post" action="{{ route('evoque.applications.fire') }}">
                    <div class="modal-body">
                        @csrf
                        <div class="custom-control custom-checkbox mt-3 mb-3">
                            <input type="checkbox" class="custom-control-input" id="fire" name="fire" required>
                            <label class="custom-control-label" for="fire">Подтверждаю, что желаю уволиться из компании</label>
                        </div>
                        <div class="form-group">
                            <label for="fire-reason">Причина</label>
                            <textarea class="form-control" id="fire-reason" rows="3" name="reason" placeholder="Не обязательно">{{ old('reason') }}</textarea>
                            @error('reason')
                            <small class="form-text">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-check"></i> Отправить</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> Та ну...</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

     <script>
         var simplemde = new SimpleMDE({
             element: $('#fire-reason')[0],
             promptURLs: true
         });
     </script>
