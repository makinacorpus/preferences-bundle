<?php

declare(strict_types=1);

namespace MakinaCorpus\Preferences\Tests\Repository;

use MakinaCorpus\Preferences\Repository\ArrayPreferencesRepository;
use PHPUnit\Framework\TestCase;

final class ArrayPreferencesRepositoryTest extends TestCase
{
    use RepositoryTestTrait;

    /**
     * {@inheritdoc}
     */
    protected function getRepositories(): iterable
    {
        return [new ArrayPreferencesRepository()];
    }
}
