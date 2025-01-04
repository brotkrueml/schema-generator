<?php

declare(strict_types=1);

/*
 * This file is part of the Schema Generator.
 *
 * For the full copyright and license information, please view the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\SchemaGenerator\Tests\Unit\Schema\Vocabulary;

use Brotkrueml\SchemaGenerator\Schema\Section;
use Brotkrueml\SchemaGenerator\Schema\Vocabulary\Comment;
use Brotkrueml\SchemaGenerator\Schema\Vocabulary\Enumeration;
use Brotkrueml\SchemaGenerator\Schema\Vocabulary\Id;
use Brotkrueml\SchemaGenerator\Schema\Vocabulary\Member;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(Enumeration::class)]
final class EnumerationTest extends TestCase
{
    #[Test]
    public function idReturnsIdCorrectly(): void
    {
        $id = new Id('schema:BookFormatType');
        $comment = new Comment('The publication format of the book.');
        $section = Section::Core;

        $subject = new Enumeration($id, $comment, $section);

        self::assertSame($id, $subject->id());
    }

    #[Test]
    public function commentReturnsCommentCorrectly(): void
    {
        $id = new Id('schema:BookFormatType');
        $comment = new Comment('The publication format of the book.');
        $section = Section::Core;

        $subject = new Enumeration($id, $comment, $section);

        self::assertSame($comment, $subject->comment());
    }

    #[Test]
    public function sectionReturnsSectionCorrectly(): void
    {
        $id = new Id('schema:BookFormatType');
        $comment = new Comment('The publication format of the book.');
        $section = Section::Core;

        $subject = new Enumeration($id, $comment, $section);

        self::assertSame($section, $subject->section());
    }

    #[Test]
    public function membersReturnsMembersCorrectly(): void
    {
        $id = new Id('schema:BookFormatType');
        $comment = new Comment('The publication format of the book.');
        $section = Section::Core;

        $members = [
            new Member(
                new Id('schema:AudiobookFormat'),
                new Comment('Book format: Audiobook.'),
            ),
            new Member(
                new Id('schema:EBook'),
                new Comment('Book format: Ebook.'),
            ),
        ];

        $subject = new Enumeration($id, $comment, $section);
        $subject->addMember($members[0]);
        $subject->addMember($members[1]);

        $actual = $subject->members();

        $actualAsArray = [];
        foreach ($actual as $member) {
            $actualAsArray[] = $member;
        }

        self::assertCount(2, $actualAsArray);
        self::assertContains($members[0], $actualAsArray);
        self::assertContains($members[1], $actualAsArray);
    }
}
