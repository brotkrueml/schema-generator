<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use Brotkrueml\SchemaGenerator\Writer;
use Brotkrueml\SchemaGenerator\Commands\GenerateCommand;
use Symfony\Component\Console\Application;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

$application = new Application();

$loader = new FilesystemLoader(__DIR__ . '/../templates');
$twig = new Environment($loader, [
    'cache' => false,
]);
$writer = new Writer($twig);
$application->add(new GenerateCommand($writer));

$application->run();
