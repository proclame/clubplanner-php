<?php

namespace Proclame\Clubplanner\Models;

use Proclame\Clubplanner\Model;
use Proclame\Clubplanner\Models\UnsupportedMethods\FindNotSupported;

class Subscription extends Model
{
    use FindNotSupported;

    protected $endpoint = 'Member/GetSubscription';
}
