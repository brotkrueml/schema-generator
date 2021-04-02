<?php

declare(strict_types=1);

/*
 * This file is part of the Schema Generator.
 *
 * For the full copyright and license information, please view the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\SchemaGenerator\Tests\Unit\Dto;

use Brotkrueml\SchemaGenerator\Dto\AdditionalProperties;
use Brotkrueml\SchemaGenerator\Dto\Extension;
use Brotkrueml\SchemaGenerator\Dto\Property;
use Brotkrueml\SchemaGenerator\Dto\Type;
use PHPUnit\Framework\TestCase;

class AdditionalPropertiesTest extends TestCase
{
    private AdditionalProperties $subject;

    protected function setUp(): void
    {
        $this->subject = new AdditionalProperties();
    }

    /**
     * @test
     */
    public function emptyTermsReturnEmptyArray(): void
    {
        self::assertSame([], $this->subject->getTerms());
    }

    /**
     * @test
     */
    public function typesAndPropertiesAreReturnedCorrectly(): void
    {
        $type1 = new Type('SomeType', '', [], new Extension('core'));
        $property11 = new Property('x', [], new Extension('core'));
        $property12 = new Property('a', [], new Extension('core'));
        $property13 = new Property('b', [], new Extension('core'));
        $this->subject->addPropertiesToType($type1, $property11, $property12, $property13);

        $type2 = new Type('AnotherType', '', [], new Extension('core'));
        $property21 = new Property('2', [], new Extension('core'));
        $property22 = new Property('1', [], new Extension('core'));
        $property23 = new Property('3', [], new Extension('core'));
        $this->subject->addPropertiesToType($type2, $property21, $property22, $property23);

        $actual = $this->subject->getTerms();
        self::assertCount(2, $actual);
        self::assertSame('AnotherType', $actual[0]['type']->getId());
        self::assertSame(['1', '2', '3'], $actual[0]['properties']);
        self::assertSame('SomeType', $actual[1]['type']->getId());
        self::assertSame(['a', 'b', 'x'], $actual[1]['properties']);
    }
}
