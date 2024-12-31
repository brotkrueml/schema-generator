<?php

declare(strict_types=1);

/*
 * This file is part of the Schema Generator.
 *
 * For the full copyright and license information, please view the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\SchemaGenerator\Schema\Vocabulary;

use Brotkrueml\SchemaGenerator\Schema\Exception\TypeNotFoundException;

/**
 * @implements \IteratorAggregate<Type>
 */
final class Types implements \IteratorAggregate
{
    private array $types = [];

    public function addType(Type $type): void
    {
        $this->types[] = $type;
    }

    public function findTypeById(Id $typeId): Type
    {
        $type = \array_values(\array_filter(
            $this->types,
            static fn(Type $type): bool => $type->id()->id() === $typeId->id(),
        ));

        if ($type === []) {
            throw new TypeNotFoundException(
                \sprintf(
                    'No type with id "%s" found',
                    $typeId->id(),
                ),
                1735290356,
            );
        }

        return $type[0];
    }

    public function getIterator(): \Traversable
    {
        yield from $this->types;
    }
}
