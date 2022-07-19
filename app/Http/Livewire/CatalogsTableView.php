<?php

namespace App\Http\Livewire;

use App\Actions\CatalogDeleteAction;
use App\Catalog;
use Illuminate\Database\Eloquent\Builder;
use LaravelViews\Facades\UI;
use LaravelViews\Facades\Header;
use LaravelViews\Views\TableView;

class CatalogsTableView extends TableView
{
    /**
     * Sets a initial query with the data to fill the table
     *
     * @return Builder Eloquent query
     */
    public function repository(): Builder
    {
        return Catalog::query()->where('shop_id', auth()->user()->current_shop->id);
    }
    /**
     * Sets the headers of the table as you want to be displayed
     *
     * @return array<string> Array of headers
     */
    public function headers(): array
    {
        return [
            Header::title('Раздел'),
            Header::title('Название'),
            Header::title('Ссылка на изображение'),
            Header::title('Цена'),
        ];
    }

    /**
     * Sets the data to every cell of a single row
     *
     * @param $model Current model for each row
     */
    public function row($model): array
    {
        return [
            UI::editable($model, 'section1'),
            UI::editable($model, 'name'),
            UI::editable($model, 'img'),
            UI::editable($model, 'price'),
        ];
    }

    public function update(Catalog $catalog, $data)
    {
        $catalog->update($data);
    }


}
