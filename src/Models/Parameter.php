<?php

namespace Proclame\Clubplanner\Models;

use Proclame\Clubplanner\Model;
use Proclame\Clubplanner\Models\UnsupportedMethods\FindNotSupported;

class Parameter extends Model
{
    use FindNotSupported;

    protected $endpointMultiple = 'general/GetParameter'; // required arguments = parameter & ownerid
}
