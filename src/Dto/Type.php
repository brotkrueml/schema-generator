<?php

declare(strict_types=1);

/*
 * This file is part of the Schema Generator.
 *
 * For the full copyright and license information, please view the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\SchemaGenerator\Dto;

final class Type
{
    private array $properties = [];
    private array $subTypeIds = [];

    public function __construct(
        private string $id,
        private string $comment,
        private array $subClassOf,
        private string $extensionUri
    ) {
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getComment(): string
    {
        return $this->comment;
    }

    public function getParentIds(): array
    {
        return $this->subClassOf;
    }

    public function getExtensionUri(): string
    {
        return $this->extensionUri;
    }

    public function addProperty(Property $property): void
    {
        $this->properties[] = $property;
    }

    public function getProperties(): array
    {
        return $this->properties;
    }

    public function addSubTypeId(string $subTypeId): void
    {
        if (!\in_array($subTypeId, $this->subTypeIds, true)) {
            $this->subTypeIds[] = $subTypeId;
        }
    }

    public function getSubTypeIds(): array
    {
        return $this->subTypeIds;
    }
}
