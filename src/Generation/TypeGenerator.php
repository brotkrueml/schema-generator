<?php

declare(strict_types=1);

/*
 * This file is part of the Schema Generator.
 *
 * For the full copyright and license information, please view the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\SchemaGenerator\Generation;

use Brotkrueml\SchemaGenerator\Manual\Manuals;
use Brotkrueml\SchemaGenerator\Schema\Section;
use Brotkrueml\SchemaGenerator\Schema\Vocabulary\Id;
use Brotkrueml\SchemaGenerator\Schema\Vocabulary\Property;
use Brotkrueml\SchemaGenerator\Schema\Vocabulary\RecursiveTypeIdCollector;
use Brotkrueml\SchemaGenerator\Schema\Vocabulary\Type;
use Brotkrueml\SchemaGenerator\Schema\Vocabulary\Types;

final readonly class TypeGenerator
{
    public function __construct(
        private ClassNameBuilder $classNameBuilder,
        private Manuals $manuals,
        private RecursiveTypeIdCollector $recursiveTypeIdCollector,
        private Writer $writer,
    ) {}

    public function generate(Section $section, Types $types, string $basePath): void
    {
        $this->removeOldFiles($basePath);
        $this->generateModelClasses($section, $types, $basePath);
        $this->generateViewHelperClasses($section, $types, $basePath);
    }

    private function removeOldFiles(string $basePath): void
    {
        foreach (\glob($this->getAbsoluteModelPath($basePath) . '/*.php') as $filename) {
            @\unlink($filename);
        }

        foreach (\glob($this->getAbsoluteViewHelperPath($basePath) . '/*.php') as $filename) {
            @\unlink($filename);
        }
    }

    private function getAbsoluteModelPath(string $basePath): string
    {
        return $basePath . '/' . Path::Model->value;
    }

    private function getAbsoluteViewHelperPath(string $basePath): string
    {
        return $basePath . '/' . Path::ViewHelper->value;
    }

    private function generateModelClasses(Section $section, Types $types, string $basePath): void
    {
        foreach ($types as $type) {
            if ($type->isEnumeration()) {
                continue;
            }

            if ($type->section() !== $section) {
                continue;
            }

            $context = [
                'className' => $this->classNameBuilder->getClassNameForModelFromType($type),
                'isWebPageType' => $this->isWebPageType($types, $type),
                'manuals' => $this->manuals->findByType($type),
                'namespace' => $section->phpNamespace(),
                'properties' => $this->collectPropertiesOfSection($section, $type),
                'type' => $type,
            ];

            $this->writer->write($this->getAbsoluteModelPath($basePath), Template::Model, $context);
        }
    }

    private function isWebPageType(Types $types, Type $type): bool
    {
        $parentTypeIds = $this->recursiveTypeIdCollector->collect($types, $type);

        return \array_any(
            $parentTypeIds,
            static fn(Id $id): bool => $id->id() === 'schema:WebPage',
        );
    }

    private function generateViewHelperClasses(Section $section, Types $types, string $basePath): void
    {
        foreach ($types as $type) {
            if ($type->isEnumeration()) {
                continue;
            }

            if ($type->section() !== $section) {
                continue;
            }

            $context = [
                'className' => $this->classNameBuilder->getClassNameForViewHelperFromType($type),
                'namespace' => $section->phpNamespace(),
                'type' => $type,
            ];

            $this->writer->write($this->getAbsoluteViewHelperPath($basePath), Template::ViewHelper, $context);
        }
    }

    /**
     * @return list<Property>
     */
    private function collectPropertiesOfSection(Section $section, Type $type): array
    {
        $properties = [];
        foreach ($type->properties() as $property) {
            if ($this->shouldConsiderSection($section, $property->section())) {
                $properties[$property->id()->label()] = $property;
            }
        }

        \usort(
            $properties,
            static fn(Property $a, Property $b): int => $a->id()->label() <=> $b->id()->label(),
        );

        return $properties;
    }

    private function shouldConsiderSection(Section $section, Section $sectionToCheckAgainst): bool
    {
        return $section === $sectionToCheckAgainst || $sectionToCheckAgainst === Section::Core;
    }
}
