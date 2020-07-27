<?php

declare(strict_types=1);

namespace LDL\DependencyInjection\CompilerPass\Command;

use LDL\DependencyInjection\CompilerPass\Finder\CompilerPassFinder;
use LDL\DependencyInjection\CompilerPass\Finder\Options\CompilerPassFinderOptions;
use Symfony\Component\Console\Command\Command as SymfonyCommand;
use LDL\DependencyInjection\CompilerPass\Finder\Exception\NoFilesFoundException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\SplFileInfo as FileInfo;

class PrintFilesCommand extends SymfonyCommand
{
    public const COMMAND_NAME = 'cpass:print';

    public function configure() : void
    {
        $defaults = CompilerPassFinderOptions::fromArray([]);

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
                    'Regex for matching files that are compiler passes, default: %s',
                    $defaults->getPattern()
                ),
                $defaults->getPattern()
            );
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $this->printFiles($input, $output);
            return 0;
        }catch(\Exception $e){
            $output->writeln("<error>{$e->getMessage()}</error>");
            return 1;
        }
    }

    private function printFiles(
        InputInterface $input,
        OutputInterface $output
    ) : void
    {
        $total = 0;

        $output->writeln("<info>[ Services files list ]</info>\n");

        try{
            $options = CompilerPassFinderOptions::fromArray([
                'directories' => explode(',', $input->getOption('scan-directories')),
                'pattern' => $input->getOption('scan-pattern')
            ]);

            $finder = new CompilerPassFinder($options);

            $files = $finder->find();

        }catch(NoFilesFoundException $e){
            $output->writeln("\n<error>{$e->getMessage()}</error>\n");

            return;
        }

        /**
         * @var FileInfo $file
         */
        foreach($files as $file){
            $total++;
            $output->writeln($file->getRealPath());
        }

        $output->writeln("\n<info>Total files: $total</info>");
    }

}