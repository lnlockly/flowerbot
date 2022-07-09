@extends('layouts.shop')
@section('content')

    <div class="grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Добавить товар</h4>
                <form class="form-inline" method="post" action="{{ route('flower.save') }}">
                    @csrf
                    <div class="form-group row">
                        <label for="name" >Название цветка</label>
                        <div class="col-sm-5 ">
                            <input type="text" class="form-control" name="name" id="name">
                        </div>
                    </div>
                    <div class="button-css">
                        <button type="submit" class="btn btn-primary me-2">Добавить товар</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
