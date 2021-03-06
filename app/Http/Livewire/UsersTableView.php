<?php

namespace App\Http\Livewire;


use App\Actions\AdminUserAction;
use App\User;
use LaravelViews\Views\TableView;

class UsersTableView extends TableView
{
    /**
     * Sets a model class to get the initial data
     */
    protected $model = User::class;

    /**
     * Sets the headers of the table as you want to be displayed
     *
     * @return array<string> Array of headers
     */
    public function headers(): array
    {
        return ['Id', 'Логин', 'Роль'];
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
            $model->username,
            $model->role
        ];
    }

    protected function bulkActions()
    {
        return [
            new AdminUserAction(),
        ];
    }
}
