<?php

declare(strict_types=1);

namespace MakinaCorpus\Preferences\DependencyInjection;

use MakinaCorpus\Preferences\Preferences;
use Symfony\Component\DependencyInjection\EnvVarProcessorInterface;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

/**
 * Provide preferences as environnement variables.
 *
 * This seems the be the right place to do this, because environement variables
 * are not stored in the compiled container, loaded at runtime, which means that
 * this is exactly the same that we need for user modifiable configuration.
 *
 * In order to use it, you will need to write in your services.yaml something
 * that looks like this:
 *
 *   services:
 *       App\Foo\Bar:
 *          arguments:
 *              - "%env(preference:app.config.value)%"
 *
 * Where "app.config.value" is the configuration key name.
 */
final class PreferencesEnvVarProcessor implements EnvVarProcessorInterface
{
    private Preferences $preferences;

    /**
     * Default constructor
     */
    public function __construct(Preferences $preferences)
    {
        $this->preferences = $preferences;
    }

    /**
     * {@inheritdoc}
     */
    public static function getProvidedTypes(): array
    {
        return [
            'preference' => 'bool|int|float|string|array',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getEnv(string $prefix, string $name, \Closure $getEnv): mixed
    {
        if ('preference' !== $prefix) {
            throw new RuntimeException(sprintf('Unsupported env var prefix "%s".', $prefix));
        }

        return $this->preferences->get($name);
    }
}
