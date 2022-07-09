@extends('layouts.shop')
@section('content')
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Мои цветы</h4>
                @livewire('flowers-table-view')
            </div>
        </div>
    </div>
@endsection