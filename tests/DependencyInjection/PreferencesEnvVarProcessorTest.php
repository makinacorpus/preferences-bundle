<?php

declare(strict_types=1);

namespace MakinaCorpus\Preferences\Tests\DependencyInjection;

use MakinaCorpus\Preferences\DefaultPreferences;
use MakinaCorpus\Preferences\DependencyInjection\PreferencesEnvVarProcessor;
use MakinaCorpus\Preferences\Repository\ArrayPreferencesRepository;
use MakinaCorpus\Preferences\Schema\ArrayPreferencesSchema;
use PHPUnit\Framework\TestCase;

final class PreferencesEnvVarProcessorTest extends TestCase
{
    public function testConventionalUseCase(): void
    {
        $preferences = new DefaultPreferences(
            new ArrayPreferencesRepository([
                'conventional_use_case' => '123',
                'with.dot.use_case' => '456',
            ]),
            new ArrayPreferencesSchema([
                'conventional_use_case' => [
                    'type' => 'string',
                ],
                'with.dot.use_case' => [
                    'type' => 'string',
                ],
            ])
        );

        $envVarProcessor = new PreferencesEnvVarProcessor($preferences);

        $value = $envVarProcessor->getEnv('preference', 'conventional_use_case', fn (string $name) => '_foo_');
        self::assertSame('123', $value);
    }

    public function testFailsWhenMisused(): void
    {
        $preferences = new DefaultPreferences(
            new ArrayPreferencesRepository([]),
            new ArrayPreferencesSchema([])
        );

        $envVarProcessor = new PreferencesEnvVarProcessor($preferences);

        self::expectException(\RuntimeException::class);
        $envVarProcessor->getEnv('other_prefix', 'foo', fn (string $name) => '_foo_');
    }
}
