# agents.md

## Agent Guidance — laravel-model-note

### Package Purpose
Adds a polymorphic note system to any Eloquent model via the `HasNotes` trait. Notes are free-text entries attached to models, optionally with author tracking and timestamps.

### Before Making Changes
- Read `src/ModelNote.php` — the `Note` Eloquent model and its fields
- Read `src/HasNotes.php` — the trait API exposed to host models
- Check `src/Events/` — events fire when notes are added/deleted
- Check `src/Exceptions/` — understand validation constraints

### Common Tasks

**Adding note categories or types**
1. Add a `type` column to the migration (nullable, string, with a sensible default)
2. Add a scope `ofType($type)` to `ModelNote`
3. Expose a typed `addNote($content, $type)` method in `HasNotes` if useful
4. Do not make `type` required — breaking change for existing callers

**Adding author tracking**
- Add a nullable `author_id` / `author_type` polymorphic column
- Auto-populate from `auth()->user()` in `addNote()` but allow override
- Must be optional — not all apps have auth context when notes are created

**Adding note pinning/priority**
1. Add a `pinned` boolean column with default `false`
2. Add a `pin()` / `unpin()` method to `ModelNote`
3. Add a `pinned()` scope
4. Expose via `HasNotes` if host models need direct access

### Testing
```sh
composer test:unit        # pest
composer test:types       # phpstan
composer test:lint        # pint
```

Test that notes are scoped to their owner model:
```php
$order->addNote('Urgent');
$invoice->addNote('Regular');
expect($order->notes()->count())->toBe(1);
expect($invoice->notes()->count())->toBe(1);
```

### Safe Operations
- Adding nullable columns to the notes migration
- Adding new methods to `HasNotes`
- Adding model scopes on `ModelNote`
- Adding events and listeners

### Risky Operations — Confirm Before Doing
- Renaming `notable_type` / `notable_id` polymorphic columns
- Making `content` non-nullable (breaks existing empty notes if any)
- Changing the `addNote()` method signature

### Do Not
- Add a maximum note length constraint in the model — leave that to the host app's validation
- Auto-delete notes when a parent model is force-deleted without a config opt-in
- Skip `declare(strict_types=1)` in any new file
