<?php

declare(strict_types=1);

/*
 * This file is part of the Schema Generator.
 *
 * For the full copyright and license information, please view the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\SchemaGenerator\Tests\Unit\Schema\Vocabulary;

use Brotkrueml\SchemaGenerator\Schema\Exception\TypeNotFoundException;
use Brotkrueml\SchemaGenerator\Schema\Section;
use Brotkrueml\SchemaGenerator\Schema\Vocabulary\Comment;
use Brotkrueml\SchemaGenerator\Schema\Vocabulary\Id;
use Brotkrueml\SchemaGenerator\Schema\Vocabulary\Type;
use Brotkrueml\SchemaGenerator\Schema\Vocabulary\Types;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(Types::class)]
final class TypesTest extends TestCase
{
    #[Test]
    public function iterationOverPreviouslyAddedIds(): void
    {
        $types = [
            new Type(
                new Id('schema:Type1'),
                new Comment('some comment 1'),
                Section::Core,
            ),
            new Type(
                new Id('schema:Type2'),
                new Comment('some comment 2'),
                Section::Auto,
            ),
            new Type(
                new Id('schema:Type3'),
                new Comment('some comment 3'),
                Section::Bib,
            ),
        ];

        $subject = new Types();
        $subject->addType($types[0]);
        $subject->addType($types[1]);
        $subject->addType($types[2]);

        foreach ($subject as $type) {
            self::assertContains($type, $types);
        }
    }

    #[Test]
    public function findTypeByIdReturnsTypeIdCorrectly(): void
    {
        $thing = new Type(
            new Id('schema:Thing'),
            new Comment(''),
            Section::Core,
        );

        $person = new Type(
            new Id('schema:Person'),
            new Comment(''),
            Section::Core,
        );

        $types = new Types();
        $types->addType($thing);
        $types->addType($person);

        $actual = $types->findTypeById(new Id('schema:Person'));

        self::assertSame($actual, $person);
    }

    #[Test]
    public function findTypeByIdThrowsExceptionIfTypeIdCannotBeFound(): void
    {
        $this->expectException(TypeNotFoundException::class);
        $this->expectExceptionCode(1735290356);

        $thing = new Type(
            new Id('schema:Thing'),
            new Comment(''),
            Section::Core,
        );

        $types = new Types();
        $types->addType($thing);

        $types->findTypeById(new Id('schema:Person'));
    }
}
