<?php

namespace App\Http\Livewire;

use App\Card;
use Illuminate\Database\Eloquent\Builder;
use LaravelViews\Views\TableView;

class CardsTableView extends TableView
{
    public function repository(): Builder
    {
        return Card::query()->where('shop_id', auth()->user()->current_shop->id);
    }
    /**
     * Sets the headers of the table as you want to be displayed
     *
     * @return array<string> Array of headers
     */
    public function headers(): array
    {
        return ['ID', 'Номер карты'];
    }

    /**
     * Sets the data to every cell of a single row
     *
     * @param $model Current model for each row
     */
    public function row($model): array
    {
        return [
            $model->id,
            $model->name
        ];
    }
}
