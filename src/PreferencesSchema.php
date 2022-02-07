<?php

declare(strict_types=1);

namespace MakinaCorpus\Preferences;

/**
 * Preferences schema repository.
 *
 * If you have none, then repositories will be in YOLO mode.
 */
interface PreferencesSchema
{
    /**
     * Does this value type exists in schema
     */
    public function has(string $name): bool;

    /**
     * Get value type from schema
     *
     * @throws \InvalidArgumentException
     *   If value does not exist and schema was not declared
     */
    public function getType(string $name): ValueSchema;

    /**
     * Fetch default value of a value if possible
     *
     * @return mixed
     *   Return type depends upon schema
     *
     * @throws \InvalidArgumentException
     *   If value does not exist and schema was not declared
     */
    public function getDefault(string $name): mixed;

    /**
     * Does it has a default value (null is not a value)
     */
    public function hasDefault(string $name): bool;
}
