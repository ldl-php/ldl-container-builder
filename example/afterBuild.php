<?php declare(strict_types=1);

use LDL\DependencyInjection\LDLContainer;

require __DIR__.'/../vendor/autoload.php';
require __DIR__.'/autoload.php';
require __DIR__.'/../container.php';

$container = new LDLContainer();

$userApplication = $container->get('LDL.example.application.user');
$adminApp = $container->get('LDL.example.application.admin');
$mailerService = $container->get('LDL.example.service.mailer');

echo "Get user application name\n";
echo $userApplication->getName()."\n";

echo "Get admin application name\n";
echo $adminApp->getName()."\n";

echo "Get template from MailerService\n";
echo $mailerService->getTemplate()->get();