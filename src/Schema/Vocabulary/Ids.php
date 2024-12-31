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
 * @implements \IteratorAggregate<Id>
 */
final class Ids implements \IteratorAggregate
{
    private array $ids = [];

    public function addId(Id $id): void
    {
        $this->ids[] = $id;
    }

    public function getIterator(): \Traversable
    {
        yield from $this->ids;
    }
}
