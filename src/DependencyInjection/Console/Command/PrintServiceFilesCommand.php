<?php

declare(strict_types=1);

namespace LDL\DependencyInjection\Console\Command;

use LDL\DependencyInjection\Service\File\Finder\Exception\NoFilesFoundException;
use LDL\DependencyInjection\Service\File\Finder\Options\ServiceFileFinderOptions;
use LDL\DependencyInjection\Service\File\Finder\ServiceFileFinder;
use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class PrintServiceFilesCommand extends SymfonyCommand
{
    public const COMMAND_NAME = 'services:print';

    public function configure(): void
    {
        $defaults = ServiceFileFinderOptions::fromArray([]);

        $defaultDirectories = implode(', ', $defaults->getDirectories());
        $defaultFiles = implode(', ', $defaults->getFiles());

        $this->setName(self::COMMAND_NAME)
            ->setDescription('Prints services files')
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
                'scan-files',
                'l',
                InputOption::VALUE_REQUIRED,
                sprintf(
                    'Comma separated list of files to scan, default: %s',
                    $defaultFiles
                ),
                $defaultFiles
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

        try {
            $options = ServiceFileFinderOptions::fromArray([
                'directories' => explode(',', $input->getOption('scan-directories')),
                'files' => explode(',', $input->getOption('scan-files')),
            ]);

            $finder = new ServiceFileFinder($options);

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
