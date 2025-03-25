<?php

declare(strict_types=1);

/*
 * This file is part of the Schema Generator.
 *
 * For the full copyright and license information, please view the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\SchemaGenerator\Generation;

use Brotkrueml\SchemaGenerator\Schema\Section;
use Brotkrueml\SchemaGenerator\Schema\Vocabulary\Comment;
use Brotkrueml\SchemaGenerator\Schema\Vocabulary\Id;
use Brotkrueml\SchemaGenerator\Schema\Vocabulary\Property;
use Brotkrueml\SchemaGenerator\Schema\Vocabulary\Type;
use Brotkrueml\SchemaGenerator\Schema\Vocabulary\Types;

final readonly class AdditionalPropertiesGenerator
{
    public function __construct(
        private ClassNameBuilder $classNameBuilder,
        private Writer $writer,
    ) {}

    public function generate(Section $section, Types $types, string $basePath): void
    {
        if ($section === Section::Core) {
            return;
        }

        $this->removeOldFiles($basePath);

        $additionalPropertiesByType = $this->collectAdditionalPropertiesOfSection($section, $types);

        foreach ($additionalPropertiesByType as $typeLabel => $_) {
            \usort(
                $additionalPropertiesByType[$typeLabel],
                static fn(Property $a, Property $b): int => $a->id()->label() <=> $b->id()->label(),
            );
        }

        $this->generateAdditionalProperties($section, $additionalPropertiesByType, $basePath);
    }

    private function removeOldFiles(string $basePath): void
    {
        $path = $basePath . '/' . Path::AdditionalProperties->value;

        foreach (\glob($path . '/*.php') as $filename) {
            @\unlink($filename);
        }
    }

    /**
     * @return array<string, array<string, list<Property>>>
     */
    private function collectAdditionalPropertiesOfSection(Section $section, Types $types): array
    {
        $additionalPropertiesByType = [];

        foreach ($types as $type) {
            if ($type->section() === $section) {
                continue;
            }

            foreach ($type->properties() as $property) {
                if ($property->section() !== $section) {
                    continue;
                }

                $typeLabel = $type->id()->label();
                if (! isset($additionalPropertiesByType[$typeLabel])) {
                    $additionalPropertiesByType[$typeLabel] = [];
                }

                $additionalPropertiesByType[$typeLabel][] = $property;
            }
        }

        return $additionalPropertiesByType;
    }

    private function generateAdditionalProperties(Section $section, array $additionalPropertiesByType, string $basePath): void
    {
        $path = \sprintf(
            '%s/%s',
            $basePath,
            Path::AdditionalProperties->value,
        );

        foreach ($additionalPropertiesByType as $type => $properties) {
            $context = [
                'additionalProperties' => $properties,
                'className' => $this->classNameBuilder->getClassNameForModelFromType(new Type(new Id('schema:' . $type), new Comment(''), $section)),
                'type' => $type,
                'namespace' => $section->phpNamespace(),
            ];

            $this->writer->write($path, Template::AdditionalProperties, $context);
        }
    }
}
