# Preferences

Preferences are configuration variables that are meant to be user managed
for which we cannot rely upon container parameters or environment variables.

This bundle provides a simple API for:

 - Defining a preferences variable schema, with variable name, type, and
   description.

 - Reading them as environment variables by the container, in order to allow
   using those variables as services parameters.

 - A form type which handles all basic types (int, type, string) as collections
   or single-values variables, which you can use in any form.

 - An implementation for storing user values in database using
   `makinacorpus/goat-query`.

 - Bus handlers and messages for `symfony/messenger`, `makinacorpus/goat` and
   `makinacorpus/corebus`.

 - An interface for reading the schema defined in project configuration.

 - An interface for reading values.

# Setup

This package is depends on `makinacorpus/goat-query`.

Simply install this package:

```sh
composer require makinacorpus/preferences-bundle
```

Then add the bundle into your `config/bundles.php` file:

```php
<?php

return [
    // ... Your other bundles.
    MakinaCorpus\Preferences\PreferencesBundle::class => ['all' => true],
];

```

## Define a custom schema

You can define a schema:

```yaml
preferences:
    schema:
        app.domain.some_variable:
            label: Some variable
            description: Uncheck this value to deactive this feature
            type: bool
            collection: false
            default: true
```

Where the number of entries is unlimited. Only limit is your memory because
the whole definition will be injected as a bare PHP array into the default
array schema implementation.

Please note that because Symfony environment variables processor validates
strictly the variables names, all non alpha-numeric and non `_` characters
will make the environment variable processor fail. If you plan to inject
your variables into services using environment variables, you must name
your variables accordingly, such as:

```yaml
preferences:
    schema:
        app_domain_some_variable:
            label: Some variable
            description: Uncheck this value to deactive this feature
            type: bool
            collection: false
            default: true
```

All options are optional. Defaults are:

```yaml
preferences:
    enabled: true
    schema:
        app_domain_some_variable:
            label: null
            description: null
            type: string
            allowed_values: null
            collection: false
            hashmap: false
            default: null
```

Parameters you can set on each variable definition:

 - `label`: is a human readable short name,
 - `description`: is a human readable long description,
 - `type`: can be either of: `string`, `bool`, `int`, `float`,
 - `allowed_values`: is an array of arbitrary values, for later validation,
 - `collection`: if set to `true`, multiple values are allowed for this variable,
 - `hashmap`: if set to true, keys are allowed, this is ignored if `collection` is `false`,
 - `default`: arbitrary default value if not configured by the user.

# Usage

## Inject preferences as service arguments

This package defines a `EnvVarProcessorInterface` implementation allowing to
inject preferences like environment variables, such as:

```yaml
services:
    my_service:
        class: App\Some\Class
        arguments: ["%env(preference:app_domain_some_variable)%"]
```

## Use Preferences service into other services.

Type hint your injected parameters using `MakinaCorpus\Preferences\Preferences`
then use the `get(string $name): mixed` method to fetch values.

# Long term roadmap

 - Add new repository implementations (Redis, PDO, other...).
 - Implement correctly bus handlers.
 - Implement PHP schema dumper.
