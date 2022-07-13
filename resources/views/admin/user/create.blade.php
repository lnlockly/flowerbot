@extends('layouts.admin')
@section('content')
    <div class="grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Добавить пользователя</h4>
                <form class="form-inline" method="post" action="{{ route('admin.user.store') }}">
                    @csrf
                    <div class="form-group row">
                        <label for="name">Имя</label>
                        <div class="col-sm-5 ">
                            <input type="text" class="form-control" name="name" id="name">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="username">Логин</label>
                        <div class="col-sm-5 ">
                            <input type="text" class="form-control" name="username" id="username">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="password">Пароль</label>
                        <div class="col-sm-5 ">
                            <input type="password" class="form-control" name="password" id="password">
                        </div>
                    </div>
                    <div class="button-css">
                        <button type="submit" class="btn btn-primary me-2">Добавить</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
