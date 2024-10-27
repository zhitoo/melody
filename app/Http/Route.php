<?php

namespace App\Http;

use Attribute;

#[Attribute]
class Route
{
    public function __construct(string $url, string $method, string $name = null)
    {
        //
    }
}
