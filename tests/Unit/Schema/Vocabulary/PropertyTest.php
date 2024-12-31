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
use Brotkrueml\SchemaGenerator\Schema\Vocabulary\Property;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(Property::class)]
final class PropertyTest extends TestCase
{
    #[Test]
    public function idReturnsIdCorrectly(): void
    {
        $id = new Id('schema:name');
        $comment = new Comment('The name of the item.');
        $section = Section::Core;

        $subject = new Property($id, $comment, $section);

        self::assertSame($id, $subject->id());
    }

    #[Test]
    public function commentReturnsCommentCorrectly(): void
    {
        $id = new Id('schema:name');
        $comment = new Comment('The name of the item.');
        $section = Section::Core;

        $subject = new Property($id, $comment, $section);

        self::assertSame($comment, $subject->comment());
    }

    #[Test]
    public function sectionReturnsSectionCorrectly(): void
    {
        $id = new Id('schema:name');
        $comment = new Comment('The name of the item.');
        $section = Section::Core;

        $subject = new Property($id, $comment, $section);

        self::assertSame($section, $subject->section());
    }

    #[Test]
    public function typeIdsReturnsTypeIdsCorrectly(): void
    {
        $id = new Id('schema:name');
        $comment = new Comment('The name of the item.');
        $section = Section::Core;

        $subject = new Property($id, $comment, $section);

        $typeIds = [
            new Id('schema:Thing'),
            new Id('schema:CreativeWork'),
            new Id('schema:Person'),
        ];

        $subject->addTypeId($typeIds[0]);
        $subject->addTypeId($typeIds[1]);
        $subject->addTypeId($typeIds[2]);

        $actual = $subject->typeIds();

        foreach ($actual as $typeId) {
            self::assertContains($typeId, $actual);
        }
    }
}
