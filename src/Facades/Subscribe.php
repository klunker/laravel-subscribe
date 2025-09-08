<?php

namespace Klunker\LaravelSubscribe\Facades;

use Illuminate\Support\Facades\Facade;

class Subscribe extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return \Klunker\LaravelSubscribe\Subscribe::class;
    }
}