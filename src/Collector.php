<?php

declare(strict_types=1);

/*
 * This file is part of the Schema Generator.
 *
 * For the full copyright and license information, please view the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\SchemaGenerator;

use Brotkrueml\SchemaGenerator\Builder\PropertyBuilder;
use Brotkrueml\SchemaGenerator\Builder\TypeBuilder;
use Brotkrueml\SchemaGenerator\Dto\Type;
use Brotkrueml\SchemaGenerator\Enumerations\Attributes;

final class Collector
{
    private const TYPE_PROPERTY = 'rdf:Property';
    private const TYPE_TYPE = 'rdfs:Class';

    private array $schema;
    /**
     * @var Type[]
     */
    private array $types = [];
    private TypeBuilder $typeBuilder;
    private PropertyBuilder $propertyBuilder;

    public function __construct(
        private string $schemaFilepath,
    ) {
        $this->readSchema();
        $this->checkSchema();

        $this->typeBuilder = new TypeBuilder();
        $this->propertyBuilder = new PropertyBuilder();
    }

    public function getTypes(): array
    {
        $this->collectTypes();
        $this->assignPropertiesToTypes();
        $this->assignSubTypesToParents();

        return $this->types;
    }

    private function readSchema(): void
    {
        $schema = \file_get_contents($this->schemaFilepath);
        if ($schema === false) {
            throw new \RuntimeException(
                \sprintf(
                    'Schema file "%s" cannot be read!',
                    $this->schemaFilepath,
                ),
                1616324290,
            );
        }

        $this->schema = \json_decode($schema, true, flags: \JSON_THROW_ON_ERROR);
    }

    private function checkSchema(): void
    {
        if (! isset($this->schema['@context']) || (! isset($this->schema['@graph']))) {
            throw new \RuntimeException('Schema file is not valid!', 1616324975);
        }
    }

    private function collectTypes(): void
    {
        foreach ($this->schema['@graph'] as $term) {
            if ($this->isMetaExtension($term)) {
                continue;
            }

            // Check if type is really a type
            // It can also be an array (e.g. with a data type which we don't want)
            // or a enumeration type starting with "schema:", which we also do not cover
            if ($term['@type'] !== self::TYPE_TYPE) {
                continue;
            }

            if ($term[Attributes::SUPERSEDED_BY] ?? false) {
                continue;
            }

            $type = $this->typeBuilder->build($term);
            $this->types[$type->getId()] = $type;
        }
        \ksort($this->types);
    }

    private function isMetaExtension(array $term): bool
    {
        if ($term[Attributes::IS_PART_OF] ?? false) {
            return $term[Attributes::IS_PART_OF][Attributes::ID] === 'https://meta.schema.org';
        }

        return false;
    }

    private function assignPropertiesToTypes(): void
    {
        foreach ($this->schema['@graph'] as $term) {
            if ($this->isMetaExtension($term)) {
                continue;
            }

            if ($term['@type'] !== self::TYPE_PROPERTY) {
                continue;
            }

            if ($term[Attributes::SUPERSEDED_BY] ?? false) {
                continue;
            }

            $property = $this->propertyBuilder->build($term);
            foreach ($property->getTypes() as $typeId) {
                if ($this->types[$typeId] ?? false) {
                    $this->types[$typeId]->addProperty($property);
                }
            }
        }
    }

    private function assignSubTypesToParents(): void
    {
        foreach ($this->types as $type) {
            foreach ($type->getParentIds() as $parentId) {
                if ($this->types[$parentId] ?? false) {
                    $this->types[$parentId]->addSubTypeId($type->getId());
                    continue;
                }

                echo \sprintf('Unknown parent type "%s" in type "%s"' . "\n", $parentId, $type->getId());
            }
        }
    }
}
