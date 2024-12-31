<?php

declare(strict_types=1);

/*
 * This file is part of the Schema Generator.
 *
 * For the full copyright and license information, please view the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\SchemaGenerator\Schema\Vocabulary;

/**
 * @implements \IteratorAggregate<Property>
 */
final class Properties implements \IteratorAggregate
{
    private array $properties = [];

    public function addProperty(Property $property): void
    {
        $this->properties[$property->id()->id()] = $property;
    }

    public function getIterator(): \Traversable
    {
        yield from $this->properties;
    }
}
