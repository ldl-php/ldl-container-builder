<?php declare(strict_types=1);

namespace LDL\DependencyInjection\Console\Command;

use LDL\DependencyInjection\CompilerPass\Finder\CompilerPassFinder;
use LDL\DependencyInjection\CompilerPass\Finder\Options\CompilerPassFinderOptions;
use LDL\DependencyInjection\CompilerPass\Reader\CompilerPassReader;
use LDL\DependencyInjection\CompilerPass\Reader\Options\CompilerPassReaderOptions;
use LDL\DependencyInjection\Container\Builder\LDLContainerBuilder;
use LDL\DependencyInjection\Container\Builder\LDLContainerBuilderInterface;
use LDL\DependencyInjection\Container\Config\ContainerConfig;
use LDL\DependencyInjection\Container\Writer\Options\ContainerWriterOptions;
use LDL\DependencyInjection\Service\Compiler\Options\ServiceCompilerOptions;
use LDL\DependencyInjection\Service\Compiler\ServiceCompiler;
use LDL\DependencyInjection\Service\Finder\Options\ServiceFileFinderOptions;
use LDL\DependencyInjection\Service\Finder\ServiceFileFinder;
use LDL\DependencyInjection\Service\Reader\Options\ServiceReaderOptions;
use LDL\DependencyInjection\Service\Reader\ServiceFileReader;
use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

abstract class AbstractContainerCommand extends SymfonyCommand
{
    public const EXIT_SUCCESS = 0;
    public const EXIT_ERROR = 1;

    /**
     * @var LDLContainerBuilderInterface
     */
    protected $container;

    /**
     * @var array
     */
    protected $dumpOptions = ContainerConfig::DEFAULT_DUMP_OPTIONS;

    public function configure() : void
    {
        $finderDefaults = ServiceFileFinderOptions::fromArray([]);
        $cpassDefaults = CompilerPassFinderOptions::fromArray([]);
        $containerWriter = ContainerWriterOptions::fromArray([]);

        $this->addArgument(
            'output-file',
            InputArgument::OPTIONAL,
            'Name of the output file',
            $containerWriter->getFilename()
        )
            ->addOption(
                'namespace',
                's',
                InputOption::VALUE_OPTIONAL,
                'Namespace of the generated container'
            )
            ->addOption(
                'className',
                'c',
                InputOption::VALUE_OPTIONAL,
                'Classname of the generated container'
            )
            ->addOption(
                'format',
                't',
                InputOption::VALUE_OPTIONAL,
                'Format of the output container'
            )
            ->addOption(
                'force-overwrite',
                'w',
                InputOption::VALUE_NONE,
                'Overwrite output file'
            )
            ->addOption(
                'scan-directories',
                'd',
                InputOption::VALUE_OPTIONAL,
                sprintf(
                    'Comma separated list of directories to scan, default: %s',
                    implode(', ', $finderDefaults->getDirectories())
                ),
                implode(',', $finderDefaults->getDirectories())
            )
            ->addOption(
                'excluded-directories',
                'e',
                InputOption::VALUE_OPTIONAL,
                'Comma separated list of excluded directories to scan'
            )
            ->addOption(
                'scan-files',
                'l',
                InputOption::VALUE_OPTIONAL,
                'Comma separated list of files to scan',
                implode(', ', $finderDefaults->getFiles())
            )
            ->addOption(
                'find-first',
                'f',
                InputOption::VALUE_REQUIRED,
                'Comma separated list of files of service files to be loaded with first priority'
            )
            ->addOption(
                'ignore-read-errors',
                'i',
                InputOption::VALUE_NONE,
                'Ignore syntax errors in service files'
            )
            ->addOption(
                'cpass-pattern',
                'p',
                InputOption::VALUE_OPTIONAL,
                'Comma separated regex pattern to find compiler pass files',
                implode(', ', $cpassDefaults->getPatterns())
            );
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        try {

            $this->build($input, $output);
            return self::EXIT_SUCCESS;

        }catch(\Exception $e){

            $output->writeln("<error>{$e->getMessage()}</error>");
            return self::EXIT_ERROR;

        }
    }

    private function build(InputInterface $input, OutputInterface $output)
    {
        $findFirst = $input->getOption('find-first');
        $excludedDirectories = $input->getOption('excluded-directories');
        $cpassPattern = $input->getOption('cpass-pattern');
        $containerNamespace = $input->getOption('namespace');
        $containerName = $input->getOption('className');
        $containerFormat = $input->getOption('format');

        if(null !== $containerNamespace){
            $this->dumpOptions['namespace'] = $containerNamespace;
        }

        if(null !== $containerName){
            $this->dumpOptions['class'] = $containerName;
        }

        if(null !== $containerFormat){
            $this->dumpOptions['format'] = $containerFormat;
        }

        $compilerProgress = new ProgressBar($output);
        $compilerProgress->setOverwrite(true);

        $serviceFinderOptions = ServiceFileFinderOptions::fromArray([
            'directories' => explode(',', $input->getOption('scan-directories')),
            'excludedDirectories' => null !== $excludedDirectories ? explode(',', $excludedDirectories) : [],
            'files' => explode(',', $input->getOption('scan-files')),
            'findFirst' => null !== $findFirst ? explode(',', $findFirst) : []
        ]);

        $serviceCompilerOptions = ServiceCompilerOptions::fromArray([
            'onBeforeCompile' => function($container, $files) use ($compilerProgress){
                $compilerProgress->setMaxSteps(count($files));
            },
            'onCompile' => function($container, $file) use ($compilerProgress){
                $compilerProgress->advance();
            },
            'onAfterCompile' => function($file, $vars) use ($compilerProgress){
                $compilerProgress->finish();
            }
        ]);

        $serviceReaderOptions = ServiceReaderOptions::fromArray([
            'ignoreErrors' => (bool)$input->getOption('ignore-read-errors')
        ]);

        $compilerPassFinderOptions = CompilerPassFinderOptions::fromArray([
            'patterns' => null !== $cpassPattern ? explode(',', $cpassPattern) : CompilerPassFinderOptions::DEFAULT_CPASS_PATTERNS,
            'directories' => explode(',', $input->getOption('scan-directories')),
            'excludedDirectories' => null !== $excludedDirectories ? explode(',', $excludedDirectories) : []
        ]);

        $compilerPassReaderOptions = CompilerPassReaderOptions::fromArray([
            'ignoreErrors' => (bool)$input->getOption('ignore-read-errors')
        ]);

        $this->container = new LDLContainerBuilder(
            new ServiceFileFinder($serviceFinderOptions),
            new ServiceCompiler($serviceCompilerOptions),
            new ServiceFileReader($serviceReaderOptions),
            new CompilerPassFinder($compilerPassFinderOptions),
            new CompilerPassReader($compilerPassReaderOptions)
        );
    }
}