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
use Brotkrueml\SchemaGenerator\Schema\Vocabulary\Id;
use Brotkrueml\SchemaGenerator\Schema\Vocabulary\Type;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(Type::class)]
final class TypeTest extends TestCase
{
    #[Test]
    public function idReturnsIdCorrectly(): void
    {
        $id = new Id('schema:Corporation');
        $comment = new Comment('Organization: A business corporation.');
        $section = Section::Core;

        $subject = new Type($id, $comment, $section);

        self::assertSame($id, $subject->id());
    }

    #[Test]
    public function commentReturnsCommentCorrectly(): void
    {
        $id = new Id('schema:Corporation');
        $comment = new Comment('Organization: A business corporation.');
        $section = Section::Core;

        $subject = new Type($id, $comment, $section);

        self::assertSame($comment, $subject->comment());
    }

    #[Test]
    public function sectionReturnsSectionCorrectly(): void
    {
        $id = new Id('schema:Corporation');
        $comment = new Comment('Organization: A business corporation.');
        $section = Section::Core;

        $subject = new Type($id, $comment, $section);

        self::assertSame($section, $subject->section());
    }

    #[Test]
    public function parentIdsReturnsParentIdsCorrectly(): void
    {
        $id = new Id('schema: LocalBusiness ');
        $comment = new Comment('A particular physical business or branch of an organization.');
        $section = Section::Core;

        $organizationId = new Id('schema:Organization');
        $placeId = new Id('schema:Place');

        $subject = new Type($id, $comment, $section);
        $subject->addParentId($organizationId);
        $subject->addParentId($placeId);

        $actual = $subject->parentIds();

        self::assertContains($organizationId, $actual);
        self::assertContains($placeId, $actual);
    }
}
