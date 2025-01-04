<?php

declare(strict_types=1);

/*
 * This file is part of the Schema Generator.
 *
 * For the full copyright and license information, please view the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\SchemaGenerator\Tests\Unit\Schema\Vocabulary;

use Brotkrueml\SchemaGenerator\Schema\Vocabulary\Comment;
use Brotkrueml\SchemaGenerator\Schema\Vocabulary\Id;
use Brotkrueml\SchemaGenerator\Schema\Vocabulary\Member;
use Brotkrueml\SchemaGenerator\Schema\Vocabulary\Members;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(Members::class)]
final class MembersTest extends TestCase
{
    #[Test]
    public function iterationOverPreviouslyAddedMembers(): void
    {
        $members = [
            new Member(
                new Id('schema:RsvpResponseMaybe'),
                new Comment('The invitee may or may not attend.'),
            ),
            new Member(
                new Id('schema:RsvpResponseNo'),
                new Comment('The invitee will not attend.'),
            ),
            new Member(
                new Id('schema:RsvpResponseYes'),
                new Comment('The invitee will attend.'),
            ),
        ];

        $subject = new Members();
        $subject->addMember($members[0]);
        $subject->addMember($members[1]);
        $subject->addMember($members[2]);

        foreach ($subject as $property) {
            self::assertContains($property, $members);
        }
    }
}
