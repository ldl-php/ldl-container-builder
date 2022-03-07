<?php

declare(strict_types=1);

namespace LDL\DependencyInjection\Console\Command;

use LDL\DependencyInjection\CompilerPass\Finder\CompilerPassFileFinder;
use LDL\DependencyInjection\CompilerPass\Finder\Exception\NoFilesFoundException;
use LDL\DependencyInjection\CompilerPass\Finder\Options\CompilerPassFileFinderOptions;
use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class PrintCompilerPassFilesCommand extends SymfonyCommand
{
    public const COMMAND_NAME = 'cpass:print';

    public function configure(): void
    {
        $defaults = CompilerPassFileFinderOptions::fromArray([]);

        $defaultDirectories = implode(', ', $defaults->getDirectories());

        $this->setName(self::COMMAND_NAME)
            ->setDescription('Prints compiler passes')
            ->addOption(
                'scan-directories',
                'd',
                InputOption::VALUE_REQUIRED,
                sprintf(
                    'Comma separated list of directories to scan, default: %s',
                    $defaultDirectories
                ),
                $defaultDirectories
            )
            ->addOption(
                'scan-pattern',
                'l',
                InputOption::VALUE_REQUIRED,
                sprintf(
                    'Comma separated regex for matching files that are compiler passes, default: %s',
                    implode(', ', $defaults->getPatterns())
                ),
                implode(', ', $defaults->getPatterns())
            );
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $this->printFiles($input, $output);

            return 0;
        } catch (\Exception $e) {
            $output->writeln("<error>{$e->getMessage()}</error>");

            return 1;
        }
    }

    private function printFiles(
        InputInterface $input,
        OutputInterface $output
    ): void {
        $total = 0;

        $output->writeln("<info>[ Services files list ]</info>\n");

        $pattern = $input->getOption('scan-pattern');

        try {
            $options = CompilerPassFileFinderOptions::fromArray([
                'directories' => explode(',', $input->getOption('scan-directories')),
                'patterns' => null !== $pattern ? explode(',', $pattern) : CompilerPassFileFinderOptions::DEFAULT_CPASS_PATTERNS,
            ]);

            $finder = new CompilerPassFileFinder($options);

            $files = $finder->find();
        } catch (NoFilesFoundException $e) {
            $output->writeln("\n<error>{$e->getMessage()}</error>\n");

            return;
        }

        /**
         * @var FileInfo $file
         */
        foreach ($files as $file) {
            $total++;
            $output->writeln($file->getRealPath());
        }

        $output->writeln("\n<info>Total files: $total</info>");
    }
}
