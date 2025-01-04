<?php

declare(strict_types=1);

/*
 * This file is part of the Schema Generator.
 *
 * For the full copyright and license information, please view the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\SchemaGenerator\Tests\Unit\Schema\Vocabulary;

use Brotkrueml\SchemaGenerator\Schema\Exception\EnumerationNotFoundException;
use Brotkrueml\SchemaGenerator\Schema\Section;
use Brotkrueml\SchemaGenerator\Schema\Vocabulary\Comment;
use Brotkrueml\SchemaGenerator\Schema\Vocabulary\Enumeration;
use Brotkrueml\SchemaGenerator\Schema\Vocabulary\Enumerations;
use Brotkrueml\SchemaGenerator\Schema\Vocabulary\Id;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(Enumerations::class)]
final class EnumerationsTest extends TestCase
{
    #[Test]
    public function iterationOverPreviouslyAddedIds(): void
    {
        $enumerations = [
            new Enumeration(
                new Id('schema:BoardingPolicyType'),
                new Comment('A type of boarding policy used by an airline.'),
                Section::Core,
            ),
            new Enumeration(
                new Id('schema:BookFormatType'),
                new Comment('The publication format of the book.'),
                Section::Core,
            ),
            new Enumeration(
                new Id('schema:ItemAvailability'),
                new Comment('A list of possible product availability options.'),
                Section::Core,
            ),
        ];

        $subject = new Enumerations();
        $subject->addEnumeration($enumerations[0]);
        $subject->addEnumeration($enumerations[1]);
        $subject->addEnumeration($enumerations[2]);

        foreach ($subject as $enumeration) {
            self::assertContains($enumeration, $enumerations);
        }
    }

    #[Test]
    public function findEnumerationByIdReturnsEnumerationIdCorrectly(): void
    {
        $itemAvailability = new Enumeration(
            new Id('schema:ItemAvailability'),
            new Comment('A list of possible product availability options.'),
            Section::Core,
        );

        $mapCategoryType = new Enumeration(
            new Id('schema:MapCategoryType'),
            new Comment('An enumeration of several kinds of Map.'),
            Section::Core,
        );

        $subject = new Enumerations();
        $subject->addEnumeration($itemAvailability);
        $subject->addEnumeration($mapCategoryType);

        $actual = $subject->findEnumerationById(new Id('schema:ItemAvailability'));

        self::assertSame($actual, $itemAvailability);
    }

    #[Test]
    public function findEnumerationByIdThrowsExceptionIfEnumerationIdCannotBeFound(): void
    {
        $this->expectException(EnumerationNotFoundException::class);
        $this->expectExceptionCode(1735895580);

        $itemAvailability = new Enumeration(
            new Id('schema:ItemAvailability'),
            new Comment('A list of possible product availability options.'),
            Section::Core,
        );

        $subject = new Enumerations();
        $subject->addEnumeration($itemAvailability);

        $subject->findEnumerationById(new Id('schema:MapCategoryType'));
    }
}
