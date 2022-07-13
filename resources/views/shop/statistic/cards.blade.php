@extends('layouts.admin')
@section('content')
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Оплата</h4>
                @livewire('cards-table-view')
                <div class="text-right"> <a class="btn btn-primary" href="{{ route('admin.card.create') }}">Добавить карту</a></div>
            </div>
        </div>
    </div>
@endsection