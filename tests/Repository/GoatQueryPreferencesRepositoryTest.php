<?php

declare(strict_types=1);

namespace MakinaCorpus\Preferences\Tests\Repository;

use Goat\Runner\Testing\DatabaseAwareQueryTest;
use MakinaCorpus\Preferences\Repository\GoatQueryPreferencesRepository;
use PHPUnit\Framework\SkippedTestError;

final class GoatQueryPreferencesRepositoryTest extends DatabaseAwareQueryTest
{
    use RepositoryTestTrait;

    /**
     * {@inheritdoc}
     */
    protected function getSupportedDrivers(): ?array
    {
        return ['pgsql'];
    }

    /**
     * {@inheritdoc}
     */
    protected function getRepositories(): iterable
    {
        foreach ($this->runnerDataProvider() as $args) {
            try {
                $runner = \reset($args)->getRunner();

                $runner->execute(
                    <<<SQL
                    create table if not exists "preferences" (
                        "name" varchar(500) not null,
                        "created_at" timestamp not null default current_timestamp,
                        "updated_at" timestamp not null default current_timestamp,
                        "type" varchar(500) default null,
                        "is_collection" bool not null default false,
                        "is_hashmap" bool not null default false,
                        "is_serialized" bool not null default false,
                        "value" text,
                        primary key ("name")
                    );
                    SQL
                );

                yield new GoatQueryPreferencesRepository($runner);
            } catch (SkippedTestError $e) {
                // Let this pass.
            }
        }
    }
}
