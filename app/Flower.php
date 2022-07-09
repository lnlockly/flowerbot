<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Flower extends Model
{
    public function catalog() {
        $this->belongsTo(Catalog::class);
    }
}
