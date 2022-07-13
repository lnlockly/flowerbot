@extends('layouts.admin')
@section('content')
  <div class="col-lg-12">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">Мои клиенты</h4>
        @livewire('users-table-view')
        <div class="text-right"> <a class="btn btn-primary" href="{{ route('admin.user.create') }}">Добавить пользователя</a></div>
      </div>
    </div>
  </div>
@endsection