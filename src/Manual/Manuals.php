<?php

declare(strict_types=1);

/*
 * This file is part of the Schema Generator.
 *
 * For the full copyright and license information, please view the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\SchemaGenerator\Manual;

use Brotkrueml\SchemaGenerator\Schema\Vocabulary\Type;

/**
 * @implements \IteratorAggregate<Manual>
 */
final class Manuals implements \IteratorAggregate
{
    /**
     * @var list<Manual>
     */
    private array $manuals = [];

    public function addManual(Manual $manual): void
    {
        $this->manuals[] = $manual;
    }

    public function findByType(Type $type): self
    {
        $manuals = new self();
        foreach ($this->manuals as $manual) {
            if ($manual->type() === $type->id()->label()) {
                $manuals->addManual($manual);
            }
        }

        return $manuals;
    }

    public function getIterator(): \Traversable
    {
        yield from $this->manuals;
    }
}
