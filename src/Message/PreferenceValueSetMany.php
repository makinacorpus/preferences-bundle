<?php

declare(strict_types=1);

namespace MakinaCorpus\Preferences\Message;

/**
 * Set many configuration value
 *
 * 'values' property is a dictionnary, keys are preferences names, values
 * are arbitrary values whose type will depend upon the schema.
 *
 * [group=settings]
 *
 * @codeCoverageIgnore
 */
final class PreferenceValueSetMany
{
    /** @var array<string,mixed> */
    public /* readonly */ array $values;

    /**
     * @param array $values
     *   Keys are preference names, values are values
     */
    public function __construct(array $values)
    {
        $this->values = $values;
    }
}
