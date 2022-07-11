@extends('layouts.shop')
@section('content')

    <div class="grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Добавить вариант доставки</h4>
                <form class="form-inline" method="post" action="{{ route('delivery.store') }}">
                    @csrf
                    <div class="form-group row">
                        <label for="name" >Район доставки</label>
                        <div class="col-sm-5 ">
                            <input type="text" class="form-control" name="name" id="name">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="price" >Цена доставки</label>
                        <div class="col-sm-5 ">
                            <input type="number" class="form-control" name="price" id="price">
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
