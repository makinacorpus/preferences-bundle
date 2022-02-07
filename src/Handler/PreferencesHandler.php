<?php

declare(strict_types=1);

namespace MakinaCorpus\Preferences\Handler;

use MakinaCorpus\Preferences\PreferencesRepository;
use MakinaCorpus\Preferences\PreferencesSchema;
use MakinaCorpus\Preferences\Message\PreferenceValueDelete;
use MakinaCorpus\Preferences\Message\PreferenceValueSet;
use MakinaCorpus\Preferences\Message\PreferenceValueSetMany;
use MakinaCorpus\Preferences\Value\ValueValidator;

/**
 * Quite generic symfony/messenger or makinacorpus/corebus alike handler.
 *
 * This is meant to be plugged over a bus, one way or another.
 */
final class PreferencesHandler
{
    private PreferencesRepository $repository;
    private ?PreferencesSchema $schema = null;

    /**
     * Default constructor
     */
    public function __construct(PreferencesRepository $repository, ?PreferencesSchema $schema = null)
    {
        $this->repository = $repository;
        $this->schema = $schema;
    }

    /**
     * Validate value, return save callback.
     */
    private function handleValue(string $name, $value): callable
    {
        if ($this->schema) {
            $schema = $this->schema->getType($name);
            $value = ValueValidator::validate($schema, $value);
        } else {
            $schema = ValueValidator::getTypeOf($value);
            $value = ValueValidator::validate($schema, $value);
        }

        return function () use ($name, $value, $schema) {
            $this->repository->set($name, $value, $schema);
        };
    }

    /**
     * Handler
     */
    public function doSet(PreferenceValueSet $command): void
    {
        ($this->handleValue($command->name, $command->value))();
    }

    /**
     * Handler
     */
    public function doSetMany(PreferenceValueSetMany $command): void
    {
        $callables = [];

        // Pre-validate everything, to ensure we won't store anything if any
        // value fails validation.
        foreach ($command->values as $name => $value) {
            $callables[] = $this->handleValue($name, $value);
        }

        // No error here means everything is valid, store everything.
        foreach ($callables as $callback) {
            $callback();
        }
    }

    /**
     * Handler
     */
    public function doDelete(PreferenceValueDelete $command): void
    {
        $this->repository->delete($command->name);
    }
}
