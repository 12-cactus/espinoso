<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed text
 */
class TagItem extends Model
{
    public $timestamps = false;
    protected $fillable = ['tag_id', 'text'];

    public function tag()
    {
        return $this->belongsTo(Tag::class);
    }
}
