# Craft & PHPStan

This repo shows how I configure PHPStan and Craft to work together. There are a few notable tricks I
like to employ to make things more strongly typed. They are,

1. Include your PHPStan stubs inside a `.phpstorm.meta.php` directory. This way the stubs are also accessible
   to PHPStorm and you get some really nice autocomplete.
2. Use a dynamic `include` from `phpstan.neon` so it can go out and find the non-PSR-4 named
   `CustomFieldBehavior`. This is done [with the following include](phpstan.neon), `includes: [phpstan-includes.php]`. Then,
   [inside that include file](phpstan-includes.php) you can search for and set a dynamic `parameters.scanFiles` to the filepath
   of the custom field definition.

With these in place you get native `$entry->customField` autocomplete with strong types when getting the
custom field.

However, you do _not_ get strong types when querying the custom field. For example, this `$query` is not
typed as an `EntryQuery`. Instead it is typed as a `CustomFieldBehavior` because the return types of the
custom field methods are marked as `static`, referencing back to the behavior, not the underlying query.

```php
$query = craft\elements\Entry::find()->customField('value')
```

You can see this by running PHPStan and inspecting the `modules/Module.php` file.

```bash
$ cat modules/Module.php
$ ./vendor/bin/phpstan --memory-limit=-1 analyse
```

This can be seen in this simplified PHPStan demo, https://phpstan.org/r/f72190d9-a040-47c9-98e2-f2d07508e518. It shows
that the `static` return from a `@mixin` stays on the defined class, not the calling parent.

```php
<?php declare(strict_types = 1);

/**
 * @method static fieldHandle(string $value)
 */
class CustomFieldBehavior {
	
}

/**
 * @mixin CustomFieldBehavior
 */
class EntryQuery {
	
}

$query = (new EntryQuery())->fieldHandle('foo');
\PHPStan\dumpType($query); // should be EntryQuery, but is CustomFieldBehavior
```
