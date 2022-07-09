<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    public function shop() {
        $this->belongsTo(Shop::class);
    }
}
