<?php

declare(strict_types=1);

use Brotkrueml\SchemaGenerator\Command\GenerateCommand;
use DI\ContainerBuilder;
use Symfony\Component\Console\Application;

require __DIR__ . '/../vendor/autoload.php';

$containerBuilder = new ContainerBuilder();
$containerBuilder->addDefinitions(__DIR__ . '/../config/container.php');
$container = $containerBuilder->build();

$application = new Application();
$application->add($container->get(GenerateCommand::class));
$application->run();
