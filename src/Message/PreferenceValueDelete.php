<?php

declare(strict_types=1);

namespace MakinaCorpus\Preferences\Message;

/**
 * Delete configuration value
 *
 * [group=settings]
 *
 * @codeCoverageIgnore
 */
final class PreferenceValueDelete
{
    public /* readonly */ string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }
}
