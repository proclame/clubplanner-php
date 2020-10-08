<?php

namespace Proclame\Clubplanner\Models;

use Proclame\Clubplanner\Model;
use Proclame\Clubplanner\Models\UnsupportedMethods\FindNotSupported;

class SubscriptionGroup extends Model
{
    use FindNotSupported;

    protected $endpoint = 'Member/GetSubscriptionGroup';
}
