@extends('layout.index')

@section('title')
    Редактирование роли | @lang('general.vtc_evoque')
@endsection

@section('content')

    <div class="container pt-5 pb-5">
        <h3 class="mt-3 text-primary">Редактирование роли</h3>
        <form method="post">
            @csrf
            <div class="form-group">
                <label for="title">Название роли</label>
                <input type="text" class="form-control" id="title" name="title" value="{{ $role->title }}" required>
            </div>
            <div class="form-group">
                <label for="group">Группа роли</label>
                <input type="text" class="form-control" id="group" name="group" value="{{ $role->group }}" required>
            </div>
            <div class="form-group">
                <label for="description">Описание</label>
                <input type="text" class="form-control" id="description" name="description" value="{{ $role->description }}">
            </div>
            <div class="custom-control custom-checkbox mb-2">
                <input type="checkbox" class="custom-control-input" id="admin" name="admin" @if($role->admin)checked @endif>
                <label class="custom-control-label" for="admin">Полные админские права</label>
            </div>
            <div class="custom-control custom-checkbox mb-2">
                <input type="checkbox" class="custom-control-input" id="manage_members" name="manage_members" @if($role->manage_members)checked @endif>
                <label class="custom-control-label" for="manage_members">Может управлять участниками</label>
            </div>
            <div class="custom-control custom-checkbox mb-2">
                <input type="checkbox" class="custom-control-input" id="manage_convoys" name="manage_convoys" @if($role->manage_convoys)checked @endif>
                <label class="custom-control-label" for="manage_convoys">Может создавать и редактировать конвои</label>
            </div>
            <div class="custom-control custom-checkbox mb-2">
                <input type="checkbox" class="custom-control-input" id="manage_table" name="manage_table" @if($role->manage_table)checked @endif>
                <label class="custom-control-label" for="manage_table">Может редактировать таблицу</label>
            </div>
            <div class="custom-control custom-checkbox mb-2">
                <input type="checkbox" class="custom-control-input" id="manage_rp" name="manage_rp" @if($role->manage_rp)checked @endif>
                <label class="custom-control-label" for="manage_rp">Может модерировать рейтинговые перевозки</label>
            </div>
            <div class="custom-control custom-checkbox mb-2">
                <input type="checkbox" class="custom-control-input" id="do_rp" name="do_rp" @if($role->do_rp)checked @endif>
                <label class="custom-control-label" for="do_rp">Может делать рейтинговые перевозки</label>
            </div>
            <div class="custom-control custom-checkbox mb-3">
                <input type="checkbox" class="custom-control-input" id="visible" name="visible" @if($role->visible) checked @endif>
                <label class="custom-control-label" for="visible">Показывать эту роль</label>
            </div>
            <button type="submit" class="btn btn-outline-warning">Сохранить</button>
        </form>
    </div>

@endsection
