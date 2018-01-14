<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    public $timestamps = false;

    public function items()
    {
        return $this->hasMany(TagItem::class);
    }
}
