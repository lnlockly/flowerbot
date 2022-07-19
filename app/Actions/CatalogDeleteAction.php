<?php

namespace App\Actions;

use LaravelViews\Actions\Action;
use LaravelViews\Views\View;

class CatalogDeleteAction extends Action
{
    /**
     * Any title you want to be displayed
     * @var String
     * */
    public $title = "Удалить букет (ы)";

    /**
     * This should be a valid Feather icon string
     * @var String
     */
    public $icon = "";

    /**
     * Execute the action when the user clicked on the button
     *
     * @param Array $selectedModels Array with all the id of the selected models
     * @param $view Current view where the action was executed from
     */
    public function handle($selectedModels, View $view)
    {
        Catalog::whereKey($selectedModels)->delete();
    }
}
