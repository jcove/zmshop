<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoodsModel extends Model
{
    protected $guarded = ['id'];
    protected $dispatchesEvents = [
        'deleting' => \App\Events\GoodsModelDeleting::class,
        'deleted' => \App\Events\GoodsModelDeleted::class,
    ];

}