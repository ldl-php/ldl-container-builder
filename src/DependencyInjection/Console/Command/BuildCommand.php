<?php declare(strict_types=1);

namespace LDL\DependencyInjection\Console\Command;

use LDL\DependencyInjection\Container\Config\ContainerConfigFactory;
use LDL\DependencyInjection\Container\Writer\ContainerFileWriter;
use LDL\DependencyInjection\Container\Writer\Options\ContainerWriterOptions;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class BuildCommand extends AbstractContainerCommand
{
    public const COMMAND_NAME = 'container:build';

    public function configure() : void
    {
        parent::configure();

        $this->setName(self::COMMAND_NAME)
            ->setDescription('Build Container');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            parent::execute($input, $output);

            $this->build($input, $output);

            return parent::EXIT_SUCCESS;

        }catch(\Exception $e){
            $output->writeln("<error>{$e->getMessage()}</error>");

            return parent::EXIT_ERROR;
        }
    }

    private function build(
        InputInterface $input,
        OutputInterface $output
    ) : void
    {
        $start = hrtime(true);

        try{

            $title = '[ Building compiled services file ]';

            $output->writeln("\n<info>$title</info>\n");

            $container = $this->container->build();

            $containerWriterOptions = ContainerWriterOptions::fromArray([
                'filename' => $input->getArgument('output-file'),
                'force' => (bool) $input->getOption('force-overwrite'),
            ]);

            $writer = new ContainerFileWriter($containerWriterOptions);

            $writer->write(ContainerConfigFactory::factory(
                $this->container->getServiceFinder(),
                $this->container->getServiceCompiler(),
                $this->container->getServiceReader(),
                $this->container->getCompilerPassFinder(),
                $this->container->getCompilerPassReader(),
                $writer,
                $this->dumpOptions
            ), $container);

            $output->writeln("");

        }catch(\Exception $e) {

            $output->writeln("\n\n<error>Build failed!</error>\n");
            $output->writeln((string)($e->getMessage()));
            $output->writeln("Scanned directories: {$input->getOption('scan-directories')}");
            $output->writeln("Scanned files: {$input->getOption('scan-files')}");

        }

        $end = hrtime(true);
        $total = round((($end - $start) / 1e+6) / 1000,2);

        $output->writeln("\n<info>Took: $total seconds</info>");
    }

}