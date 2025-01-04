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
 * @implements \IteratorAggregate<Member>
 */
final class Members implements \IteratorAggregate
{
    private array $members = [];

    public function addMember(Member $member): void
    {
        $this->members[$member->id()->id()] = $member;
    }

    public function getIterator(): \Traversable
    {
        yield from $this->members;
    }
}
