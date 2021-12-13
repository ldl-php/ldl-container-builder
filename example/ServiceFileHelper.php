<?php

declare(strict_types=1);

require __DIR__.'/../vendor/autoload.php';

use LDL\DependencyInjection\Service\Helper\ServiceFileHelper;
use LDL\File\File;

$services = ServiceFileHelper::getDefinedServicesInFile(new File(__DIR__.'/Build/Application/Admin/services.xml'));

dump($services->toPrimitiveArray(true));
