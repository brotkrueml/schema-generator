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

    #[Test]
    public function isEnumerationIsReturnedCorrectlyWhenNotSet(): void
    {
        $subject = new Type(
            new Id('schema: LocalBusiness '),
            new Comment('A particular physical business or branch of an organization.'),
            Section::Core,
        );

        self::assertFalse($subject->isEnumeration());
    }

    #[Test]
    public function isEnumerationIsReturnedCorrectlyWhenSet(): void
    {
        $subject = new Type(
            new Id('schema: LocalBusiness '),
            new Comment('A particular physical business or branch of an organization.'),
            Section::Core,
        );
        $subject->setAsEnumeration();

        self::assertTrue($subject->isEnumeration());
    }

    #[Test]
    public function propertiesReturnsPropertiesCorrectly(): void
    {
        $properties = [
            new Property(
                new Id('schema:name'),
                new Comment('The name of the item. '),
                Section::Core,
            ),
            new Property(
                new Id('schema:url'),
                new Comment('URL of the item. '),
                Section::Core,
            ),
        ];

        $subject = new Type(
            new Id('schema:Corporation'),
            new Comment('Organization: A business corporation.'),
            Section::Core,
        );
        $subject->addProperty($properties[0]);
        $subject->addProperty($properties[1]);

        $actual = $subject->properties();

        $actualAsArray = [];
        foreach ($actual as $property) {
            $actualAsArray[] = $property;
        }

        self::assertCount(2, $actualAsArray);
        self::assertContains($properties[0], $actualAsArray);
        self::assertContains($properties[1], $actualAsArray);
    }
}
