@extends('layouts.shop')
@section('content')
<div class="grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <div class="flower-choice">
                <div class="col-sm-2">
                    <label for="inputNumberFlower">Кол-во</label>
                    <input type="number" class="form-control" id="inputNumberFlower">
                </div> 
                <div class="col-sm-2">   
                    <div class="Flower">
                        <label class="label-flower" for="input">Выбрать цветок</label>
                        <select id="flower">       
                            <option value="Flower1">Одуванчик</option>
                            <option value="Flower2">Ландыш</option>
                            <option value="Flower3">Тюльпан</option>
                            <option value="Flower4">Роза</option>
                            <option value="Flower5">Астра</option>
                        </select>
                    </div>
                </div> 
                <a class="addFlower" href="#"><img src="{{ asset('/images/add-circle.svg') }}" /></a>
            </div>
        </div>
    </div>
</div>
@endsection
