<?php

declare(strict_types=1);

namespace MakinaCorpus\Preferences\Tests\Repository;

use MakinaCorpus\Preferences\Repository\ArrayPreferencesRepository;
use MakinaCorpus\Preferences\Repository\CacheAllPreferencesRepository;
use PHPUnit\Framework\TestCase;

final class CacheAllPreferencesRepositoryTest extends TestCase
{
    use RepositoryTestTrait;

    /**
     * {@inheritdoc}
     */
    protected function getRepositories(): iterable
    {
        return [new CacheAllPreferencesRepository(new ArrayPreferencesRepository())];
    }

    public function testInitialization(): void
    {
        $instance = new CacheAllPreferencesRepository(
            new ArrayPreferencesRepository([
                'foo' => 12,
                'bar' => ['a', 'b'],
            ])
        );

        self::assertSame(12, $instance->get('foo'));
        self::assertSame(['a', 'b'], $instance->get('bar'));
    }
}
