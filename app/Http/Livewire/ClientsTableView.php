<?php

namespace App\Http\Livewire;

use App\Actions\AdminClientAction;
use App\Actions\FlowerClientAction;
use App\Actions\SendNotifyClientAction;
use App\Client;
use Illuminate\Database\Eloquent\Builder;
use LaravelViews\Views\TableView;

class ClientsTableView extends TableView
{

    /**
     * Sets the headers of the table as you want to be displayed
     *
     * @return array<string> Array of headers
     */
    public function headers(): array
    {
        return ['Id', 'Роль', 'Имя', 'Username', 'Первое обращение', 'Последнее сообщение'];
    }

    /**
     * Sets the data to every cell of a single row
     *
     * @param $model Current model for each row
     */
    public function row($model): array
    {
        return [
            $model->telegram_id,
            ucfirst($model->role),
            $model->first_name . ' ' . $model->last_name,
            $model->username,
            $model->created_at,
            $model->updated_at,
        ];
    }
    /**
     * Sets a initial query with the data to fill the table
     *
     * @return Builder Eloquent query
     */
    public function repository(): Builder
    {
        return Client::query()->where('shop_id', auth()->user()->current_shop->id);
    }

    protected function bulkActions()
    {
        return [
            new AdminClientAction(),
            new FlowerClientAction(),
            new SendNotifyClientAction()
        ];
    }
}
