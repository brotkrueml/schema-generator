<?php

declare(strict_types=1);

/*
 * This file is part of the Schema Generator.
 *
 * For the full copyright and license information, please view the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\SchemaGenerator\Schema;

use Brotkrueml\SchemaGenerator\Schema\Exception\TypeNotFoundException;
use Brotkrueml\SchemaGenerator\Schema\Vocabulary\Ids;
use Brotkrueml\SchemaGenerator\Schema\Vocabulary\Properties;
use Brotkrueml\SchemaGenerator\Schema\Vocabulary\PropertyBuilder;
use Brotkrueml\SchemaGenerator\Schema\Vocabulary\Type;
use Brotkrueml\SchemaGenerator\Schema\Vocabulary\TypeBuilder;
use Brotkrueml\SchemaGenerator\Schema\Vocabulary\Types;
use Psr\Log\LoggerInterface;

final readonly class SchemaCollector
{
    private const array IDS_TO_SKIP = [
        'schema:DataType',
    ];

    private Properties $properties;
    private Types $types;

    public function __construct(
        private LoggerInterface $logger,
        private PropertyBuilder $propertyBuilder,
        private TypeBuilder $typeBuilder,
    ) {
        $this->properties = new Properties();
        $this->types = new Types();
    }

    public function build(string $schemaFile): void
    {
        $schema = $this->readSchema($schemaFile);

        // 1. Collect types
        foreach ($schema['@graph'] as $term) {
            if (\in_array($term['@id'], self::IDS_TO_SKIP, true)) {
                $this->logger->notice(
                    \sprintf(
                        'Skip term with id "%s"',
                        $term['@id'],
                    ),
                );
                continue;
            }

            $this->processTerm($term);
        }

        // 2. Collect properties and assign to types
        foreach ($this->properties as $property) {
            foreach ($property->typeIds() as $typeId) {
                try {
                    $this->types->findTypeById($typeId)->addProperty($property);
                } catch (TypeNotFoundException $e) {
                    $this->logger->notice($e->getMessage(), [
                        'property' => $property->id()->id(),
                    ]);
                }
            }
        }

        // 3. Collect properties from parent types
        foreach ($this->types as $type) {
            $this->collectParentProperties($type, $type->parentIds());
        }
    }

    private function collectParentProperties(Type $type, Ids $parentIds): void
    {
        foreach ($parentIds as $parentId) {
            try {
                $parentType = $this->types->findTypeById($parentId);
                foreach ($parentType->properties() as $property) {
                    $type->addProperty($property);
                }
                $this->collectParentProperties($type, $parentType->parentIds());
            } catch (TypeNotFoundException) {
            }
        }
    }

    public function types(): Types
    {
        return $this->types;
    }

    private function readSchema(string $schemaFile): array
    {
        $schema = \file_get_contents($schemaFile);
        if ($schema === false) {
            throw new \RuntimeException(
                \sprintf(
                    'Schema file "%s" cannot be read!',
                    $schemaFile,
                ),
                1734865594,
            );
        }

        return \json_decode($schema, true, flags: \JSON_THROW_ON_ERROR);
    }

    /**
     * @param array<string, string|array> $term
     */
    private function processTerm(array $term): void
    {
        if (isset($term['schema:supersededBy'])) {
            return;
        }

        $termType = $term['@type'];
        if (\is_array($termType)) {
            // Is a data type (which we do not process)
            // or an enumeration member (which we process in a separate step)
            return;
        }

        if ($this->isDataType($term)) {
            return;
        }

        $sectionUri = $term['schema:isPartOf']['@id'] ?? '';
        if ($sectionUri === 'https://meta.schema.org') {
            return;
        }

        if ($termType === 'rdfs:Class') {
            $this->types->addType($this->typeBuilder->build($term));
            return;
        }
        if ($termType === 'rdf:Property') {
            $this->properties->addProperty($this->propertyBuilder->build($term));
        }
    }

    private function isDataType(array $term): bool
    {
        $dataTypes = [
            'schema:Boolean',
            'schema:Time',
            'schema:Number',
            'schema:DateTime',
            'schema:Text',
            'schema:Date',
        ];

        return \in_array($term['rdfs:subClassOf']['@id'] ?? '', $dataTypes, true);
    }
}