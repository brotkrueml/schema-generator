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
use PHPUnit\Framework\TestCase;

class PropertyTest extends TestCase
{
    /**
     * @test
     */
    public function gettersImplementedCorrectly(): void
    {
        $types = [new Type('SomeTypeId', '', [], new Extension('core'))];

        $subject = new Property(
            'SomeId',
            $types,
            new Extension('auto')
        );

        self::assertSame('SomeId', $subject->getId());
        self::assertSame($types, $subject->getTypes());
        self::assertSame('auto', $subject->getExtension()->getName());
    }
}
