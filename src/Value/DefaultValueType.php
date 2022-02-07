<?php

declare(strict_types=1);

namespace MakinaCorpus\Preferences\Value;

use MakinaCorpus\Preferences\ValueType;

class DefaultValueType implements ValueType
{
    private string $nativeType;
    private bool $enum = false;
    /** @var mixed[] */
    private array $allowedValues = [];
    private bool $collection = false;
    private bool $hashMap = false;

    public function __construct(string $nativeType, bool $collection = false, ?array $allowedValues = null, bool $hashMap = false)
    {
        $this->nativeType = $nativeType;
        $this->collection = $collection;

        if ($allowedValues) {
            // It can only be an enum if it has allowed values.
            $this->allowedValues = $allowedValues;
            $this->enum = true;
        } else if ($collection) {
            // It cannot be a hashmap if there's allowed values.
            $this->hashMap = $hashMap;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getNativeType(): string
    {
        return $this->nativeType;
    }

    /**
     * {@inheritdoc}
     */
    public function isEnum(): bool
    {
        return $this->enum;
    }

    /**
     * {@inheritdoc}
     */
    public function getAllowedValues(): array
    {
        return $this->allowedValues;
    }

    /**
     * {@inheritdoc}
     */
    public function isCollection(): bool
    {
        return $this->collection;
    }

    /**
     * {@inheritdoc}
     */
    public function isHashMap(): bool
    {
        return $this->hashMap;
    }
}
