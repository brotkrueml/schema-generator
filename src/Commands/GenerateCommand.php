<?php

declare(strict_types=1);

/*
 * This file is part of the Schema Generator.
 *
 * For the full copyright and license information, please view the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\SchemaGenerator\Commands;

use Brotkrueml\SchemaGenerator\Collector;
use Brotkrueml\SchemaGenerator\Enumerations\Extensions;
use Brotkrueml\SchemaGenerator\Generator;
use Brotkrueml\SchemaGenerator\Writer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class GenerateCommand extends Command
{
    private const EXTENSIONS = [
        'core',
        'auto',
        'bib',
        'health',
        'pending',
    ];
    private const PATH_SCHEMA = __DIR__ . '/../../schema/schemaorg-current-https.jsonld';

    protected static $defaultName = 'schema:generate';

    private Writer $writer;

    public function __construct(Writer $writer)
    {
        $this->writer = $writer;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setDescription('Generates the classes for the given vocabulary');

        $this
            ->addArgument(
                'extension',
                InputArgument::REQUIRED,
                \sprintf('The extension (%s)', \implode(', ', self::EXTENSIONS)),
            )
            ->addArgument('basePath', InputArgument::REQUIRED, 'The base path (e.g. /path/to/schema-ext)');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $extension = $input->getArgument('extension');
        $this->checkExtensionValue($extension);
        $basePath = $this->checkBasePath($input->getArgument('basePath'));

        $collector = new Collector(self::PATH_SCHEMA);
        $types = $collector->getTypes();

        $generator = new Generator($this->writer, $types, $extension, $basePath);
        $generator->generate();

        return Command::SUCCESS;
    }

    private function checkExtensionValue(string $extension): void
    {
        $classConstant = \sprintf(
            '%s::%s',
            Extensions::class,
            \strtoupper($extension),
        );

        if (! \defined($classConstant)) {
            throw new \InvalidArgumentException(
                \sprintf(
                    'Extension "%s" is not available, possible extensions: %s!',
                    $extension,
                    \implode(', ', self::EXTENSIONS),
                ),
                1616347918,
            );
        }
    }

    private function checkBasePath(string $basePath): string
    {
        if (\is_dir($basePath)) {
            return \rtrim($basePath, '/');
        }

        throw new \InvalidArgumentException(
            \sprintf('Base Path "%s" is not a directory!', $basePath),
            1616347919,
        );
    }
}
