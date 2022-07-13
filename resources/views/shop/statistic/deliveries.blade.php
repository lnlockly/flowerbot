@extends('layouts.admin')
@section('content')
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Доставка</h4>
                @livewire('deliveries-table-view')
                <div class="text-right"> <a class="btn btn-primary" href="{{ route('admin.delivery.create') }}">Добавить район</a></div>
            </div>
        </div>
    </div>
@endsection