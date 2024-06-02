<?php

declare(strict_types = 1);

namespace Centrex\LaravelModelNote\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Centrex\LaravelModelNote\LaravelModelNote
 */
class LaravelModelNote extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Centrex\LaravelModelNote\LaravelModelNote::class;
    }
}
