<?php

declare(strict_types=1);

namespace MakinaCorpus\Preferences;

/**
 * Preferences reader.
 */
interface Preferences
{
    /**
     * Fetch current value for preference
     *
     * If variable name does not exists, it will not fail and just return null.
     */
    public function get(string $name);
}
