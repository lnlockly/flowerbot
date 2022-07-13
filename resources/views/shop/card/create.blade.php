@extends('layouts.admin')
@section('content')

    <div class="grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Добавить карту для оплаты</h4>
                <form class="form-inline" method="post" action="{{ route('admin.card.store') }}">
                    @csrf
                    <div class="form-group row">
                        <label for="name" >Номер карты</label>
                        <div class="col-sm-5 ">
                            <input type="text" class="form-control" name="name" id="name">
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
