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
use Brotkrueml\SchemaGenerator\Schema\Vocabulary\Property;
use Brotkrueml\SchemaGenerator\Schema\Vocabulary\Types;

final readonly class AdditionalPropertiesGenerator
{
    public function __construct(
        private Writer $writer,
    ) {}

    public function generate(Section $section, Types $types, string $basePath): void
    {
        if ($section === Section::Core) {
            return;
        }

        $additionalPropertiesBySectionAndType = $this->collectAdditionalPropertiesOfSection($section, $types);

        // Sort section short names, move "core" to the start of the array
        \uksort($additionalPropertiesBySectionAndType, static function (string $a, string $b): int {
            if ($a === 'core') {
                $a = '';
            }
            if ($b === 'core') {
                $b = '';
            }

            return \strcasecmp($a, $b);
        });

        foreach ($additionalPropertiesBySectionAndType as $sectionShortName => $types) {
            \ksort($additionalPropertiesBySectionAndType[$sectionShortName]);
            foreach ($types as $typeLabel => $_) {
                \usort(
                    $additionalPropertiesBySectionAndType[$sectionShortName][$typeLabel],
                    static fn(Property $a, Property $b): int => $a->id()->label() <=> $b->id()->label(),
                );
            }
        }

        $this->generateAdditionalProperties($section, $additionalPropertiesBySectionAndType, $basePath);
    }

    /**
     * @return array<string, array<string, list<Property>>>
     */
    private function collectAdditionalPropertiesOfSection(Section $section, Types $types): array
    {
        $additionalPropertiesBySectionAndType = [];

        foreach ($types as $type) {
            if ($type->section() === $section) {
                continue;
            }

            foreach ($type->properties() as $property) {
                if ($property->section() !== $section) {
                    continue;
                }

                $sectionShortName = $type->section()->shortName();
                if (! isset($additionalPropertiesBySectionAndType[$sectionShortName])) {
                    $additionalPropertiesBySectionAndType[$sectionShortName] = [];
                }

                $typeLabel = $type->id()->label();
                if (! isset($additionalPropertiesBySectionAndType[$sectionShortName][$typeLabel])) {
                    $additionalPropertiesBySectionAndType[$sectionShortName][$typeLabel] = [];
                }

                $additionalPropertiesBySectionAndType[$sectionShortName][$typeLabel][] = $property;
            }
        }

        return $additionalPropertiesBySectionAndType;
    }

    private function generateAdditionalProperties(Section $section, array $additionalPropertiesBySectionAndType, string $basePath): void
    {
        $path = $basePath . '/' . Path::AdditionalProperties->value;

        $context = [
            'additionalProperties' => $additionalPropertiesBySectionAndType,
            'className' => 'RegisterAdditionalProperties',
            'currentSection' => $section,
            'namespace' => $section->phpNamespace(),
        ];

        $this->writer->write($path, Template::AdditionalProperties, $context);
    }
}
