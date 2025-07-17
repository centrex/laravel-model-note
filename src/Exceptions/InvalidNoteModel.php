<?php

declare(strict_types = 1);

namespace Centrex\LaravelModelNote\Exceptions;

use Centrex\LaravelModelNote\ModelNote;
use Exception;

/**
 * Exception thrown when an invalid note model is provided.
 *
 * This exception indicates that a provided model class does not properly
 * extend the base ModelNote class required by the package.
 */
class InvalidNoteModel extends Exception
{
    /**
     * Factory method to create a new exception instance.
     *
     * @param  string  $model  The invalid model class name that was provided
     * @return self The created exception instance
     */
    public static function create(string $model): self
    {
        return new self(
            sprintf(
                'The model `%s` is invalid. A valid model must extend `%s`.',
                $model,
                ModelNote::class,
            ),
        );
    }

    /**
     * Additional context about how to resolve this exception.
     *
     * @return array Diagnostic information for debugging
     */
    public function context(): array
    {
        return [
            'documentation'   => 'https://your-package-docs.com/model-requirements',
            'required_parent' => ModelNote::class,
            'suggestion'      => 'Ensure your custom note model extends ' . ModelNote::class,
        ];
    }
}
