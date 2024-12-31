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
use Brotkrueml\SchemaGenerator\Schema\Vocabulary\RecursiveTypeIdCollector;
use Brotkrueml\SchemaGenerator\Schema\Vocabulary\Type;
use Brotkrueml\SchemaGenerator\Schema\Vocabulary\Types;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(RecursiveTypeIdCollector::class)]
final class RecursiveTypeIdCollectorTest extends TestCase
{
    #[Test]
    public function collectReturnsGivenTypeIdCorrectly(): void
    {
        $thing = new Type(
            new Id('schema:Thing'),
            new Comment(''),
            Section::Core,
        );

        $types = new Types();
        $types->addType($thing);

        $actual = new RecursiveTypeIdCollector()->collect($types, $thing);

        self::assertSame([$thing->id()], $actual);
    }

    #[Test]
    public function collectReturnsMultipleTypeIdsCorrectly(): void
    {
        // Testing "Library":
        // Thing > Organization > LocalBusiness > Library
        // Thing > Place > LocalBusiness > Library

        $library = new Type(
            new Id('schema:Library'),
            new Comment(''),
            Section::Core,
        );

        $localBusiness = new Type(
            new Id('schema:LocalBusiness'),
            new Comment(''),
            Section::Core,
        );

        $organization = new Type(
            new Id('schema:Organization'),
            new Comment(''),
            Section::Core,
        );

        $place = new Type(
            new Id('schema:Place'),
            new Comment(''),
            Section::Core,
        );

        $thing = new Type(
            new Id('schema:Thing'),
            new Comment(''),
            Section::Core,
        );

        $organization->addParentId($thing->id());
        $place->addParentId($thing->id());
        $localBusiness->addParentId($organization->id());
        $localBusiness->addParentId($place->id());
        $library->addParentId($localBusiness->id());

        $types = new Types();
        $types->addType($library);
        $types->addType($thing);
        $types->addType($localBusiness);
        $types->addType($organization);
        $types->addType($place);

        $actual = new RecursiveTypeIdCollector()->collect($types, $library);

        self::assertContains($library->id(), $actual);
        self::assertContains($localBusiness->id(), $actual);
        self::assertContains($organization->id(), $actual);
        self::assertContains($place->id(), $actual);
        self::assertContains($thing->id(), $actual);
    }

    #[Test]
    public function collectIgnoresUnavailableTypes(): void
    {
        $place = new Type(
            new Id('schema:Place'),
            new Comment(''),
            Section::Core,
        );

        $thing = new Type(
            new Id('schema:Thing'),
            new Comment(''),
            Section::Core,
        );

        $place->addParentId($thing->id());

        $types = new Types();
        $types->addType($place);

        $actual = new RecursiveTypeIdCollector()->collect($types, $place);

        self::assertContains($place->id(), $actual);
    }
}
