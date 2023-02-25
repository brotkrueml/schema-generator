<?php

declare(strict_types=1);

/*
 * This file is part of the Schema Generator.
 *
 * For the full copyright and license information, please view the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\SchemaGenerator\Tests\Unit\Dto;

use Brotkrueml\SchemaGenerator\Dto\Extension;
use Brotkrueml\SchemaGenerator\Dto\Property;
use Brotkrueml\SchemaGenerator\Dto\Type;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class TypeTest extends TestCase
{
    #[Test]
    public function gettersImplementedCorrectly(): void
    {
        $subject = new Type(
            'SomeId',
            'some comment',
            ['SomeSubId'],
            new Extension('bib'),
        );

        self::assertSame('SomeId', $subject->getId());
        self::assertSame('some comment', $subject->getComment());
        self::assertSame(['SomeSubId'], $subject->getParentIds());
        self::assertSame('bib', $subject->getExtension()->getName());
    }

    #[Test]
    public function addPropertyAndGetProperties(): void
    {
        $subject = new Type('SomeId', 'some comment', [], new Extension('core'));
        $property1 = new Property('someProperty', ['SomeId'], new Extension('auto'));
        $subject->addProperty($property1);

        self::assertCount(1, $subject->getProperties());
        self::assertSame($property1, $subject->getProperties()[0]);

        $property2 = new Property('anotherProperty', ['SomeId'], new Extension('auto'));
        $subject->addProperty($property2);

        self::assertCount(2, $subject->getProperties());
        self::assertSame($property1, $subject->getProperties()[0]);
        self::assertSame($property2, $subject->getProperties()[1]);
    }
}
