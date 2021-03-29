<?php

declare(strict_types=1);

/*
 * This file is part of the Schema Generator.
 *
 * For the full copyright and license information, please view the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\SchemaGenerator\Tests\Unit\Dto;

use Brotkrueml\SchemaGenerator\Dto\Property;
use Brotkrueml\SchemaGenerator\Dto\Type;
use PHPUnit\Framework\TestCase;

class TypeTest extends TestCase
{
    /**
     * @test
     */
    public function gettersImplementedCorrectly(): void
    {
        $subject = new Type(
            'SomeId',
            'some comment',
            ['SomeSubId'],
            'https://bib.schema.org'
        );

        self::assertSame('SomeId', $subject->getId());
        self::assertSame('some comment', $subject->getComment());
        self::assertSame(['SomeSubId'], $subject->getParentIds());
        self::assertSame('https://bib.schema.org', $subject->getExtensionUri());
    }

    /**
     * @test
     */
    public function addPropertyAndGetProperties(): void
    {
        $subject = new Type('SomeId', 'some comment', [], '');
        $property1 = new Property('someProperty', ['SomeId'], 'https://auto.schema.org');
        $subject->addProperty($property1);

        self::assertCount(1, $subject->getProperties());
        self::assertSame($property1, $subject->getProperties()[0]);

        $property2 = new Property('anotherProperty', ['SomeId'], 'https://auto.schema.org');
        $subject->addProperty($property2);

        self::assertCount(2, $subject->getProperties());
        self::assertSame($property1, $subject->getProperties()[0]);
        self::assertSame($property2, $subject->getProperties()[1]);
    }
}
