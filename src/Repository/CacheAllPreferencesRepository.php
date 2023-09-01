<?php

declare(strict_types=1);

namespace MakinaCorpus\Preferences\Repository;

use MakinaCorpus\Preferences\PreferencesRepository;
use MakinaCorpus\Preferences\ValueType;

/**
 * SQL based implementation
 */
final class CacheAllPreferencesRepository extends ArrayPreferencesRepository
{
    private PreferencesRepository $decorated;

    public function __construct(PreferencesRepository $decorated)
    {
        parent::__construct(fn () => $decorated->all());

        $this->decorated = $decorated;
    }

    /**
     * {@inheritdoc}
     */
    public function set(string $name, $value, ?ValueType $type = null): void
    {
        $this->decorated->set($name, $value, $type);

        parent::set($name, $value, $type);
    }

    /**
     * {@inheritdoc}
     */
    public function delete(string $name): void
    {
        $this->decorated->delete($name);

        parent::delete($name);
    }
}
