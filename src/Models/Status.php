<?php

namespace Proclame\Clubplanner\Models;

use Proclame\Clubplanner\Model;
use Proclame\Clubplanner\Models\UnsupportedMethods\FindNotSupported;

class Status extends Model
{
    use FindNotSupported;

    protected $endpointMultiple = 'Member/GetStatusses';
}
