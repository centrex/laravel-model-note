# Add notes to any Eloquent model

[![Latest Version on Packagist](https://img.shields.io/packagist/v/centrex/laravel-model-note.svg?style=flat-square)](https://packagist.org/packages/centrex/laravel-model-note)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/centrex/laravel-model-note/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/centrex/laravel-model-note/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/centrex/laravel-model-note/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/centrex/laravel-model-note/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/centrex/laravel-model-note?style=flat-square)](https://packagist.org/packages/centrex/laravel-model-note)

Attach polymorphic notes to any Eloquent model with support for tagging, privacy control, and bulk operations. Notes are stored in a `model_notes` table and ordered newest-first by default.

## Installation

```bash
composer require centrex/laravel-model-note
php artisan vendor:publish --tag="laravel-model-note-migrations"
php artisan migrate
```

## Usage

### 1. Add the trait

```php
use Centrex\LaravelModelNote\HasNotes;

class Order extends Model
{
    use HasNotes;
}
```

### 2. Add notes

```php
$order->addNote('Payment confirmed by finance.');

// Private note (not shown to customers)
$order->addPrivateNote('Suspicious address — flag for review.');

// Tagged note
$order->addNote('Dispatched via DHL.', tag: 'shipping');
$order->addNote('Customer called in.', isPrivate: true, tag: 'support');
```

### 3. Read notes

```php
// Latest note content (shortcut)
echo $order->note();

// Latest note object, optionally filtered by tag
$note = $order->lastNote('shipping');
echo $note->time_ago;  // "2 hours ago"

// All notes
$order->allNotes();
$order->allNotes('support');   // filtered by tag

// Private notes only
$order->privateNotes();
$order->privateNotes('shipping');
```

### 4. Delete notes

```php
$order->deleteNote(5);
$order->deleteNote([5, 6, 7]);
$order->deleteNoteByTag('shipping');
$order->deleteAllNotes();
```

### 5. Query scopes on ModelNote

```php
use Centrex\LaravelModelNote\ModelNote;

ModelNote::private()->get();
ModelNote::public()->get();
ModelNote::withTag('support')->get();
```

### ModelNote attributes

| Attribute | Type | Description |
|---|---|---|
| `note` | `string` | Note content |
| `tag` | `string\|null` | Optional category tag |
| `is_private` | `bool` | Hidden from non-admin views |
| `user_id` | `int\|null` | Author (defaults to `auth()->id()`) |
| `time_ago` | `string` | Human-readable age (`diffForHumans()`) |

## Testing

```bash
composer test        # full suite
composer test:unit   # pest only
composer test:types  # phpstan
composer lint        # pint
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Credits

- [centrex](https://github.com/centrex)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
