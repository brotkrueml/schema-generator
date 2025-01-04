<?php

declare(strict_types=1);

/*
 * This file is part of the Schema Generator.
 *
 * For the full copyright and license information, please view the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\SchemaGenerator\Schema\Vocabulary;

final readonly class Member
{
    private Ids $typeIds;

    public function __construct(
        private Id $id,
        private Comment $comment,
    ) {
        $this->typeIds = new Ids();
    }

    public function id(): Id
    {
        return $this->id;
    }

    public function comment(): Comment
    {
        return $this->comment;
    }

    public function addTypeId(Id $typeId): void
    {
        $this->typeIds->addId($typeId);
    }

    public function typeIds(): Ids
    {
        return $this->typeIds;
    }
}
