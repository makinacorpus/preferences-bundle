<?php

declare(strict_types=1);

namespace MakinaCorpus\Preferences;

/**
 * Necessary information for validating an incomming value.
 */
class ValueType
{
    /** PHP native type. */
    public /* readonly */ string $nativeType;

    /** Should this value restricted to given allowed values. */
    public /* readonly */ bool $enum = false;

    /** Array of allowed values if enum. */
    public /* readonly */ array $allowedValues = [];

    /** Is this a collection of values. */
    public /* readonly */ bool $collection = false;

    /** If this is a collection, does it allows named keys. */
    public /* readonly */ bool $hashMap = false;

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
}
