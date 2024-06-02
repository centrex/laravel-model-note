<?php

declare(strict_types = 1);

namespace Centrex\LaravelModelNote\Commands;

use Illuminate\Console\Command;

class LaravelModelNoteCommand extends Command
{
    public $signature = 'laravel-model-note';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
