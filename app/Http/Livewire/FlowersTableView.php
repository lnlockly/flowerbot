<?php

namespace App\Http\Livewire;

use LaravelViews\Views\TableView;
use Illuminate\Database\Eloquent\Builder;
use LaravelViews\Facades\UI;
use App\Flower;

class FlowersTableView extends TableView
{
    /**
     * Sets a initial query with the data to fill the table
     *
     * @return Builder Eloquent query
     */
    public function repository(): Builder
    {
        return Flower::query()->where('shop_id', auth()->user()->current_shop->id);
    }
    /**
     * Sets the headers of the table as you want to be displayed
     *
     * @return array<string> Array of headers
     */
    public function headers(): array
    {
        return ['ID', 'Название цветка'];
    }

    /**
     * Sets the data to every cell of a single row
     *
     * @param $model Current model for each row
     */
    public function row($model): array
    {
        return [
            UI::editable($model, 'id'),
            UI::editable($model, 'name'),
        ];
    }

    public function update(Flower $flower, $data)
    {
        $flower->update($data);
    }
}