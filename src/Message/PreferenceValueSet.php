<?php

declare(strict_types=1);

namespace MakinaCorpus\Preferences\Message;

/**
 * Set configuration value
 *
 * [group=settings]
 *
 * @codeCoverageIgnore
 */
final class PreferenceValueSet
{
    public /* readonly */ string $name;
    public /* readonly */ mixed $value;

    public function __construct(string $name, mixed $value)
    {
        $this->name = $name;
        $this->value = $value;
    }
}
