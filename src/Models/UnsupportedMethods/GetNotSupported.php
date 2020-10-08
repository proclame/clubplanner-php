<?php

namespace Proclame\Clubplanner\Models\UnsupportedMethods;

use Proclame\Clubplanner\Exceptions\MethodNotSupportedException;

trait GetNotSupported
{
    public function get($attributes = [])
    {
        throw new MethodNotSupportedException();
    }
}
