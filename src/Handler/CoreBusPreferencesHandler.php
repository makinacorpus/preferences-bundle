<?php

declare(strict_types=1);

namespace MakinaCorpus\Preferences\Handler;

use MakinaCorpus\Preferences\Message\PreferenceValueDelete;
use MakinaCorpus\Preferences\Message\PreferenceValueSet;
use MakinaCorpus\Preferences\Message\PreferenceValueSetMany;

/**
 * Quite generic symfony/messenger or makinacorpus/corebus alike handler.
 *
 * This is meant to be plugged over a bus, one way or another.
 */
#[\MakinaCorpus\CoreBus\Attr\CommandHandler]
final class CoreBusPreferencesHandler extends PreferencesHandler
{
    /**
     * Handler
     */
    public function doSet(PreferenceValueSet $command): void
    {
        parent::doSet($command);
    }

    /**
     * Handler
     */
    public function doSetMany(PreferenceValueSetMany $command): void
    {
        parent::doSetMany($command);
    }

    /**
     * Handler
     */
    public function doDelete(PreferenceValueDelete $command): void
    {
        parent::doDelete($command);
    }
}
