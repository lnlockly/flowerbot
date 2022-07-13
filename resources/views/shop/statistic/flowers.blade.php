@extends('layouts.shop')
@section('content')
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Мои цветы</h4>
                @livewire('flowers-table-view')
                <div class="text-right"> <a class="btn btn-primary" href="{{ route('flower.create') }}">Добавить цветок</a></div>
            </div>
        </div>
    </div>
@endsection