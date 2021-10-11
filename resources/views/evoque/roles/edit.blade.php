@extends('layout.index')

@section('title')
    Редактирование роли | @lang('general.vtc_evoque')
@endsection

@section('content')

    <div class="container pt-5 pb-5">
        @include('layout.alert')
        @can('update', \App\Role::class)
            <h3 class="mt-3 text-primary">Редактирование должности</h3>
            <form method="post">
                @csrf
                <div class="form-group">
                    <label for="title">Название должности</label>
                    <input type="text" class="form-control" id="title" name="title" value="{{ $role->title }}" required>
                </div>
                <div class="form-group">
                    <label for="group">Группа должности</label>
                    <input type="text" class="form-control" id="group" name="group" value="{{ $role->group }}" required>
                </div>
                <div class="form-group">
                    <label for="description">Описание</label>
                    <input type="text" class="form-control" id="description" name="description" value="{{ $role->description }}">
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="min_scores">Минимальное количество баллов (включительно)</label>
                        <input type="number" class="form-control" id="min_scores" name="min_scores" value="{{ $role->min_scores }}">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="max_scores">Максимальное количество баллов (включительно)</label>
                        <input type="number" class="form-control" id="max_scores" name="max_scores" value="{{ $role->max_scores }}">
                    </div>
                </div>
                <div class="custom-control custom-checkbox mb-3">
                    <input type="checkbox" class="custom-control-input" id="visible" name="visible" @if($role->visible) checked @endif>
                    <label class="custom-control-label" for="visible">Показывать эту роль в таблице</label>
                </div>
                <button type="submit" class="btn btn-outline-warning">Сохранить</button>
            </form>
        @endcan
        @can('updatePermissions', $role)
            <form action="{{ route('evoque.admin.roles.editPermissions', $role->id) }}" method="post">
                @csrf
                <div class="row my-5">
                    <h1 class="col-12 text-primary mb-3">Права должности на сайте</h1>
                    <div class="col-12">
                        <div class="custom-control custom-checkbox mb-4">
                            <input type="checkbox" class="custom-control-input" id="admin" name="admin" @if($role->admin)checked @endif>
                            <label class="custom-control-label text-danger" for="admin">Полные админские права</label>
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <h3 class="text-primary">Сотрудники</h3>
                        <div class="custom-control custom-checkbox mb-4">
                            <input type="checkbox" class="custom-control-input" id="manage_members" name="manage_members" @if($role->manage_members)checked @endif>
                            <label class="custom-control-label text-danger" for="manage_members">Полные права по управлению сотрудниками</label>
                        </div>
                        <div class="custom-control custom-checkbox mb-2">
                            <input type="checkbox" class="custom-control-input" id="edit_members" name="edit_members" @if($role->edit_members)checked @endif>
                            <label class="custom-control-label" for="edit_members">Редактировать сотрудников</label>
                        </div>
    {{--                    <div class="custom-control custom-checkbox mb-2">--}}
    {{--                        <input type="checkbox" class="custom-control-input" id="edit_members_activity" name="edit_members_activity" @if($role->edit_members_activity)checked @endif>--}}
    {{--                        <label class="custom-control-label" for="edit_activity_members">Редактировать статистику по посещениям сотрудников</label>--}}
    {{--                    </div>--}}
                        <div class="custom-control custom-checkbox mb-2">
                            <input type="checkbox" class="custom-control-input" id="edit_members_rp_stats" name="edit_members_rp_stats" @if($role->edit_members_rp_stats)checked @endif>
                            <label class="custom-control-label" for="edit_members_rp_stats">Редактировать статистику по рейтинговым перевозкам сотрудников</label>
                        </div>
                        <div class="custom-control custom-checkbox mb-2">
                            <input type="checkbox" class="custom-control-input" id="fire_members" name="fire_members" @if($role->fire_members)checked @endif>
                            <label class="custom-control-label" for="fire_members">Увольнять сотрудников</label>
                        </div>
                        <div class="custom-control custom-checkbox mb-2">
                            <input type="checkbox" class="custom-control-input" id="set_members_activity" name="set_members_activity" @if($role->set_members_activity)checked @endif>
                            <label class="custom-control-label" for="set_members_activity">Выставлять баллы, эвики и посещения через таблицу</label>
                        </div>
                        <div class="custom-control custom-checkbox mb-2">
                            <input type="checkbox" class="custom-control-input" id="reset_members_activity" name="reset_members_activity" @if($role->reset_members_activity)checked @endif>
                            <label class="custom-control-label" for="reset_members_activity">Сбрасывать посещения через таблицу</label>
                        </div>
                        <div class="custom-control custom-checkbox mb-2">
                            <input type="checkbox" class="custom-control-input" id="see_bans" name="see_bans" @if($role->see_bans)checked @endif>
                            <label class="custom-control-label" for="see_bans">Видеть баны сотрудников в таблице</label>
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <h3 class="text-primary">Заявки</h3>
                        <div class="custom-control custom-checkbox mb-4">
                            <input type="checkbox" class="custom-control-input" id="manage_applications" name="manage_applications" @if($role->manage_applications)checked @endif>
                            <label class="custom-control-label text-danger" for="manage_applications">Полные права по всем заявкам</label>
                        </div>
                        <div class="custom-control custom-checkbox mb-2">
                            <input type="checkbox" class="custom-control-input" id="view_recruitments" name="view_recruitments" @if($role->view_recruitments)checked @endif>
                            <label class="custom-control-label" for="view_recruitments">Смотреть заявки на вступление</label>
                        </div>
                        <div class="custom-control custom-checkbox mb-2">
                            <input type="checkbox" class="custom-control-input" id="claim_recruitments" name="claim_recruitments" @if($role->claim_recruitments)checked @endif>
                            <label class="custom-control-label" for="claim_recruitments">Принимать или отклонять заявки на вступление</label>
                        </div>
                        <div class="custom-control custom-checkbox mb-4">
                            <input type="checkbox" class="custom-control-input" id="delete_recruitments" name="delete_recruitments" @if($role->delete_recruitments)checked @endif>
                            <label class="custom-control-label" for="delete_recruitments">Удалять заявки на вступление</label>
                        </div>
                        <div class="custom-control custom-checkbox mb-2">
                            <input type="checkbox" class="custom-control-input" id="make_applications" name="make_applications" @if($role->make_applications)checked @endif>
                            <label class="custom-control-label" for="make_applications">Подавать заявки</label>
                        </div>
                        <div class="custom-control custom-checkbox mb-2">
                            <input type="checkbox" class="custom-control-input" id="edit_applications" name="edit_applications" @if($role->edit_applications)checked @endif>
                            <label class="custom-control-label" for="edit_applications">Редактировать свои заявки</label>
                        </div>
                        <div class="custom-control custom-checkbox mb-2">
                            <input type="checkbox" class="custom-control-input" id="view_applications" name="view_applications" @if($role->view_applications)checked @endif>
                            <label class="custom-control-label" for="view_applications">Смотреть все заявки сотрудников</label>
                        </div>
                        <div class="custom-control custom-checkbox mb-2">
                            <input type="checkbox" class="custom-control-input" id="claim_applications" name="claim_applications" @if($role->claim_applications)checked @endif>
                            <label class="custom-control-label" for="claim_applications">Принимать или отклонять заявки сотрудников</label>
                        </div>
                        <div class="custom-control custom-checkbox mb-2">
                            <input type="checkbox" class="custom-control-input" id="delete_applications" name="delete_applications" @if($role->delete_applications)checked @endif>
                            <label class="custom-control-label" for="delete_applications">Удалять заявки сотрудников</label>
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <h3 class="text-primary">Конвои</h3>
                        <div class="custom-control custom-checkbox mb-4">
                            <input type="checkbox" class="custom-control-input" id="manage_convoys" name="manage_convoys" @if($role->manage_convoys)checked @endif>
                            <label class="custom-control-label text-danger" for="manage_convoys">Полные права по конвоям</label>
                        </div>
                        <div class="custom-control custom-checkbox mb-2">
                            <input type="checkbox" class="custom-control-input" id="view_all_convoys" name="view_all_convoys" @if($role->view_all_convoys)checked @endif>
                            <label class="custom-control-label" for="view_all_convoys">Смотреть все регламенты будущих конвоев</label>
                        </div>
                        <div class="custom-control custom-checkbox mb-2">
                            <input type="checkbox" class="custom-control-input" id="book_convoys" name="book_convoys" @if($role->book_convoys)checked @endif>
                            <label class="custom-control-label" for="book_convoys">Проводить конвои (бронировать)</label>
                        </div>
                        <div class="custom-control custom-checkbox mb-2">
                            <input type="checkbox" class="custom-control-input" id="quick_book_convoys" name="quick_book_convoys" @if($role->quick_book_convoys)checked @endif>
                            <label class="custom-control-label" for="quick_book_convoys">Делать быстрые брони (без регламента)</label>
                        </div>
                        <div class="custom-control custom-checkbox mb-2">
                            <input type="checkbox" class="custom-control-input" id="add_convoys" name="add_convoys" @if($role->add_convoys)checked @endif>
                            <label class="custom-control-label" for="add_convoys">Создавать конвои</label>
                        </div>
                        <div class="custom-control custom-checkbox mb-2">
                            <input type="checkbox" class="custom-control-input" id="edit_convoys" name="edit_convoys" @if($role->edit_convoys)checked @endif>
                            <label class="custom-control-label" for="edit_convoys">Редактировать конвои (в т.ч. модерировать брони)</label>
                        </div>
                        <div class="custom-control custom-checkbox mb-2">
                            <input type="checkbox" class="custom-control-input" id="delete_convoys" name="delete_convoys" @if($role->delete_convoys)checked @endif>
                            <label class="custom-control-label" for="delete_convoys">Удалять конвои</label>
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <h3 class="text-primary">Скрин TAB</h3>
                        <div class="custom-control custom-checkbox mb-4">
                            <input type="checkbox" class="custom-control-input" id="manage_tab" name="manage_tab" @if($role->manage_tab)checked @endif>
                            <label class="custom-control-label text-danger" for="manage_tab">Полные права по скрин TAB</label>
                        </div>
                        <div class="custom-control custom-checkbox mb-2">
                            <input type="checkbox" class="custom-control-input" id="view_tab" name="view_tab" @if($role->view_tab)checked @endif>
                            <label class="custom-control-label" for="view_tab">Видеть все скрин TAB</label>
                        </div>
    {{--                    <div class="custom-control custom-checkbox mb-2">--}}
    {{--                        <input type="checkbox" class="custom-control-input" id="add_tab" name="add_tab" @if($role->add_tab)checked @endif>--}}
    {{--                        <label class="custom-control-label" for="add_tab">Выкладывать скрин TAB</label>--}}
    {{--                    </div>--}}
                        <div class="custom-control custom-checkbox mb-2">
                            <input type="checkbox" class="custom-control-input" id="edit_tab" name="edit_tab" @if($role->edit_tab)checked @endif>
                            <label class="custom-control-label" for="edit_tab">Редактировать скрин TAB</label>
                        </div>
                        <div class="custom-control custom-checkbox mb-2">
                            <input type="checkbox" class="custom-control-input" id="accept_tab" name="accept_tab" @if($role->accept_tab)checked @endif>
                            <label class="custom-control-label" for="accept_tab">Принимать или отклонять скрин TAB</label>
                        </div>
                        <div class="custom-control custom-checkbox mb-2">
                            <input type="checkbox" class="custom-control-input" id="delete_tab" name="delete_tab" @if($role->delete_tab)checked @endif>
                            <label class="custom-control-label" for="delete_tab">Удалять скрин TAB</label>
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <h3 class="text-primary">Рейтинговые перевозки</h3>
                        <div class="custom-control custom-checkbox mb-4">
                            <input type="checkbox" class="custom-control-input" id="manage_rp" name="manage_rp" @if($role->manage_rp)checked @endif>
                            <label class="custom-control-label text-danger" for="manage_rp">Полные права по рейтинговым перевозкам</label>
                        </div>
                        <div class="custom-control custom-checkbox mb-2">
                            <input type="checkbox" class="custom-control-input" id="add_reports" name="add_reports" @if($role->add_reports)checked @endif>
                            <label class="custom-control-label" for="add_reports">Выполнять рейтинговые перевозки (выкладывать отчёты)</label>
                        </div>
                        <div class="custom-control custom-checkbox mb-2">
                            <input type="checkbox" class="custom-control-input" id="view_all_reports" name="view_all_reports" @if($role->view_all_reports)checked @endif>
                            <label class="custom-control-label" for="view_all_reports">Смотреть все отчёты</label>
                        </div>
                        <div class="custom-control custom-checkbox mb-2">
                            <input type="checkbox" class="custom-control-input" id="delete_own_reports" name="delete_own_reports" @if($role->delete_own_reports)checked @endif>
                            <label class="custom-control-label" for="delete_own_reports">Удалять свои отчёты</label>
                        </div>
                        <div class="custom-control custom-checkbox mb-2">
                            <input type="checkbox" class="custom-control-input" id="delete_all_reports" name="delete_all_reports" @if($role->delete_all_reports)checked @endif>
                            <label class="custom-control-label" for="delete_all_reports">Удалять любые отчёты</label>
                        </div>
                        <div class="custom-control custom-checkbox mb-2">
                            <input type="checkbox" class="custom-control-input" id="accept_reports" name="accept_reports" @if($role->accept_reports)checked @endif>
                            <label class="custom-control-label" for="accept_reports">Принимать или отклонять отчёты</label>
                        </div>
                        <div class="custom-control custom-checkbox mb-2">
                            <input type="checkbox" class="custom-control-input" id="reset_stats" name="reset_stats" @if($role->reset_stats)checked @endif>
                            <label class="custom-control-label" for="reset_stats">Обнулять недельную статистику</label>
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <h3 class="text-primary">Правила</h3>
                        <div class="custom-control custom-checkbox mb-4">
                            <input type="checkbox" class="custom-control-input" id="manage_rules" name="manage_rules" @if($role->manage_rules)checked @endif>
                            <label class="custom-control-label text-danger" for="manage_rules">Полные права по правилам</label>
                        </div>
                        <div class="custom-control custom-checkbox mb-2">
                            <input type="checkbox" class="custom-control-input" id="add_rules" name="add_rules" @if($role->add_rules)checked @endif>
                            <label class="custom-control-label" for="add_rules">Создавать параграфы правил</label>
                        </div>
                        <div class="custom-control custom-checkbox mb-2">
                            <input type="checkbox" class="custom-control-input" id="edit_rules" name="edit_rules" @if($role->edit_rules)checked @endif>
                            <label class="custom-control-label" for="edit_rules">Редактировать параграфы правила</label>
                        </div>
                        <div class="custom-control custom-checkbox mb-2">
                            <input type="checkbox" class="custom-control-input" id="delete_rules" name="delete_rules" @if($role->delete_rules)checked @endif>
                            <label class="custom-control-label" for="delete_rules">Удалять параграфы правил</label>
                        </div>
                        <div class="custom-control custom-checkbox mb-2">
                            <input type="checkbox" class="custom-control-input" id="view_rules_changelog" name="view_rules_changelog" @if($role->view_rules_changelog)checked @endif>
                            <label class="custom-control-label" for="view_rules_changelog">Смотреть историю изменений правил</label>
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <h3 class="text-primary">Должности</h3>
                        <div class="custom-control custom-checkbox mb-4">
                            <input type="checkbox" class="custom-control-input" id="manage_roles" name="manage_roles" @if($role->manage_roles)checked @endif>
                            <label class="custom-control-label text-danger" for="manage_roles">Полные права по должностям</label>
                        </div>
                        <div class="custom-control custom-checkbox mb-2">
                            <input type="checkbox" class="custom-control-input" id="view_roles" name="view_roles" @if($role->view_roles)checked @endif>
                            <label class="custom-control-label" for="view_roles">Смотреть список должностей</label>
                        </div>
                        <div class="custom-control custom-checkbox mb-2">
                            <input type="checkbox" class="custom-control-input" id="add_roles" name="add_roles" @if($role->add_roles)checked @endif>
                            <label class="custom-control-label" for="add_roles">Создавать должности</label>
                        </div>
                        <div class="custom-control custom-checkbox mb-2">
                            <input type="checkbox" class="custom-control-input" id="edit_roles" name="edit_roles" @if($role->edit_roles)checked @endif>
                            <label class="custom-control-label" for="edit_roles">Редактировать должности</label>
                        </div>
                        <div class="custom-control custom-checkbox mb-2">
                            <input type="checkbox" class="custom-control-input" id="edit_roles_permissions" name="edit_roles_permissions" @if($role->edit_roles_permissions)checked @endif>
                            <label class="custom-control-label" for="edit_roles_permissions">Редактировать права должностей</label>
                        </div>
                        <div class="custom-control custom-checkbox mb-2">
                            <input type="checkbox" class="custom-control-input" id="delete_roles" name="delete_roles" @if($role->delete_roles)checked @endif>
                            <label class="custom-control-label" for="delete_roles">Удалять должности</label>
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <h3 class="text-primary">База знаний</h3>
                        <div class="custom-control custom-checkbox mb-4">
                            <input type="checkbox" class="custom-control-input" id="manage_kb" name="manage_kb" @if($role->manage_kb)checked @endif>
                            <label class="custom-control-label text-danger" for="manage_kb">Полные права базе знаний</label>
                        </div>
                        <div class="custom-control custom-checkbox mb-2">
                            <input type="checkbox" class="custom-control-input" id="view_private" name="view_private" @if($role->view_private)checked @endif>
                            <label class="custom-control-label" for="view_private">Читать не публичные статьи</label>
                        </div>
                        <div class="custom-control custom-checkbox mb-2">
                            <input type="checkbox" class="custom-control-input" id="view_hidden" name="view_hidden" @if($role->view_hidden)checked @endif>
                            <label class="custom-control-label" for="view_hidden">Читать не видимые статьи</label>
                        </div>
                        <div class="custom-control custom-checkbox mb-2">
                            <input type="checkbox" class="custom-control-input" id="add_article" name="add_article" @if($role->add_article)checked @endif>
                            <label class="custom-control-label" for="add_article">Создавать статьи</label>
                        </div>
                        <div class="custom-control custom-checkbox mb-2">
                            <input type="checkbox" class="custom-control-input" id="edit_own_article" name="edit_own_article" @if($role->edit_own_article)checked @endif>
                            <label class="custom-control-label" for="edit_own_article">Редактировать свои статьи</label>
                        </div>
                        <div class="custom-control custom-checkbox mb-2">
                            <input type="checkbox" class="custom-control-input" id="delete_own_article" name="delete_own_article" @if($role->delete_own_article)checked @endif>
                            <label class="custom-control-label" for="delete_own_article">Удалять свои статьи</label>
                        </div>
                        <div class="custom-control custom-checkbox mb-2">
                            <input type="checkbox" class="custom-control-input" id="edit_all_articles" name="edit_all_articles" @if($role->edit_all_articles)checked @endif>
                            <label class="custom-control-label" for="edit_all_articles">Редактировать все статьи</label>
                        </div>
                        <div class="custom-control custom-checkbox mb-2">
                            <input type="checkbox" class="custom-control-input" id="delete_all_articles" name="delete_all_articles" @if($role->delete_all_articles)checked @endif>
                            <label class="custom-control-label" for="delete_all_articles">Удалять все статьи</label>
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <h3 class="text-primary">Пользователи сайта</h3>
                        <div class="custom-control custom-checkbox mb-4">
                            <input type="checkbox" class="custom-control-input" id="manage_users" name="manage_users" @if($role->manage_users)checked @endif>
                            <label class="custom-control-label text-danger" for="manage_users">Полные права по пользователям сайта</label>
                        </div>
                        <div class="custom-control custom-checkbox mb-2">
                            <input type="checkbox" class="custom-control-input" id="view_users" name="view_users" @if($role->view_users)checked @endif>
                            <label class="custom-control-label" for="view_users">Просматривать список пользователей сайта</label>
                        </div>
                        <div class="custom-control custom-checkbox mb-2">
                            <input type="checkbox" class="custom-control-input" id="set_user_as_member" name="set_user_as_member" @if($role->set_user_as_member)checked @endif>
                            <label class="custom-control-label" for="set_user_as_member">Назначать пользователя сотрудником</label>
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <h3 class="text-primary">Галлерея</h3>
                        <div class="custom-control custom-checkbox mb-4">
                            <input type="checkbox" class="custom-control-input" id="manage_gallery" name="manage_gallery" @if($role->manage_gallery)checked @endif>
                            <label class="custom-control-label text-danger" for="manage_gallery">Полные права по галлерее</label>
                        </div>
                        <div class="custom-control custom-checkbox mb-2">
                            <input type="checkbox" class="custom-control-input" id="upload_screenshots" name="upload_screenshots" @if($role->upload_screenshots)checked @endif>
                            <label class="custom-control-label " for="upload_screenshots">Загружать скриншоты в галлерею</label>
                        </div>
                        <div class="custom-control custom-checkbox mb-2">
                            <input type="checkbox" class="custom-control-input" id="upload_without_moderation" name="upload_without_moderation" @if($role->upload_without_moderation)checked @endif>
                            <label class="custom-control-label" for="upload_without_moderation">Загружать скриншоты без премодерации</label>
                        </div>
                        <div class="custom-control custom-checkbox mb-2">
                            <input type="checkbox" class="custom-control-input" id="toggle_visibility" name="toggle_visibility" @if($role->toggle_visibility)checked @endif>
                            <label class="custom-control-label" for="toggle_visibility">Скроывать или показывать скриншоты</label>
                        </div>
                        <div class="custom-control custom-checkbox mb-2">
                            <input type="checkbox" class="custom-control-input" id="delete_screenshots" name="delete_screenshots" @if($role->delete_screenshots)checked @endif>
                            <label class="custom-control-label" for="delete_screenshots">Удалять скриншоты</label>
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <h3 class="text-primary">Тест</h3>
                        <div class="custom-control custom-checkbox mb-4">
                            <input type="checkbox" class="custom-control-input" id="manage_test" name="manage_test" @if($role->manage_test)checked @endif>
                            <label class="custom-control-label text-danger" for="manage_test">Полные права по тесту и вопросам</label>
                        </div>
                        <div class="custom-control custom-checkbox mb-2">
                            <input type="checkbox" class="custom-control-input" id="add_questions" name="add_questions" @if($role->add_questions)checked @endif>
                            <label class="custom-control-label " for="add_questions">Добавлять вопросы</label>
                        </div>
                        <div class="custom-control custom-checkbox mb-2">
                            <input type="checkbox" class="custom-control-input" id="edit_questions" name="edit_questions" @if($role->edit_questions)checked @endif>
                            <label class="custom-control-label" for="edit_questions">Редактировать вопросы</label>
                        </div>
                        <div class="custom-control custom-checkbox mb-2">
                            <input type="checkbox" class="custom-control-input" id="delete_questions" name="delete_questions" @if($role->delete_questions)checked @endif>
                            <label class="custom-control-label" for="delete_questions">Удалять вопросы</label>
                        </div>
                        <div class="custom-control custom-checkbox mb-2">
                            <input type="checkbox" class="custom-control-input" id="view_results" name="view_results" @if($role->view_results)checked @endif>
                            <label class="custom-control-label" for="view_results">Смотреть результаты всех сотрудников</label>
                        </div>
                        <div class="custom-control custom-checkbox mb-2">
                            <input type="checkbox" class="custom-control-input" id="delete_results" name="delete_results" @if($role->delete_results)checked @endif>
                            <label class="custom-control-label" for="delete_results">Удалять результаты</label>
                        </div>
                        <div class="custom-control custom-checkbox mb-2">
                            <input type="checkbox" class="custom-control-input" id="do_test" name="do_test" @if($role->do_test)checked @endif>
                            <label class="custom-control-label" for="do_test">Проходить тест</label>
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <h3 class="text-primary">Официальный тюнинг</h3>
                        <div class="custom-control custom-checkbox mb-4">
                            <input type="checkbox" class="custom-control-input" id="manage_tunings" name="manage_tunings" @if($role->manage_tunings)checked @endif>
                            <label class="custom-control-label text-danger" for="manage_tunings">Полные права по разделу тюнинга</label>
                        </div>
                        <div class="custom-control custom-checkbox mb-2">
                            <input type="checkbox" class="custom-control-input" id="add_tunings" name="add_tunings" @if($role->add_tunings)checked @endif>
                            <label class="custom-control-label " for="add_tunings">Добавлять тюнинг</label>
                        </div>
                        <div class="custom-control custom-checkbox mb-2">
                            <input type="checkbox" class="custom-control-input" id="edit_tunings" name="edit_tunings" @if($role->edit_tunings)checked @endif>
                            <label class="custom-control-label" for="edit_tunings">Редактировать тюнинг</label>
                        </div>
                        <div class="custom-control custom-checkbox mb-2">
                            <input type="checkbox" class="custom-control-input" id="delete_tunings" name="delete_tunings" @if($role->delete_tunings)checked @endif>
                            <label class="custom-control-label" for="delete_tunings">Удалять тюнинг</label>
                        </div>
                        <div class="custom-control custom-checkbox mb-2">
                            <input type="checkbox" class="custom-control-input" id="view_tunings" name="view_tunings" @if($role->view_tunings)checked @endif>
                            <label class="custom-control-label" for="view_tunings">Смотреть тюнинги</label>
                        </div>
                    </div>

                </div>
                <button type="submit" class="btn btn-outline-warning">Сохранить права</button>
            </form>
        @endcan
    </div>

@endsection
