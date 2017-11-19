<?php

namespace App\Model;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class TelegramChat
 * @package App\Model
 *
 * @property int id
 * @property string type
 * @property string title
 * @property string username
 * @property string first_name
 * @property string last_name
 * @property bool all_members_are_administrators
 * @property string photo
 * @property string description
 * @property Carbon created_at
 * @property Carbon updated_at
 */
class TelegramChat extends Model
{
}
