<?php

namespace Proclame\Clubplanner\Models;

use Proclame\Clubplanner\Model;
use Proclame\Clubplanner\Models\UnsupportedMethods\FindNotSupported;

class Calendar extends Model
{
    use FindNotSupported;

    protected $endpoint = 'Planner/GetCalendar';
}
