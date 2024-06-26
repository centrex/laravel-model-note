<?php

declare(strict_types = 1);

namespace Centrex\LaravelModelNote\Exceptions;

use Exception;

class InvalidNoteModel extends Exception
{
    public static function create(string $model): self
    {
        return new self("The model `{$model}` is invalid. A valid model must extend the model \Centrex\LaravelModelNote\ModelNote.");
    }
}
