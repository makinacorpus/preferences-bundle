<?php

declare(strict_types=1);

namespace MakinaCorpus\Preferences;

/**
 * More descriptive information about a value.
 */
class ValueSchema extends ValueType
{
    /** Internal name in schema. */
    public /* readonly */ string $name;

    /** Human readable short name. */
    public /* readonly */ ?string $label = null;

    /** Human readable full description, for building user interface. */
    public /* readonly */ ?string $description = null;

    /** Default value if not set by user. */
    public /* readonly */ mixed $default = null;

    public function __construct(
        string $nativeType,
        bool $collection = false,
        ?array $allowedValues = null,
        bool $hashMap = false,
        ?string $name = null,
        ?string $label = null,
        ?string $description = null,
        mixed $default = null
    ) {
        parent::__construct($nativeType, $collection, $allowedValues, $hashMap);

        $this->name = $name ?? $nativeType; // Just ensure there will be a value here.
        $this->label = $label;
        $this->description = $description;
        $this->default = $default;
    }

    /**
     * Create instance from array
     *
     * @param array $data
     *   May contain any of the following data (all are optional):
     *      - 'name' (string, mandatory): value name
     *      - 'type' (string, default is "string"): value type
     *      - 'default' (anything, default is null): default value
     *      - 'collection' (boolean, default is false): multiple values allowed
     *      - 'hashmap' (boolean, default is false): values can have keys
     *      - 'allowed_values': (array of anything, default is null) allowed values
     *      - 'label' (string, default is null): value label
     *      - 'description (string, default null): value string
     */
    public static function fromArray(array $data): self
    {
        if (!isset($data['name'])) {
            throw new \InvalidArgumentException("'name' key is mandatory for schema values");
        }

        return new self(
            (string)($data['type'] ?? 'string'),
            (bool)($data['collection'] ?? false),
            $data['allowed_values'] ?? null,
            (bool)($data['hashmap'] ?? false),
            $data['name'] ?? null,
            $data['default'] ?? null,
            $data['label'] ?? null,
            $data['description'] ?? null
        );
    }
}
