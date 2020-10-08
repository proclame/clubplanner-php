<?php

namespace Proclame\Clubplanner\Models;

use Proclame\Clubplanner\Model;
use Proclame\Clubplanner\Models\UnsupportedMethods\GetNotSupported;

class Checkinpoint extends Model
{
    use GetNotSupported;

    protected $endpoint = 'General/GetCheckinPoint';
}
