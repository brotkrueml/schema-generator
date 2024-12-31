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
use Brotkrueml\SchemaGenerator\Schema\Vocabulary\Properties;
use Brotkrueml\SchemaGenerator\Schema\Vocabulary\Property;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(Properties::class)]
final class PropertiesTest extends TestCase
{
    #[Test]
    public function iterationOverPreviouslyAddedIds(): void
    {
        $properties = [
            new Property(
                new Id('schema:property1'),
                new Comment('some comment 1'),
                Section::Core,
            ),
            new Property(
                new Id('schema:property2'),
                new Comment('some comment 2'),
                Section::Auto,
            ),
            new Property(
                new Id('schema:property3'),
                new Comment('some comment 3'),
                Section::Bib,
            ),
        ];

        $subject = new Properties();
        $subject->addProperty($properties[0]);
        $subject->addProperty($properties[1]);
        $subject->addProperty($properties[2]);

        foreach ($subject as $property) {
            self::assertContains($property, $properties);
        }
    }
}
