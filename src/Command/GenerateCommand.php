<?php

declare(strict_types=1);

/*
 * This file is part of the Schema Generator.
 *
 * For the full copyright and license information, please view the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\SchemaGenerator\Command;

use Brotkrueml\SchemaGenerator\Generation\AdditionalPropertiesGenerator;
use Brotkrueml\SchemaGenerator\Generation\EnumerationGenerator;
use Brotkrueml\SchemaGenerator\Generation\TypeGenerator;
use Brotkrueml\SchemaGenerator\Schema\SchemaCollector;
use Brotkrueml\SchemaGenerator\Schema\Section;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'schema:generate',
    description: 'Generates the classes for the given vocabulary',
)]
final class GenerateCommand extends Command
{
    public function __construct(
        private readonly AdditionalPropertiesGenerator $additionalPropertiesGenerator,
        private readonly EnumerationGenerator $enumerationGenerator,
        private readonly LoggerInterface $logger,
        private readonly SchemaCollector $schemaBuilder,
        private readonly TypeGenerator $typeGenerator,
        private readonly string $schemaFile,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument(
                'section',
                InputArgument::REQUIRED,
                \sprintf(
                    'The section (%s)',
                    \implode(
                        ', ',
                        $this->availableSections(),
                    ),
                ),
            )
            ->addArgument('basePath', InputArgument::REQUIRED, 'The base path (e.g. /path/to/schema-ext)');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $section = Section::fromShortName($input->getArgument('section'));
        $basePath = $this->checkBasePath($input->getArgument('basePath'));

        try {
            $this->schemaBuilder->build($this->schemaFile);
            $types = $this->schemaBuilder->types();
            $enumerations = $this->schemaBuilder->enumerations();

            $this->typeGenerator->generate($section, $types, $basePath);
            $this->additionalPropertiesGenerator->generate($section, $types, $basePath);
            $this->enumerationGenerator->generate($section, $enumerations, $basePath);
        } catch (\Throwable $t) {
            $this->logger->error($t->getMessage(), [
                'file' => $t->getFile(),
                'line' => $t->getLine(),
            ]);
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }

    /**
     * @return list<string>
     */
    private function availableSections(): array
    {
        return \array_filter(
            \array_map(
                static fn(Section $section): string => \strtolower($section->name),
                Section::cases(),
            ),
            static fn(string $sectionName): bool => $sectionName !== 'meta',
        );
    }

    private function checkBasePath(string $basePath): string
    {
        if (\is_dir($basePath)) {
            return \rtrim($basePath, '/');
        }

        throw new \InvalidArgumentException(
            \sprintf('Base path "%s" is not a directory!', $basePath),
            1735238299,
        );
    }
}
