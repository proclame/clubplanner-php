<?php

namespace Proclame\Clubplanner\Models\UnsupportedMethods;

use Proclame\Clubplanner\Exceptions\MethodNotSupportedException;

trait FindNotSupported
{
    public function find($id, $key = 'id')
    {
        throw new MethodNotSupportedException();
    }
}
