# This package add note to eloquent model

[![Latest Version on Packagist](https://img.shields.io/packagist/v/centrex/laravel-model-note.svg?style=flat-square)](https://packagist.org/packages/centrex/laravel-model-note)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/centrex/laravel-model-note/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/centrex/laravel-model-note/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/centrex/laravel-model-note/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/centrex/laravel-model-note/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/centrex/laravel-model-note?style=flat-square)](https://packagist.org/packages/centrex/laravel-model-note)

This is where your description should go. Limit it to a paragraph or two. Consider adding a small example.

## Contents

  - [Installation](#installation)
  - [Usage](#usage)
  - [Testing](#testing)
  - [Changelog](#changelog)
  - [Contributing](#contributing)
  - [Credits](#credits)
  - [License](#license)

## Installation

You can install the package via composer:

```bash
composer require centrex/laravel-model-note
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="laravel-model-note-config"
```

This is the contents of the published config file:

```php
return [
];
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="laravel-model-note-migrations"
php artisan migrate
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="laravel-model-note-views"
```

## Usage

Add the `HasNotes` trait to a model you like to use notes on.

```php
use Centrex\LaravelModelNote\HasNotes;

class YourEloquentModel extends Model
{
    use HasNotes;
}
```

### Add a new note

You can add a new note like this:

```php
$model->addNote('whatever you like');
```

### Add a private note

You can add a new private note which can be seen only be you like this:

```php
$model->addNote('whatever you like' , true);

//or alternatively
$model->addPrivateNote('whatever you like');

```

### Add a note with tag

Sometimes you will need to tag your note with some tag which can be done like this:

```php
$model->addNote('whatever you like' , false , "tag1");

//or for the private note
$model->addPrivateNote('whatever you like' , "tag2");

```

### Retrieving notes

You can get the last note of model:

```php
$model->note; // returns the text of the last note

$model->note(); // returns the last instance of `Centrex\LaravelModelNote\ModelNote`

//or alternatively
$model->lastNote(); // returns the last instance of `Centrex\LaravelModelNote\ModelNote`
```

All associated notes of a model can be retrieved like this:

```php
$all_notes = $model->notes;

//or alternatively
$all_notes = $model->notes();
```

All associated notes of a model with specific tag or tags can be retrieved like this:

```php

//last note of specific tag
$last_note = $model->lastNote("tag1"); 

//specific tag
$all_notes = $model->allNotes("tag1");

//specific tags
$all_notes = $model->allNotes("tag1" , "tag2");
```

All associated private notes of a model with specific tag or tags can be retrieved like this:

```php
//specific tag
$all_notes = $model->privateNotes("tag1");

//specific tags
$all_notes = $model->privateNotes("tag1" , "tag2");
```

### Delete a note from model

You can delete any note that has been added on the model by id at any time by using the `deleteNote` method:

```php
//specific id
$model->deleteNote(1);

//specific ides
$model->deleteNote(1, 2, 3);

```

You can delete any note that has been added on the model by tag at any time by using the `deleteNote` method:

```php
//specific tag
$model->deleteNoteByTag("tag1");

//specific tags
$model->deleteNoteByTag("tag1", "tag2", "tag3");

```

### Delete all notes from model

You can delete all notes that had been added on the model at any time by using the `deleteAllNotes` method:

Delete all notes from model:

```php
$model->deleteAllNotes();
```

## Testing

🧹 Keep a modern codebase with **Pint**:
```bash
composer lint
```

✅ Run refactors using **Rector**
```bash
composer refacto
```

⚗️ Run static analysis using **PHPStan**:
```bash
composer test:types
```

✅ Run unit tests using **PEST**
```bash
composer test:unit
```

🚀 Run the entire test suite:
```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [rochi88](https://github.com/centrex)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
