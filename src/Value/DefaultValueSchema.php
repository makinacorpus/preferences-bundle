<?php

declare(strict_types=1);

namespace MakinaCorpus\Preferences\Value;

use MakinaCorpus\Preferences\ValueSchema;

final class DefaultValueSchema extends DefaultValueType implements ValueSchema
{
    private string $name;
    private ?string $label = null;
    private ?string $description = null;
    private mixed $default = null;

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

        $ret = new self(
            (string)($data['type'] ?? 'string'),
            (bool)($data['collection'] ?? false),
            $data['allowed_values'] ?? null,
            (bool)($data['hashmap'] ?? false)
        );
        $ret->default = $data['default'] ?? null;
        $ret->description = $data['description'] ?? null;
        $ret->label = $data['label'] ?? null;
        $ret->name = $data['name'] ?? null;

        return $ret;
    }

    protected function __construct(string $nativeType, bool $collection = false, ?array $allowedValues = null, bool $hashMap = false)
    {
        parent::__construct($nativeType, $collection, $allowedValues, $hashMap);

        // Just ensure it will never be null.
        $this->name = $nativeType;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getDefault()
    {
        return $this->default;
    }

    public function hasDefault(): bool
    {
        return null !== $this->default;
    }
}
