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

final readonly class Enumeration
{
    private Members $members;

    public function __construct(
        private Id $id,
        private Comment $comment,
        private Section $section,
    ) {
        $this->members = new Members();
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

    public function addMember(Member $member): void
    {
        $this->members->addMember($member);
    }

    public function members(): Members
    {
        return $this->members;
    }
}
