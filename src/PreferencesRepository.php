<?php

declare(strict_types=1);

namespace MakinaCorpus\Preferences;

/**
 * Preference access interface.
 *
 * NONE OF THIS OBJECTS METHODS CAN BE CALLED AT RUNTIME EXCEPT get().
 *
 * You've been warn, drivers are allowed to be extremely slow, except
 * when the get() method is being called.
 */
interface PreferencesRepository extends Preferences
{
    /**
     * List all known stored names.
     *
     * @return iterable
     *   Can be anything iterable, it is recommened if your storage backend
     *   permits it using a generator that streams data instead of loading
     *   everything into memory.
     */
    public function list(): iterable;

    /**
     * Get all at once.
     *
     * @return iterable<string, mixed>
     *   Keys are value names, values are values of course.
     *   Can be anything iterable, since this method will be probably used
     *   to build a all in cache blob entry, you should probably return an
     *   already well formed array.
     */
    public function all(): iterable;

    /**
     * Does it has a value for
     */
    public function has(string $name): bool;

    /**
     * Fetch multiple values at once
     *
     * @return mixed[]
     *   Keys are names, values are values, missing values not in there
     */
    public function getMultiple(array $names): array;

    /**
     * Get value type if the value exists, otherwise "string".
     */
    public function getType(string $name): ValueType;

    /**
     * Store a value
     */
    public function set(string $name, $value, ?ValueType $type = null): void;

    /**
     * Delete value
     */
    public function delete(string $name): void;
}
