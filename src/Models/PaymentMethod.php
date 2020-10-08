<?php

namespace Proclame\Clubplanner\Models;

use Proclame\Clubplanner\Model;
use Proclame\Clubplanner\Models\UnsupportedMethods\FindNotSupported;
use Proclame\Clubplanner\Models\UnsupportedMethods\GetNotSupported;

class PaymentMethod extends Model
{
    use FindNotSupported;
    protected $endpoint = 'General/GetPaymentMethod';
}
