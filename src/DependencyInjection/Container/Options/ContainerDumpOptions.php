<?php

declare(strict_types=1);

namespace LDL\DependencyInjection\Container\Options;

use LDL\File\Contracts\FileInterface;
use LDL\File\File;
use LDL\Framework\Base\Exception\JsonFactoryException;
use LDL\Framework\Base\Exception\JsonFileFactoryException;
use LDL\Type\Collection\Interfaces\Type\StringCollectionInterface;
use LDL\Type\Collection\Types\String\StringCollection;

class ContainerDumpOptions implements ContainerDumpOptionsInterface
{
    /**
     * @var string|null
     */
    private $namespace;

    /**
     * @var string|null
     */
    private $class;

    /**
     * @var string|null
     */
    private $base_class;

    /**
     * @var bool|null
     */
    private $as_files;

    /**
     * @var bool|null
     */
    private $debug;

    /**
     * @var string|null
     */
    private $hot_path_tag;

    /**
     * @var StringCollectionInterface|null
     */
    private $preload_tags;

    /**
     * @var string|null
     */
    private $inline_factories_parameter;

    /**
     * @var string|null
     */
    private $inline_class_loader_parameter;

    /**
     * @var StringCollectionInterface|null
     */
    private $preload_classes;

    /**
     * @var string|null
     */
    private $service_locator_tag;

    public function __construct(
        string $class = null,
        string $namespace = null,
        string $base_class = null,
        bool $as_files = null,
        bool $debug = null,
        string $hot_path_tag = null,
        iterable $preload_tags = null,
        string $inline_factories_parameter = null,
        string $inline_class_loader_parameter = null,
        iterable $preload_classes = null,
        string $service_locator_tag = null
    ) {
        $this->namespace = null === $namespace ? 'LDL\\DependencyInjection' : $this->normalizeNamespace($namespace);
        $this->base_class = null === $base_class ? $base_class : $this->normalizeClassName($base_class);
        $this->class = null === $class ? 'ServiceContainer' : $this->normalizeClassName($class);
        $this->debug = $debug;
        $this->as_files = $as_files;
        $this->hot_path_tag = $hot_path_tag;
        $this->preload_tags = new StringCollection($preload_tags);
        $this->inline_class_loader_parameter = $inline_class_loader_parameter;
        $this->inline_factories_parameter = $inline_factories_parameter;
        $this->preload_classes = new StringCollection($preload_classes);
        $this->service_locator_tag = $service_locator_tag;
    }

    public function getNamespace(): ?string
    {
        return $this->namespace;
    }

    public function getClass(): ?string
    {
        return $this->class;
    }

    public function getBaseClass(): ?string
    {
        return $this->base_class;
    }

    public function isAsFiles(): ?bool
    {
        return $this->as_files;
    }

    public function isDebug(): ?bool
    {
        return $this->debug;
    }

    public function getHotPathTag(): ?string
    {
        return $this->hot_path_tag;
    }

    public function getPreloadTags(): ?StringCollectionInterface
    {
        return $this->preload_tags;
    }

    /**
     * @return string
     */
    public function getInlineFactoriesParameter(): ?string
    {
        return $this->inline_factories_parameter;
    }

    /**
     * @return string
     */
    public function getInlineClassLoaderParameter(): ?string
    {
        return $this->inline_class_loader_parameter;
    }

    public function getPreloadClasses(): ?StringCollectionInterface
    {
        return $this->preload_classes;
    }

    /**
     * @return string
     */
    public function getServiceLocatorTag(): ?string
    {
        return $this->service_locator_tag;
    }

    public function toArray(bool $useKeys = null, bool $skipEmptyVars = true): array
    {
        $return = [];
        $vars = get_object_vars($this);

        if (false === $skipEmptyVars) {
            return $vars;
        }

        foreach ($vars as $key => $var) {
            if (null === $var || '' === $var) {
                continue;
            }

            if (!$var instanceof StringCollectionInterface) {
                $return[$key] = $var;
                continue;
            }

            if (0 === count($var)) {
                continue;
            }

            $return[$key] = $var->toPrimitiveArray(false);
        }

        return $return;
    }

    public function write(string $path, int $perms = 0644, bool $force = false): FileInterface
    {
        return File::create(
            $path,
            json_encode(
                $this,
                \JSON_THROW_ON_ERROR | \JSON_PRETTY_PRINT
            ),
            $perms,
            $force
        );
    }

    public static function fromArray(array $data = []): ContainerDumpOptionsInterface
    {
        return new self(
            $data['class'],
            $data['namespace'],
            $data['base_class'],
            $data['as_files'],
            $data['debug'],
            $data['hot_path_tag'],
            $data['preload_tags'],
            $data['inline_factories_parameter'],
            $data['inline_class_loader_parameter'],
            $data['preload_classes'],
            $data['service_locator_tag']
        );
    }

    public static function fromJsonFile(string $file): ContainerDumpOptionsInterface
    {
        try {
            return self::fromJsonString(
                (new File($file))->getLinesAsString()
            );
        } catch (\Throwable $e) {
            $msg = "Error creating dump options from JSON file: $file";
            throw new JsonFileFactoryException($msg, 0, $e);
        }
    }

    public static function fromJsonString(string $json)
    {
        try {
            return self::fromArray(json_decode($json, true, \JSON_THROW_ON_ERROR));
        } catch (\Throwable $e) {
            $msg = 'Error creating dump options from JSON string';
            throw new JsonFactoryException($msg, 0, $e);
        }
    }

    public function jsonSerialize()
    {
        return $this->toArray(true);
    }

    //<editor-fold desc="Private methods">
    private function normalizeNamespace(string $ns): string
    {
        if ('' === $ns) {
            return '';
        }

        return implode(
            '\\',
            array_map(static function ($ns) {
                return preg_replace("#\W#", '', $ns);
            }, explode('\\', trim($ns, '\\'))));
    }

    private function normalizeClassName(string $className): string
    {
        return preg_replace('#\W#', '', $className);
    }
    //</editor-fold>
}
