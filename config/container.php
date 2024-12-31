<?php

use Brotkrueml\SchemaGenerator\Command\GenerateCommand;
use Brotkrueml\SchemaGenerator\Generation\Writer;
use Brotkrueml\SchemaGenerator\Manual\Manuals;
use Brotkrueml\SchemaGenerator\Manual\ManualsBuilder;
use Brotkrueml\SchemaGenerator\Twig\SchemaClassName;
use Brotkrueml\SchemaGenerator\Twig\SchemaSection;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Twig\Environment as TwigEnvironment;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFunction;
use function DI\autowire;

$manualsFile = realpath(__DIR__ . '/manuals.php');
$schemaFile = realpath(__DIR__ . '/../schema/schemaorg-current-https.jsonld');

return [
    GenerateCommand::class => autowire()
        ->constructorParameter('schemaFile', $schemaFile),

    LoggerInterface::class => static function (ContainerInterface $container): LoggerInterface {
        $logger = new Logger('schema-generator');

        $logger->pushHandler(
            new StreamHandler('php://stdout', Level::Info),
        );

        return $logger;
    },

    Manuals::class => static function(ContainerInterface $container) use ($manualsFile): Manuals
    {
        return (new ManualsBuilder($manualsFile))->build();
    },

    TwigEnvironment::class => static function (ContainerInterface $container): TwigEnvironment {
        $loader = new FilesystemLoader(__DIR__ . '/../templates');

        $twig = new TwigEnvironment($loader, [
            'cache' => false,
        ]);

        $twig->addFunction(
            new TwigFunction(
                'schema_model_class_name',
                [SchemaClassName::class, 'forModel'],
            )
        );

        $twig->addFunction(
            new TwigFunction(
                'schema_section',
                [SchemaSection::class, 'fromShortName'],
            )
        );

        return $twig;
    },

    Writer::class => static function (ContainerInterface $container): Writer
    {
        return new Writer($container->get(TwigEnvironment::class));
    }
];
