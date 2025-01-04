<?php

declare(strict_types=1);

/*
 * This file is part of the Schema Generator.
 *
 * For the full copyright and license information, please view the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\SchemaGenerator\Schema\Vocabulary;

use Brotkrueml\SchemaGenerator\Schema\Exception\EnumerationNotFoundException;

/**
 * @implements \IteratorAggregate<Enumeration>
 */
final class Enumerations implements \IteratorAggregate
{
    /**
     * @var list<Enumeration>
     */
    private array $enumerations = [];

    public function addEnumeration(Enumeration $enumeration): void
    {
        $this->enumerations[] = $enumeration;
    }

    public function findEnumerationById(Id $enumerationId): Enumeration
    {
        $enumeration = \array_values(\array_filter(
            $this->enumerations,
            static fn(Enumeration $enumeration): bool => $enumeration->id()->id() === $enumerationId->id(),
        ));

        if ($enumeration === []) {
            throw EnumerationNotFoundException::enumerationIdNotFound($enumerationId);
        }

        return $enumeration[0];
    }

    public function getIterator(): \Traversable
    {
        yield from $this->enumerations;
    }
}
