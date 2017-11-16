<?php

namespace App\Model;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class TelegramChat
 * @package App\Model
 *
 * @property Carbon created_at
 * @property Carbon updated_at
 */
class TelegramChat extends Model
{
    protected $fillable = [
        'id',
        'first_name',
        'last_name',
        'username',
        'type'
    ];
}
