<?php

declare(strict_types=1);

namespace MakinaCorpus\Preferences\Schema;

use MakinaCorpus\Preferences\PreferencesSchema;
use MakinaCorpus\Preferences\ValueSchema;

/**
 * Array based schema, suitable for small applications
 */
final class ArrayPreferencesSchema implements PreferencesSchema
{
    /** @var mixed[] */
    private array $data;

    /**
     * Default constructor
     *
     * @param array $data
     *   Keys must be preference value names, values must be arrays that must be
     *   compatible with ValueSchema::fromArray() static constructor.
     *   Only 'name' can and should be be ommited here.
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * {@inheritdoc}
     */
    public function has(string $name): bool
    {
        return isset($this->data[$name]);
    }

    /**
     * {@inheritdoc}
     */
    public function getType(string $name): ValueSchema
    {
        $data = $this->data[$name] ?? $this->undefinedValueSchema($name);

        return ValueSchema::fromArray(['name' => $name] + $data);
    }

    /**
     * {@inheritdoc}
     */
    public function getDefault(string $name): mixed
    {
        $data = $this->data[$name] ?? $this->undefinedValueSchema($name);

        return $data['default'] ?? null;
    }

    /**
     * {@inheritdoc}
     */
    public function hasDefault(string $name): bool
    {
        return isset($this->data[$name]['default']); 
    }

    /**
     * Raise an exception.
     */
    private function undefinedValueSchema(string $name)
    {
        throw new \InvalidArgumentException(\sprintf(
            "'%s': schema is not defined",
            $name
        ));
    }
}
