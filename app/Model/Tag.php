<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    public $timestamps = false;
    protected $fillable = ['telegram_chat_id', 'name'];

    public function items()
    {
        return $this->hasMany(TagItem::class);
    }
}
