<?php

declare(strict_types=1);

namespace LDL\DependencyInjection\Console\Command;

use LDL\DependencyInjection\CompilerPass\Compiler\CompilerPassCompiler;
use LDL\DependencyInjection\CompilerPass\Finder\CompilerPassFileFinder;
use LDL\DependencyInjection\CompilerPass\Finder\Options\CompilerPassFileFinderOptions;
use LDL\DependencyInjection\Container\Builder\LDLContainerBuilder;
use LDL\DependencyInjection\Container\Helper\ContainerHelper;
use LDL\DependencyInjection\Service\Compiler\ServiceCompiler;
use LDL\DependencyInjection\Service\File\Finder\Options\ServiceFileFinderOptions;
use LDL\DependencyInjection\Service\File\Finder\ServiceFileFinder;
use LDL\Framework\Base\Collection\CallableCollection;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class BuildCommand extends Command
{
    public const COMMAND_NAME = 'ldl:container:build';

    public function configure(): void
    {
        parent::configure();

        $this->setName(self::COMMAND_NAME)
            ->setDescription('Builds dependency injection container')
            ->setDefinition(
                new InputDefinition([
                    new InputArgument(
                      'directories',
                        InputArgument::REQUIRED,
                        'Directories to search'
                    ),
                    new InputArgument(
                        'output-file',
                        InputArgument::OPTIONAL,
                        'Output file name',
                    ),
                    new InputOption(
                      'exclude-directories',
                      'e',
                      InputOption::VALUE_REQUIRED
                    ),
                    new InputOption(
                        'service-files',
                        's',
                        InputOption::VALUE_REQUIRED,
                        'Service file patterns',
                        'services.xml,services.yml,services.ini,services.php'
                    ),
                    new InputOption(
                        'cpass-file-pattern',
                        'c',
                        InputOption::VALUE_REQUIRED,
                        CompilerPassFileFinderOptions::DEFAULT_FILE_PATTERN
                    ),
                ])
            );
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $title = '[ LDL DIC Builder ]';

            $output->writeln("\n<info>$title</info>\n");
            $output->writeln('<info>Finding service files ...</info>');

            $serviceFiles = (new ServiceFileFinder(
                ServiceFileFinderOptions::fromArray([
                    'directories' => explode(',', $input->getArgument('directories')),
                    'files' => explode(',', $input->getOption('service-files')),
                    'excludedDirectories' => explode(',', (string) $input->getOption('exclude-directories')),
                ]),
                new CallableCollection([
                    static function ($f) use ($output) {
                        $output->writeln("<info>Found service file: $f</info>");
                    },
                ])
            ))->find();

            $output->writeln(sprintf('<info>Found %s service files</info>', count($serviceFiles)));

            $output->writeln('<info>Finding compiler pass files ...</info>');

            $compilerPassFiles = (new CompilerPassFileFinder(
                CompilerPassFileFinderOptions::fromArray([
                    'directories' => explode(',', $input->getArgument('directories')),
                    'excludedDirectories' => explode(',', (string) $input->getOption('exclude-directories')),
                ]),
                new CallableCollection([
                    static function ($f) use ($output) {
                        $output->writeln("<info>Found compiler pass file: $f</info>");
                    },
                ])
            ))->find();

            $output->writeln(sprintf('<info>Found %s compiler pass files</info>', count($compilerPassFiles)));

            $builder = new LDLContainerBuilder(
                new ServiceCompiler(),
                new CompilerPassCompiler()
            );

            $container = $builder->build(
                $serviceFiles,
                $compilerPassFiles
            );

            $output->writeln(
                sprintf(
                    '<info>Found %s service definitions</info>',
                    count($container->getServiceIds())
                )
            );

            if ($input->getArgument('output-file')) {
                $output->writeln("<info>Writing container to {$input->getArgument('output-file')}</info>");
                ContainerHelper::write($container, $input->getArgument('output-file'));
            }

            return self::SUCCESS;
        } catch (\Exception $e) {
            $output->writeln("<error>{$e->getMessage()}</error>");

            return self::FAILURE;
        }
    }
}
