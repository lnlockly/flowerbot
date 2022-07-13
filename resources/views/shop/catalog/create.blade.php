@extends('layouts.shop')
@section('content')
<div id="app2">
    <div class="grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Добавить букет</h4>
                <form class="form-inline" method="post" action="{{ route('catalog.store') }}">
                    @csrf
                    <div class="form-group row">
                        <label for="name" >Категория букета</label>
                        <div class="col-sm-5 ">
                            <input type="text" class="form-control" name="section1" id="section1">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="section1">Название</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" name="name" id="name">
                        </div>
                    </div>
                    <div class="form-group row" v-for="(item, index) in flowers">
                        <div class="col-sm-2">
                            <label for="inputNumberFlower">Кол-во</label>
                            <input type="number" class="form-control" id="inputNumberFlower" min="1" :name="'flowers['+ index +']' + '[count]'">
                        </div>
                        <div class="col-sm-2">
                            <div class="Flower">
                                <label class="label-flower" for="input">Выбрать цветок</label>
                                <select id="flower" :name="'flowers['+ index +']' + '[id]'">
                                    @foreach($flowers as $flower)
                                        <option  value="{{ $flower->id }}">{{ $flower->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <a @click="addFlower">Add</a>
                    </div>
                    <div class="form-group row">
                        <label for="img">Ссылка на изображение товара, услуги</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" id="img" name="img">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="price">Цена товара</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" id="price" name="price">
                        </div>
                    </div>
                    <div class="button-css">
                        <button type="submit" class="btn btn-primary me-2">Добавить букет</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/vue@2.7.0/dist/vue.js"></script>
<script>
    new Vue({
        el: '#app2',
        data: {
          flowers: 1
        },
        methods: {
            addFlower() {
                this.flowers += 1;
            }
        }
    })
</script>
<style>

    .flower-choice{
        display: flex;
        padding-left: 50px;
    }
    .col-sm-2{
        padding-left: 30px;
        padding-top: 5px;
        padding-bottom: 10px;
    }
    #flower{
        font-size:x-large;
        padding-top: 2px;
    }
    .Flower{
        padding-top: 10px;
    }
    .addFlower{
        display: flex;
        padding-left: 40px;
        padding-top: 24px;
        height: 70px;
        width: 70px;
    }
</style>
@endsection
