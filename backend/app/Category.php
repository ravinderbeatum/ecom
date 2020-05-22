<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'category_name', 'user_id', 'order_counter_id',
    ];

    public function products()
    {
        return $this->hasMany('App\Product');
    }
}
