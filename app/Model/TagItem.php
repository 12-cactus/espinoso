<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class TagItem extends Model
{
    public $timestamps = false;

    public function tag()
    {
        return $this->belongsTo(Tag::class);
    }
}
