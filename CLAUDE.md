# CLAUDE.md

## Package Overview

`centrex/laravel-model-note` — Polymorphic note system for any Eloquent model.

Namespace: `Centrex\LaravelModelNote\`  
Service Provider: `LaravelModelNoteServiceProvider`  
Main class: `ModelNote`  
Trait: `HasNotes`

## Commands

Run from inside this directory (`cd laravel-model-note`):

```sh
composer install          # install dependencies
composer test             # full suite: rector dry-run, pint check, phpstan, pest
composer test:unit        # pest tests only
composer test:lint        # pint style check (read-only)
composer test:types       # phpstan static analysis
composer test:refacto     # rector refactor check (read-only)
composer lint             # apply pint formatting
composer refacto          # apply rector refactors
composer analyse          # phpstan (alias)
composer build            # prepare testbench workbench
composer start            # build + serve testbench dev server
```

Run a single test:
```sh
vendor/bin/pest tests/ExampleTest.php
vendor/bin/pest --filter "test name"
```

## Structure

```
src/
  ModelNote.php                     # Note Eloquent model
  LaravelModelNoteServiceProvider.php
  HasNotes.php                      # Trait — add to any model
  Commands/
  Events/
  Exceptions/
config/config.php
database/migrations/
tests/
workbench/
```

## Usage

```php
use Centrex\LaravelModelNote\HasNotes;

class Order extends Model
{
    use HasNotes;
}

$order->addNote('Customer requested expedited shipping');
$notes = $order->notes;
```

## Conventions

- PHP 8.2+, `declare(strict_types=1)` in all files
- Pest for tests, snake_case test names
- Pint with `laravel` preset
- Rector targeting PHP 8.3 with `CODE_QUALITY`, `DEAD_CODE`, `EARLY_RETURN`, `TYPE_DECLARATION`, `PRIVATIZATION` sets
- PHPStan at level `max` with Larastan
