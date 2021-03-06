<?php

declare(strict_types=1);

namespace MakinaCorpus\Preferences;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;

/**
 * @codeCoverageIgnore
 */
final class PreferencesCommand extends Command
{
    protected static $defaultName = 'preferences:manage';

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setDescription('List, view, set or delete preferences')
            ->addOption('format', null, InputOption::VALUE_REQUIRED, "Format, can be 'plain' or 'rst'", 'plain')
            ->addOption('group', null, InputOption::VALUE_OPTIONAL, "Only given group")
            ->addOption('desc', null, InputOption::VALUE_NONE, "Show descriptions")
            ->addOption('config', null, InputOption::VALUE_REQUIRED, "Use configuration file")
            ->setHelp(<<<EOT
List, view, set or delete preferences.

Examples:

List all preferences:

    bin/console preferences:manage list

View a single value:

    bin/console preferences:manage view NAME

Delete a single value:

    bin/console preferences:manage delete NAME

Set a single value

    bin/console preferences:manage view NAME [--type=bool|int|float|string] [--collection[=hashmap]]

EOT
            )
        ;
    }

    //
}
