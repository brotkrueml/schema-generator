<?php

declare(strict_types=1);

/*
 * This file is part of the Schema Generator.
 *
 * For the full copyright and license information, please view the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\SchemaGenerator\Schema\Vocabulary;

use Brotkrueml\SchemaGenerator\Schema\Section;

/**
 * Relates to "@type": "rdfs:Class"
 */
final class Type
{
    private readonly Ids $parentIds;
    private readonly Properties $properties;
    private bool $isEnumeration = false;

    public function __construct(
        private readonly Id $id,
        private readonly Comment $comment,
        private readonly Section $section,
    ) {
        $this->parentIds = new Ids();
        $this->properties = new Properties();
    }

    public function addParentId(Id $parentId): void
    {
        $this->parentIds->addId($parentId);
    }

    public function addProperty(Property $property): void
    {
        $this->properties->addProperty($property);
    }

    public function setAsEnumeration(): void
    {
        $this->isEnumeration = true;
    }

    public function id(): Id
    {
        return $this->id;
    }

    public function comment(): Comment
    {
        return $this->comment;
    }

    public function section(): Section
    {
        return $this->section;
    }

    public function parentIds(): Ids
    {
        return $this->parentIds;
    }

    public function properties(): Properties
    {
        return $this->properties;
    }

    public function isEnumeration(): bool
    {
        return $this->isEnumeration;
    }
}
